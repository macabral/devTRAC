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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUser;
use App\Mail\UserAssociated;

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
        
        $ret = User::select('*')
            ->where('id', $userId)
            ->with('projects')->get();

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
    public function associate(Request $request, $idUser)
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

        $project = (int) $input['projects_id'];

        $ret = UsersProjects::where('users_id','=',$idUser)
            ->where('projects_id','=',$project)
            ->get();

        try {

            if (! isset($ret[0]->id)) {

                $ret = UsersProjects::create($input);
                $id = $ret['id'];

            } else {

                $id = $ret[0]->id;
                UsersProjects::findOrFail($id)->fill($input)->save();
                
            }

            Toast::title(__('Project Associated.'))->autoDismiss(5);

            // enviar email
            try {

                Mail::Queue(new UserAssociated($idUser,$project));
                
            } catch (\Exception $e) {
                
                Toast::title(__('Não foi possível enviar email.'))->autoDismiss(5);
                
            }
        
        } catch (\Exception $e) {

            Toast::title(__('Error! ' .  $e->getMessage()))->danger()->autoDismiss(15);
            return response()->json(['messagem' => $e], 422);
            
        }

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

        try{

            $ret = User::findOrFail($id);

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

        $id = base64_decode($id);

        $this->validate($request, [
            'name' => 'required|max:254',
            'email' => 'required|max:254|unique:users,email,'.$id,
            'admin' => 'required',
            'active' => 'required'
        ]);
        
        $input = $request->all();

        try {
            
            $ret = User::findOrFail($id);
            
            $ret->fill($input);

            $ret->save();

            Toast::title(__('User saved!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e->getMessage()))->danger()->autoDismiss(5);

            return response()->json(['messagem' => $e], 422);
            
        }

        return redirect()->back();
    }

    /**
     * Creating a new resource.
     */
    public function create(Request $request)
    {
        
        $this->validate($request, [
            'name' => 'required|max:254',
            'email' => 'required|max:254|unique:users',
            'admin' => 'required',
            'active' => 'required'
        ]);

        $input = $request->all();

        $password = Str::random(10);

        $input['password'] = Hash::make($password);

        $email = $input['email'];

        try {
            
            $ret = User::create($input);

            Toast::title(__('User saved!'))->autoDismiss(5);

            // enviar email
            try {

                Mail::Queue(new NewUser($ret['id']));
        
            } catch (\Exception $e) {
        
                Toast::title(__('Não foi possível enviar email.'))->autoDismiss(5);
        
            }

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e->getMessage()))->danger()->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }

        return redirect()->back();
    }

}


