<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <title>Planning Exe Progetti | Calendario</title>

    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
    <link type="javascript" src="../js/script.js" />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />
    <link href="http://127.0.0.2/Easy-Planning/public/css/app.css" rel="stylesheet">
    <link href="../public/css/aspect.css" rel="stylesheet">
    <script src='../packages/core/main.js'></script>
    <script src='../packages/daygrid/main.js'></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" id="mainNav">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        @if(Auth::guest())
          <div class="dropdown myDropDown">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
            <a class="nav-link" href="{{ route('register') }}">Registrati</a>
          </div>
        @else
          <div class="dropdown myDropDown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                @if(Auth::user()->admin == 1)<a class="dropdown-item" href="workers">Gestione utenti</a>@endif
                <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
          </div>
        @endif
      </div>
    </nav>
    @if(Auth::user())
      <style>
        .generalContainer > .tableUser:not(:nth-child(3)) > div > div > div {
          display: none;
        }
        .generalContainer > .tableUser:not(:nth-child(3)) > div.fc-view-container > div > table > thead {
          display: none;
        }
        .generalContainer > .tableUser:not(:nth-child(3)) > div.fc-toolbar.fc-header-toolbar > div.fc-center {
          display: none;
        }
        #calendarHardMor > div > div > table > thead {
          display: none;
        }
      </style>
    @endif
    @if(Auth::guest())
      <style>
        .generalContainer > .tableUser:not(:nth-child(2)) > div > div > div {
          display: none;
        }
        .generalContainer > .tableUser:not(:nth-child(2)) > div.fc-view-container > div > table > thead {
          display: none;
        }
        .generalContainer > .tableUser:not(:nth-child(2)) > div.fc-toolbar.fc-header-toolbar > div.fc-center {
          display: none;
        }
        #calendarHardMor > div > div > table > thead {
          display: none;
        }
      </style>
    @endif
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
    <div class="generalContainer">
      @if(Auth::user())
        <div class="main buttonBottom" id="nav-icon1">
          <div class="bottomRightCorner">
            <span class="spanButton spanButtonOne"></span>
            <span class="spanButton spanButtonTwo"></span>
            <span class="spanButton spanButtonThree"></span>
          </div>
          <div class="userNav">
            @if(Auth::user()->admin)
              <form method="POST" action="{{ action('PlanningController@indexWeekly') }}">
                {{ csrf_field() }}
                <input  id="startDate" class="hidden" value="" name="startDate">
                <input  id="endDate" class="hidden" value="" name="endDate">
                <button id="confermaWeekly" type="submit"><i class="fa fa-plus" id="confermaWeeklyI"></i>7</button>
              </form>
            @endif
            <a class="connect" href="../public/planning/add"><i class="fa fa-plus"></i></a>
          </div>
        </div>
      @endif
      @foreach ($workers as $worker)
        @if($worker->suspended != 1)
          <strong style="text-transform: capitalize;">{{ $worker->name }}</strong>
          <div class="tableUser" id='calendar{{ $worker->name }}'></div>
          <div class="divButton">
            <button class="expandTable buttonToExpande " onclick="let worker = '{{ $worker->name }}'; hide(worker, this)"><i class="fa fa-angle-down"></i></button>
            <button class="expandTable buttonToExpande " onclick="let worker = '{{ $worker->name }}'; hideAll(worker, this)"><i class="expendAll fa fa-angle-double-down"></i></button>
          </div>
          <script>
            function hide(worker, e){
              $("#calendar"+worker).toggleClass('hidden');
              $(e.children).toggleClass('fa-angle-up');
            }
          </script>
          <script>
            function hideAll(worker, e){
              $(".tableUser").toggleClass('hidden');
              $(".expendAll").toggleClass('fa-angle-double-up');
            }
          </script>
          <script>
            $(document).ready(function() {
              // page is now ready, initialize the calendar...
              $('#calendar{{ $worker->name }}').fullCalendar({
                height: 200,
                lang: 'it',
                header: {
                  left: '',
                  center: 'title',
                  right: 'Prec,Pros,Vai'
                },
                eventRender: function (event, element) {
                  if(event.hour == "0") {
                    element.find('.fc-title').html('<div class="activity">'+event.title+'</div><div class="hour">M</div>');
                  }
                  else {
                    element.find('.fc-title').html('<div class="activity">'+event.title+'</div><div class="hour">P</div>');
                  }
                },
                customButtons: {
                  Vai: {
                    text: 'Vai a data',
                    click: function() {
                      let date = $('#goToDate').val();
                      $('.tableUser, #calendarHardMor, #calendarHardEvn').fullCalendar('gotoDate', date);
                      let startDate = $('#calendarPaolo').fullCalendar('getView').start.format('YYYY-MM-DD');
                      let endDate = $('#calendarPaolo').fullCalendar('getView').end.format('YYYY-MM-DD');
                      $("#startDate").attr('value',startDate);
                      $("#endDate").attr('value',endDate);
                    }
                  },
                  Prec: {
                    text: 'Prec',
                    click: function() {
                      $('.tableUser').fullCalendar('prev');
                      $("#calendarHardMor").fullCalendar('prev');
                      $("#calendarHardEvn").fullCalendar('prev');
                      let startDate = $('#calendarPaolo').fullCalendar('getView').start.format('YYYY-MM-DD');
                      let endDate = $('#calendarPaolo').fullCalendar('getView').end.format('YYYY-MM-DD');
                      $("#startDate").attr('value',startDate);
                      $("#endDate").attr('value',endDate);
                    }
                  },
                  Pros: {
                    text: 'Pros',
                    click: function() {
                      $('.tableUser').fullCalendar('next');
                      $("#calendarHardMor").fullCalendar('next');
                      $("#calendarHardEvn").fullCalendar('next');
                      let startDate = $('#calendarPaolo').fullCalendar('getView').start.format('YYYY-MM-DD');
                      let endDate = $('#calendarPaolo').fullCalendar('getView').end.format('YYYY-MM-DD');
                      $("#startDate").attr('value',startDate);
                      $("#endDate").attr('value',endDate);
                    }
                  }
                },
                defaultView: 'basicWeek',
                hiddenDays: [0,6],
                // put your options and callbacks here
                eventOrder: "hour",
                events: [
                  @foreach($tasks as $task)
                    @if ($worker->name === $task->operator)
                    {
                        title : '$task->time {{ $task->activity }}',
                        start : '{{ $task->date }}',
                        end : '{{ $task->date }}',
                        url : '{{ action("PlanningController@edit", $task["id"]) }}',
                        className : '{{ $task->type }}',
                        color : '@foreach($types as $type) @if($type->type === $task->type) {{ $type->color }} @endif @endforeach',
                        hour : '{{ $task->hour }}'
                    },
                    @endif
                  @endforeach
                ],
              });
            });
          </script>
          @endif
      @endforeach
    </div>
    <div id='calendarHardMor'></div>
    <div id='calendarHardEvn'></div>
    <script>
      let dayDate, oldDayDate, operator, oldOperator, busy, suspended, noAssi, countWorkers;
      @php $countWorkers = sizeof($workers); @endphp
      countWorkers = {{ $countWorkers }};
      $(document).ready(function() {
        $('#calendarHardMor').fullCalendar({
          defaultView: 'basicWeek',
          hiddenDays: [0,6],
          header: false,
          dayRender: function(date,cell){
              cell.css('background-color','#03a300');
              busy = 0;
              oldDayDate = "";
              @foreach($tasksMor as $taskMor)
                suspended = {{ $taskMor->suspended }};
                noAssi = {{ $taskMor->no_assi }};
                if(cell[0].dataset.date === "{{ $taskMor->date }}" && suspended != 1 && noAssi != 1){
                  dayDate = "{{ $taskMor->date }}";
                  operator = "{{ $taskMor->operator }}";
                  if(oldDayDate === "")
                    busy++;
                  if(oldDayDate === dayDate){  //old date è vuoto il primo giro!
                    if(oldOperator != operator)
                      busy++;
                  }
                  if(busy==={{$intensityLight->number}}){
                    console.log("busyLight = ",{{$intensityLight->number}});
                    cell.css('background-color','#31ff2e');
                  }
                  if(busy==={{$intensityMedium->number}}){
                    console.log("busyMedium = ",{{$intensityMedium->number}});
                    cell.css('background-color','#fffc2e');
                  }
                  if(busy>={{$intensityHard->number}}){
                    console.log("busyHard : ", {{$intensityHard->number}});
                    cell.css('background-color','red');
                  }
                  oldOperator = operator;
                  oldDayDate = dayDate;
                }
              @endforeach
            }
        });
        $('#calendarHardEvn').fullCalendar({
          defaultView: 'basicWeek',
          hiddenDays: [0,6],
          header: false,
          dayRender: function(date,cell){
            cell.css('background-color','#03a300');
            busy = 0;
            @foreach($tasksAft as $taskAft)
              suspended = {{ $taskAft->suspended }};
              noAssi = {{ $taskAft->no_assi }};
              if(cell[0].dataset.date === "{{ $taskAft->date }}" && suspended != 1 && noAssi != 1){
                if(oldDayDate === "")
                    busy++;
                dayDate = "{{ $taskAft->date }}";
                operator = "{{ $taskAft->operator }}";
                if(oldDayDate === dayDate){
                  if(oldOperator != operator)
                    busy++;
                }
                if(busy==={{$intensityLight->number}}){
                  console.log("busyLight = ",{{$intensityLight->number}});
                  cell.css('background-color','#31ff2e');
                }
                if(busy==={{$intensityMedium->number}}){
                  console.log("busyMedium = ",{{$intensityMedium->number}});
                  cell.css('background-color','#fffc2e');
                }
                if(busy>={{$intensityHard->number}}){
                  console.log("busyHard : ", {{$intensityHard->number}});
                  cell.css('background-color','red');
                }
                oldOperator = operator;
                oldDayDate = dayDate;
              }
            @endforeach
          }
        });
      });
    </script>
    <script>
      $('#nextButton').click(function() {
        $('#calendarHardMor, #calendarHardEvn').fullCalendar('next');
      });
      $('#prevButton').click(function() {
        $('#calendarHardMor, #calendarHardEvn').fullCalendar('prev');
      });
    </script>
    <!-- <script>
      $('.expandableButton').click(function(){
        if ($('.userNav').is(':hidden'))
            $('.userNav').show('slide',{direction:'right'},1000);
        else
            $('.userNav').hide('slide',{direction:'right'},1000);
      });
    </script> -->
    <script>
      $('#nav-icon1').click(function(){
        $('.userNav').toggleClass('show').toggleClass('horizontal');
        $('.bottomRightCorner').toggleClass('openCorner');
        $('.main').toggleClass('extend');
      });

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
      $(".next").click(function(){
        $(".tableUser").fullCalendar('next');
        $("#calendarHardMor, #calendarHardEvn").fullCalendar('next');
      });
      $(".prev").click(function(){
        $(".tableUser").fullCalendar('prev');
        $("#calendarHardMor, #calendarHardEvn").fullCalendar('prev');
      });
      $(".today").click(function(){
        $(".tableUser").fullCalendar('today');
        $("#calendarHardMor, #calendarHardEvn").fullCalendar('today');
      });
    </script>
    <script>
      $("#mainNav").mouseenter(function(){
        $(this).addClass('down');
      }).mouseleave(function(){
        $(this).removeClass('down');
      });
    </script>
    <script>
      $(document).ready(function() {
        let startDate = $('#calendarPaolo').fullCalendar('getView').start.format('YYYY-MM-DD');
        let endDate = $('#calendarPaolo').fullCalendar('getView').end.format('YYYY-MM-DD');
        $("#startDate").attr('value',startDate);
        $("#endDate").attr('value',endDate);
      });
    </script>
    <script>
      $(".close").click(function(){
          $('.alert').css('display','none');
      });
    </script>
  </body>
</html>