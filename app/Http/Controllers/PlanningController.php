<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Redirect;
use Carbon;
use DateTime;
use View;

use App\Planning as Planning;
use App\User as User;
use App\Activity as Activity;


class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Planning::where('date','<>','')
                                ->get();
        $types = Activity::all();
        if(Auth::user()){
            $userLogged = Auth::user()->name;
            $workers = User::where('no_assi','0')
                                ->orderByRaw("name = '$userLogged' DESC")
                                ->get();
        } else {
            $workers = User::where('no_assi','0')
                                ->orderby('name','asc')
                                ->get();
        }
        $tasks = $tasks->sortBy('operator');
        $tasksMor = DB::table('plannings')->where('hour','0')->get();
        $tasksAft = DB::table('plannings')->where('hour','1')->get();
        $tasksMor = DB::table('plannings')
                                    ->leftjoin('users','plannings.operator','=','users.name')
                                    ->select('plannings.*','users.suspended','users.no_assi')
                                    ->where([
                                        ['hour','0'],
                                        ['activity','<>','Assistenza']
                                    ])
                                    ->get();
        $tasksAft = DB::table('plannings')
                                    ->leftjoin('users','plannings.operator','=','users.name')
                                    ->select('plannings.*','users.suspended','users.no_assi')
                                    ->where([
                                        ['hour','1'],
                                        ['activity','<>','Assistenza']
                                    ])
                                    ->get();
        return view('planning/planning',compact('tasks','types','workers','tasksMor','tasksAft'));
    }

    /**
     * Display view for weekly activity
     */
    public function indexWeekly(Request $request)
    {
        $users = User::all();
        $types = Activity::all();
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $particularActs = DB::table('plannings')
                                    ->where('particular','1')
                                    ->get();
        return view('planning/addWeekly', compact('users', 'types', 'particularActs','startDate','endDate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $types = Activity::all();
        return view('planning/add',compact('users','types'));
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
        $activity->edit = Auth::user()->name;
        $activity->particular = $request->get('particular');
        $activity->repetition = $request->get('repetition');
        $activity->save();
        if($activity->repetition > 0)
        {
            $user = DB::table('users')
                                ->where('name',$activity->operator)
                                ->update(['particular'=>1]);
            return redirect()->back()->with('Messaggio: ','Operazione completata');
        }
        else
            return redirect('/planning');
    }

    /**
     * Store new Activity
     */
    public function storeActivity(Request $request)
    {
        $type = new Activity;
        $type->type = $request->get('type');
        $type->color = $request->get('color');
        $type->inv_hex = $request->get('inv_hex');
        $type->save();
        return Redirect::back()->with('Messaggio: ','Operazione completata');
    }

    /**
     * Store default activty
     */
    public function storeWeeklyActivity(Request $request)
    {
        $workers = User::all();
        $changeDate = "";
        $newDate = "";
        $startDate = $request->get('startDate');
        if($startDate == "")
            return redirect()->back()->with('alert','Nessuna data di inizio. Torna al menu principale.');
        foreach($workers as $worker)
        {
            // if($worker->particular == 1){
            //     $activities = DB::table('plannings')
            //                                 ->where([
            //                                     ['particular','1'],
            //                                     ['operator',$worker->name] 
            //                                 ])
            //                                 ->get();
            // }
            $finalDate = new DateTime($request->get('endDate'));
            $changeDate = new DateTime($request->get('startDate'));
            while($changeDate != $finalDate)
            {
                for($x=0;$x<2;$x++)
                {
                    $activity = new Planning;
                    $activity->activity = "Assistenza";
                    $activity->operator = $worker->name;
                    $activity->date = $changeDate;
                    $activity->type = "programmato";
                    $activity->hour = $x;
                    $activity->edit = Auth::user()->name;
                    $activity->particular = "0";
                    $activity->repetition = "0";
                    $activity->save();
                }
                $changeDate->modify('+1 day');
            }
        }
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
        $types = Activity::all();
        return view('planning/editplanning', compact('activity','users', 'types'));
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
        $activity->particular = $request->get('particular');
        $activity->repetition = $request->get('repetition');
        $activity->edit = Auth::user()->name;
        $activity->save();
        if($activity->repetition > 0)
            return redirect()->back()->with('Messaggio: ','Operazione completata');
        else
            return redirect('/planning');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity = Planning::find($id);
        $user = $activity->operator;
        if($activity->repetition > 0)
        {
            $activity->delete();
            $activities = DB::table('plannings')
                                        ->where('operator',$user)
                                        ->get();
            if($activities->isEmpty())
            {
                $user = DB::table('users')
                                    ->where('name',$user)
                                    ->update(['particular'=>0]);
            }
            return redirect()->back()->with('messaggio','Operazione completata');
        }
        else
        {
            $activity->delete();
            return redirect('/planning');
        }
    }
}