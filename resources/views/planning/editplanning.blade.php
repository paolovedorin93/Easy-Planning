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
                        @if ($activity->type == "richiesta permesso/ferie" || $activity->type == "ferie")
                            <th class="hoursRequired">Ore</th>
                        @endif
                        <th class="hoursRequired hidden">Ore</th>
                        <th>Operatore</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="editActivity stopped">
                        <td>
                            <textarea class="textarea" name="activity" rows="2" cols="40" placeholder="" required disabled>{{ $activity->activity }}</textarea>
                        </td>
                        <td>
                            <select name="type" class="activitySelect" onchange="onChange();" required disabled>
                                @foreach($types as $type)
                                    <option value="{{ $type->type }}" @if($type->type == $activity->type) selected @endif style="background-color: {{ $type->color }}; color: #000;; font-weight: bold;">{{ $type->type }}</option>
                                @endforeach
                            </select>                        
                        </td>
                        <td>
                            <div class="content hourDiv">
                                <input id="mattino" type="checkbox" name="hour" value="0" onclick="checkBox('pomeriggio')" disabled @if(!$activity->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Mattino</span>
                            </div>
                            <div class="content hourDiv">
                                <input id="pomeriggio" type="checkbox" name="hour" value="1" onclick="checkBox('mattino')" disabled @if($activity->hour) checked @endif><span>&nbsp;&nbsp;&nbsp;Pomeriggio</span>
                            </div>
                        </td>
                        <td>
                            <input name="particular" value="0" style="display: none;">
                            <input name="repetition" value="0" style="display: none;">
                            <input name="date" id="datepicker" autocomplete="off" value="{{$activity->date}}" required disabled>
                        </td>
                        @if ($activity->type == "richiesta permesso/ferie" || $activity->type == "ferie")
                        <td class="hoursRequired">
                            <input type="number" id="hoursRequired" class="hoursRequiredInput" value="{{ $activity->time }}" min="0.25" max="4" step=".25" disabled/>
                        </td>
                        <script>
                            $(".hoursRequiredInput").attr('name','time');
                        </script>
                        @else
                        <td class="hoursRequired hidden">
                            <input type="number" id="hoursRequired" class="hoursRequiredNoHour" min="0.25" max="4" step=".25"/>
                        </td>
                        <script>
                            $(".hoursRequiredNoHour").attr('name','time');
                        </script>
                        @endif
                        <td>
                            <select name="operator" required disabled>
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}" @if($activity->operator == $user->name) selected @endif>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td id="button">
                            <button id="conferma" type="submit" class="btn btn-success aggiungi buttonShadow edit" onclick="openOutlook();" disabled><i class="fa fa-check fa-lg i" aria-hidden="true" style="position: relative; right: 1px;"></i></button>
                            <button id="modifica" type="button" class="btn btn-primary modifica buttonShadow edit"><i class="fa fa-pencil fa-lg i" aria-hidden="true"></i></button>
        </form> 
                            <br>                    
                            <a id="elimina" class="btn btn-danger elimina buttonShadow edit" href='{{ action("PlanningController@destroy", $activity["id"]) }}' disabled><i class="fa fa-trash-o editI i" aria-hidden="true"></i></a>
                            <a id="indietro" class="btn btn-danger buttonShadow edit" href="{{ action('PlanningController@index') }}" style="margin-left: 2px;" disabled><i class="fa fa-chevron-left editI i" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        <p class="activityLastEdit">Ultima Modifica: {{ $activity->edit }} il </p>
        <script>
            let date = new Date('{{ $activity->updated_at }}');
            let dateToAdd = date.toLocaleString('it-IT');
            dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10)
            $(".activityLastEdit").append(dateToAdd);
        </script>
        <h3 class="headTitle">Commenti</h3>
        <div class="container containerComment">
            <table class="tableComments">
                @foreach($comments as $comment)
                    <tr>
                        <td class="generalData{{ $comment->id }}">{{ $comment->operator }}<br></td>
                        @if(Auth::user()->name === $comment->operator)
                            <form action="{{ action('PlanningController@updateComment', [$comment['id']]) }}">
                                <td>
                                    <textarea id="editableComment" name="comment" class="tareaComments hidden" cols="50" rows="3" value="{{ $comment->comment }}">{{ $comment->comment }}</textarea>
                                    <p class="pComment">{{ $comment->comment }}</p>
                                </td>
                                <td class="tdButton">
                                    <button id="modificaComm" type="button" class="btn btn-primary modifica buttonShadow"><i class="fa fa-pencil fa-lg i" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Modifica</button>
                                    <button id="editComm" type="submit" class="btn btn-success aggiungi buttonShadow hidden"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;Conferma</button><br>
                                    <a id="elimina" class="btn btn-danger eliminaComm eliminaMed buttonShadow" href='{{ action("PlanningController@destroyComment", $comment["id"]) }}' disabled><i class="fa fa-trash-o i" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Elimina</a>
                                </td>
                            </form>
                        @else
                            <td>
                                <p>{{ $comment->comment }}</p>
                            </td>
                        @endif
                        <script>
                            date = new Date('{{ $comment->updated_at }}');
                            dateToAdd = date.toLocaleString('it-IT');
                            dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10)
                            $(".generalData{{ $comment->id }}").append(dateToAdd);
                        </script>
                    </tr>
                @endforeach
                    <tr>
                        <td></td>
                        <td>
                            <form action="{{ action('PlanningController@storeComment') }}">
                                <textarea class="tareaComments" name="comment" id="" cols="50" rows="3"></textarea>
                                <input value="{{ $activity->id }}" name="idActivity" hidden>
                                <input value="{{ Auth::user()->name }}" name="operator" hidden>
                                <button id="conferma" type="submit" class="btn btn-success aggiungi addComm buttonShadow"><i class="fa fa-check fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Inserisci</button>
                            </form>
                        </td>
                    </tr>
            </table>
        </div>
        @if (session('Messaggio'))
            <div class="alert alert-success alertBox">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session('Messaggio') }}
            </div>
        @endif
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
    $("#modifica").click(function(){
        $(".editActivity > td > *").prop('disabled', false);
        $(".editActivity > td > div > *").prop('disabled', false);
        $(".conferma").prop('disabled', false);
        $(".elimina").prop('disabled', false);
    });
</script>
<script>
    $("#modificaComm").click(function(){
        $(this).css('display','none');
        $(".pComment").css('display','none');
        $("#editableComment").toggleClass('hidden');
        $("#editComm").toggleClass('hidden');
    })
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
    });
</script>
<script>
    $(".eliminaComm").click(function(){
        let result = confirm('Sei sicuro di voler cancellare il commento?');
        if(result)
            return true;
        else
            return false;
    });
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
            hoursToEmal = $("#hoursRequired").val();
            dateToEmail = new Date($("#datepicker").val());
            dateToAdd = dateToEmail.toLocaleString('it-IT');
            dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10);
            period = {{ $activity->hour }};
            if(period === 0)
                period = "mattino";
            else
                period = "pomeriggio";
            emailTo = "elena@exeprogetti.mail";
            emailCC = "martino@exeprogetti.mail";
            emailSub = "Richiesta permesso/ferie";
            emailBody = "Ciao," + "%0D%0A" + "%0D%0A" + "  chiedo permesso di " + hoursToEmal + " ore per il giorno " + dateToAdd + " " + period;
            location.href = "mailto:"+emailTo+'?cc='+emailCC+'&subject='+emailSub+'&body='+emailBody;
        }
    }
</script>