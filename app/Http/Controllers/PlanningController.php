<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Redirect;
use Carbon\Carbon;
use DateTime;
use View;

use App\Planning as Planning;
use App\User as User;
use App\Activity as Activity;
use App\Tbgene as Intensity;
use App\Comments as Comment;


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
        $intensityLight = Intensity::where('type','1')
                                    ->orderBy('number','asc')
                                    ->limit(1)
                                    ->first();
        $intensityHard = Intensity::where('type','1')
                                    ->orderBy('number','desc')
                                    ->limit(1)
                                    ->first();
        $intensityMedium = Intensity::where('type','1')
                                    ->skip(1)
                                    ->first();
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
        return view('planning/planning',compact('tasks','types','workers','tasksMor','tasksAft','intensityLight','intensityMedium','intensityHard'));
    //    echo "INTENSITY LIGHT" .$intensityLight;
    //    echo "<pre>";
    //    echo "INTENSITY MEDIUM" .$intensityMedium;
    //    echo "<pre>";
    //    echo "INTENSITY HARD" .$intensityHard;
    //    return "FINE";
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
        // $particularActs = DB::table('plannings')
        //                             ->where('particular','1')
        //                             ->get();
        $particularActs = Planning::where('particular','1')
                                            ->orderBy('operator','ASC')
                                            ->orderBy('created_at','DESC')
                                            ->get();
        return view('planning/addWeekly', compact('users', 'types', 'particularActs','startDate','endDate'));
    }

    /**
     * Show view where to add vacation
     */
    public function indexVacation()
    {
        $users = User::all();
        $today = Carbon::now()->toDateString();
        $vacations = DB::table('plannings')
                                    ->where([
                                        ['activity','permesso'],
                                        ['activity','ferie']
                                    ])
                                    ->get();
        return view('planning/addVacation', compact('users','today','vacations'));
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
            return redirect('/planning')->with('Messaggio: ','Operazione completata');
    }

    /**
     * Store new Activity
     */
    public function storeActivity(Request $request)
    {
        $type = new Activity;
        $type->type = $request->get('type');
        $type->color = $request->get('color');
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
            return redirect('/planning')->with('alert','Nessuna data di inizio. Ripetere operazione.');
        foreach($workers as $worker) //worker
        {
            $repetition = 1;
            $finalDate = new DateTime($request->get('endDate'));
            $changeDate = new DateTime($request->get('startDate'));
            while($changeDate != $finalDate) //day
            {
                for($x=0;$x<2;$x++) //period
                {
                    $activities = DB::table('plannings')
                                                    ->where([
                                                        ['particular','1'],
                                                        ['operator',$worker->name],
                                                        ['repetition',$repetition],
                                                        ['hour',$x]
                                                    ])
                                                    ->get();
                    if($worker->particular == 1 && !$activities->isEmpty())
                    {
                        foreach($activities as $act)
                        {
                            $activity = new Planning;
                            $activity->activity = $act->activity;
                            $activity->operator = $worker->name;
                            $activity->date = $changeDate;
                            $activity->type = $act->type;
                            $activity->hour = $x;
                            $activity->edit = Auth::user()->name;
                            $activity->particular = "0";
                            $activity->repetition = "0";
                            $activity->save();
                        }
                    }
                    else
                    {
                        $activity = new Planning;
                        $activity->activity = "Assistenza";
                        $activity->operator = $worker->name;
                        $activity->date = $changeDate;
                        $activity->type = "assistenza";
                        $activity->hour = $x;
                        $activity->edit = Auth::user()->name;
                        $activity->particular = "0";
                        $activity->repetition = "0";
                        $activity->save();
                    }
                }
                $changeDate->modify('+1 day');
                $repetition++;
            }
        }
        return redirect('/planning')->with('Messaggio: ','Operazione completata');
    }

    /**
     * Store vacation
     */
    public function storeVacation(Request $request)
    {
        $duration = $request->get('duration');
        // if($duration == "hours")
        // {
        //     //to do a normal save
        // }
        // else
        // {
            $startDate = new DateTime($request->get('startDate'));
            $endDate = new DateTime($request->get('endDate'));
            $plannings = DB::table('plannings')
                                                ->where([
                                                    ['period',$x],
                                                    ['operator', $request->get('operator')]
                                                ])
                                                ->whereBetween('date',$startDate,'endDate',$endDate)
                                                ->get();
            // while($startDate <= $endDate)
            // {
            //     for($x=0;$x<2;$x++)
            //     {
            //         $activity = new Planning;
            //         $activity->activity = $request->get('activity');
            //         $activity->type = $request->get('type');
            //         $activity->date = $startDate;
            //         $activity->operator = $request->get('operator');
            //         $activity->particular = $request->get('particular');
            //         $activity->repetition = $request->get('repetition');
            //         $activity->save();
            //     }
            // }
            echo $plannings;
            return "CIAO";
        //}
    }

    /**
     * Store comments for activity
     */
    public function storeComment(Request $request)
    {
        $comment = new Comment;
        $comment->operator = $request->get('operator');
        $comment->comment = $request->get('comment');
        $comment->idActivity = $request->get('idActivity');
        $comment->save();
        return redirect()->back()->with('Messaggio: ','Commento inserito');
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
        $comments = Comment::where('idActivity', $id)
                                ->get();
        return view('planning/editplanning', compact('activity','users', 'types', 'comments'));
    }

    /**
     * Edit number of busy users to change color calendarHard
     */
    public function updateIntensity(Request $request)
    {
        $count = 0;
        $intensities = Intensity::where('type','1')
                                        ->orderBy('id','asc')
                                        ->get();
        foreach($intensities as $intensity)
        {
            $count++;
            if($count==1)
                $color = 'green';
            elseif($count==2)
                $color = 'yellow';
            else
                $color = 'red';
            $intense = Intensity::find($intensity->id);
            $intense->number = $request->get($color);
            $intense->save();
        }
        return redirect('/workers');
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
        return redirect()->back()->with('messaggio','Operazione completata');
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
            return redirect()->back()->with('messaggio','Operazione completata con successo');
        }
        else
        {
            $activity->delete();
            return redirect('/planning')->with('messaggio','Operazione completata con successo');
        }
    }
}