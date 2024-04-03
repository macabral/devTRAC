<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\User;
use App\Models\Projects;
use App\Models\UsersProjects;
use App\Library\TracMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('email', 'LIKE', "%$value%")
                        ->orwhere('name', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(User::class)
            ->orderby('name', 'asc')
            ->allowedSorts(['name'])
            ->allowedFilters(['name', 'email',  $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('users.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->defaultSort('name','desc')
                ->column('name', label: __('Name'), sortable: true, searchable: true, canBeHidden:false)
                ->column('email', label: __('email'), searchable: true)
                ->column('admin', label: __('is Admin?'), searchable: true)
                ->column('active', label: __('is Active?'), searchable: true)
                ->column('action', label: '', canBeHidden:false)
        ]);
    }

    /**
     * Show the projects for a User
     */
    public function projects($userId)
    {
        
        $ret = User::where('id', $userId)->with('projects')->get();

        return view('users.users-projects', [
            'userId' => $userId,
            'ret' => SpladeTable::for($ret[0]->projects)
                ->perPageOptions([7, 10, 50, 100, 200])
                ->withGlobalSearch()
                ->defaultSort('','desc')
                ->column('title', label: __('Project'), sortable: true, searchable: true, canBeHidden:false)
                ->column('pivot.gp', label: __('gp'), searchable: true)
                ->column('pivot.relator', label: __('relator'), searchable: true)
                ->column('pivot.dev', label: __('dev'), searchable: true)
                ->column('pivot.tester', label: __('tester'), searchable: true)
                ->column('pivot.users_id', hidden: true)
                ->column('action', label: '', canBeHidden:false)
        ]);

    }

    /**
     * Associate new Project.
     */
    public function newprojects($id)
    {
        $ret = Projects::where('status','=',"Enabled")->get();

        return view('users.new-project', [
            'id' => $id,
            'ret' => $ret,
        ]);

    }

    /**
     * Associate new Project.
     */
    public function associate(Request $request, $idUser, TracMail $TracMailInstance)
    {
        $this->validate($request, [
            'projects_id' => 'required|max:255',
            'gp' => 'required',
            'relator' => 'required',
            'dev' => 'required',
            'tester' => 'required'
        ]);

        $input = $request->all();

        $input['users_id'] = $idUser;

        $project = $input['projects_id'];

        $ret = UsersProjects::where('users_id','=',$idUser)->where('projects_id','=',$project)->get();

        Try {
            if (! isset($ret[0]->id)) {
                UsersProjects::create($input);
            } else {
                $id = $ret[0]->id;
                UsersProjects::findOrFail($id)->fill($input)->save();
            }
        
        } catch (\Exception $e) {

            Toast::title(__('Error! ' .  $e))->danger()->autoDismiss(15);
            return response()->json(['messagem' => $e], 422);
            
        }

        $proj = Projects::select('title')->Where('id','=', $project)->get();
        $projectUser = $proj[0]->title;

        $to = User::select('email')->where('id', '=', $idUser)->get(); 

        $acessos = ''; $j = 0;

        if ($input['gp']) {
            $acessos .= 'Gerente de Projeto';
            $j = 1;
        }

        if ($input['relator']) {
            if($j) {
                $acessos .= '; ';
            } else {
                $j = 1;
            }
            $acessos .= ' Relator';
        }

        if ($input['dev']) {
            if($j) {
                $acessos .= '; ';
            } else {
                $j = 1;
            }
            $acessos .= ' Desenvolvedor';
        }

        if ($input['tester']) {
            if($j) {
                $acessos .= '; ';
            } else {
                $j = 1;
            }
            $acessos .= ' Testador';
        }


        $mailData = [
            'to' => $to[0]['email'],
            'cc' => null,
            'subject' => 'devTRAC: Projeto Associado',
            'title' => "Usuário associado ao Projeto",
            'body' => "Você está recebendo esse email porque o Administrador associou você ao Projeto $projectUser com os seguintes acessos: $acessos.",
            'priority' => 0,
            'attachments' => null
        ];
            
        $TracMailInstance->save($mailData);

        Toast::title(__('Project Associated.'))->autoDismiss(5);
        
        return redirect()->back();

    }

    /**
     * Delete user.
     */
    public function deleteproj($userId, $id)
    {
        $id = base64_decode($id);

        UsersProjects::where('users_id','=',$userId)->where('projects_id','=', $id)->delete();

        return $this->projects($userId);
    }

    /**
     * Confirm to Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        $id = base64_decode($id);

        $ret = User::findOrFail($id);

        return view('users.confirm-delete', [
            'ret' => $ret,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $ret = User::findOrFail($id);

        try{

            $ret->delete();
            Toast::title(__('User deleted!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('User cannot be deleted!'))->danger()->autoDismiss(5);
            
        }

        return redirect()->back();

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = base64_decode($id);

        if ($id == 0) {

            $ret = array(
                'id' => 0,
                'nome' => '',
                'email' => '',
                'admin' => 0,
                'active' => 1
            );

            return view('users.new-users-form', [
                'ret' => $ret,
            ]);

        } else {

            $ret = User::findOrFail($id);

            return view('users.edit-users-form', [
                'ret' => $ret,
            ]);

        }



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|max:254',
            'email' => 'required|max:254',
            'admin' => 'required',
            'active' => 'required'
        ]);

        $id = base64_decode($id);
        
        $input = $request->all();

        $ret = User::findOrFail($id);

        try {
            
            $ret->fill($input);

        } catch (\Exception $e) {

            return response()->json(['messagem' => $e], 422);
            
        }

        $ret->save();

        Toast::title(__('User saved!'))->autoDismiss(5);

        return redirect()->back();
    }

        /**
     * Creating a new resource.
     */
    public function create(Request $request, TracMail $TracMailInstance)
    {
        
        $this->validate($request, [
            'name' => 'required|max:254',
            'email' => 'required|max:254',
            'admin' => 'required',
            'active' => 'required'
        ]);

        $input = $request->all();

        $password = Str::random(10);

        $input['password'] = Hash::make($password);

        $email = $input['email'];

        try {
            
            User::create($input);

            $mailData = [
                'to' => $input['email'],
                'cc' => null,
                'subject' => 'devTRAC: Você foi cadastrado.',
                'title' => "Novo Cadastro",
                'body' => "Você foi cadastrado no devTRAC com o email <b>$$email</b> e senha <b>$password</b>.<br>Não esqueça de trocar a senha no próximo login.",
                'priority' => 10,
                'attachments' => null
            ];
                
            $TracMailInstance->save($mailData);


        } catch (\Exception $e) {

            Toast::title(__('Type error!' . $e))->danger()->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('User saved!'))->autoDismiss(5);

        return redirect()->route('users.index');
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reset($id, TracMail $TracMailInstance)
    {

        $id = base64_decode($id);

        $ret = User::findOrFail($id);

        $password = Str::random(10);
        
        $ret->update([
            'password' =>  Hash::make($password)
        ]);

        $mailData = [
            'to' => $ret->email,
            'cc' => null,
            'subject' => 'devTRAC: Sua senha foi Resetada',
            'title' => "Nova Senha",
            'body' => "Sua senha foi alterada pelo Administrador.<br>No próximo login utiliza a senha <b>$password</b>.<br>Não esqueça de trocar a senha.",
            'priority' => 10,
            'attachments' => null
        ];
            
        $TracMailInstance->save($mailData);

        Toast::title(__('Password was reseted!'))->autoDismiss(5);

        return redirect()->route('users.index');

    }

}


