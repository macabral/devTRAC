<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ProtoneMedia\Splade\Facades\Toast;
use App\Models\Logtickets;
use App\Models\Tickets;
use App\Models\UsersProjects;
use App\Library\TracMail;
use Illuminate\Support\Facades\Session;
class LogticketsController extends Controller

{

    /**
     * Creating a new resource.
     */
    public function create(Request $request, $id)
    {
       
        $this->validate($request, [
            'description' => 'required'
        ]);

        $input = $request->all();

        $input['users_id'] = auth('sanctum')->user()->id;
        $input['tickets_id'] = $id;

        try {
            
            Logtickets::create($input);

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e))->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('Comment saved!'))->autoDismiss(5);

        return redirect()->back();
    }

    public function update($id, $status, TracMail $TracMailInstance)
    {

        $input['users_id'] = auth('sanctum')->user()->id;
        $input['tickets_id'] = $id;

        if ($status == 'Testing') {
            $input['description'] = "Tíquete enviado para teste.";

            try {

                $destinatario = '';

                $project = Session::get('ret')[0]['id'];

                $ret = UsersProjects::select('a.email')->where('projects_id','=',$project)->where('tester','=','1')->leftJoin('users as a','a.id','=','users_id')->get();
                foreach($ret as $elem) {
                    $destinatario .= $elem->email . '; ';
                }

                $mailData = [
                    'to' => $destinatario,
                    'cc' => null,
                    'subject' => 'devTRAC: Ticket enviado para teste',
                    'title' => "Ticket em teste",
                    'body' => "Você está recebendo esse email porque o tíquete #$id foi encaminhado para teste.",
                    'priority' => 0,
                    'attachments' => null
                ];
                    
                $TracMailInstance->save($mailData);

            } catch (\Exception $e) {
    
                Toast::title(__('It was not possible to send email notification.'))->danger()->autoDismiss(5);
    
            }

        } else if (($status == 'Open')) {
            $input['description'] = "Tíquete Aberto.";
        } else if (($status == 'Closed')) {
            $input['description'] = "Tíquete Fechado.";
        }

        if (! empty($input['description'])) {
            
            $ticket = Tickets::findOrFail($id);

            $ticket['status'] = $status;
            $ticket->save();

        }

        try {
            
            Logtickets::create($input);

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e))->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('Ticket updated!'))->autoDismiss(5);

        return redirect()->back();
    }

    public function edit($id)
    {
        $id = base64_decode($id);

        $log = Logtickets::findOrFail($id);

        $origin = $log->origin;
        $ticket = $log->tickets_id;
        $userId = $log->users_id;
        $description = $log->description;

        $ret = Tickets::Where('id', $ticket)->get();

        if (! is_null($origin)) {
            $log = Logtickets::
                select("logtickets.*", "users.name")
                ->leftJoin('users','users.id','=','users_id')
                ->where('origin',"=",$origin)
                ->orwhere('logtickets.id',"=",$origin)
                ->orderBy('Created_at','desc')
                ->get();

        } else {
            $log = Logtickets::
                select("logtickets.*", "users.name")
                ->leftJoin('users','users.id','=','users_id')
                ->where('logtickets.id',"=",$id)
                ->orderBy('Created_at','desc')
                ->get();
            $origin = $id;
        }

        return view('logtickets.edit', [
            'logs' => $log,
            'ret' => $ret[0],
            'origin' => $origin,
            'userId' => $userId,
            'description' => $description
     
        ]);

    }

        /**
     * Creating a new resource.
     */
    public function save($id, $origin, Request $request)
    {
       
        $this->validate($request, [
            'description' => 'required'
        ]);

        $input = $request->all();

        $input['users_id'] = auth('sanctum')->user()->id;
        $input['tickets_id'] = $id;
        $input['origin'] = $origin;

        try {
           
            Logtickets::create($input);

            $ret = Logtickets::findOrFail($origin);

            $ret['origin'] = 0;

            $ret->save();


        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e))->autoDismiss(5);
            return response()->json(['messagem' => $e], 422);
            
        }

        Toast::title(__('Comment saved!'))->autoDismiss(5);

        return redirect()->back();
    }
}
