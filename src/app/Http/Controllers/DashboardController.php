<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\Facades\Toast;
use App\Models\Sprints;
use App\Models\UsersProjects;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        error_log(App::currentLocale());

        $input = $request->all();

        $userId = auth('sanctum')->user()->id;

        $ret = UsersProjects::Select('projects.id as projects_id','projects.status','title','sitelink','gitlink','media_sp','media_pf','dev','relator','tester','gp')
            ->Join('projects','projects.id','=','users_projects.projects_id')
            ->where('projects.status','!=','Disabled')
            ->where('users_projects.users_id','=',$userId)
            ->orderby('title')
            ->get();
  
        // se usuário não tiver projeto associado retorna para o login.
        if (count($ret) == 0) {

            Toast::title(__('User has no project!'))->autoDismiss(5);
            Session::forget(Session::driver()->getId());
            Session::invalidate();
            Auth::guard('web')->logout();
            return redirect('/login');

        }

        $chart1 = null; $chart2 = null; $chart3 = null;

        $ind = 0;  // determina o projeto selecionado

        if (empty($input)) {

            if (isset(Session::get('ret')[0]['id']) && Session::get('ret')[0]['id'] != 0) {

                $projects_id = Session::get('ret')[0]['id'];
                $sprints_id = Session::get('ret')[0]['sprint'];

                // verifica se o projeto anteriormente selecionado ainda está associado ao usuário
                $achou = false;
                $tcount = count($ret);
                for($i=0; $i<$tcount;$i++) {
                    if($ret[$i]->projects_id == $projects_id) {
                        $achou = true;
                        $ind = $i;
                        break;
                    }
                }
                if (! $achou) {
                    $projects_id = $ret[0]->projects_id;
                }

            } else {

                $projects_id = $ret[$ind]->projects_id;

                $sprints = Sprints::Select('id')
                    ->where('status','Open')
                    ->where('projects_id','=',$projects_id)
                    ->limit(1)
                    ->get();

                if (count($sprints) == 0) {
                    $sprints_id = 0;
                } else {
                    $sprints_id = $sprints[0]['id'];
                }

            }

            $input['projects_id'] = $projects_id;
            $input['sprints_id'] = $sprints_id;

        } else {

            $projects_id = $input['projects_id'];
            
            // verifica se o projeto anteriormente selecionado ainda está associado ao usuário
            $achou = false;
            $tcount = count($ret);
            for($i=0; $i<$tcount; $i++) {
                if($ret[$i]->projects_id == $projects_id) {
                    $ind = $i;
                    $achou = true;
                    break;
                }
            }
            if (! $achou) {
                $projects_id = $ret[0]->projects_id;
            }

            if(isset($input['sprints_id'])) {

                $sprints_id = $input['sprints_id'];

            } else {
                
                if (isset(Session::get('ret')[0]['sprint']) && Session::get('ret')[0]['sprint'] != 0) {

                    $sprints_id = Session::get('ret')[0]['sprint'];

                } else {

                    $sprints_id = 0;

                }

            }
            
        }

        // permissões de acesso do usuário logado
        $ar = Array(
            'userId' => $userId,
            'admin' => auth('sanctum')->user()->admin,
            'id' => $ret[$ind]->projects_id,
            'sprint' => $sprints_id,
            'title' => $ret[$ind]->title,
            'gp' => $ret[$ind]->gp,
            'dev' => $ret[$ind]->dev,
            'relator' => $ret[$ind]->relator,
            'tester' => $ret[$ind]->tester,
            'gitlink' => $ret[$ind]->gitlink,
            'sitelink' => $ret[$ind]->sitelink
        );

        Session::forget('ret');
        Session::push("ret", $ar);

        // estatísticas da sprint

        $result1 = $this->sprintEstat($sprints_id);

        // total de story points
        $storyPointSprint = 0;
        $tcount = count($result1);
        for($i=0; $i<$tcount; $i++) {
            $storyPointSprint += $result1[$i]['storypoint'];
        }

        // estatísticas por Dev

        $result2 = $this->devEstat($projects_id,$sprints_id);

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

            $sql = "select count(*) as vlr from tickets where sprints_id = $sprint";
            $total = DB::select($sql);

            $sql = "select  count(*) as vlr from tickets where sprints_id = $sprint and status='Closed'";
            $closed = DB::select($sql);

            $progressoReal = floor(($closed[0]->vlr * $storyPointSprint ) / $totalDays);

            $totalStoryPoint = $total[0]->vlr * $storyPointSprint ;
            $progresso = floor($totalStoryPoint / $totalDays);

            $vlr = $totalStoryPoint; $estimado = $vlr . ',';
            for($j=0; $j<=$totalDays; ++$j) {
                $vlr = $vlr - $progresso;
                if ($vlr < 0) {
                    $vlr = 0;
                }
                $estimado .= $vlr . ',';
            }

            $vlr = $totalStoryPoint; $real = $vlr . ',';
            for($j=0; $j<=$totalDays; ++$j) {
                $vlr = $vlr - $progressoReal;
                if ($vlr < 0) {
                    $vlr = 0;
                }
                $real .= $vlr . ',';
            }

            $chart1 = [
                'data1' => "[" . $estimado . "]",
                'data2' => "[" . $real . "]",
                'categories' => $categories,
                'title' => "Sprint Burndown"
            ];

        }

        // gráfico sprint

        $chart2 = $this->sprintGrafico($projects_id);

        // gráfico story points

        $chart3 = $this->storyGrafico($projects_id);

        // gráfico pf
        $chart4 = $this->pfGrafico($projects_id);

        //total de tíquetes do projeto
        $sql = "select count(*) as total from tickets where projects_id = $projects_id";
        $total = DB::select($sql);
     
        return view('dashboard',[
            'proj' => $ret,
            'input' => $input,
            'stats' => $result1,
            'total' => $tcount,
            'perdev' => $result2,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4,
            'sprints' => '',
            'storypoint_medio' =>  $ret[$ind]->media_sp,
            'pf_medio' =>  $ret[$ind]->media_pf,
            'projeto' => $projects_id,
            'sprint' => $sprints_id,
            'totalEquipe' => $totalEquipe[0]->total,
            'sitelink' => $ret[$ind]->sitelink,
            'gitlink' => $ret[$ind]->gitlink
        ]);

    }

    private function devEstat($projects_id, $sprints_id) {

        $sql = "select projects.title as project, sprints.id as versionId, sprints.version, sprints.start, sprints.end, users.name, tickets.status, count(*) as qtd, sum(valorsp) as storypoint 
            from tickets 
            left join users on users.id = tickets.resp_id 
            inner join sprints on sprints.id = tickets.sprints_id and sprints.status = 'Open' 
            left join projects on projects.id = tickets.projects_id 
            left join types on types.id = tickets.types_id 
            where projects.id =  $projects_id and sprints.id = $sprints_id
            group by projects.title,sprints.id,tickets.projects_id, tickets.sprints_id, tickets.resp_id, tickets.status,users.name
            order by users.name, sprints.version, tickets.status";

        $stats = DB::select($sql);

        $result = [];
    
        foreach($stats as $item) {

            $found = false;
            $tcount = count($result);
            for ($i=0; $i<$tcount; $i++) {
                    if ($result[$i]['name'] == $item->name)  {
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
                    'sprint' => $item->version,
                    'start' => $item->start,
                    'end' => $item->end,
                    'project' => $item->project,
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

    private function sprintEstat($sprints_id) {
        
        $sql = "select projects.title as project, sprints.id as versionId, sprints.version, sprints.start, sprints.end, types.title as type, tickets.status, count(*) as qtd, sum(valorsp) as storypoint, sum(pf) as pf
            from tickets 
            inner join sprints on sprints.id = tickets.sprints_id and sprints.status = 'Open' 
            left join projects on projects.id = tickets.projects_id 
            left join types on types.id = tickets.types_id 
            where  sprints.id = $sprints_id
            group by projects.title,sprints.id,sprints.version,tickets.projects_id, tickets.sprints_id, tickets.types_id, types.title, tickets.status 
            order by sprints.version, types.title, tickets.status";

        $stats = DB::select($sql);

        $result = [];

        foreach($stats as $item) {

            $found = false; 
            $tcount = count($result);
            for ($i=0; $i<$tcount; $i++) {

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
                    $result[$i]['pf'] += $item->pf;
                }

            }

            if (! $found) {
                $raj = array(
                    'versionId' => $item->versionId,
                    'sprint' => $item->version,
                    'start' => $item->start,
                    'end' => $item->end,
                    'project' => $item->project,
                    'type' => $item->type,
                    'open' => 0,
                    'testing' => 0,
                    'closed' => 0,
                    'storypoint' => 0,
                    'pf' => 0
                );

                if ($item->status == 'Open') {
                    $raj['open'] += $item->qtd;
                } else if ($item->status == 'Closed') {
                    $raj['closed'] += $item->qtd;
                } else {
                    $raj['testing'] += $item->qtd;
                }

                $raj['storypoint'] += $item->storypoint;
                $raj['pf'] += $item->pf;

                array_push($result, $raj);
            }
        }

        return $result;
    }

    private function sprintGrafico($projects_id) {
        
        $sql ="SELECT  tickets.sprints_id, sprints.version, 
            (SELECT COUNT(*) FROM tickets where types_id = 1 AND tickets.sprints_id = sprints.id) AS melhoria, 
            (SELECT COUNT(*) FROM tickets where types_id = 2 AND tickets.sprints_id = sprints.id) AS defeito,
            (SELECT COUNT(*) FROM tickets where types_id = 3 AND tickets.sprints_id = sprints.id) AS suporte
            FROM tickets
            LEFT JOIN sprints ON sprints.id = tickets.sprints_id
            LEFT JOIN types ON types.id = tickets.types_id
            WHERE tickets.projects_id = $projects_id AND  sprints.status <> 'Waiting'
            GROUP BY sprints_id,sprints.version,melhoria,defeito,suporte
            order by sprints_id
            LIMIT 12";

        $ticketsSprints = DB::select($sql);

        $categ = ""; $series1 = ''; $series2 = ''; $series3 = '';

        foreach($ticketsSprints as $item) {

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

        $sql ="SELECT  tickets.sprints_id, sprints.version, 
            SUM(valorsp) AS total
            FROM tickets
            LEFT JOIN sprints ON sprints.id = tickets.sprints_id
            LEFT JOIN types ON types.id = tickets.types_id
            WHERE tickets.projects_id = $projects_id AND  sprints.status <> 'Waiting'
            GROUP BY sprints_id,sprints.version
            order by sprints_id
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

    private function pfGrafico($projects_id) {

        $sql ="SELECT  tickets.sprints_id, sprints.version, 
            SUM(pf) AS total
            FROM tickets
            LEFT JOIN sprints ON sprints.id = tickets.sprints_id
            LEFT JOIN types ON types.id = tickets.types_id
            WHERE tickets.projects_id = $projects_id AND  sprints.status <> 'Waiting'
            GROUP BY sprints_id,sprints.version
            order by sprints_id
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
            'title' => "Sprints/Pontos de Função"
        ];

        return $chart;
    }

}
