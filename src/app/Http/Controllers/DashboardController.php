<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\Facades\Toast;
use App\Models\User;
use App\Models\Releases;
use App\Models\UsersProjects;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $input = $request->all();

        $userId = auth('sanctum')->user()->id;

        $ret = User::where('id', $userId)->where('active','=',1)->with('projects')->get();

        if (empty($input)) {
           
            // se usuário não tiver projeto associado retorna para o login.
            if (count($ret[0]['projects']) == 0) {

                Toast::title(__('User has no project!'))->autoDismiss(5);
                Session::forget(Session::driver()->getId());
                Session::invalidate();
                Auth::guard('web')->logout();
                return redirect('/login');

            } else {

                $projetos = $ret[0]['projects'];

                if (isset(Session::get('ret')[0]['id']) && Session::get('ret')[0]['id'] != 0) {

                    $projects_id = Session::get('ret')[0]['id'];

                    // verifica se o projeto anteriormente selecionado ainda está associado ao usuário
                    $achou = false;
                    for($i=0; $i<count($projetos);$i++) {
                        if($projetos[$i]->id == $projects_id) {
                            $achou = true;
                            break;
                        }
                    }
                    if (! $achou) {
                        $projects_id = $projetos[0]->id;
                    }

                } else {

                    $projects_id = $projetos[0]->id;

                }

                $releases = Releases::Select('id')->where('status','Open')->where('projects_id','=',$projects_id)->limit(1)->get();

                if (count($releases) > 0) {
                    $releases_id = $releases[0]['id'];
                } else {
                    $releases_id = 0;
                }

                $input['projects_id'] = $projects_id;
                $input['releases_id'] = $releases_id;

            }

        } else {

            $projects_id = $input['projects_id'];

            if(isset($input['releases_id'])) {
                $releases_id = $input['releases_id'];
            } else {
                $releases_id = 0;
            }
            
        }

        $projetos = UsersProjects::leftJoin('projects','projects.id','=','users_projects.projects_id')->Where('projects_id','=',$projects_id)->where('users_id','=',$userId)->get();

        // permissões de acesso do usuário logado
        $ar = Array(
            'admin' => auth('sanctum')->user()->admin,
            'id' => $projetos[0]->projects_id,
            'description' => $projetos[0]->description, 
            'title' => $projetos[0]->title,
            'gp' => $projetos[0]->gp,
            'dev' => $projetos[0]->dev,
            'relator' => $projetos[0]->relator,
            'tester' => $projetos[0]->tester
        );

        Session::forget('ret');
        Session::push("ret", $ar);

        // releases
        $releases = Releases::select('id','version')->where('status','Open')->where('projects_id', $projects_id)->orderBy('version')->get();

        // estatísticas do release

        $result1 = $this->sprintEstat($releases_id);

        // total de story points
        $storyPointRelease = 0;
        for($i=0; $i<count($result1); $i++) {
            $storyPointRelease += $result1[$i]['storypoint'];
        }

        // estatísticas por Dev

        $result2 = $this->devEstat($projects_id,$releases_id);

        // gráfico burndown da primeira sprint

        if ($result1) {
            $start_time = Carbon::parse($result1[0]['start']);
            $finish_time =  Carbon::parse($result1[0]['end']);
            $totalDays = $start_time->diffInDays($finish_time) - 1;


            $categories = '';
            for($i=$start_time; $i<=$finish_time; $i->addDays(1)) {
                $categories .= $i->format('d') . ',';
            }

            $sprint = $result1[0]['versionId'];

            $sql = "select count(*) as vlr from tickets where releases_id = $sprint";
            $total = DB::select($sql);

            $sql = "select  count(*) as vlr from tickets where releases_id = $sprint and status='Closed'";
            $closed = DB::select($sql);

            $progressoReal = floor(($closed[0]->vlr * $storyPointRelease ) / $totalDays);

            $totalStoryPoint = $total[0]->vlr * $storyPointRelease ;
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
                'title' => "Sprint Burndown"
            ];

        } else {
            $chart = null;
        }

        // gráfico sprint

        $chart2 = $this->sprintGrafico($projects_id);

        // gráfico story points

        $chart3 = $this->storyGrafico($projects_id);

        // média de Story Points

        return view('dashboard',[
            'proj' => $ret[0]['projects'],
            'input' => $input,
            'stats' => $result1,
            'perdev' => $result2,
            'chart' => $chart,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'releases' => $releases,
            'storypoint_medio' =>  $projetos[0]->media_sp
        ]);

    }

    private function devEstat($projects_id, $releases_id) {

        $sql = "select projects.title as project, releases.id as versionId, releases.version, releases.start, releases.end, types.title as type, users.name, tickets.status, count(*) as qtd, sum(valorsp) as storypoint 
            from tickets 
            left join users on users.id = tickets.resp_id 
            inner join releases on releases.id = tickets.releases_id and releases.status = 'Open' 
            left join projects on projects.id = tickets.projects_id 
            left join types on types.id = tickets.types_id 
            where projects.id =  $projects_id and releases.id = $releases_id
            group by tickets.projects_id, tickets.releases_id, tickets.resp_id, tickets.types_id, tickets.status 
            order by users.name, releases.version, types.title, tickets.status";
        $stats = DB::select($sql);

        $result = [];
    
        foreach($stats as $item) {

            $found = false;

            for ($i=0; $i<count($result); $i++) {
                    if ($result[$i]['type'] == $item->type && $result[$i]['name'] == $item->name)  {
                    if ($item->status == 'Open') {
                        $result[$i]['open'] += $item->qtd;
                    } else if ($item->status == 'Closed') {
                        $result[$i]['closed'] += $item->qtd;
                    } else {
                        $result[$i]['testing'] += $item->qtd;
                    }
                    $result[$i]['storypoint'] += $item->storypoint;
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
                    'closed' => 0,
                    'storypoint' => 0
                );

                if ($item->status == 'Open') {
                    $raj['open'] += $item->qtd;
                } else if ($item->status == 'Closed') {
                    $raj['closed'] += $item->qtd;
                } else {
                    $raj['testing'] += $item->qtd;
                }

                $raj['storypoint'] += $item->storypoint;
                array_push($result, $raj);
            }
        }

        return $result;
    }

    private function sprintEstat($releases_id) {
        
        $sql = "select projects.title as project, releases.id as versionId, releases.version, releases.start, releases.end, types.title as type, tickets.status, count(*) as qtd, sum(valorsp) as storypoint
            from tickets 
            inner join releases on releases.id = tickets.releases_id and releases.status = 'Open' 
            left join projects on projects.id = tickets.projects_id 
            left join types on types.id = tickets.types_id 
            where  releases.id = $releases_id
            group by tickets.projects_id, tickets.releases_id, tickets.types_id, tickets.status 
            order by releases.version, types.title, tickets.status";

        $stats = DB::select($sql);

        $result = [];

        foreach($stats as $item) {

            $found = false; 
            
            for ($i=0; $i<count($result); $i++) {

                if ($result[$i]['type'] == $item->type)  {
                    if ($item->status == 'Open') {
                        $result[$i]['open'] += $item->qtd;
                    } else if ($item->status == 'Closed') {
                        $result[$i]['closed'] += $item->qtd;
                    } else {
                        $result[$i]['testing'] += $item->qtd;
                    }
                    $found = true;
                    $result[$i]['storypoint'] += $item->storypoint;
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
                    'open' => 0,
                    'testing' => 0,
                    'closed' => 0,
                    'storypoint' => 0
                );

                if ($item->status == 'Open') {
                    $raj['open'] += $item->qtd;
                } else if ($item->status == 'Closed') {
                    $raj['closed'] += $item->qtd;
                } else {
                    $raj['testing'] += $item->qtd;
                }

                $raj['storypoint'] += $item->storypoint;

                array_push($result, $raj);
            }
        }

        return $result;
    }

    private function sprintGrafico($projects_id) {
        
        $sql ="SELECT  tickets.releases_id, releases.version, 
            (SELECT COUNT(*) FROM tickets where types_id = 1 AND tickets.releases_id = releases.id) AS melhoria, 
            (SELECT COUNT(*) FROM tickets where types_id = 2 AND tickets.releases_id = releases.id) AS defeito,
            (SELECT COUNT(*) FROM tickets where types_id = 3 AND tickets.releases_id = releases.id) AS suporte
            FROM tickets
            LEFT JOIN releases ON releases.id = tickets.releases_id
            LEFT JOIN types ON types.id = tickets.types_id
            WHERE tickets.projects_id = $projects_id AND  releases.status <> 'Waiting'
            GROUP BY releases_id
            order by releases_id
            LIMIT 12";

        $ticketsReleases = DB::select($sql);

        $categ = ""; $series1 = ''; $series2 = ''; $series3 = '';

        foreach($ticketsReleases as $item) {

            $categ .=  $item->version . ",";
            $series1 .= $item->melhoria . ',';
            $series2 .= $item->defeito . ',';
            $series3 .= $item->suporte . ',';

        }

        $chart = [
            'data1' => "[" . $series1 . "]",
            'data2' => "[" . $series2 . "]",
            'data3' => "[" . $series3 . "]",
            'categories' =>   substr($categ,0,strlen($categ)-1),
            'title' => "Sprints"
        ];

        return $chart;
    }

    private function storyGrafico($projects_id) {

        $sql ="SELECT  tickets.releases_id, releases.version, 
            SUM(valorsp) AS total
            FROM tickets
            LEFT JOIN releases ON releases.id = tickets.releases_id
            LEFT JOIN types ON types.id = tickets.types_id
            WHERE tickets.projects_id = $projects_id AND  releases.status <> 'Waiting'
            GROUP BY releases_id
            order by releases_id
            LIMIT 12";

        $ticketsStory = DB::select($sql);

        $categ = ""; $series1 = ''; $total = 0;

        foreach($ticketsStory as $item) {

            $categ .=  $item->version . ",";
            $series1 .= $item->total . ',';
            $total += $item->total;

        }

        $chart = [
            'data1' => "[" . $series1 . "]",
            'categories' =>   substr($categ,0,strlen($categ)-1),
            'title' => "Sprints/Story Points"
        ];

        return $chart;
    }

}
