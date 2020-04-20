<!DOCTYPE html>
<html lang='it'>
  <head>
    <title>Planning | Modifica attività</title>
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
    @if (session('messaggio'))
        <div class="alert alert-success alertBox">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('messaggio') }}
        </div>
    @endif
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
                            <select name="type" class="activitySelect" onchange="openDiv();" required disabled>
                                @foreach($types as $type)
                                    <option value="{{ $type->type }}" @if($type->type == $activity->type) selected @endif style="background-color: {{ $type->color }}; color: {{ $type->inv_hex }}; font-weight: bold;">{{ $type->type }}</option>
                                @endforeach
                                <option value="Add" class="addActivity">Aggiungi...</option>
                            </select>                        
                        </td>
                        <td>
                            <div class="content hourDiv">
                                <input id="mattino" type="checkbox" name="hour" value="0" onclick="checkBox('pomeriggio')" required disabled @if(!$activity->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                            </div>
                            <div class="content hourDiv">
                                <input id="pomeriggio" type="checkbox" name="hour" value="1" onclick="checkBox('mattino')" required disabled @if($activity->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                            </div>
                        </td>
                        <td>
                            <input name="particular" value="0" style="display: none;">
                            <input name="repetition" value="0" style="display: none;">
                            <input name="date" id="datepicker" autocomplete="off" value="{{$activity->date}}" required disabled>
                        </td>
                        <td>
                            <select name="operator" required disabled>
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}" @if($activity->operator == $user->name) selected @endif>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td id="button">
                            <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow edit" disabled><i class="fa fa-check fa-lg i" aria-hidden="true" style="position: relative; right: 1px;"></i></button>
                            <button id="modifica" type="button" class="btn btn-primary modifica buttonShadow edit"><i class="fa fa-pencil fa-lg i" aria-hidden="true"></i></button>
        </form> 
                            <br>                    
                            <a id="elimina" class="btn btn-danger elimina buttonShadow edit" href='{{ action("PlanningController@destroy", $activity["id"]) }}' disabled><i class="fa fa-trash-o editI i" aria-hidden="true"></i></a>
                            <a id="indietro" class="btn btn-danger buttonShadow edit" href='/Easy-Planning/public/planning' style="margin-left: 2px;" disabled><i class="fa fa-chevron-left editI i" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        <div class="addActivityDiv addActivityDivClose">
            <form method="post" action="{{ action('PlanningController@storeActivity') }}">
                {{ csrf_field() }}
                <div class="content divContent">
                    <input name="type" placeholder="Aggiungi tipo attività..." onkeyup="return forceLower(this); " required>
                    <input id="hex" name="color" type="color" required>
                    <button id="addType" type="submit" class="btn btn-primary aggiungi buttonShadow"><i class="fa fa-plus fa-lg"></i>&nbsp;&nbsp;&nbsp;Aggiungi</button>
                </div>
            </form>
        </div>
        <p class="activityLastEdit">Ultima Modifica: {{ $activity->edit }} il </p>
        <script>
            let date = new Date('{{ $activity->updated_at }}');
            let dateToAdd = date.toLocaleString('it-IT');
            dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10)
            console.log(dateToAdd.toString().substring(0,dateToAdd.length - 10));
            $(".activityLastEdit").append(dateToAdd);
        </script>
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
    });
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
<script>
    $(".close").click(function(){
        $('.alert').css('display','none');
    });
</script>
<script>
    $('textarea').keypress(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            alert('INVIO non consentito.');
            // let str = $('textarea').val();
            // str = str.replace(/(?:\r|\n|\r\n)/g, '<br>');
        }
    });
</script>
<script>
    function forceLower(strInput) {
        strInput.value=strInput.value.toLowerCase();
    }
</script>