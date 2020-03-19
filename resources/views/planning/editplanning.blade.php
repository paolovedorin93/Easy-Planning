<!DOCTYPE html>
<html lang='it'>
  <head>
    <meta charset='utf-8' />

    <link type="javascript" src="/Easy-Planning/public/js/script.js" />
    <link href="/Easy-Planning/public/css/aspect.css" rel="stylesheet">
    <link href='/Easy-Planning/packages/core/main.css' rel='stylesheet' />
    <link href='/Easy-Planning/packages/daygrid/main.css' rel='stylesheet' />


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
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
    <form method="post" action="{{ action('PlanningController@update', [$activity['id']]) }}" onsubmit='return checkRequired()'>
        {{ csrf_field() }}
        <table class="table table-striped addActivityTable">
            <thead>
                <tr class="addActivity">
                    <th>Descrizione attività</th>
                    <th>Tipo</th>
                    <th>Periodo</th>
                    <th>Data</th>
                    <th>Operator</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr class="addActivity">
                    <td>
                        <textarea name="activity" rows="2" cols="40" placeholder="" required>{{ $activity->activity }}</textarea>
                    </td>
                    <td>
                        <input name="type" rows="2" cols="40" value="{{ $activity->type }}" required>
                    </td>
                    <td>
                        <div class="content hourDiv">
                            <input type="checkbox" name="hour" value="0" @if(!$activity->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                        </div>
                        <div class="content hourDiv">
                            <input type="checkbox" name="hour" value="1" @if($activity->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                        </div>
                    </td>
                    <td>
                        <input name="date" id="datepicker" autocomplete="off" value="{{$activity->date}}">
                    </td>
                    <td>
                        <select name="operator">
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" @if($activity->operator == $user->name) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td id="button">
                        <div class="form-group row">
                            <button id="elimina" type="submit" class="btn btn-primary"><i class="fa fa-plus fa-lg">&nbsp;&nbsp;&nbsp;</i>Aggiungi</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
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