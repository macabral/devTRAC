<?php

namespace App\Tables;

use App\Models\Tickets;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Sprints;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;

class myTickets extends AbstractTable
{
    private $projects_id;
    private $userId;
    private $relator;
    private $dev;
    private $gp;
    private $admin;
    private $sprints;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
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
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        $sprints = Sprints::select('version','id')->where('projects_id','=',$this->projects_id)->where('status','=','Open')->get();

        $this->sprints = $sprints->pluck('version','id')->toArray();

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

        return QueryBuilder::for(Tickets::class)
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

    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->withGlobalSearch(columns: ['id'])
            ->perPageOptions([])
            ->withGlobalSearch()
            ->selectFilter('sprints_id', $this->sprints)
            ->column('id', label: __('ID'), searchable: true)
            ->column('project', label: __('Project'),  searchable: false, canBeHidden:false)
            ->column('sprint', label: __('Sprint'))
            ->column('start', label: __(''),searchable: false, canBeHidden:false)
            ->column('title', label: __('Title'))
            ->column('type', label: __('Type'))
            ->column('relator', label: __('Relator'))
            ->column('resp', label: __('Assign to'))
            ->column('prioridade', label: __('Priority'))
            ->column('status', label: __('Status'))
            ->column('action', label: '', canBeHidden:false, exportAs: false);
            // ->export();

    }
}
