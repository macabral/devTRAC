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
                        ->orwhere('name', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(User::class)
            ->orderby('name', 'asc')
            ->allowedSorts(['name'])
            ->allowedFilters(['name', 'email',  $globalSearch])
            ->paginate(10)
            ->withQueryString();

        return view('users.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->defaultSort('name','desc')
                ->column('name', label: __('Name'), sortable: true, searchable: true, canBeHidden:false)
                ->column('email', label: __('email'), searchable: true)
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
    public function associate(Request $request, $id, TracMail $TracMailInstance)
    {
        $this->validate($request, [
            'projects_id' => 'required|max:255',
            'gp' => 'required',
            'relator' => 'required',
            'dev' => 'required',
            'tester' => 'required'
        ]);

        $input = $request->all();

        $input['users_id'] = $id;

        $project = $input['projects_id'];

        $ret = UsersProjects::where('users_id','=',$id)->where('projects_id','=',$project)->get();

        if (! isset($ret[0]->id)) {
            UsersProjects::create($input);
        } else {
            $id = $ret[0]->id;
            UsersProjects::findOrFail($id)->fill($input)->save();
        }

        $proj = Projects::select('title')->Where('id','=', $project)->get();
        $projectUser = $proj[0]->title;

        $to = User::select('email')->where('id', '=', $id)->get(); 

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

}
