<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Planning Exe Progetti | Utenti</title>

        <!-- Fonts & Style -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href='../packages/core/main.css' rel='stylesheet' />
        <link href='../packages/daygrid/main.css' rel='stylesheet' />
        <link href="../public/css/aspect.css" rel="stylesheet">
        <link type="javascript" src="../js/script.js" />
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
        <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
        <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />
        <link href="http://127.0.0.2/Easy-Planning/public/css/app.css" rel="stylesheet">
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
        @if(Auth::guest() || Auth::user()->admin==0)
        <div class="container centeredText">Non hai diritti di amministratore per poter modificare le impostazioni utenti</div>
        @else
        <div class="flex-center position-ref full-height">
            <form method="post" action="{{ action('WorkerController@update') }}">
                <table>
                    <th>
                        <td class="label">Sospeso</td>
                        <td class="label">Amministratore</td>
                        <td class="label lastWorker">No Assistenza</td>
                    </th>
                        {{csrf_field()}}
                        @foreach($workers as $worker)
                            <?php
                                $suspended = $worker->suspended;
                                $no_assi = $worker->no_assi;
                                $admin = $worker->admin;
                            ?>
                            <tr class="contentTable">
                                <td class="nameWorker">{{ $worker['name'] }}</td>
                                <td class="inputWorker"><input id="workerSuspended" type="checkbox" value="{{ $suspended }}" name="suspended[{{$worker->id}}]" @if($suspended) checked  @endif></td>
                                <td class="inputWorker"><input id="workerNoAssi" type="checkbox" value="{{ $admin }}" name="admin[{{$worker->id}}]" @if($admin) checked  @endif></td>
                                <td class="inputWorker lastWorker"><input id="workerNoAssi" type="checkbox" value="{{ $no_assi }}" name="no_assi[{{$worker->id}}]" @if($no_assi) checked  @endif></td>
                            </tr>
                        @endforeach
                </table>
                <div class="content save">
                    <button type="submit" class="btn btn-info">save</button>
                </div>
            </form>
        </div>
        @endif
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
    </body>
</html>
