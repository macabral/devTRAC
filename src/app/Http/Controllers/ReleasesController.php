<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Releases;
use App\Models\Tickets;
use App\Models\UsersProjects;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Requests\ReleaseRequest;
use App\Http\Requests\ReleaseRequestUpdate;

class ReleasesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (! isset(Session::get('ret')[0]['id'])) {

            return redirect()->back();

        }

        $userId = auth('sanctum')->user()->id;

        $projects_id = Session::get('ret')[0]['id'];
        
        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('version', 'LIKE', "%$value%")
                        ->orwhere('releases.description', 'LIKE', "%$value%")
                        ->orwhere('releases.projects_id', '=', $value);
                });
            });
        });


        $ret = QueryBuilder::for(Releases::class)
            ->select("projects.title as project","releases.id","releases.version","releases.description as desc","releases.start","releases.end","releases.status")
            ->leftJoin('projects','projects.id','=','releases.projects_id')
            ->where('releases.projects_id','=',$projects_id)
            ->orderby('releases.start')
            ->allowedSorts(['version'])
            ->allowedFilters(['version', 'description', 'status', 'projects_id', $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('releases.result-search', [
            'ret' => SpladeTable::for($ret)
                ->withGlobalSearch()
                ->perPageOptions([])
                ->defaultSort('title','desc')
                ->column('project', label: __('Project'),  canBeHidden:false)
                ->column('version', label: __('Sprint'), searchable: true, canBeHidden:false)
                ->column('desc', label: __('Description'), searchable: true)
                ->column('start', label: __('Start'), searchable: false, as: fn ($datadoc) => date('d/m/Y', strtotime($datadoc)))
                ->column('end', label: __('End'), searchable: false, as: fn ($datadoc) => date('d/m/Y', strtotime($datadoc)))
                ->column('status', label: __('Status'), searchable: true)
                ->column('action', label: '', canBeHidden:false)
                ->column('action', label: '', canBeHidden:false)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        if (! isset(Session::get('ret')[0]['id'])) {

            return redirect()->back();

        }

        $id = base64_decode($id);

        $projects_id = Session::get('ret')[0]['id'];

        $userId = auth('sanctum')->user()->id;

        $projects = UsersProjects::select('projects.id','title')
            ->leftJoin('projects','projects.id','=','projects_id')
            ->where('users_id','=',$userId)
            ->where('relator','=','1')
            ->where('projects_id','=',$projects_id)
            ->get();

        if (isset($projects) && $id == 0) {
            $project = $projects[0]->id;
        } else {
            $project = 0;
        }

        if ($id == 0) {

            $ret = array(
                'id' => 0,
                'version' => '',
                'description' => '',
                'status' => 'Open',
                'projects_id' => $project,

            );

            return view('releases.new-form', [
                'ret' => $ret,
                'projects' => $projects,
            ]);

        } else {

            $ret = Releases::findOrFail($id);

            return view('releases.edit-form', [
                'ret' => $ret,
                'projects' => $projects
            ]);

        }

    }

    /**
     * Creating a new resource.
     */
    public function create(ReleaseRequest $request,)
    {

        $input = $request->all();
        
        try {
            
            Releases::create($input);

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e->getMessage()))->danger()->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('Sprint saved!'))->autoDismiss(5);

        return redirect()->route('releases.index',0);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReleaseRequestUpdate $request, string $id)
    {
        $id = base64_decode($id);

        $ret = Releases::findOrFail($id);

        $input = $request->all();

        $ret->fill($input);

        try {
            
            $ret->save();

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e->getMessage()))->danger()->autoDismiss(5);

            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('Sprint saved!'))->autoDismiss(5);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {

        $id = base64_decode($id);

        $ret = Releases::findOrFail($id);

        return view('releases.confirm-delete', [
            'ret' => $ret,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $ret = Releases::findOrFail($id);

        try {
            
            $ret->delete();

            Toast::title(__('Sprit deleted!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('Sprint cannot be deleted!'))->danger()->autoDismiss(5);
            
        }

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function exports($id)
    {
 
        $id = base64_decode($id);

        $ret = QueryBuilder::for(Tickets::class)
            ->select("tickets.*", "a.name as resp","b.id as user_id","b.name as relator","types.title as type","releases.version as release","projects.title as project")
            ->where('releases_id', '=', $id)
            ->Join('users as a','a.id','=','resp_id')
            ->Join('users as b','b.id','=','relator_id')
            ->Join('types','types.id','=','types_id')
            ->Join('releases','releases.id','=','tickets.releases_id')
            ->Join('projects','projects.id','=','tickets.projects_id')
            ->orderby('status')
            ->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        $sheet = $spreadsheet->getActiveSheet();
            
        $linha = 2;
        $sheet->setCellValue('A' . $linha, "ID");
        $sheet->setCellValue('B' . $linha, __('Title'));
        $sheet->setCellValue('C' . $linha, __('Release'));
        $sheet->setCellValue('D' . $linha, __('Type'));
        $sheet->setCellValue('E' . $linha, __('Relator'));
        $sheet->setCellValue('F' . $linha, __('Assign to'));
        $sheet->setCellValue('G' . $linha, "Status");
        $sheet->setCellValue('H' . $linha, __('Created at'));


        foreach($ret as $item) {
            ++$linha;
            $sheet->setCellValue('A' . $linha, $item->id);
            $sheet->setCellValue('B' . $linha, $item->title);
            $sheet->getCell('C' . $linha)->setValueExplicit($item->release,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING );
            $sheet->setCellValue('D' . $linha, $item->type);
            $sheet->setCellValue('E' . $linha, $item->relator);
            $sheet->setCellValue('F' . $linha, $item->resp);
            $sheet->setCellValue('G' . $linha, $item->status);
            $sheet->setCellValue('H' . $linha, $item->created_at);
        }
    
        $writer = new Xlsx($spreadsheet);
        $fileName = 'release-tickets.xlsx';

        $path = public_path('/uploads/downloads/' . auth('sanctum')->user()->id);
        
        if (! is_dir($path)) {
            mkdir($path, 0757, true);
        }

        $path = $path . '/' . $fileName;

        $writer->save($path);

        return response()->download($path, $fileName)->deleteFileAfterSend(true);

    }
}
