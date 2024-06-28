<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Projects;
use App\Models\UsersProjects;

class ProjectsController extends Controller
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
                        ->orwhere('title', 'LIKE', "%$value%")
                        ->orwhere('description', 'LIKE', "%$value%")
                        ->orwhere('status', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Projects::class)
            ->orderby('title')
            ->allowedSorts(['title','status'])
            ->allowedFilters(['description', 'status',  $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('projects.result-search', [
            'projs' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->defaultSort('title','desc')
                ->column('title', label: __('Project'), sortable: true, searchable: true, canBeHidden:false)
                ->column('description', label: __('Description'), searchable: true)
                ->column('status', label: __('Status'), searchable: true)
                ->column('action', label: '', canBeHidden:false)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = base64_decode($id);

        if ($id == 0) {

            $proj = array(
                'id' => 0,
                'title' => '',
                'description' => '',
                'status' => 'Enabled',
                'media_sp' => 0,
                'media_pf' => 0,
                'sitelink' => null,
                'gitlink' => null
            );

            return view('projects.new-project-form', [
                'proj' => $proj,
            ]);

        } else {

            $proj = Projects::findOrFail($id);

            return view('projects.edit-project-form', [
                'proj' => $proj,
            ]);

        }

    }

    /**
     * Creating a new resource.
     */
    public function create(Request $request)
    {
        
        $this->validate($request, [
            'title' => 'required|max:254|unique:projects',
            'description' => 'max:254',
            'status' => 'required',
            'media_sp' => 'numeric|min:0|max:255',
            'media_pf' => 'numeric|min:0|max:255',
            'sitelink' => 'nullable|string|max:255',
            'gitlink' => 'nullable|string|max:255'
        ]);

        $input = $request->all();

        try {
            
            Projects::create($input);

            Toast::title(__('Project saved!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('Error! ' .  $e->getMessage()))->danger()->autoDismiss(15);
            return response()->json(['messagem' => $e], 422);
            
        }

        return redirect()->route('projects.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = base64_decode($id);

        $this->validate($request, [
            'title' => 'required|max:254|unique:projects,title,'.$id,
            'description' => 'max:254',
            'status' => 'required',
            'media_sp' => 'numeric|min:0|max:255',
            'media_pf' => 'numeric|min:0|max:255',
            'sitelink' => 'nullable|string|max:255',
            'gitlink' => 'nullable|string|max:255'
        ]);
       
        $input = $request->all();

        $projs = Projects::findOrFail($id);

        try {
            
            $projs->fill($input);

            $projs->save();

            Toast::title(__('Project saved!'))->autoDismiss(5);

        } catch (\Exception $e) {

            
            Toast::title(__('Error! ' .  $e->getMessage()))->danger()->autoDismiss(15);
            return response()->json(['messagem' => $e], 422);
            
        }

        return redirect()->back();
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

        $proj = Projects::findOrFail($id);

        return view('projects.delete-project-form', [
            'proj' => $proj,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $proj = Projects::findOrFail($id);

        try{

            $proj->delete();
            Toast::title(__('Project deleted!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('Project cannot be deleted!' . $e->getMessage()))->danger()->autoDismiss(5);
            
        }

        return redirect()->route('projects.index');

    }

    /**
     * Display Users Associeted to a Project
     */
    public function users(string $id)
    {
        $projects_id = base64_decode($id);

        $ret = UsersProjects::Select('name','email','gp','relator','tester','dev','admin','users.active','users.avatar')
            ->leftJoin('projects','projects.id','=','users_projects.projects_id')
            ->leftJoin('users','users.id','=','users_projects.users_id')
            ->Where('projects_id','=',$projects_id)
            ->orderby('name')
            ->get();

            return view('projects.users', [
                'ret' => SpladeTable::for($ret)
                    ->perPageOptions([50])
                    ->defaultSort('','desc')
                    ->column('name', label: __('User'), sortable: true, searchable: false, canBeHidden:false)
                    ->column('email', label: __('Email'), sortable: true, searchable: false, canBeHidden:false)
                    ->column('gp', label: __('gp'), searchable: false, canBeHidden:false)
                    ->column('relator', label: __('relator'), searchable: false, canBeHidden:false)
                    ->column('dev', label: __('dev'), searchable: false, canBeHidden:false)
                    ->column('tester', label: __('tester'), searchable: false, canBeHidden:false)
            ]);


    }
}
