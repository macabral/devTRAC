<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Session;
use App\Models\Planningpokers;
use App\Models\Tickets;
use App\Models\UsersProjects;


class PlanningpokerController extends Controller
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
    public function index($tickets_id)
    {

        $this->init();

        $id = base64_decode($tickets_id);

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

        $pp = Planningpokers::select('planningpokers.id','users_id','valorsp','name','avatar')
            ->where('tickets_id', $id)
            ->leftjoin('users','users.id','users_id')
            ->get();

        return view('planningpoker.viewpp', [
            'ret' => $ret[0],
            'pp' => $pp
        ]);

    }

    /**
     * Inicia o Planning Poker
    */
    public function start($tickets_id)
    {

        $id = base64_decode($tickets_id);

        $tickets = Tickets::Select('projects_id')
            ->where('tickets.id',$id)
            ->get();

        $projects = $tickets[0];

        $users = UsersProjects::Select('users_id')
            ->where('projects_id',$projects->projects_id)
            ->get();

        $ret = Planningpokers::select('planningpokers.id','tickets_id','users_id')
            ->where('tickets_id', $id)
            ->leftjoin('users','users.id','users_id')
            ->get();

        if (count($ret) != 0) {

            // exclui os registros
            Planningpokers::where('tickets_id',$id)->delete();

        }

        try {
            // insere registros
            foreach($users as $item) {
                $cursor = [
                    'tickets_id' => $id,
                    'users_id' => $item->users_id,
                    'storypoiint' => 0,
                    'valorsp' => 0
                ];
                
                Planningpokers::create($cursor);

            }

            // seta planning_poker_status = 0

            $ret = Tickets::findOrFail($id);
            
            $ret->planning_poker_status = 0;

            $ret->save();

            $ret = Planningpokers::select('planningpokers.id','users_id','valorsp','name','avatar')
                ->where('tickets_id', $id)
                ->leftjoin('users','users.id','users_id')
                ->get();

            return response()->json(['data' => $ret], 200);

        } catch (\Exception $e) {
            
            return response()->json(['messagem' => $e], 422);
                
        }


    }

    /**
     * Voto 
     */
    public function vote($id)
    {

        $id = base64_decode($id);

        $ret = Planningpokers::findOrFail($id);

        return view('planningpoker.vote', [
            'id' => $id
        ]);

    }

    /**
     * Salvar Voto 
     */
    public function save(Request $request, $id)
    {

        $id = base64_decode($id);

        $input = $request->all();

        $sp = [0, 1, 2, 3, 5, 8, 13, 20, 40, 100];

        $input['valorsp'] = $sp[$input['storypoint']];

        $ret = Planningpokers::findOrFail($id);

        $input = [
            'tickets_id' => $ret->tickets_id,
            'users_id' => $ret->users_id,
            'storypoint' => $input['storypoint'],
            'valorsp' => $input['valorsp']
        ];

        try {

            $ret->fill($input);

            $ret->save();

            Toast::title(__('Vote confirmed!'))->autoDismiss(5);

            return redirect()->back();

        } catch (\Exception $e) {

            return response()->json(['messagem' => $e], 422);
            
        }

    }

    /**
     * Show to all
     */
    public function show($tickets_id)
    {

        $id = base64_decode($tickets_id);

        try {
            // seta planning_poker_status = 1

            $ret = Tickets::findOrFail($id);
            
            $ret->planning_poker_status = 1;

            $ret->save();

            $ret = Planningpokers::select('planningpokers.id','users_id','valorsp','name','avatar')
                ->where('tickets_id', $id)
                ->leftjoin('users','users.id','users_id')
                ->get();

            return response()->json(['data' => $ret], 200);

        } catch (\Exception $e) {
            
            return response()->json(['messagem' => $e], 422);
                
        }

    }

    /**
     * End
     */
    public function end($tickets_id)
    {

        $id = base64_decode($tickets_id);

        $ret = Planningpokers::select('valorsp')
            ->where('tickets_id', $id)
            ->get();

        $total = 0; $contsp = 0; $sp = [0, 1, 2, 3, 5, 8, 13, 20, 40, 100,999]; $pos = 0;

        foreach($ret as $item) {
            if ($item->valorsp != 0) {
                $total += $item->valorsp;
                $contsp++;
            }
        }

        if ($contsp != 0) {
            $media = round($total/$contsp,0);
        } else {
            $media = 0;
        }
        
        $countsp = 10;

        if ($media == 100) {
            $pos = 9;
        } elseif ($media == 0) {
            $pos = 0;
        } else {
            for($i=0;$i<$countsp; $i++) {
                
                if ($media <= $sp[$i] && $media < $sp[$i+1]) {
                    $pos = $i;
                    $media = $sp[$i];
                    break;
                }
            }
        }

        try {
            // seta planning_poker_status = 2

            $ret = Tickets::findOrFail($id);
            
            $ret->planning_poker_status = 2;

            $ret->valorsp = $media;
            $ret->storypoint = $pos;

            $ret->save();

            Toast::title(__('End Planning Poker!'))->autoDismiss(5);


        } catch (\Exception $e) {
            
            return response()->json(['messagem' => $e], 422);
                
        }

        return $this->index($tickets_id);
    }
}
