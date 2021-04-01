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
use App\Notifications as Notification;


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
        if(Auth::user()) {
            $userLogged = Auth::user()->name;
            $workers = User::orderByRaw("name = '$userLogged' DESC")->get();
        } else {
            $workers = User::all();
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
        $notifications = DB::table('notifications')
                                                ->leftjoin('plannings','notifications.id_ref','=','plannings.id')
                                                ->where([
                                                    ['read','!=','1'],
                                                ])
                                                ->get();                                        
        return view('planning/planning',compact('tasks','types','workers','tasksMor','tasksAft','intensityLight','intensityMedium','intensityHard','notifications'));
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
        $today = new DateTime();
        $vacationsFiltered = DB::table('plannings')
                                                ->where('type','Richiesta Permesso/Ferie')
                                                ->orderby('operator','ASC')
                                                ->orderby('date','ASC')
                                                ->orderby('hour','ASC')
                                                ->get();
        $operatorVacations = DB::table('plannings')
                                                ->where([
                                                    ['activity','ferie'],
                                                    ['operator',Auth::user()->name],
                                                    ['date','>=',Carbon::now()->subDays(7)],
                                                    ['date','<=',Carbon::now()->addDays(7)],
                                                ])
                                                ->orderby('date','ASC')
                                                ->orderby('hour','ASC')
                                                ->get();
        $hoursRequired = DB::table('plannings')
                                            ->select(DB::raw('SUM(time) as totalHours'))
                                            ->where([
                                                ['type','Richiesta Permesso/Ferie'],
                                                ['operator',Auth::user()->name],
                                                ['date','>=','01/01/'.date("y")],
                                                ['date','<=','31/12/'.date("y")],
                                            ])
                                            ->get();
        // $pastHour = date('y') - 1;
        // $pastHoursRequested = DB::table('plannings')
        //                                         ->select(DB::raw('SUM(time) as pastTotalHours'))
        //                                         ->where([
        //                                             ['operator',Auth::user()->name],
        //                                             ['date','>=','20'.$pastHour.'-01-01'],
        //                                             ['date','<=','20'.$pastHour.'-12-31'],
        //                                         ])
        //                                         ->get();
        return view('planning/addVacation', compact('users','today','vacationsFiltered','operatorVacations'));
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
        if($activity->type == "Richiesta Permesso/Ferie" || $activity->type == "ferie")
            $activity->time = $request->get('time');
        else 
            $activity->time = 0;
        $activity->save();
        if(Auth::user()->name != $activity->operator && $request->get('repetition')==0) { //todo: è necessario repetition?
            $notification = new Notification;
            $notification->worker = $activity->operator;
            $notification->id_ref = $activity->id;
            $notification->save();
        }
        if($activity->repetition > 0) {
            $user = DB::table('users')
                                    ->where('name',$activity->operator)
                                    ->update(['particular'=>1]);
            return redirect()->back()->with('Messaggio: ','Operazione completata');
        } else {
            return redirect()->to('/planning')->with(['date' => $request->get('date')]);
        }       
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
        $workers =  DB::table('users')
                                ->where('no_assi','0')
                                ->get();
        $changeDate = "";
        $newDate = "";
        $startDate = $request->get('startDate');
        if($startDate == "")
            return redirect('/planning')->with('alert','Nessuna data di inizio. Ripetere operazione.');
        foreach($workers as $worker) { //worker
            $repetition = 1;
            $finalDate = new DateTime($request->get('endDate'));
            $changeDate = new DateTime($request->get('startDate'));
            while($changeDate != $finalDate) { //day
                for($x=0;$x<2;$x++) { //period
                    $activities = DB::table('plannings')
                                                    ->where([
                                                        ['particular','1'],
                                                        ['operator',$worker->name],
                                                        ['repetition',$repetition],
                                                        ['hour',$x]
                                                    ])
                                                    ->get();
                    if($worker->particular == 1 && !$activities->isEmpty()) {
                        foreach($activities as $act) {
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
                    } else {
                        $activity = new Planning;
                        $activity->activity = "Assistenza";
                        $activity->operator = $worker->name;
                        $activity->date = $changeDate;
                        $activity->type = "Assistenza";
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
        session()->flash('messaggio','Operazione completata.');
        return redirect()->to('/planning')->with(['date' => $request->get('startDate')]);
    }

    /**
     * Store vacation
     */
    public function storeVacation(Request $request)
    {
        $duration = $request->get('duration');
        if($duration == "hours") {   
            $plannings = DB::table('plannings')
                                            ->where([
                                                ['operator', $request->get('operator')],
                                                ['activity','<>','assistenza'],
                                                ['hour',$request->get('hour')],
                                                ['date',$request->get('date')]
                                            ])
                                            ->get();
            if(!$plannings->isEmpty()) {
                session()->flash('alert','Attività definite per il periodo selezionato. Impossibile procedere.');
                return redirect()->to('/planning')->with(['date' => $request->get('date')]);
            }
            $activity = new Planning;
            $activity->activity = $request->get('activity');
            $activity->operator = $request->get('operator');
            $activity->date = $request->get('date');
            $activity->type = "Richiesta Permesso/Ferie";
            $activity->hour = $request->get('hour');
            $activity->time = $request->get('time');
            $activity->edit = Auth::user()->name;
            $activity->particular = 0;
            $activity->save();
            session()->flash('messaggio','Operazione completata');
            return redirect()->to('/planning')->with(['date' => $request->get('date')]);
        } else {
            $finalDate = new DateTime($request->get('endDate'));
            $changeDate = new DateTime($request->get('startDate'));
            if($request->get('operator') == "all")
                $plannings = DB::table('plannings')
                                                ->where([
                                                    ['type','<>','assistenza'],
                                                    ['date','>=',$request->get('startDate')],
                                                    ['date','<=',$request->get('endDate')]
                                                ])
                                                ->get();
            else 
                $plannings = DB::table('plannings')
                                                ->where([
                                                    ['operator', $request->get('operator')],
                                                    ['type','<>','assistenza'],
                                                    ['date','>=',$request->get('startDate')],
                                                    ['date','<=',$request->get('endDate')]
                                                ])
                                                ->get();
            if(!$plannings->isEmpty()) {
                session()->flash('alert','Attività definite per il periodo selezionato. Impossibile procedere.');
                return redirect()->to('/planning')->with(['date' => $request->get('startDate')]);
            }
            $plannings = DB::table('plannings')
                                            ->where([
                                                ['operator', $request->get('operator')],
                                                ['type','assistenza'],
                                                ['date','>=',$request->get('startDate')],
                                                ['date','<=',$request->get('endDate')]
                                            ])
                                            ->delete();
            while($changeDate <= $finalDate) { //day 
                $isweek = $this->isWeekend($changeDate);
                if(!$isweek) {
                    if($request->get('operator') == "all") {
                        $users = User::all();
                        for($x=0;$x<2;$x++) { //period
                            foreach($users as $user) {
                                $activity = new Planning;
                                $activity->activity = "Ferie";
                                $activity->operator = $user->name;
                                $activity->date = $changeDate;
                                $activity->type = "Richiesta Permesso/Ferie";
                                $activity->hour = $x;
                                $activity->edit = Auth::user()->name;
                                $activity->time = $request->get('time');
                                $activity->particular = 0;
                                $activity->save();
                            }
                        }
                    } else
                        for($x=0;$x<2;$x++) { //period
                            $activity = new Planning;
                            $activity->activity = "Ferie";
                            $activity->operator = $request->get('operator');
                            $activity->date = $changeDate;
                            $activity->type = "Richiesta Permesso/Ferie";
                            $activity->hour = $x;
                            $activity->edit = Auth::user()->name;
                            $activity->time = $request->get('time');
                            $activity->particular = 0;
                            $activity->save();
                        }
                }
                $changeDate->modify('+1 day');
            }
            session()->flash('messaggio','Operazione completata.');
            return redirect()->to('/planning')->with(['date' => $request->get('startDate')]);
        }
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
        //TODO: add notification callback
        return redirect()->back()->with('Messaggio: ','Commento inserito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function showAllActivity($user)
    {
        $tasks = DB::table('plannings')
                                    ->where([
                                        ['operator',$user],
                                        ['particular','<>',1],
                                        ['type','<>','Assistenza'],
                                        ['date','>=',Carbon::now()->subDays(7)],
                                        ['date','<=',Carbon::now()->addDays(7)],
                                    ])
                                    ->orderby('date','ASC')
                                    ->orderby('hour','ASC')
                                    ->get();
        return view('planning/allActivities', compact('tasks'));
    }

    /**
     * Make filter vacation
     */
    public function filterVacation(Request $request)
    {
        $users = User::all();
        $operator = $request->get('operatorFilter');
        if($operator == '') {
            $vacationsFiltered = DB::table('plannings')
                                                    ->where([
                                                        ['type','Richiesta Permesso/Ferie'],
                                                        ['date','>=',$request->get('startDateFilter')],
                                                        ['date','<=',$request->get('endDateFilter')],
                                                    ])
                                                    ->orderby('date','ASC')
                                                    ->orderby('hour','ASC')
                                                    ->get();
            $operatorVacations = DB::table('plannings')
                                                    ->where([
                                                        ['activity','ferie'],
                                                        ['operator',Auth::user()->name],
                                                        ['date','>=',Carbon::now()->subDays(7)],
                                                        ['date','<=',Carbon::now()->addDays(7)],
                                                    ])
                                                    ->orderby('date','ASC')
                                                    ->orderby('hour','ASC')
                                                    ->get();
        } else {
            $vacationsFiltered = DB::table('plannings')
                                                    ->where([
                                                        ['type','Richiesta Permesso/Ferie'],
                                                        ['date','>=',$request->get('startDateFilter')],
                                                        ['date','<=',$request->get('endDateFilter')],
                                                        ['operator',$operator],
                                                    ])
                                                    ->orderby('date','ASC')
                                                    ->orderby('hour','ASC')
                                                    ->get();
            $operatorVacations = DB::table('plannings')
                                                    ->where([
                                                        ['activity','ferie'],
                                                        ['operator',Auth::user()->name],
                                                        ['date','>=',Carbon::now()->subDays(7)],
                                                        ['date','<=',Carbon::now()->addDays(7)],
                                                    ])
                                                    ->orderby('date','ASC')
                                                    ->orderby('hour','ASC')
                                                    ->get();
        }
        $pastHour = date('y') - 1;
        $pastHoursRequested = DB::table('plannings')
                                                ->select(DB::raw('SUM(time) as pastTotalHours'))
                                                ->where([
                                                    ['operator',Auth::user()->name],
                                                    ['date','>=','20'.$pastHour.'-01-01'],
                                                    ['date','<=','20'.$pastHour.'-12-31'],
                                                ])
                                                ->get();
        return view('planning/addVacation',compact('users','vacationsFiltered','operatorVacations','pastHoursRequested'));
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
        $notification = Notification::where('id_ref', $id)
                                                    ->first();
        // $notification->read = 1;
        // $notification->save();
        return view('planning/editplanning', compact('activity','users', 'types', 'comments'));
    }

    public function editNotification($id,$notification)
    {
        $activity = Planning::find($id);
        $users = User::all();
        $types = Activity::all();
        $comments = Comment::where('idActivity', $id)
                                                ->get();
        // $notification->read = 0;
        // $notification->save();
        // return view('planning/editplanning', compact('activity','users', 'types', 'comments'));
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
        foreach($intensities as $intensity) {
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
        if($activity->type == "Richiesta Permesso/Ferie" || $activity->type == "ferie")
            $activity->time = $request->get('time');
        else 
            $activity->time = 0;
        $activity->save();
        return redirect()->back()->with('messaggio','Operazione completata');
    }

    /**
     * Update comments activity
     */
    public function updateComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->comment = $request->get('comment');
        $comment->save();
        return redirect()->back()->with('messaggio','Operazione completata');
    }

    /**
     * Update vacation status
     */
    public function confirmVacation(Request $request)
    {
        $operator = $request->get('operatorFiltered');  
        if($request->get('startDateFiltered') != '' && $request->get('endDateFiltered') != ''){
            if($operator == '') {
                $vacationsFiltered = DB::table('plannings')
                                                        ->where([
                                                            ['type','Richiesta Permesso/Ferie'],
                                                            ['date','>=',$request->get('startDateFiltered')],
                                                            ['date','<=',$request->get('endDateFiltered')],
                                                        ])
                                                        ->get();
            } else {
                $vacationsFiltered = DB::table('plannings')
                                                        ->where([
                                                            ['type','Richiesta Permesso/Ferie'],
                                                            ['date','>=',$request->get('startDateFiltered')],
                                                            ['date','<=',$request->get('endDateFiltered')],
                                                            ['operator',$operator],
                                                        ])
                                                        ->get();
            }
            foreach($vacationsFiltered as $vacationFiltered) {
                $vacation = Planning::find($vacationFiltered->id);
                $vacation->type = "ferie";
                $vacation->save();
            }
        } else {
            if($operator == '') {
                $vacationsFiltered = DB::table('plannings')
                                                        ->where([
                                                            ['type','Richiesta Permesso/Ferie']
                                                        ])
                                                        ->get();
            } else {
                $vacationsFiltered = DB::table('plannings')
                                                        ->where([
                                                            ['type','Richiesta Permesso/Ferie'],
                                                            ['operator',$operator],
                                                        ])
                                                        ->get();
            }
            foreach($vacationsFiltered as $vacationFiltered) {
                $vacation = Planning::find($vacationFiltered->id);
                $vacation->type = "ferie";
                $vacation->save();
            }
        }
        return redirect('planning/ferie')->with('Messaggio','Operazione completata');
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
        if($activity->repetition > 0) {
            $activity->delete();
            $activities = DB::table('plannings')
                                            ->where([
                                                ['operator',$user],
                                                ['particular','<>','0']
                                            ])
                                            ->get();
            if($activities->isEmpty()) {
                $user = DB::table('users')
                                        ->where('name',$user)
                                        ->update(['particular'=>0]);
            }
            return redirect()->back()->with('messaggio','Operazione completata');
        } else {
            $date = $activity->date;
            $activity->delete();
            session()->flash('messaggio','Operazione completata');
            return redirect()->to('/planning')->with(['date' => $date]);
        }
    }

    /**
     * Remove comments
     */
    public function destroyComment($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        return redirect()->back()->with('messaggio','Operazione completata');
    }

    /**
     * Remove activity
     */
    public function destroyActivity($id)
    {
        $activity = Activity::find($id);
        $activity->delete();
        return redirect()->back()->with('messaggio','Operazione completata');
    }

    /**
     * Check if is weekend
     */
    public function isWeekend($date) {
        return ($date->format('N') >= 6);
    }

}