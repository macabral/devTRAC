<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Session;
use App\Models\Projects;

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
                        ->orwhere('description', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Projects::class)
            ->orderby('created_at', 'desc')
            ->allowedSorts(['title'])
            ->allowedFilters(['description', 'status',  $globalSearch])
            ->paginate(10)
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
                'status' => 'Enabled'
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
            'title' => 'required|max:254',
            'description' => 'max:254',
            'status' => 'required'
        ]);

        $input = $request->all();

        try {
            
            Projects::create($input);

        } catch (\Exception $e) {

            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('Project saved!'))->autoDismiss(5);

        return redirect()->route('projects.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title' => 'required|max:254',
            'description' => 'max:254',
            'status' => 'required'
        ]);

        $id = base64_decode($id);
        
        $input = $request->all();

        $projs = Projects::findOrFail($id);

        try {
            
            $projs->fill($input);

        } catch (\Exception $e) {

            return response()->json(['messagem' => $e], 422);
            
        }

        $projs->save();

        Toast::title(__('Project saved!'))->autoDismiss(5);

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

            Toast::title(__('Project cannot be deleted!'))->danger()->autoDismiss(5);
            
        }

        return redirect()->back();

    }
}
