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
    <h3 class="headTitle">MODIFICA ATTIVITÀ</h3>
    @if(Auth::user())
        <form method="post" action="{{ action('PlanningController@update', [$activity['id']]) }}" onsubmit='return checkRequired()'>
            {{ csrf_field() }}
            <table class="table table-striped editActivityTable">
                <thead>
                    <tr class="editActivity">
                        <th>Descrizione attività</th>
                        <th>Tipo</th>
                        <th>Periodo</th>
                        <th>Data</th>
                        <th>Operatore</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="editActivity stopped">
                        <td>
                            <textarea name="activity" rows="2" cols="40" placeholder="" required disabled>{{ $activity->activity }}</textarea>
                        </td>
                        <td>
                            <select name="type" class="activitySelect" onchange="openDiv();" disabled>
                                @foreach($types as $type)
                                    <option value="{{ $type->type }}" @if($type->type == $activity->type) selected @endif style="background-color: {{ $type->color }}; color: {{ $type->inv_hex }}; font-weight: bold;">{{ $type->type }}</option>
                                @endforeach
                                <option value="Add" class="addActivity">Aggiungi...</option>
                            </select>                        
                        </td>
                        <td>
                            <div class="content hourDiv">
                                <input id="mattino" type="checkbox" name="hour" value="0" onclick="checkBox('pomeriggio')" disabled @if(!$activity->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                            </div>
                            <div class="content hourDiv">
                                <input id="pomeriggio" type="checkbox" name="hour" value="1" onclick="checkBox('mattino')" disabled @if($activity->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                            </div>
                        </td>
                        <td>
                            <input name="particular" value="0" style="display: none;">
                            <input name="repetition" value="0" style="display: none;">
                            <input name="date" id="datepicker" autocomplete="off" value="{{$activity->date}}" disabled>
                        </td>
                        <td>
                            <select name="operator" disabled>
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}" @if($activity->operator == $user->name) selected @endif>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td id="button">
                            <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow" disabled><i class="fa fa-check fa-lg" aria-hidden="true"></i></button>
                            <button id="modifica" type="button" class="btn btn-primary modifica buttonShadow"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></button>
        </form> 
                            <br>                    
                            <a id="elimina" class="btn btn-danger elimina buttonShadow" href='{{ action("PlanningController@destroy", $activity["id"]) }}' disabled><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Elimina</a>
                        </td>
                    </tr>
                </tbody>
            </table>
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
    @else
        <h3 class="headTitle container centeredText"><strong>Devi effettuare l'accesso per poter modificare un'attività</strong></h3>
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
    $(".elimina").click(function(){
        let result = confirm('Sei sicuro di voler cancellare l\'attività?');
        if(result)
            return true;
        else
            return false;
    })
</script>