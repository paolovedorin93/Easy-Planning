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
                        <th class="hoursRequired hidden">Ore</th>
                        <th>Operatore</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="addActivity">
                        <td>
                            <textarea class="textarea" name="activity" rows="2" cols="40" required></textarea>
                        </td>
                        <td>
                            <select name="type" class="activitySelect" onchange="onChange();">
                                <option value="" selected></option>
                                @foreach($types as $type)
                                <option value="{{ $type->type }}" style="background-color: {{ $type->color }}; color: #000;">{{ $type->type }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <div class="content hourDiv">
                                <input id="mattino" type="checkbox" name="hour" value="0" onclick="checkBox('pomeriggio')" checked><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                            </div>
                            <div class="content hourDiv">
                                <input id="pomeriggio" type="checkbox" name="hour" value="1" onclick="checkBox('mattino')"><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                            </div>
                        </td>
                        <td>
                            <input name="repetition" value="0" style="display: none;">
                            <input name="particular" value="0" style="display: none;">
                            <input name="date" id="datepicker" autocomplete="off">
                        </td>
                        <td class="hoursRequired hidden">
                            <input name="time" value="0" min="0" max="4" step=".25" type="number" id="hoursRequired" class="hoursRequired"/>
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
                            <button id="conferma" type="submit" onclick="openOutlook();" class="btn btn-success aggiungi buttonShadow"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Conferma</button>
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
    $(document).ready(function(){
        $("#addType").click(function(){
            let invColor = invertHex(document.getElementById("hex").value.substr(1));
            document.getElementById("invHex").value = "#"+invColor;
        });
    });
</script>
<script>
    function onChange(){
        let value = $(".activitySelect").val();
        if(value === "richiesta permesso/ferie" || value === "ferie"){
            $('.hoursRequired').removeClass("hidden");
            $('#hoursRequired').prop('required',true);
            $(".textarea").val("Ferie");
        } else {
            $('.hoursRequired').addClass("hidden");
            $('#hoursRequired').prop('required',false);
            if(value === "assistenza")
                $(".textarea").val("Assistenza");
        }
    }
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
<script>
    function openOutlook(){
        let value = $(".activitySelect").val();
        if(value === "richiesta permesso/ferie"){
            hoursToEmail = $("#hoursRequired").val();
            dateToEmail = new Date($("#datepicker").val());
            dateToAdd = dateToEmail.toLocaleString('it-IT');
            dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10);
            if($(".hourDiv > #mattino").is(":checked"))
                period = "mattino";
            else
                period = "pomeriggio";
            emailTo = "elena@exeprogetti.mail";
            emailCC = "martino@exeprogetti.mail";
            emailSub = "Richiesta permesso/ferie";
            emailBody = "Ciao," + "%0D%0A" + "%0D%0A" + "  chiedo permesso di " + hoursToEmail + " ore per il giorno " + dateToAdd + " " + period;
            location.href = "mailto:"+emailTo+'?cc='+emailCC+'&subject='+emailSub+'&body='+emailBody;
        }
    }
</script>