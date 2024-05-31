<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Sprints;
use App\Models\UsersProjects;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/sprints/{project_id}/{perfil}', function ($project_id,$perfil) {

    if ($perfil == '1') {
        $sprints = Sprints::Select("id","version")->Where('projects_id','=',$project_id)->where('status','!=','Closed')->get();
    } else {
        $sprints = Sprints::Select("id","version")->Where('projects_id','=',$project_id)->where('status','=','Waiting')->get();
    }

    return response()->json($sprints);

});

Route::get('/sprints-dashboard/{project_id}', function ($project_id) {

    $ret = Sprints::Select("id","version")
            ->Where('projects_id','=',$project_id)
            ->where('status','=','Open')
            ->orderby('start')
            ->get();

    return response()->json($ret);

});

Route::get('/users/{project_id}', function ($project) {

    $users =  UsersProjects::Select('users.id','users.name')->leftJoin('users','users.id','=','users_projects.users_id')->where('users_projects.dev','=','1')->where('users_projects.projects_id','=',$project)->get();

    return response()->json($users);

});
