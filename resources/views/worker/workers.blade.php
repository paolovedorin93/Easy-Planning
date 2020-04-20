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
        @include('include.nav')
        @if(Auth::guest() || Auth::user()->admin==0)
        <div class="container centeredText">Non hai diritti di amministratore per poter modificare le impostazioni utenti</div>
        @else
        <div class="flex-center position-ref full-height">
            <div id="firstWorkerForm">
                <span class="spanWorker">Qui è possibile modificare le abilitazioni degli utenti</span>
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
            <div id="secondWorkerForm">
                <span class="spanWorker">Qui è possibile modificare la gestione dell'intensità lavoro</span>
                <form class="formSecondWorker" method="post" action="{{ action('PlanningController@updateIntensity') }}">
                    {{csrf_field()}}
                    <?php $count = 1; ?>
                    @foreach($intensities as $intensity)
                        @if($count==1)
                            <div class="divLight">
                                <span>Numero utenti occupati per colore</span><span class="spanColorWorkerLight"><strong> Verde chiaro</strong></span>
                                <input name="green" type="number" value="{{ $intensity->number }}">
                            </div>
                        @endif
                        @if($count==2)
                            <div class="divMedium">
                                <span>Numero utenti occupati per colore</span><span class="spanColorWorkerMedium"><strong> Giallo</strong></span>
                                <input name="yellow" type="number" value="{{ $intensity->number }}">
                            </div>
                        @endif
                        @if($count==3)
                            <div class="divHard">
                                <span>Numero utenti occupati per colore</span><span class="spanColorWorkerHard"><strong> Rosso</strong></span>
                                <input name="red" type="number" value="{{ $intensity->number }}">
                            </div>
                        @endif
                        <?php 
                            $count++;
                        ?>
                    @endforeach
                    <div class="content save secondFormSave">
                        <button type="submit" class="btn btn-info">save</button>
                    </div>
                </form>
                <a class="backTo" href="../public/planning">Torna al planning</a>
            </div>
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
