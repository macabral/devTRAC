<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $input = $request->all();

        $userId = auth('sanctum')->user()->id;
        $ret = User::where('id', $userId)->where('active','=',1)->with('projects')->get();

        if (empty($ret[0])) {
            Session::forget(Session::driver()->getId());
            Session::invalidate();
            Auth::guard('web')->logout();
            return redirect('/login');
        }

        // se usuário não tiver projeto associado retorna para o login.
        if (count($ret[0]->projects) == 0) {

            Session::forget(Session::driver()->getId());
            Session::invalidate();
            Auth::guard('web')->logout();
            return redirect('/login');

        } else {

            if (isset($input['selProject'])) {
                foreach($ret[0]->projects as $item) {
                    if ($item->id == $input['selProject']) {
                        $project = $item;
                        $selProject = $item->id;
                    }
                }
            } else {
                $project = $ret[0]->projects[0];
                $selProject = $project->id;
            }

            $ar = Array(
                'qtd' => count($ret[0]->projects),
                'admin' => auth('sanctum')->user()->admin,
                'id' => $project->id,
                'description' => $project->description, 
                'title' => $project->title,
                'gp' => $project->pivot->gp,
                'dev' => $project->pivot->dev,
                'relator' => $project->pivot->relator,
                'tester' => $project->pivot->tester
            );
            Session::forget('ret');
            Session::push("ret", $ar);

        }

        if ($project->pivot->gp == '1' || $project->pivot->tester == '1' || $project->pivot->relator == '1' || auth('sanctum')->user()->admin == 1) {
            $sql = "select projects.title as project,  releases.id as versionId, releases.version, releases.start, releases.end, types.title as type, tickets.status, count(*) as qtd from tickets inner join releases on releases.id = tickets.releases_id and releases.status = 'Open' left join projects on projects.id = tickets.projects_id left join types on types.id = tickets.types_id where projects.id =  $project->id group by tickets.projects_id, tickets.releases_id, tickets.types_id, tickets.status order by releases.version, types.title, tickets.status";
        } else {
            $sql = "select projects.title as project,  releases.id as versionId, releases.version, releases.start, releases.end, types.title as type, tickets.status, count(*) as qtd from tickets inner join releases on releases.id = tickets.releases_id and releases.status = 'Open' left join projects on projects.id = tickets.projects_id left join types on types.id = tickets.types_id where projects.id =  $project->id and (tickets.resp_id = $userId  or tickets.relator_id = $userId) group by tickets.projects_id, releases.version, types.title, tickets.status order by releases.version, types.title, tickets.status";
        }

        $stats = DB::select($sql);

        $result = []; $result1 = []; $result2 = [];
 
        foreach($stats as $item) {

            $found = false;

            for ($i=0; $i<count($result); $i++) {

                if ($result[$i]['release'] == $item->version && $result[$i]['type'] == $item->type)  {
                    if ($item->status == 'Open') {
                        $result[$i]['open'] += $item->qtd;
                    } else if ($item->status == 'Closed') {
                        $result[$i]['closed'] += $item->qtd;
                    } else {
                        $result[$i]['testing'] += $item->qtd;
                    }
                    $found = true;
                    break;
                }
   
            }
            if (! $found) {
                $raj = array(
                    'release' => $item->version,
                    'start' => $item->start,
                    'end' => $item->end,
                    'project' => $item->project,
                    'type' => $item->type,
                    'open' => 0,
                    'testing' => 0,
                    'closed' => 0
                );

                if ($item->status == 'Open') {
                    $raj['open'] += $item->qtd;
                } else if ($item->status == 'Closed') {
                    $raj['closed'] += $item->qtd;
                } else {
                    $raj['testing'] += $item->qtd;
                }

                array_push($result, $raj);
            }
        }

        $result1 = $result;

        if ($project->pivot->gp == '1' || auth('sanctum')->user()->admin == 1) {
            $sql = "select projects.title as project, releases.id as versionId, releases.version, releases.start, releases.end, types.title as type, users.name, tickets.status, count(*) as qtd from tickets left join users on users.id = tickets.resp_id inner join releases on releases.id = tickets.releases_id and releases.status = 'Open' left join projects on projects.id = tickets.projects_id left join types on types.id = tickets.types_id where projects.id =  $project->id group by tickets.projects_id, tickets.releases_id, tickets.resp_id, tickets.types_id, tickets.status order by releases.version, types.title, tickets.status";
            $stats = DB::select($sql);

            $result = [];
     
            foreach($stats as $item) {
    
                $found = false;
    
                for ($i=0; $i<count($result); $i++) {
    
                    if ($result[$i]['release'] == $item->version && $result[$i]['type'] == $item->type && $result[$i]['name'] == $item->name)  {
                        if ($item->status == 'Open') {
                            $result[$i]['open'] += $item->qtd;
                        } else if ($item->status == 'Closed') {
                            $result[$i]['closed'] += $item->qtd;
                        } else {
                            $result[$i]['testing'] += $item->qtd;
                        }
                        $found = true;
                        break;
                    }
       
                }
                if (! $found) {
                    $raj = array(
                        'versionId' => $item->versionId,
                        'release' => $item->version,
                        'start' => $item->start,
                        'end' => $item->end,
                        'project' => $item->project,
                        'type' => $item->type,
                        'name' => $item->name,
                        'open' => 0,
                        'testing' => 0,
                        'closed' => 0
                    );
    
                    if ($item->status == 'Open') {
                        $raj['open'] += $item->qtd;
                    } else if ($item->status == 'Closed') {
                        $raj['closed'] += $item->qtd;
                    } else {
                        $raj['testing'] += $item->qtd;
                    }
    
                    array_push($result, $raj);
                }
            }

            $result2 = $result;
        }


        // gráfico burndown da primeira sprint
        $start_time = Carbon::parse($result[0]['start']);
        $finish_time =  Carbon::parse($result[0]['end']);
        $totalDays = $start_time->diffInDays($finish_time) - 1;
        $storyPoint = 16;

        $categories = ''; $count = 0;
        for($i=$start_time; $i<=$finish_time; $i->addDays(1)) {
            $categories .= $i->format('d') . ',';
        }

        $sprint = $result[0]['versionId'];
        $sql = "select count(*) as vlr from tickets where releases_id = $sprint";
        $total = DB::select($sql);

        $sql = "select  count(*) as vlr from tickets where releases_id = $sprint and status='Closed'";
        $closed = DB::select($sql);
        $progressoReal = floor(($closed[0]->vlr * $storyPoint) / $totalDays);

        $totalStoryPoint = $total[0]->vlr * $storyPoint;
        $progresso = floor($totalStoryPoint / $totalDays);

        $vlr = $totalStoryPoint; $estimado = $vlr . ',';
        for($j=0; $j<=$totalDays; ++$j) {
            $vlr = $vlr - $progresso;
            if ($vlr < 0) {
                $vlr = 0;
            }
            error_log($vlr);
            $estimado .= $vlr . ',';
        }

        $vlr = $totalStoryPoint; $real = $vlr . ',';
        for($j=0; $j<=$totalDays; ++$j) {
            $vlr = $vlr - $progressoReal;
            if ($vlr < 0) {
                $vlr = 0;
            }
            error_log($vlr);
            $real .= $vlr . ',';
        }

        $chart = [
            'data1' => "[" . $estimado . "]",
            'data2' => "[" . $real . "]",
            'categories' => $categories,
            'title' => "Sprint Burndown (esforço em horas)"
        ];

        return view('dashboard',[
            'proj' => $ret[0]->projects,
            'selProject' => $selProject,
            'stats' => $result1,
            'perdev' => $result2,
            'chart' => $chart
        ]);

    }

    public function selproj(Request $request)
    {

        error_log("selecionou ");
        $input = $request->all();

        if (isset($input['sel'])) {
            error_log($input['sel']);
        }

        return redirect()->back();
    }
}
