<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Planning as Planning;
use App\User as User;


class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = collect(Planning::all());
        $workers = User::all();
        $tasks = $tasks->sortBy('operator');
        $tasksMor = DB::table('plannings')->where('hour','0')->get();
        $tasksAft = DB::table('plannings')->where('hour','1')->get();
        return view('planning/planning',compact('tasks','workers','tasksMor','tasksAft'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('planning/add',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $activity = new Planning;
        $activity->activity = $request->get('activity');
        $activity->operator = $request->get('operator');
        $activity->date = $request->get('date');
        $activity->type = $request->get('type');
        $activity->hour = $request->get('hour');
        $worker = DB::table('users')->where('name','$activity->operator')->first();
        echo $worker->suspended;
        return redirect('/planning');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function show(Planning $planning)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activity = Planning::find($id);
        $users = User::all();
        return view('planning/editplanning', compact('activity','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $workers = User::all();
        $activity = Planning::find($id);
        $activity->activity = $request->get('activity');
        $activity->operator = $request->get('operator');
        $activity->date = $request->get('date');
        $activity->type = $request->get('type');
        $activity->hour = $request->get('hour');
        $activity->suspended = $worker->suspended;
        $activity->save();
        return redirect('/planning');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function destroy(Planning $planning)
    {
        //
    }
}
