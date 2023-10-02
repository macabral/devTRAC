<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Library\LogService;
use Ramsey\Uuid\Uuid;
use ZipArchive;
use App\Models\Tickets;
use App\Models\Releases;
use App\Models\UsersProjects;
use App\Models\Type;
use App\Models\Logtickets;
use App\Models\User;
use App\Library\TracMail;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Session::get('ret')[0]['id'];

        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('tickets.title', 'LIKE', "%$value%")
                        ->orwhere('tickets.description', 'LIKE', "%$value%")
                        ->orwhere('releases.version', 'LIKE', "%$value%")
                        ->orwhere('types.title', 'LIKE', "%$value%")
                        ->orwhere('a.name', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Tickets::class)
            ->select("tickets.*", "a.name as resp","b.id as user_id","b.name as relator","types.title as type","releases.version as release")
            ->where('tickets.projects_id', $project)
            ->leftJoin('users as a','a.id','=','resp_id')
            ->leftJoin('users as b','b.id','=','relator_id')
            ->leftJoin('types','types.id','=','types_id')
            ->leftJoin('releases','releases.id','=','tickets.releases_id')
            ->orderby('created_at', 'desc')
            ->allowedSorts(['title','type','relator'])
            ->allowedFilters(['id','title', 'status', 'resp', $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('tickets.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->column('id', label: __('ID'), searchable: true)
                ->column('title', label: __('Title'), canBeHidden:false)
                ->column('release', label: __('Release'))
                ->column('type', label: __('Type'))
                ->column('relator', label: __('Relator'))
                ->column('resp', label: __('Assign to'))
                ->column('status', label: __('Status'), searchable: true)
                ->column('action', label: '', canBeHidden:false)
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function mytickets()
    {

        $userId = auth('sanctum')->user()->id;

        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('tickets.title', 'LIKE', "%$value%")
                        ->orwhere('tickets.description', 'LIKE', "%$value%")
                        ->orwhere('releases.version', 'LIKE', "%$value%")
                        ->orwhere('types.title', 'LIKE', "%$value%")
                        ->orwhere('a.name', 'LIKE', "%$value%")
                        ->orwhere('b.name', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Tickets::class)
            ->select("tickets.*", "a.name as resp","b.id as user_id","b.name as relator","types.title as type","releases.version as release","projects.title as project")
            ->Join('users as a','a.id','=','resp_id')
            ->Join('users as b','b.id','=','relator_id')
            ->Join('types','types.id','=','types_id')
            ->Join('releases','releases.id','=','tickets.releases_id')
            ->Join('projects','projects.id','=','tickets.projects_id')
            ->Where(function($query) {
                $query->where('tickets.status', '=', 'Open')
                    ->orwhere('tickets.status', '=', 'Testing');
                })
            ->Where(function($query) {
                $query->where('tickets.resp_id', '=', auth('sanctum')->user()->id)
                    ->orwhere('tickets.relator_id', '=', auth('sanctum')->user()->id);
                })
            ->where('releases.status', '=', 'Open')
            ->orderby('status')
            ->orderBy('created_at', 'desc')
            ->allowedFilters(['id','title', 'status', $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('tickets.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->column('id', label: __('ID'), searchable: true)
                ->column('title', label: __('Title'))
                ->column('project', label: __('Project'))
                ->column('release', label: __('Release'))
                ->column('type', label: __('Type'))
                ->column('relator', label: __('Relator'))
                ->column('resp', label: __('Assign to'))
                ->column('status', label: __('Status'))
                ->column('action', label: '', canBeHidden:false, exportAs: false)
        ]);
    }


    /**
     * Display a listing of the resource.
     */
    public function testing()
    {
   
        $user = auth('sanctum')->user()->id;

        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('tickets.title', 'LIKE', "%$value%")
                        ->orwhere('tickets.description', 'LIKE', "%$value%")
                        ->orwhere('releases.version', 'LIKE', "%$value%")
                        ->orwhere('types.title', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Tickets::class)
            ->select("tickets.*", "a.name as resp","b.id as user_id","b.name as relator","types.title as type","releases.version as release","projects.title as project")
            ->where('tickets.status', 'Testing')
            ->leftJoin('users as a','a.id','=','resp_id')
            ->leftJoin('users as b','b.id','=','relator_id')
            ->leftJoin('types','types.id','=','types_id')
            ->leftJoin('releases','releases.id','=','tickets.releases_id')
            ->leftJoin('projects','projects.id','=','tickets.projects_id')
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->allowedSorts(['title','type','relator'])
            ->allowedFilters(['id','title', 'status', $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('tickets.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->column('id', label: __('ID'), searchable: true)
                ->column('title', label: __('Title'), canBeHidden:false)
                ->column('project', label: __('Project'), canBeHidden:false)
                ->column('type', label: __('Type'))
                ->column('release', label: __('Release'))
                ->column('relator', label: __('Relator'))
                ->column('resp', label: __('Assign to'))
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

        $project = $project = Session::get('ret')[0]['id'];

        // releases
        $releases = Releases::select('id','version')->where('status','Open')->orwhere('status','Waiting')->where('projects_id', $project)->orderBy('version')->get();

        // devs
        $devs = UsersProjects::select('users_id','name')->where('projects_id', $project)->where('dev', '1')->leftJoin('users','users.id','=','users_id')->where('users.active','=',1)->get();

        // Type 
        $types = Type::select('id','title')->where('status','Enabled')->get();

        if ($id == 0) {

            $ret = array(
                'id' => 0,
                'title' => '',
                'description' => '',
                'status' => 'Open'
            );

            return view('tickets.new-form', [
                'ret' => $ret,
                'releases' => $releases,
                'devs' => $devs,
                'types' => $types
            ]);

        } else {

            $ret = Tickets::findOrFail($id);

            return view('tickets.edit-form', [
                'ret' => $ret,
                'releases' => $releases,
                'devs' => $devs,
                'types' => $types
            ]);

        }

    }
    
    /**
     * Display the specified resource.
    */
    public function edit(string $id)
    {
        $id = base64_decode($id);

        $queryTicket = Tickets::
            select("tickets.*", "a.name as resp","b.name as relator","types.title as type","releases.version as release")
            ->where('tickets.id', $id)
            ->leftJoin('users as a','a.id','=','resp_id')
            ->leftJoin('users as b','b.id','=','relator_id')
            ->leftJoin('types','types.id','=','types_id')
            ->leftJoin('releases','releases.id','=','tickets.releases_id')->get();

        $queryLogs = Logtickets::
            select("logtickets.*", "users.name")
            ->where('tickets_id', $id)
            ->orderBy('Created_at')
            ->leftJoin('users','users.id','=','users_id')->get();

        return view('tickets.detail-form', [
            'ret' => $queryTicket[0],
            'logs' => $queryLogs
        ]);
    }


    /**
     * Creating a new resource.
     */
    public function create(Request $request, TracMail $TracMailInstance)
    {
        
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => 'required',
            'releases_id' => 'required',
            'resp_id' => 'required',
            'types_id' => 'required'
        ]);

        $input = $request->all();

        $input['projects_id'] = Session::get('ret')[0]['id'];
        $input['relator_id'] = auth('sanctum')->user()->id;

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

                $input['docs'] = $zip->count();

                $zip->close();

            }
        }

        $input['file'] = $zip_file;

        Try {

            Tickets::create($input);

        } catch (\Exception $e) {

            Toast::title(__('Error! ' .  $e))->danger()->autoDismiss(15);
            return response()->json(['messagem' => $e], 422);
            
        }
        
        try {

            $data = Tickets::latest()->first();

            $destinatario = User::select('email')->where('id','=',$data['resp_id'])->get();

            $id = $data['id'];
            $title = $data['title'];

            $mailData = [
                'to' => $destinatario[0]['email'],
                'cc' => null,
                'subject' => 'devTRAC: Novo Tíquete',
                'title' => "Novo Tíquete",
                'body' => "Você está recebendo esse email porque um Tíquete foi atribuído para você: [$id] - $title",
                'priority' => 0,
                'attachments' => null
            ];
                
            $TracMailInstance->save($mailData);


        } catch (\Exception $e) {

            Toast::title(__('It was not possible to send email notification.'))->danger()->autoDismiss(5);

        }

        Toast::title(__('Ticket saved!'))->autoDismiss(5);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, LogService $logServiceInstance)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => 'required',
            'releases_id' => 'required',
            'resp_id' => 'required',
            'types_id' => 'required'
        ]);

        $id = base64_decode($id);
        
        $input = $request->all();

        $ret = Tickets::findOrFail($id);

        // registra log das alterações
        $logServiceInstance->saveLog($id, $ret, $input);

        try {
            
            $ret->fill($input);

        } catch (\Exception $e) {

            return response()->json(['messagem' => $e], 422);
            
        }

        $ret->save();

        Toast::title(__('Ticket saved!'))->autoDismiss(5);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {

        $id = base64_decode($id);

        $ret = Tickets::findOrFail($id);

        return view('tickets.confirm-delete', [
            'ret' => $ret,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $ret = Tickets::findOrFail($id);

        try {
            
            if (! empty($ret['file'])) {
                $created = substr($ret['created_at'],0,4);
                $destinationPath = public_path('uploads/' . $ret['projects_id'] . '/' . $created) . '/' . $ret['file'];

                if (file_exists($destinationPath)) {
                    unlink($destinationPath);
                }
            }

            $ret->delete();

            Toast::title(__('Ticket deleted!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('Ticket cannot be deleted!'))->danger()->autoDismiss(5);
            
        }

        return redirect()->back();

    }
}
