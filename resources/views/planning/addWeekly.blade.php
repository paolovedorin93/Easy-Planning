<!DOCTYPE html>
<html lang='it'>
  <head>
    <meta charset='utf-8' />

    <link type="javascript" src="/Easy-Planning/public/js/script.js" />
    <link href="/Easy-Planning/public/css/aspect.css" rel="stylesheet">
    <link href='/Easy-Planning/packages/core/main.css' rel='stylesheet' />
    <link href='/Easy-Planning/packages/daygrid/main.css' rel='stylesheet' />

    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />
    <link href="http://127.0.0.2/Easy-Planning/public/css/app.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://github.com/jquery/jquery-ui/blob/master/ui/i18n/datepicker-it.js"></script>
    <script>
    $( function() {
        $( "#datepicker" ).datepicker({
            beforeShowDay: $.datepicker.noWeekends,
            dateFormat: "yy-mm-dd",
            dayNamesMin: [ "Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab" ],
            monthNames: [ "Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno",
                          "Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre" ],
            firstday: 1
        });
    } );
    </script>
    
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            @if(Auth::guest())
            <div class="dropdown myDropDown">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
                <a class="nav-link" href="{{ route('register') }}">Registrati</a>
            </div>
            @else
            <div class="dropdown myDropDown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    @if(Auth::user()->administrator)<a class="dropdown-item" href="workers">Gestione utenti</a>@endif
                    <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            @endif
        </div>
    </nav>
    @if (session('alert'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('alert') }}
        </div>
    @endif
    @if (session('messaggio'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('messaggio') }}
        </div>
    @endif
    <h3 class="headTitle">CREA NUOVE ATTIVITÀ SETTIMANALI</h3>
    @if(Auth::User())
        @if(Auth::user()->admin)
            <div class="main2 buttonBottom2" id="nav-icon2">
                <form action="{{ action('PlanningController@storeWeeklyActivity') }}">
                    {{ csrf_field() }}
                    <div class="bottomRightCorner2 extend2 open2">
                        <input class="hidden" name="startDate" value="{{ $startDate }}">
                        <input class="hidden" name="endDate" value="{{ $endDate }}">
                        <button id="genWeekly" type="submit">Genera attività settimanali</button>
                    </div>
                </form>
            </div>
            <form method="post" action="{{ action('PlanningController@store') }}" onsubmit='return checkRequired()'>
                {{ csrf_field() }}
                <table class="table table-striped editActivityTable">
                    <thead>
                        <tr class="editActivity">
                            <th>Descrizione attività</th>
                            <th>Tipo</th>
                            <th>Periodo</th>
                            <th>Operatore</th>
                            <th>Ripetere ogni</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="editActivity stopped">
                            <td>
                                <textarea name="activity" rows="2" cols="40" required></textarea>
                            </td>
                            <td>
                                <select name="type" class="activitySelect" onchange="openDiv();">
                                    <option value=""></option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->type }}" style="background-color: {{ $type->color }}; color: {{ $type->inv_hex }};">{{ $type->type }}</option>
                                    @endforeach
                                    <option value="Add" class="addActivity">Aggiungi...</option>
                                </select>                       
                            </td>
                            <td>
                                <div class="content hourDiv">
                                    <input id="mattino" type="checkbox" name="hour" value="0" onclick="checkBox('pomeriggio')"><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                                </div>
                                <div class="content hourDiv">
                                    <input id="pomeriggio" type="checkbox" name="hour" value="1" onclick="checkBox('mattino')"><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                                </div>
                            </td>
                            <td>
                                <select name="operator">
                                    <option value=""></option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input name="particular" value="1" style="display: none;">
                                <select name="repetition" id="repetition" required>
                                    <option value=""></option>
                                    <option value="1">Lunedì</option>
                                    <option value="2">Martedì</option>
                                    <option value="3">Mercoledì</option>
                                    <option value="4">Giovedì</option>
                                    <option value="5">Venerdì</option>
                                </select>
                            </td>
                            <td id="button">
                                <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Conferma</button>
                                <br>                    
                                <a id="elimina" class="btn btn-danger elimina eliminaBig buttonShadow" onclick="return window.history.back();"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Indietro</a>
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </form> 
            <div class="addActivityDiv addActivityDivClose">
                <form method="post" action="{{ action('PlanningController@storeActivity') }}">
                    {{ csrf_field() }}
                    <div class="content divContent">
                        <input name="type" placeholder="Aggiungi tipo attività..." required>
                        <input id="hex" name="color" type="color" required>
                        <input id="invHex" name="inv_hex" style="display: none;">
                        <button id="addType" type="submit" class="btn btn-primary aggiungi buttonShadow"><i class="fa fa-plus fa-lg"></i>&nbsp;&nbsp;&nbsp;Aggiungi</button>
                    </div>
                </form>
            </div>
            <table class="table table-striped editActivityTable">
                <thead class="editActivity">
                    <tr>
                        <th>Descrizione attività</th>
                        <th>Tipo</th>
                        <th>Periodo</th>
                        <th>Operatore</th>
                        <th>Ripetere ogni</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <script>
                        let found;
                    </script>
                    @foreach($particularActs as $particularAct)
                    <form method="POST" action="{{ action('PlanningController@update', $particularAct->id) }}">
                        {{ csrf_field() }}
                        <tr class="editActivity stopped tr{{$particularAct->operator}} tr{{$particularAct->id}}">
                            <td>
                                <textarea name="activity" rows="2" cols="40" required disabled>{{ $particularAct->activity }}</textarea>
                            </td>
                            <td>
                                <select name="type" class="activitySelect" onchange="openDiv();" disabled>
                                    @foreach($types as $type)
                                        <option value="{{ $type->type }}" @if($type->type == $particularAct->type) selected @endif style="background-color: {{ $type->color }}; color: {{ $type->inv_hex }}; font-weight: bold;">{{ $type->type }}</option>
                                    @endforeach
                                    <option value="Add" class="addActivity">Aggiungi...</option>
                                </select>                        
                            </td>
                            <td>
                                <div class="content hourDiv">
                                    <input id="mattino" type="checkbox" name="hour" value="0" onclick="checkBox('pomeriggio')" disabled @if(!$particularAct->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                                </div>
                                <div class="content hourDiv">
                                    <input id="pomeriggio" type="checkbox" name="hour" value="1" onclick="checkBox('mattino')" disabled @if($particularAct->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                                </div>
                            </td>
                            <td>
                                <select name="repetition" style="display:none;">
                                    <option value=""></option>
                                </select>
                                <select name="operator" disabled>
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}" @if($particularAct->operator == $user->name) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input name="particular" value="1" style="display: none;">
                                <select name="repetition" id="repetition" required disabled>
                                    <option value=""></option>
                                    <option id="giorno1" value="1">Lunedì</option>
                                    <option id="giorno2" value="2">Martedì</option>
                                    <option id="giorno3" value="3">Mercoledì</option>
                                    <option id="giorno4" value="4">Giovedì</option>
                                    <option id="giorno5" value="5">Venerdì</option>
                                </select>
                                <script>
                                    found = 0;
                                    for(let x=0;x<6 && found==0;x++){
                                        let value = $("#repetition")[0].children[x].value;
                                        console.log("value: ", value);
                                        console.log("{{ $particularAct->repetition }}");
                                        if(value === "{{ $particularAct->repetition }}"){
                                            $("#giorno"+x).prop('selected','true');
                                            found=1;
                                        }   
                                    }
                                </script>
                            </td>
                            <td>
                                <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow" disabled><i class="fa fa-check fa-lg" aria-hidden="true"></i></button>
                                <button id="modifica" type="button" class="btn btn-primary modifica buttonShadow"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></button>
                                <br>
                                <a id="elimina" class="btn btn-danger elimina buttonShadow" href='{{ action("PlanningController@destroy", $particularAct->id) }}' disabled><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Elimina</a>
                            </td>
                            <td></td>
                        </tr>
                    </form>
                    @endforeach
                </tbody>
            </table>
        @else
            <h3 class="headTitle container centeredText"><strong>Non sei autorizzato a creare attività predefinite. <a href="./">Torna al planning</a></strong></h3>
        @endif
    @else
        <h3 class="headTitle container centeredText"><strong>Devi effettuare l'accesso per poter inserire un'attività</strong></h3>
    @endif
  </body>
</html>
<script>
    function checkRequired(){
        var checkedboxes = $('.hourDiv :checkbox:checked').length;

        if (checkedboxes === 0){
            alert('Selezionare periodo di attività');
            return false;
        }
    }
</script>
<script>
    $(document).ready(function(){
        $('#nav-icon1').click(function(){
            $(this).toggleClass('open');
        });
        $('.myDropDown').click(function(){
            $('.dropdown-menu-right').toggleClass('openDrop');
        });
    });
</script>
<script>
    function openDiv(){
        let variable = document.getElementsByClassName("activitySelect")[0];
        let inside = 0;
        if(variable.value === "Add" && inside!=1){
            $(".addActivityDiv").toggleClass('addActivityDivClose');
            inside++;
        }
        else{
            $(".addActivityDiv").toggleClass('addActivityDivClose');
            inside--;
        }
    }
</script>
<script>
    function invertHex(hex) {
        return (Number(`0x1${hex}`) ^ 0xFFFFFF).toString(16).substr(1).toUpperCase();
    }
</script>
<script>
    $(document).ready(function(){
        $("#addType").click(function(){
            let invColor = invertHex(document.getElementById("hex").value.substr(1));
            document.getElementById("invHex").value = "#"+invColor;
        });
    });
</script>
<script>
    $("#modifica").click(function(){
        $(".editActivity > td > *").prop('disabled', false);
        $(".editActivity > td > div > *").prop('disabled', false);
        $(".conferma").prop('disabled', false);
        $(".elimina").prop('disabled', false);
    })
</script>
<script>
    function checkBox(notChecked) {
        $("#"+notChecked).prop('checked', false);
    }
</script>
<script>
    $('textarea').keypress(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            alert('INVIO non consentito.');
        }
    });
</script>
<script>
    $("#genWeekly").on('click',function(){
        if(confirm("Generare attività per la settimana {{$startDate}} - {{$endDate}} ?"))
            return true;
        else
            return false;
    })
</script>
<script>
    $(".close").click(function(){
        $('.alert').css('display','none');
    });
</script>