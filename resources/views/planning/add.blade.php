<!DOCTYPE html>
<html lang='it'>
  <head>
    <title>Planning | Aggiungi attività</title>
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
    </script>
  </head>
  <body>
    @include('include.nav')
    <h3 class="headTitle">CREA NUOVA ATTIVITÀ</h3>
    @if(Auth::user())
        <form method="post" action="{{ action('PlanningController@store') }}">
            {{ csrf_field() }}
            <table class="table table-striped addActivityTable">
                <thead>
                    <tr class="addActivity">
                        <th>Descrizione attività</th>
                        <th>Tipo</th>
                        <th>Periodo</th>
                        <th>Data</th>
                        <th>Operatore</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="addActivity">
                        <td>
                            <textarea name="activity" rows="2" cols="40" required></textarea>
                        </td>
                        <td>
                            <select name="type" class="activitySelect" onchange="openDiv();">
                                @foreach($types as $type)
                                <option value="{{ $type->type }}" style="background-color: {{ $type->color }}; color: #000;">{{ $type->type }}</option>
                                @endforeach
                                <option value="Add" class="addActivity">Aggiungi...</option>
                            </select>
                        </td>
                        <td>
                            <div class="content">
                                <input id="mattino" type="checkbox" name="hour" value="0" onclick="checkBox('pomeriggio')" checked><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                            </div>
                            <div class="content">
                                <input id="pomeriggio" type="checkbox" name="hour" value="1" onclick="checkBox('mattino')"><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                            </div>
                        </td>
                        <td>
                            <input name="repetition" value="0" style="display: none;">
                            <input name="particular" value="0" style="display: none;">
                            <input name="date" id="datepicker" autocomplete="off">
                        </td>
                        <td>
                            <select name="operator">
                                <option value="" selected></option>
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td id="button">
                            <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Conferma</button>
        </form>
                            <br>
                            <a id="elimina" class="btn btn-danger elimina eliminaBig buttonShadow" href="{{ action('PlanningController@index') }}"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Indietro</a>
                        </td>
                    </tr>
                </tbody>
            </table>
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
    function forceLower(strInput) {
        strInput.value=strInput.value.toLowerCase();
    }
</script>