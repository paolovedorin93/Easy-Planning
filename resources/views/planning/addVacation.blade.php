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
    </script>
  </head>
  <body>
    @include('include.nav')
    @if(Auth::user())
        <h3 class="headTitle">GESTIONE PERMESSI/FERIE</h3>
        <h6 class="headLittleTitle">Durata permesso/ferie</h6>
        <span class="description">ORE <i class="fa fa-arrow-right margin" aria-hidden="true"></i> Selezionare questo in caso di permesso/ferie per un periodo inferiore alla giornata</span><br>
        <span class="description">GIORNI <i class="fa fa-arrow-right" aria-hidden="true"></i> Permette di selezionare il periodo d'inizio e fine di permesso/ferie</span>
        <form method="post" action="{{ action('PlanningController@store') }}">
            {{ csrf_field() }}
            <table class="table table-striped addActivityTable">
                <thead>
                    <tr class="addActivity">
                        <th>Tipo</th>
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
                        <input name="activity" id="activityHidden" type="text" value="" hidden/>
                        <input name="repetition" value="0" hidden>
                        <input name="particular" value="0" hidden>
                        <!-- TIPO -->
                        <td>
                            <select name="type" id="typeVacation" onchange="return addValue(this); " required>
                                <option value=""></option>
                                <option value="permesso">Permesso</option>
                                <option value="ferie">Ferie</option>
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
                            <input name="time" type="number" class="hours" required/>
                        </td>
                        <!-- FINE ORE -->
                        <!-- DURATA - DATA -->
                        <td class="hoursTd hiddenHour">
                            <input name="date" id="datepicker" autocomplete="off" class="hours" required/>
                        </td>
                        <!-- FINE DURATA - DATA -->
                        <!-- DATA INIZIO -->
                        <td class="datesTd hiddenDate">
                            <input name="startDate" id="datepickerStart" autocomplete="off" class="dates" required/>
                        </td>
                        <!-- FINE DATA INIZIO -->
                        <!-- DATA FINE -->
                        <td class="datesTd hiddenDate">
                            <input name="date" id="datepickerEnd" autocomplete="off" class="dates" required/>
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
                            <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow" onclick="return showMessage();"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Conferma</button>
        </form>
                            <br>
                            <a id="elimina" class="btn btn-danger elimina eliminaBig buttonShadow" href="{{ action('PlanningController@index') }}"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Indietro</a>
                        </td>
                    </tr>
                </tbody>
            </table>
                @foreach($vacations as $vacation)
                    @if(Auth::user()->name == $vacation->operator)
                        <form method="post" action="{{ action('PlanningController@store') }}">
                        {{ csrf_field() }}
                        <table class="table table-striped addActivityTable">
                            <thead>
                                <tr class="addActivity">
                                    <th>Tipo</th>
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
                                    <input name="activity" type="text" value="ferie" hidden/>
                                    <input name="repetition" value="0" hidden>
                                    <input name="particular" value="0" hidden>
                                    <!-- TIPO -->
                                    <td>
                                        <select name="type" id="typeVacation" required>
                                            <option value=""></option>
                                            <option value="permesso">Permesso</option>
                                            <option value="ferie">Ferie</option>
                                        </select>
                                    </td>
                                    <!-- FINE TIPO -->
                                    <!-- DURATA -->
                                    <td>
                                        <input name="activity" type="text" value="ferie" hidden/>
                                        <select id="howLongVacation" onchange="removeClass();" required>
                                            <option value=""></option>
                                            <option value="hours">Ore</option>
                                            <option value="days">Giorni</option>
                                        </select>
                                    </td>
                                    <!-- DURATA -->
                                    <!-- ORE -->
                                    <td class="hoursTd hiddenHour">
                                        <input name="time" type="number" class="hours" required/>
                                    </td>
                                    <!-- FINE ORE -->
                                    <!-- DURATA - DATA -->
                                    <td class="hoursTd hiddenHour">
                                        <input name="startDate" id="datepicker" autocomplete="off" class="hours" required/>
                                    </td>
                                    <!-- FINE DURATA - DATA -->
                                    <!-- DATA INIZIO -->
                                    <td class="datesTd hiddenDate">
                                        <input name="startDate" id="datepickerStart" autocomplete="off" class="dates" required/>
                                    </td>
                                    <!-- FINE DATA INIZIO -->
                                    <!-- DATA FINE -->
                                    <td class="datesTd hiddenDate">
                                        <input name="date" id="datepickerEnd" autocomplete="off" class="dates" required/>
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
                                        <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Conferma</button>
                    </form>
                                        <br>
                                        <a id="elimina" class="btn btn-danger elimina eliminaBig buttonShadow" href="{{ action('PlanningController@index') }}"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Indietro</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                @endif
                @if($today <= $vacation->startDate)
                    <script>
                        $("#button").attr('display','none');
                    </script>
                @endif
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
    let insideHours = 0;
    let insideDates = 0;
    function removeClass() {
        let value = $('#howLongVacation > option:selected').val();
        if(value === 'hours'){
            if(insideDates===1){
                console.log("insideDates: ", insideDates);
                $('thead > tr > .dates').addClass('hiddenDate');
                $('tbody > tr > .datesTd').addClass('hiddenDate');
                insideDates = 0;
            }
            $('thead > tr > .hours').removeClass('hiddenHour');
            $('tbody > tr > .hoursTd').removeClass('hiddenHour');
            insideHours = 1;
        } else if(value === 'days') {
            if(insideHours==1){
                console.log("insideHours: ", insideHours);
                $('thead > tr > .hours').addClass('hiddenHour');
                $('tbody > tr > .hoursTd').addClass('hiddenHour');
                insideHours = 0;
            }
            $('thead > tr > .dates').removeClass('hiddenDate');
            $('tbody > tr > .datesTd').removeClass('hiddenDate');
            insideDates = 1;
        }
    }
</script>
<script>
    function addValue(elem){
        let value = elem.value;
        $('#activityHidden').attr('value',value);
    }
</script>
<script>
    function showMessage(){
        if(confirm('La procedura di creazione è IRREVERSIBILE. Confermi la procedura di creazione?'))
        {
            return true;
        } else
        {
            return false;
        }
    }
</script>