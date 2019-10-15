<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
    <link href="../public/css/aspect.css" rel="stylesheet">
    <link type="javascript" src="../js/script.js" />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />
    <link href="http://127.0.0.2/Easy-Planning/public/css/app.css" rel="stylesheet">

    <script src='../packages/core/main.js'></script>
    <script src='../packages/daygrid/main.js'></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        @if(Auth::guest())
          <div class="dropdown myDropDown">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          </div>
        @else
          <div class="dropdown myDropDown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
    <div class="generalContainer">
      <div class="main buttonBottom" id="nav-icon1">
        <div class="bottomRightCorner">
          <span class="spanButton spanButtonOne"></span>
          <span class="spanButton spanButtonTwo"></span>
          <span class="spanButton spanButtonThree"></span>
        </div>
        <div class="userNav">
          <a class="connect" href="../public/planning/add"><i class="fa fa-plus"></i></a>
        </div>
      </div>
      @foreach ($workers as $worker)
        @if($worker->suspended != 1)
          <span>TABELLA DI {{ $worker->name }}</span>
          <div class="tableUser" id='calendar{{ $worker->name }}'></div>
          <div class="divButton">
            <button class="expandTable buttonToExpande " onclick="let worker = '{{ $worker->name }}'; hide(worker, this)"><i class="fa fa-angle-down"></i></button>
          </div>
          <script>
            function hide(worker, e){
              $("#calendar"+worker).toggleClass('show');
              console.log("e: ", e);
              $(e.children).toggleClass('fa-angle-up');
            }
          </script>
          <script>
            $(document).ready(function() {
              // page is now ready, initialize the calendar...
              $('#calendar{{ $worker->name }}').fullCalendar({
                lang: 'it',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay',
                },
                defaultView: 'basicWeek',
                hiddenDays: [0,6],
                // put your options and callbacks here
                events: [
                  @foreach($tasks as $task)
                    @if ($worker->name === $task->operator)
                    {
                        title : '{{ $task->operator }} >>> {{ $task->activity }}',
                        start : '{{ $task->date }}',
                        end : '{{ $task->enddate }}',
                        url : '{{ action("PlanningController@edit", $task["id"]) }}',
                        className : '{{ $task->type }}',
                    },
                    @endif
                  @endforeach
                ]
              });
              $("#calendar{{ $worker->name }}").css('display','none');
            });
          </script>
        @endif
      @endforeach
    </div>
    <div class="divButtonHard">
      <button class="fc-next-button fc-button fc-state-default fc-corner-left fc-state-hover" id="prevButton"><span class="fc-icon fc-icon-left-single-arrow"></span></button>
      <button class="fc-next-button fc-button fc-state-default fc-corner-right fc-state-hover" id="nextButton"><span class="fc-icon fc-icon-right-single-arrow"></span></button>
    </div>
    <div id='calendarHardMor'></div>
    <div id='calendarHardEvn'></div>
    <script>
      let dayDate, oldDayDate, operator, oldOperator, busy, suspended, noAssi, countWorkers;
      @php $countWorkers = sizeof($workers); @endphp
      countWorkers = {{ $countWorkers }};
      console.log("countWorkers: ", countWorkers);
      $(document).ready(function() {
        $('#calendarHardMor').fullCalendar({
          defaultView: 'basicWeek',
          hiddenDays: [0,6],
          dayRender: function(date,cell){
              console.log("dayRender just entried");
              cell.css('background-color','#03a300');
              busy = 1;
              @foreach($tasksMor as $taskMor)
                suspended = {{ $taskMor->suspended }};
                noAssi = {{ $taskMor->no_assi }};
                if(cell[0].dataset.date === "{{ $taskMor->date }}" && suspended != 1 && noAssi != 1){
                  dayDate = "{{ $taskMor->date }}";
                  operator = "{{ $taskMor->operator }}";
                  if(oldDayDate === dayDate){
                    if(oldOperator != operator)
                      busy++;
                  }

                  if(busy===3){
                    console.log("busy = 3");
                    cell.css('background-color','#31ff2e');
                  }
                  if(busy===4){
                    console.log("busy = 4");
                    cell.css('background-color','#fffc2e');
                  }
                  if(busy>=5){
                    console.log("busy : ", busy);
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
          dayRender: function(date,cell){
            cell.css('background-color','#03a300');
            busy = 1;
            @foreach($tasksAft as $taskAft)
              suspended = {{ $taskAft->suspended }};
              noAssi = {{ $taskAft->no_assi }};
              if(cell[0].dataset.date === "{{ $taskAft->date }}" && suspended != 1 && noAssi != 1){
                dayDate = "{{ $taskAft->date }}";
                operator = "{{ $taskAft->operator }}";
                if(oldDayDate === dayDate){
                  if(oldOperator != operator)
                    busy++;
                }

                if(busy===3){
                  console.log("busy = 3");
                  cell.css('background-color','#31ff2e');
                }
                if(busy===4){
                  console.log("busy = 4");
                  cell.css('background-color','#fffc2e');
                }
                if(busy>=5){
                  console.log("busy : ", busy);
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
      });
    </script>
  </body>
</html>