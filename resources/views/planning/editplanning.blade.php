<!DOCTYPE html>
<html lang='it'>
  <head>
    <meta charset='utf-8' />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link type="javascript" src="../js/script.js" />
    <link type="javascript" src="../public/js/function.js"/>
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://github.com/jquery/jquery-ui/blob/master/ui/i18n/datepicker-it.js"></script>
    <script>
    $( function() {
        $( "#datepicker" ).datepicker({
            beforeShowDay: $.datepicker.noWeekends,
            dateFormat: "yy-mm-dd",
            dayNamesMin: [ "Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab" ],
            monthNames: [ "Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno",
                          "Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre" ],
            firstDay: 1
        });
    } );
    </script>

  </head>
  <body>
    <form method="post" action="{{ action('PlanningController@update', [$activity['id']]) }}" onsubmit='return checkRequired()'>
        {{ csrf_field() }}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Descrizione attività</th>
                    <th>Tipo</th>
                    <th>Periodo</th>
                    <th>Data</th>
                    <th>Operator</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody>
                <tr align="center">
                    <td>
                        <div class="col-sm-10">
                            <textarea name="activity" rows="2" cols="40" required placeholder="{{ $activity->activity }}">{{ $activity->activity }}</textarea>
                        </div>
                    </td>
                    <td>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <input name="type" rows="2" cols="40" required value="{{ $activity->type }}" placeholder="{{ $activity->type }}">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group row" id="hourDiv">
                            <div class="col-sm-10">
                                <input type="checkbox" name="hour" value="0"><span>Mattino</span>
                                <input type="checkbox" name="hour" value="1"><span>Pomeriggio</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <input name="date" id="datepicker" placeholder="{{ $activity->date }}" autocomplete="off">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <select name="operator">
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}" <?php if($user->name == $activity->operator) echo "selected" ?> >{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </td>
                    <td id="button">
                        <div class="form-group row">
                            <button id="elimina" type="submit" class="btn btn-primary"><i class="fa fa-plus fa-lg">&nbsp;&nbsp;&nbsp;</i>Aggiungi</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
  </body>
</html>
<script>
    function checkRequired(){
        var checkedboxes = $('#hourDiv :checkbox:checked').length;

        if (checkedboxes === 0){
            alert('Selezionare periodo di attività');
            return false;
        }
    }
</script>