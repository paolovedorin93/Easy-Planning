<!DOCTYPE html>
<html lang='it'>
  <head>
    <title>Planning | Aggiungi ferie</title>
    @include('include.head')
    <script>
        $(function(){
            $( "#datepicker" ).datepicker({
                beforeShowDay: $.datepicker.noWeekends,
                dateFormat: "yy-mm-dd",
                dayNamesMin: [ "Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab" ],
                monthNames: [ "Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno",
                            "Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre" ],
                firstday: 1
            });
        });
        $(function(){
            $( "#datepickerStart" ).datepicker({
                beforeShowDay: $.datepicker.noWeekends,
                dateFormat: "yy-mm-dd",
                dayNamesMin: [ "Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab" ],
                monthNames: [ "Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno",
                            "Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre" ],
                firstday: 1
            });
        });
        $(function(){
            $( "#datepickerEnd" ).datepicker({
                beforeShowDay: $.datepicker.noWeekends,
                dateFormat: "yy-mm-dd",
                dayNamesMin: [ "Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab" ],
                monthNames: [ "Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno",
                            "Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre" ],
                firstday: 1
            });
        });
        $(function(){
            $( "#datepickerStartFilter" ).datepicker({
                beforeShowDay: $.datepicker.noWeekends,
                dateFormat: "yy-mm-dd",
                dayNamesMin: [ "Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab" ],
                monthNames: [ "Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno",
                            "Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre" ],
                firstday: 1
            });
        });
        $(function(){
            $( "#datepickerEndFilter" ).datepicker({
                beforeShowDay: $.datepicker.noWeekends,
                dateFormat: "yy-mm-dd",
                dayNamesMin: [ "Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab" ],
                monthNames: [ "Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno",
                            "Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre" ],
                firstday: 1
            });
        });
    </script>
  </head>
  <body>
    @include('include.nav')
    @if(Auth::user())
        @if (session('Messaggio'))
            <div class="alert alert-success alertBox">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session('Messaggio') }}
            </div>
        @endif
        @if (session('alert'))
            <div class="alert alert-danger alertBox">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session('alert') }}
            </div>
        @endif
        <h3 class="headTitle">GESTIONE PERMESSI/FERIE</h3>
        <h6 class="headLittleTitle">Durata permesso/ferie</h6>
        <span class="description">ORE <i class="fa fa-arrow-right margin" aria-hidden="true"></i> Selezionare questo in caso di permesso/ferie per un periodo inferiore alla giornata</span><br>
        <span class="description">GIORNI <i class="fa fa-arrow-right" aria-hidden="true"></i> Permette di selezionare il periodo d'inizio e fine di permesso/ferie</span>
        <form method="post" action="{{ action('PlanningController@storeVacation') }}">
            {{ csrf_field() }}
            <table class="table table-striped addActivityTable">
                <thead>
                    <tr class="addActivity">
                        <th></th>
                        <th>Durata permesso/ferie</th>
                        <th class="hours hiddenHour">N° Ore</th>
                        <th class="hours hiddenHour">Data</th>
                        <th class="dates hiddenDate">Data inizio</th>
                        <th class="dates hiddenDate">Data fine</th>
                        <th>Operatore</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="addActivity">
                        <input name="activity" id="activityHidden" type="text" value="Ferie" hidden/>
                        <input name="repetition" value="0" hidden>
                        <input name="particular" value="0" hidden>
                        <!-- TIPO -->
                        <td>
                            <select name="type" id="typeVacation" hidden>
                                <option value="richiesta permesso/ferie" selected>Ferie</option>
                            </select>
                        </td>
                        <!-- FINE TIPO -->
                        <!-- DURATA -->
                        <td>
                            <select name="duration" id="howLongVacation" onchange="removeClass();" required>
                                <option value=""></option>
                                <option value="hours">Ore</option>
                                <option value="days">Giorni</option>
                            </select>
                        </td>
                        <!-- DURATA -->
                        <!-- ORE -->
                        <td class="hoursTd hiddenHour">
                            <input name="time" type="number" id="hoursRequired" class="hoursRequired" value="0" min="0" max="4" step=".25" required/>
                            <div class="content hourDiv">
                                <input id="mattino" type="checkbox" name="hour" value="0" onclick="checkBox('pomeriggio')" checked><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                            </div>
                            <div class="content hourDiv">
                                <input id="pomeriggio" type="checkbox" name="hour" value="1" onclick="checkBox('mattino')"><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                            </div>
                        </td>
                        <!-- FINE ORE -->
                        <!-- DURATA - DATA -->
                        <td class="hoursTd hiddenHour">
                            <input name="date" value="0000-00-00" id="datepicker" autocomplete="off" class="hours" required/>
                        </td>
                        <!-- FINE DURATA - DATA -->
                        <!-- DATA INIZIO -->
                        <td class="datesTd hiddenDate">
                            <input name="startDate" value="0000-00-00" id="datepickerStart" autocomplete="off" class="dates" required/>
                        </td>
                        <!-- FINE DATA INIZIO -->
                        <!-- DATA FINE -->
                        <td class="datesTd hiddenDate">
                            <input name="time" type="number" id="hoursRequired" class="hoursRequired" value="4" hidden/>
                            <input name="endDate" value="0000-00-00" id="datepickerEnd" autocomplete="off" class="dates" required/>
                        </td>
                        <!-- FINE DATA FINE -->
                        <!-- OPERATORE -->
                        <td>
                            <select name="operator" required>
                                <option value="" selected></option>
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <!-- FINE OPERATORE -->
                        <td id="button">
                            <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow" onclick="openOutlook();"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Conferma</button>
        </form>
                            <br>
                            <a id="elimina" class="btn btn-danger elimina eliminaBig buttonShadow" href="{{ action('PlanningController@index') }}"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Indietro</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        @if(Auth::user()->ammVacation == 1)
            <h3 class="headTitle">PERMESSI DA GESTIRE</h3>
            <div class="container containerFilter">
                <form method="post" action="{{ action('PlanningController@filterVacation') }}">
                    {{ csrf_field() }}
                    <span>Filtro da data</span>
                    <input name="startDateFilter" value="<?php echo isset($_POST['startDateFilter']) ? $_POST['startDateFilter'] : '' ?>" id="datepickerStartFilter" autocomplete="off" class="dates"/>
                    <span class="spanDateFilter">a data</span>
                    <input name="endDateFilter" value="<?php echo isset($_POST['endDateFilter']) ? $_POST['endDateFilter'] : '' ?>" id="datepickerEndFilter" autocomplete="off" class="dates inputDateFilter"/>
                    <span class="">e operatore</span>
                    <select name="operatorFilter" id="operatorFilter">
                        <option value="" selected></option>
                        @foreach($users as $user)
                            <?php
                                $selectedOperator = '';
                                if(isset($_POST['operatorFilter']))
                                    $selectedOperator = $_POST['operatorFilter'];
                                if($user['name'] == $selectedOperator)
                                    $isSelected = ' selected="selected"';
                                else
                                    $isSelected = '';
                                echo "<option value='".$user['name']."'".$isSelected.">".$user['name']."</option>";
                            ?>
                        @endforeach
                    </select>
                    <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow" style="margin-left: 63px;"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Conferma</button>
                    <a class="btn btn-danger elimina eliminaBig buttonShadow" href="{{ action('PlanningController@indexVacation') }}"><i class="fa fa-times fa-lg" aria-hidden="true"></i>&nbsp;No Filtro</a>
                </form>
            </div>
            <div class="container">
                <table class="tableComments containerFilter">
                    <thead>
                        <tr class="bold">
                            <td>UTENTE</td>
                            <td>PERIODO</td>
                            <td>DURATA (h)</td>
                            <td>DATA</td>
                            <td>STATO</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vacationsFiltered as $vacation)
                            <tr>
                                <td>{{ $vacation->operator }}</td>
                                @if($vacation->hour == 0)
                                    <td>Mattina</td>
                                @else
                                    <td>Pomeriggio</td>
                                @endif
                                <td>{{ $vacation->time }}</td>
                                <td class="date{{ $vacation->id }}"></td>
                                <script>
                                    date = new Date('{{ $vacation->date }}');
                                    dateToAdd = date.toLocaleString('it-IT');
                                    dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10)
                                    $(".date{{ $vacation->id }}").append(dateToAdd);
                                </script>
                                <td>Da confermare</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="main2 buttonBottom2" id="nav-icon2">
                <form method="post" action="{{ action('PlanningController@confirmVacation') }}">
                    {{ csrf_field() }}
                    <div class="bottomRightCorner2 extend2 open2">
                        <input name="startDateFiltered" value="<?php echo isset($_POST['startDateFilter']) ? $_POST['startDateFilter'] : '' ?>" id="datePickedStartFilter" autocomplete="off" class="dates" hidden/>
                        <input name="endDateFiltered" value="<?php echo isset($_POST['endDateFilter']) ? $_POST['endDateFilter'] : '' ?>" id="datePickedEndFilter" autocomplete="off" class="dates inputDateFilter" hidden/>
                        <select name="operatorFiltered" id="operatorFilter" hidden>
                            <option value="" selected></option>
                            @foreach($users as $user)
                                <?php
                                    $selectedOperator = '';
                                    if(isset($_POST['operatorFilter']))
                                        $selectedOperator = $_POST['operatorFilter'];
                                    if($user['name'] == $selectedOperator)
                                        $isSelected = ' selected="selected"';
                                    else
                                        $isSelected = '';
                                    echo "<option value='".$user['name']."'".$isSelected.">".$user['name']."</option>";
                                ?>
                            @endforeach
                        </select>
                        <button id="genWeekly" type="submit">Conferma ferie in elenco</button>
                    </div>
                </form>
            </div>
        @endif
        <h3 class="headTitle">I TUOI PERMESSI</h3>
        <div class="container">
            <table class="tableComments containerFilter">
                <thead>
                    <tr class="bold">
                        <td>UTENTE</td>
                        <td>PERIODO</td>
                        <td>DURATA (h)</td>
                        <td>DATA</td>
                        <td>STATO</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $time = 0; ?>
                    <?php $pastTime = 0; ?>
                    @foreach($operatorVacations as $operatorVacation)
                        <tr>
                            <td>{{ $operatorVacation->operator }}</td>
                            @if($operatorVacation->hour == 0)
                                <td>Mattina</td>
                            @else
                                <td>Pomeriggio</td>
                            @endif
                            <td>{{ $operatorVacation->time }}</td>
                            <td class="date{{ $operatorVacation->id }}2"></td>
                            <script>
                                date = new Date('{{ $operatorVacation->date }}');
                                dateToAdd = date.toLocaleString('it-IT');
                                dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10)
                                $(".date{{ $operatorVacation->id }}2").append(dateToAdd);
                            </script>
                            @if( $operatorVacation->type == "ferie" )
                                <td>Confermata</td>
                            @else
                                <td>Da confermare</td>
                            @endif
                        </tr>
                        <?php 
                            $time = $time + $operatorVacation->time;
                        ?>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="centerTop">TOTALE ORE PERMESSI RICHIESTI: {{ $time }}</p>
        @foreach($pastHoursRequired as $pastHourRequired)
            <p class="centerBottom">TOTALE ORE PERMESSI RICHIESTI {{ date('yy') -1 }}: {{ $pastHourRequired->pastTotalHours }}</p>
        @endforeach
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
        $(".fc-button-group").append('<input name="date" id="goToDate" placeholder="aaaa/mm/gg" />');
    });
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
    function checkBox(notChecked) {
        $("#"+notChecked).prop('checked', false);
    }
</script>
<script>
    let insideHours = 0;
    let insideDates = 0;
    function removeClass() {
        let value = $('#howLongVacation > option:selected').val();
        if(value === 'hours'){
            if(insideDates===1){
                $('thead > tr > .dates').addClass('hiddenDate');
                $('tbody > tr > .datesTd').addClass('hiddenDate');
                insideDates = 0;
            }
            $('thead > tr > .hours').removeClass('hiddenHour');
            $('#datepicker').removeAttr("value");
            $('#hoursRequired').removeAttr("value");
            $('#datepickerStart').attr("value","0000-00-00");
            $('#datepickerEnd').attr("value","0000-00-00");
            $('tbody > tr > .hoursTd').removeClass('hiddenHour');
            insideHours = 1;
        } else if(value === 'days') {
            if(insideHours==1){
                $('thead > tr > .hours').addClass('hiddenHour');
                $('tbody > tr > .hoursTd').addClass('hiddenHour');
                insideHours = 0;
            }
            $('thead > tr > .dates').removeClass('hiddenDate');
            $('#datepickerStart').removeAttr("value");
            $('#datepickerEnd').removeAttr("value");
            $('#datepicker').attr("value","0000-00-00");
            $('#hoursRequired').attr("value","0");
            $('tbody > tr > .datesTd').removeClass('hiddenDate');
            insideDates = 1;
        }
    }
</script>
<script>
    $(".close").click(function(){
        $('.alert').css('display','none');
    });
</script>
<script>
    function openOutlook(){
        hoursToEmal = $("#hoursRequired").val();
        dateToEmail = new Date($("#datepicker").val());
        dateToAdd = dateToEmail.toLocaleString('it-IT');
        dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10);
        period = $("#mattino");
        if($("#mattino").is(':checked'))
            period = "mattino";
        else if ($("#pomeriggio").is(':checked'))
            period = "pomeriggio";
        emailTo = "elena@exeprogetti.mail";
        emailCC = "martino@exeprogetti.mail";
        emailSub = "Richiesta permesso/ferie";
        emailBody = "Ciao," + "%0D%0A" + "%0D%0A" + "  chiedo permesso di " + hoursToEmal + " ore per il giorno " + dateToAdd + " " + period;
        location.href = "mailto:"+emailTo+'?cc='+emailCC+'&subject='+emailSub+'&body='+emailBody;
    }
</script>