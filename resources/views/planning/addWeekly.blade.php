<!DOCTYPE html>
<html lang='it'>
  <head>
    <title>Planning | Aggiungi attività settimanali</title>
    @include('include.head')
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
    @include('include.nav')
    @if (session('alert'))
        <div class="alert alert-danger alertBox">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('alert') }}
        </div>
    @endif
    @if (session('messaggio'))
        <div class="alert alert-success alertBox">
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
                                        <option value="{{ $type->type }}" style="background-color: {{ $type->color }}; color: #000;">{{ $type->type }}</option>
                                    @endforeach
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
                                <a id="elimina" class="btn btn-danger elimina eliminaBig buttonShadow" href="{{ action('PlanningController@index') }}"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Indietro</a>
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </form> 
            <table class="table table-striped editActivityWeeklyTable">
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
                                            <option value="{{ $type->type }}" @if($type->type == $particularAct->type) selected @endif style="background-color: {{ $type->color }}; color: #000; font-weight: bold;">{{ $type->type }}</option>
                                        @endforeach
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
                                            if(value === "{{ $particularAct->repetition }}"){
                                                $(" .tr{{$particularAct->id}} > td > #repetition > #giorno"+x).prop('selected','true');
                                                found=1;
                                            }   
                                        }
                                    </script>
                                </td>
                                <td>
                                    <button id="conferma{{ $particularAct->id }}" type="submit" class="btn btn-success aggiungi buttonShadow edit" disabled><i class="fa fa-check fa-lg" aria-hidden="true"></i></button>
                                    <button id="modifica{{ $particularAct->id }}" type="button" class="btn btn-primary modifica buttonShadow edit"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></button>
                                    <script>
                                        $("#modifica{{ $particularAct->id }}").click(function(){
                                            $(".tr{{ $particularAct->id }} > td > *").prop('disabled', false);
                                            $(".tr{{ $particularAct->id }} > td > div > *").prop('disabled', false);
                                            $(".conferma{{ $particularAct->id }}").prop('disabled', false);
                                            $(".elimina").prop('disabled', false);
                                        });
                                    </script>
                                    <br>
                                    <a id="elimina" class="btn btn-danger elimina buttonShadow" href='{{ action("PlanningController@destroy", $particularAct->id) }}' style="padding-right: 9px; padding-left: 9px;" disabled><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Elimina</a>
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
    let startDate = new Date('{{ $startDate }}');
    let endDate = new Date('{{ $endDate }}');
    let startDateToAdd = startDate.toLocaleString('it-IT');
    startDateToAdd = startDateToAdd.toString().substring(0,startDateToAdd.length - 10);
    let endDateToAdd = endDate.toLocaleString('it-IT');
    endDateToAdd = endDateToAdd.toString().substring(0,endDateToAdd.length - 10);
    $("#genWeekly").on('click',function(){
        if(confirm("Generare attività per la settimana " + startDateToAdd + " - " + endDateToAdd + " ?"))
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
<script>
    function forceLower(strInput) {
        strInput.value=strInput.value.toLowerCase();
    }
</script>