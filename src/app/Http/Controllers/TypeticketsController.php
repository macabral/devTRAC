<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;
use Illuminate\Support\Facades\Session;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Type;

class TypeticketsController extends Controller
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
                        ->orwhere('title', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Type::class)
            ->orderby('created_at', 'desc')
            ->allowedSorts(['title'])
            ->allowedFilters(['title', 'status',  $globalSearch])
            ->paginate(10)
            ->withQueryString();

        return view('typetickets.result-search', [
            'projs' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->defaultSort('title','desc')
                ->column('title', label: __('Type'), sortable: true, searchable: true, canBeHidden:false)
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

            $ret = array(
                'id' => 0,
                'title' => '',
                'status' => 'Enabled'
            );

            return view('typetickets.new-form', [
                'ret' => $ret,
            ]);

        } else {

            $ret = Type::findOrFail($id);

            return view('typetickets.edit-form', [
                'ret' => $ret,
            ]);

        }

    }

    /**
     * Creating a new resource.
     */
    public function create(Request $request,)
    {
        
        $this->validate($request, [
            'title' => 'required|max:255',
            'status' => 'required'
        ]);

        $input = $request->all();

        try {
            
            Type::create($input);

        } catch (\Exception $e) {

            Toast::title(__('Type error!' . $e->getMessage()))->danger()->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('Type saved!'))->autoDismiss(5);

        return redirect()->route('typetickets.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title' => 'required|max:254',
            'status' => 'required'
        ]);

        $id = base64_decode($id);
        
        $input = $request->all();

        $ret = Type::findOrFail($id);

        try {
            
            $ret->fill($input);

        } catch (\Exception $e) {

            return response()->json(['messagem' => $e], 422);
            
        }

        $ret->save();

        Toast::title(__('Type saved!'))->autoDismiss(5);

        return redirect()->back();
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {

        $id = base64_decode($id);

        $ret = Type::findOrFail($id);

        return view('typetickets.confirm-delete', [
            'ret' => $ret,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $ret = Type::findOrFail($id);

        try {
            
            $ret->delete();

            Toast::title(__('Type deleted!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('Type cannot be deleted!'))->danger()->autoDismiss(5);
            
        }


        return redirect()->back();

    }
}
