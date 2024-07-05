<?php
namespace App\Library;
  
use App\Models\Logtickets;
use App\Models\Sprints;
use App\Models\User;
use App\Models\Type;
use App\Mail\NewTicket;
use Illuminate\Support\Facades\Mail;
class LogService
{
    public function saveLog($id, $ret, $input)
    {

      $user_id = auth('sanctum')->user()->id;

      $texto = ''; $sp = [0, 1, 2, 3, 5, 8, 13, 20, 40, 100];

      $sp_anterior = $ret['valorsp'];
      $sp_atual = $input['valorsp'];

      if ($sp_anterior != $sp_atual) {
        $texto .= 'Story Point alterado! [' . $sp_anterior.'] => [' . $sp_atual . ']' . chr(13);
      }

      if ($ret['title'] != $input['title']) {
        $texto .= 'Título alterado! [' . $ret['title'] .'] => [' . $input['title'] . ']' . chr(13);
      }

      if ($ret['description'] != $input['description']) {
          $texto .= 'Descrição alterada! [' . $ret['description'] .'] => [' . $input['description'] . ']' . chr(13);
      }

      if ($ret['sprints_id'] != $input['sprints_id']) {
          $query1 = Sprints::Select('version')->where('id', '=', $ret['sprints_id'])->get();
          $query2 = Sprints::Select('version')->where('id', '=', $input['sprints_id'])->get();
          $texto .= 'Sprint alterada! [' . $query1[0]['version'] .'] => [' .  $query2[0]['version'] . ']' . chr(13);
      }

      if (isset($input['resp_id'])) {
        if (is_null($ret['resp_id'])) {
          $query2 = User::Select('name')->where('id', '=', $input['resp_id'])->get();
          $texto .= 'Responsável atribuído! [' .  $query2[0]['name'] . ']' . chr(13);

          if (! is_null($input['resp_id'])) {

            Mail::Queue(new NewTicket($ret['id']));

          }
          

        } else {
          if ($ret['resp_id'] != $input['resp_id']) {
            $query1 = User::Select('name')->where('id', '=', $ret['resp_id'])->get();
            $query2 = User::Select('name')->where('id', '=', $input['resp_id'])->get();
            $texto .= 'Responsável alterado! [' . $query1[0]['name'] .'] => [' .  $query2[0]['name'] . ']' . chr(13);

            if (! is_null($input['resp_id'])) {

              Mail::Queue(new NewTicket($ret['id']));

            }
            
          }
        }
      }

      if ($ret['types_id'] != $input['types_id']) {
        $query1 = Type::Select('title')->where('id', '=', $ret['types_id'])->get();
        $query2 = Type::Select('title')->where('id', '=', $input['types_id'])->get();
        $texto .= 'Tipo alterado! [' . $query1[0]['title'] .'] => [' .  $query2[0]['title'] . ']' . chr(13);
       }

       if ($ret['testcondition'] != $input['testcondition']) {
        $texto .= 'Condição de Teste alterado! [' . $ret['testcondition'] .'] => [' . $input['testcondition'] . ']' . chr(13);
    }

      if ($ret['status'] != $input['status']) {
          $texto .= 'Status alterado! [' . $ret['status'] .'] => [' . $input['status'] . ']' . chr(13);
      }

      $input = array(
        'users_id' => $user_id,
        'tickets_id' => $id,
        'description' => $texto
      );

      Logtickets::create($input);
      
    }

    public function saveUploadLog($id, $texto)
    {
      $user_id = auth('sanctum')->user()->id;

      $input = array(
        'users_id' => $user_id,
        'tickets_id' => $id,
        'description' => $texto
      );

      Logtickets::create($input);

    }
}