<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Documents;
use App\Models\Tipodocs;
use ZipArchive;
use Ramsey\Uuid\Uuid;

use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (! isset(Session::get('ret')[0]['id'])) {

            return redirect()->back();

        }

        $projects_id = Session::get('ret')[0]['id'];
        
        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('documents.title', 'LIKE', "%$value%")
                        ->orwhere('tipodocs.title', 'LIKE', "%$value%")
                        ->orwhere('users.name', 'LIKE', "%$value%");

                });
            });
        });

        $ret = QueryBuilder::for(Documents::class)
            ->select("projects.title as project","documents.title","documents.datadoc","documents.id","tipodocs.title as tipodoc","users.name")
            ->leftJoin('projects','projects.id','=','documents.projects_id')
            ->leftJoin('tipodocs','tipodocs.id','=','documents.tipodocs_id')
            ->leftJoin('users','users.id','=','documents.users_id')
            ->where('documents.projects_id','=',$projects_id)
            ->orderby('documents.datadoc')
            ->allowedFilters(['title', 'projects_id', $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('documents.result-search', [
            'ret' => SpladeTable::for($ret)
                ->withGlobalSearch()
                ->perPageOptions([])
                ->defaultSort('title','desc')
                ->column('project', label: __('Project'),  canBeHidden:false)
                ->column('title', label: __('Title'), searchable: true)
                ->column('tipodoc', label: __('Type'), searchable: true)
                ->column('datadoc', label: __('Data'), searchable: false, as: fn ($datadoc) => date('d/m/Y', strtotime($datadoc)))
                ->column('name', label: __('User'), searchable: true)
                ->column('action', label: '', canBeHidden:false)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = base64_decode($id);

        $tipodocs = Tipodocs::where('status','Enabled')->get();

        if ($id == 0) {

            $ret = array(
                'id' => 0,
                'title' => '',
                'tipodocs_id' => 0
            );

            return view('documents.new-form', [
                'ret' => $ret,
                'tipodocs' => $tipodocs
            ]);

        } else {

            $ret = Documents::findOrFail($id);

            return view('documents.edit-form', [
                'ret' => $ret,
                'tipodocs' => $tipodocs
            ]);

        }

    }

    /**
     * Creating a new resource.
     */
    public function create(Request $request)
    {

        $input = $request->all();

        $input['users_id'] = auth('sanctum')->user()->id;
        $input['projects_id'] =  Session::get('ret')[0]['id'];

        $arqs = $request->file('arquivos');

        $zip_file = '';

        if (!is_null($arqs)) {
            $created = date('Y');
            $destinationPath = public_path('uploads/' . $input['projects_id'] . '/' . $created );
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $zip_file = Uuid::uuid4() . '.zip';
            while (file_exists($destinationPath . '/' . $zip_file)) {
                $zip_file = Uuid::uuid4() . '.zip';
            }

            $destino = $destinationPath . '/' . $zip_file;

            $zip = new ZipArchive();

            $zipStatus = $zip->open($destino, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if ($zipStatus == true) {

                foreach($arqs as $file) {
                    
                    $zip->addFile($file, basename($file->getClientOriginalName()));

                }

                $zip->close();

            }
        }

        $input['file'] = $zip_file;
       
        try {
            
            Documents::create($input);

            Toast::title(__('Document saved!'))->autoDismiss(5);

            return redirect()->route('documents.index',0);

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e->getMessage()))->danger()->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = base64_decode($id);

        $ret = Documents::findOrFail($id);

        $input = $request->all();

        $ret->fill($input);

        try {
            
            $ret->save();

            Toast::title(__('Document saved!'))->autoDismiss(5);

            return redirect()->back();

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e->getMessage()))->danger()->autoDismiss(5);

            return response()->json(['messagem' => $e], 422);
            
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {

        $id = base64_decode($id);

        $ret = Documents::findOrFail($id);

         return view('documents.confirm-delete', [
            'ret' => $ret,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $ret = Documents::findOrFail($id);

        try {
            
            $ret->delete();

            if (! empty($ret['file'])) {
                $created = substr($ret['created_at'],0,4);
                $destinationPath = public_path('uploads/' . $ret['projects_id'] . '/' . $created) . '/' . $ret['file'];
    
                if (file_exists($destinationPath)) {
                    unlink($destinationPath);
                }
            }

            Toast::title(__('Document deleted!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('Document cannot be deleted!'))->danger()->autoDismiss(5);
            
        }

        return redirect()->back();

    }
}
