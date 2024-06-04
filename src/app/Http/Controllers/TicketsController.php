<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Library\LogService;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use ZipArchive;
use App\Models\Tickets;
use App\Models\Sprints;
use App\Models\UsersProjects;
use App\Models\Type;
use App\Models\Logtickets;
use App\Mail\NewTicket;
use Illuminate\Support\Facades\Mail;

class TicketsController extends Controller
{
    private $projects_id;
    private $userId;
    private $relator;
    private $dev;
    private $gp;
    private $admin;

    private function init()
    {
        if (! isset(Session::get('ret')[0]['id']) || is_null(Session::get('ret'))) {
            Session::forget(Session::driver()->getId());
            Session::invalidate();
            Auth::guard('web')->logout();
            return redirect('/login');
        }

        $this->projects_id = Session::get('ret')[0]['id'];
        $this->userId = auth('sanctum')->user()->id;
        $this->relator = Session::get('ret')[0]['relator'];
        $this->dev = Session::get('ret')[0]['dev'];
        $this->gp = Session::get('ret')[0]['gp'];
        $this->admin = Session::get('ret')[0]['admin'];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->init();
        
        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('tickets.title', 'LIKE', "%$value%")
                        ->orwhere('tickets.description', 'LIKE', "%$value%")
                        ->orwhere('sprints.version', 'LIKE', "%$value%")
                        ->orwhere('types.title', 'LIKE', "%$value%")
                        ->orwhere('tickets.prioridade', 'LIKE', "%$value%")
                        ->orwhere('a.name', 'LIKE', "%$value%");
                });
            });
        });

        $sprints = Sprints::select('version','id')->where('projects_id','=',$this->projects_id)->orderby('start')->get();

        $sprints = $sprints->pluck('version','id')->toArray();

        $ret = QueryBuilder::for(Tickets::class)
            ->select("projects.title as project","tickets.id","tickets.title","tickets.status","tickets.start","tickets.created_at","tickets.prioridade", "a.name as resp","b.id as user_id","b.name as relator","types.title as type","sprints.version as sprint")
            ->leftJoin('users as a','a.id','=','resp_id')
            ->leftJoin('users as b','b.id','=','relator_id')
            ->leftJoin('types','types.id','=','types_id')
            ->leftJoin('sprints','sprints.id','=','tickets.sprints_id')
            ->leftJoin('projects','projects.id','=','sprints.projects_id')
            ->where('tickets.projects_id','=',$this->projects_id)
            ->orderby('sprints.start')
            ->orderby('prioridade')
            ->orderby('created_at', 'desc')
            ->allowedSorts(['title','type','relator'])
            ->allowedFilters(['id','title', 'status', 'resp', 'prioridade','sprints_id', $globalSearch])
            ->paginate(50)
            ->withQueryString();

        return view('tickets.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->selectFilter('sprints_id',$sprints)
                ->column('id', label: __('ID'), searchable: true)
                ->column('project', label: __('Project'), sortable: true, searchable: false, canBeHidden:false)
                ->column('sprint', label: __('Sprint'))
                ->column('title', label: __('Title'), canBeHidden:false)
                ->column('type', label: __('Type'))
                ->column('relator', label: __('Relator'))
                ->column('resp', label: __('Assign to'))
                ->column('prioridade', label: __('Priority'))
                ->column('status', label: __('Status'), searchable: false)
                ->column('action', label: '', canBeHidden:false)
        ]);
    }

    /**
     * Lista tíquetes por Sprint.
     */
    public function sprint($id)
    {

        $this->init();

        $sprintId = base64_decode($id);

        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('tickets.title', 'LIKE', "%$value%")
                        ->orwhere('tickets.prioridade', 'LIKE', "%$value%")
                        ->orwhere('sprints.version', 'LIKE', "%$value%")
                        ->orwhere('types.title', 'LIKE', "%$value%")
                        ->orwhere('a.name', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Tickets::class)
            ->select("projects.title as project","tickets.id","tickets.title","tickets.status","tickets.start","tickets.created_at","tickets.prioridade", "a.name as resp","b.id as user_id","b.name as relator","types.title as type","sprints.version as sprint")
            ->leftJoin('users as a','a.id','=','resp_id')
            ->leftJoin('users as b','b.id','=','relator_id')
            ->leftJoin('types','types.id','=','types_id')
            ->leftJoin('sprints','sprints.id','=','tickets.sprints_id')
            ->leftJoin('projects','projects.id','=','sprints.projects_id')
            ->where('tickets.sprints_id', $sprintId)
            ->orderby('prioridade')
            ->orderby('created_at', 'desc')
            ->allowedSorts(['title','type','relator'])
            ->allowedFilters(['id','title', 'status', 'resp', $globalSearch])
            ->paginate(50)
            ->withQueryString();

        return view('tickets.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->column('id', label: __('ID'), searchable: true)
                ->column('project', label: __('Project'), sortable: true, searchable: true, canBeHidden:false)
                ->column('sprint', label: __('Sprint'))
                ->column('title', label: __('Title'), canBeHidden:false)
                ->column('type', label: __('Type'))
                ->column('relator', label: __('Relator'))
                ->column('resp', label: __('Assign to'))
                ->column('prioridade', label: __('Priority'))
                ->column('status', label: __('Status'), searchable: true)
                ->column('action', label: '', canBeHidden:false)
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function mytickets()
    {
       
        $this->init();

        $sprints = Sprints::select('version','id')->where('projects_id','=',$this->projects_id)->where('status','=','Open')->get();

        $sprints = $sprints->pluck('version','id')->toArray();

        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('tickets.title', 'LIKE', "%$value%")
                        ->orwhere('tickets.prioridade', 'LIKE', "%$value%")
                        ->orwhere('tickets.description', 'LIKE', "%$value%")
                        ->orwhere('sprints.version', 'LIKE', "%$value%")
                        ->orwhere('types.title', 'LIKE', "%$value%")
                        ->orwhere('a.name', 'LIKE', "%$value%")
                        ->orwhere('b.name', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Tickets::class)
            ->select("tickets.title","tickets.id","tickets.status","tickets.start","tickets.created_at","tickets.prioridade","a.name as resp","b.id as user_id","b.name as relator","types.title as type","sprints.version as sprint","projects.title as project")
            ->leftJoin('users as a','a.id','=','resp_id')
            ->Join('users as b','b.id','=','relator_id')
            ->Join('types','types.id','=','types_id')
            ->Join('sprints','sprints.id','=','tickets.sprints_id')
            ->Join('projects','projects.id','=','tickets.projects_id')
            ->Where('sprints.status', '=', 'Open')
            ->Where('tickets.projects_id','=', $this->projects_id)
            ->Where('tickets.status', '!=', 'Closed')
            ->Where(function($query) {
                if ($this->admin != '1' || $this->gp != '1') {
                    if ($this->dev == '1') {
                        $query->orwhere('tickets.resp_id', '=', $this->userId);
                    }
                    if ($this->relator == '1') {
                        $query->orwhere('tickets.relator_id', '=', $this->userId);
                    }
                }
            })
            ->orderby('sprints_id')
            ->orderby('status')
            ->orderby('prioridade')
            ->orderBy('created_at')
            ->allowedFilters(['id','title', 'status', 'sprints_id', $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('tickets.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->selectFilter('sprints_id',$sprints)
                ->column('id', label: __('ID'), searchable: true)
                ->column('project', label: __('Project'), sortable: true, searchable: false, canBeHidden:false)
                ->column('sprint', label: __('Sprint'))
                ->column('start', label: __(''),searchable: false, canBeHidden:false)
                ->column('title', label: __('Title'))
                ->column('type', label: __('Type'))
                ->column('relator', label: __('Relator'))
                ->column('resp', label: __('Assign to'))
                ->column('prioridade', label: __('Priority'))
                ->column('status', label: __('Status'))
                ->column('action', label: '', canBeHidden:false, exportAs: false)
        ]);
    }


    /**
     * Display a listing of the resource.
     */
    public function testing()
    {
        $this->init();
   
        $sprints = Sprints::select('version','id')->where('projects_id','=',$this->projects_id)->where('status','=','Open')->get();

        $sprints = $sprints->pluck('version','id')->toArray();

        $globalSearch = AllowedFilter::callback('global', function ($query,$value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orwhere('tickets.title', 'LIKE', "%$value%")
                        ->orwhere('tickets.description', 'LIKE', "%$value%")
                        ->orwhere('sprints.version', 'LIKE', "%$value%")
                        ->orwhere('tickets.prioridade', 'LIKE', "%$value%")
                        ->orwhere('types.title', 'LIKE', "%$value%");
                });
            });
        });

        $ret = QueryBuilder::for(Tickets::class)
            ->select("tickets.*", "a.name as resp","b.id as user_id","b.name as relator","types.title as type","sprints.version as sprint","projects.title as project")
            ->where('tickets.status', 'Testing')
            ->leftJoin('users as a','a.id','=','resp_id')
            ->leftJoin('users as b','b.id','=','relator_id')
            ->leftJoin('types','types.id','=','types_id')
            ->leftJoin('sprints','sprints.id','=','tickets.sprints_id')
            ->leftJoin('projects','projects.id','=','tickets.projects_id')
            ->Where('tickets.projects_id','=',$this->projects_id)
            ->orderby('prioridade')
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->allowedSorts(['title','type','relator'])
            ->allowedFilters(['id','title', 'status', 'sprints_id', $globalSearch])
            ->paginate(7)
            ->withQueryString();

        return view('tickets.result-search', [
            'ret' => SpladeTable::for($ret)
                ->perPageOptions([])
                ->withGlobalSearch()
                ->selectFilter('sprints_id',$sprints)
                ->column('id', label: __('ID'), searchable: true)
                ->column('project', label: __('Project'), sortable: true, searchable: true, canBeHidden:false)
                ->column('sprint', label: __('Sprint'))
                ->column('title', label: __('Title'), canBeHidden:false)
                ->column('type', label: __('Type'))
                ->column('relator', label: __('Relator'))
                ->column('resp', label: __('Assign to'))
                ->column('prioridade', label: __('Priority'))
                ->column('status', label: __('Status'), searchable: true)
                ->column('action', label: '', canBeHidden:false)
        ]);
    }

    /**
     * Display the specified resource.
    */
    public function show(string $id)
    {
        $this->init();

        $id = base64_decode($id);

        $hoje = Carbon::today();

        $projects = UsersProjects::select('projects.id','title')
            ->leftJoin('projects','projects.id','=','projects_id')
            ->where('users_id','=',$this->userId)
            ->where('projects_id','=',$this->projects_id)
            ->get();

        // sprints
        if ($this->gp == '1') {
            $sprints = Sprints::select('id','version')->wherein('status',['Open','Waiting'])->where('sprints.projects_id', $this->projects_id)->where('end','>=',$hoje)->orderBy('status')->get();
        } else {
            $sprints = Sprints::select('id','version')->where('projects_id', $this->projects_id)->where('status','Waiting')->where('end','>=',$hoje)->orderBy('status')->get();
        }

        // devs
        $devs = UsersProjects::select('users_id as id','name')
            ->leftJoin('users','users.id','=','users_id')
            ->where('projects_id', $this->projects_id)
            ->where('dev', '1')
            ->where('users.active','=',1)
            ->orderby('name')
            ->get();

        // Type 
        $types = Type::select('id','title')->where('status','Enabled')->get();

        if ($id == 0) {

            $ret = array(
                'id' => 0,
                'title' => '',
                'description' => '',
                'status' => 'Open',
                'projects_id' => $this->projects_id,
                'perfil' => $this->gp,
                'storypoint' => 0,
                'pf' => 0
            );

            return view('tickets.new-form', [
                'ret' => $ret,
                'sprints' => $sprints,
                'devs' => $devs,
                'types' => $types,
                'projects' => $projects,
                'perfil' => $this->gp
            ]);

        } else {

            $ret = Tickets::findOrFail($id);

            return view('tickets.edit-form', [
                'ret' => $ret,
                'sprints' => $sprints,
                'devs' => $devs,
                'types' => $types,
                'projects' => $projects,
                'perfil' => $this->gp
            ]);

        }

    }
    
    /**
     * Display the specified resource.
    */
    public function edit(string $id)
    {
        $this->init();

        $id = base64_decode($id);

        $ret = Tickets::
            select("tickets.*","projects.title as project","a.name as resp","b.name as relator","types.title as type","sprints.version as sprint")
            ->leftJoin('projects','projects.id','=','tickets.projects_id')
            ->leftJoin('users as a','a.id','=','resp_id')
            ->leftJoin('users as b','b.id','=','relator_id')
            ->leftJoin('types','types.id','=','types_id')
            ->leftJoin('sprints','sprints.id','=','tickets.sprints_id')
            ->where('tickets.id', $id)
            ->where('tickets.projects_id','=',$this->projects_id)
            ->get();

        $log = Logtickets::
            select("logtickets.*", "users.name")
            ->leftJoin('users','users.id','=','users_id')
            ->where('tickets_id', $id)
            ->where(
                function($query) {
                  return $query
                         ->where('origin',"=",null)
                         ->orWhere('origin','=',0);
                 })
            ->orderBy('Created_at')
            ->get();

        // verifica se o item foi editado (se origin = 0)
        $cont = 0;
        foreach($log as $item) {

            if ($item->origin == 0) {
                $result =  Logtickets::select("logtickets.*", "users.name")
                    ->leftJoin('users','users.id','=','users_id')
                    ->where('tickets_id', $id)
                    ->where('origin', $item->id)
                    ->orderBy('Created_at','desc')
                    ->limit(1)
                    ->get();

                if (count($result) != 0) {
                    $log[$cont] = $result[0];
                }
            }
            ++$cont;
        }

         return view('tickets.detail-form', [
            'ret' => $ret[0],
            'logs' => $log
        ]);
    }


    /**
     * Creating a new resource.
     */
    public function create(Request $request)
    {
        $this->init();
        
        $this->validate($request, [
            'projects_id' => 'required',
            'title' => 'required|max:255',
            'status' => 'required',
            'sprints_id' => 'required',
            'types_id' => 'required',
            'prioridade' => 'required'
        ]);

        $input = $request->all();

        $sp = [0, 1, 2, 3, 5, 8, 13, 20, 40, 100];

        $input['valorsp'] = $sp[$input['storypoint']];

        $input['relator_id'] = $this->userId;

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

            $ret = Tickets::create($input);
            
            Mail::Queue(new NewTicket($ret['id']));

            Toast::title(__('Ticket saved!'))->autoDismiss(5);

        } catch (\Exception $e) {

            Toast::title(__('Error! ' .  $e->getMessage()))->danger()->autoDismiss(15);
            return response()->json(['messagem' => $e], 422);
            
        }
        
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, LogService $logServiceInstance)
    {

        $this->validate($request, [
            'title' => 'required|max:255',
            'status' => 'required',
            'sprints_id' => 'required',
            'types_id' => 'required'
        ]);

        $id = base64_decode($id);
        
        $input = $request->all();

        $ret = Tickets::findOrFail($id);

        $sp = [0, 1, 2, 3, 5, 8, 13, 20, 40, 100];

        $input['valorsp'] = $sp[$input['storypoint']];

        try {

            // registra log das alterações
            $logServiceInstance->saveLog($id, $ret, $input);
            
            $ret->fill($input);

            $ret->save();

            Toast::title(__('Ticket saved!'))->autoDismiss(5);

        } catch (\Exception $e) {

            return response()->json(['messagem' => $e], 422);
            
        }

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

    /**
     * Inicia a tarefa
     */
    public function start(string $id)
    {

        $id = base64_decode($id);

        $ret = Tickets::findOrFail($id);

        $ret['start'] = 1;

        $ret->save();

        return redirect()->back();

    }

    /**
     * Pausa a tarefa
     */
    public function pause(string $id)
    {

        $id = base64_decode($id);

        $ret = Tickets::findOrFail($id);

        $ret['start'] = 2;
        
        $ret->save();

        return redirect()->back();

    }
}
