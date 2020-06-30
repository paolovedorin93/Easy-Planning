<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('include.head')
    <body>
        @include('include.nav')
        @if(Auth::guest() || Auth::user()->admin==0)
        <div class="container centeredText">Non hai diritti di amministratore per poter modificare le impostazioni utenti</div>
        @else
        <div class="flex-center position-ref full-height">
            <div id="firstWorkerForm">
                <span class="spanWorker">Qui è possibile modificare le abilitazioni degli utenti</span>
                <form method="post" action="{{ action('WorkerController@update') }}">
                    {{csrf_field()}}
                    <table>
                        <th>
                            <td class="label">Sospeso</td>
                            <td class="label">Amministratore</td>
                            <td class="label">Amm. Permessi</td>
                            <td class="label lastWorker">No Assistenza</td>
                        </th>   
                        @foreach($workers as $worker)
                            <?php
                                $suspended = $worker->suspended;
                                $no_assi = $worker->no_assi;
                                $admin = $worker->admin;
                            ?>
                            <tr class="contentTable">
                                <td class="nameWorker">{{ $worker['name'] }}</td>
                                <td class="inputWorker"><input id="workerSuspended" type="checkbox" value="{{ $worker->suspended }}" name="suspended[{{ $worker->id }}]" @if($worker->suspended) checked  @endif></td>
                                <td class="inputWorker"><input id="workerNoAssi" type="checkbox" value="{{ $worker->admin }}" name="admin[{{ $worker->id }}]" @if($worker->admin) checked  @endif></td>
                                <td class="inputWorker"><input id="workerNoAssi" type="checkbox" value="{{ $worker->ammVacation }}" name="ammVacation[{{ $worker->id }}]" @if($worker->ammVacation) checked  @endif></td>
                                <td class="inputWorker lastWorker"><input id="workerNoAssi" type="checkbox" value="{{ $worker->no_assi }}" name="no_assi[{{ $worker->id }}]" @if($worker->no_assi) checked  @endif></td>
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
            <div id="thirdWorkerForm">
                <span class="spanWorker">Qui è possibile modificare le abilitazioni degli utenti</span>
                <table>
                    <th>
                        <td class="label color">Colore</td>
                        <td class="label">Attività</td>
                    </th>   
                    @foreach($activities as $activity)
                        <tr>
                            <td></td>
                            <td class="label color" style="background-color: {{ $activity->color }};"></td>
                            <td class="labelActivity">{{ $activity->type }}</td>
                        </tr>
                    @endforeach
                    <tr class="">
                        <td></td>
                        <td class="label color"></td>
                        <td>
                            <form method="post" action="{{ action('PlanningController@storeActivity') }}">
                                {{ csrf_field() }}
                                <input name="type" placeholder="Aggiungi tipo attività..." onkeyup="return forceLower(this);" required>
                                <input id="hex" name="color" type="color" required>
                                <button id="addType" type="submit" class="btn btn-primary aggiungi buttonShadow"><i class="fa fa-plus fa-lg"></i>&nbsp;&nbsp;&nbsp;Aggiungi</button>
                            </form>
                        </td>
                    </tr>
                </table>
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
