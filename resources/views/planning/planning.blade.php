<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
    <link href="../public/css/aspect.css" rel="stylesheet">
    <link type="javascript" src="../js/script.js" />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />


    <script src='../packages/core/main.js'></script>
    <script src='../packages/daygrid/main.js'></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <!-- <script type="text/javascript">
      function printConsole() {
          let date;
          let myDom = $("#calendar > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td.fc-day");
          myDom.on('click',function(e){
              date = $(this)[0].dataset.date;
              document.cookie = 'date='+date+'';
          });
      }
    </script> -->

    <!-- <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          plugins: [ 'dayGrid' ]
        });

        calendar.render();
      });

    </script> -->
  </head>
  <body onload="makeCalendar();">

    @if(Auth::user())
        <div>Ciao {{@Auth::user()->name}}</div>
    @endif

    <div id='calendar'></div>
    <script>
      function makeCalendar(){
        $(document).ready(function() {
          $("#calendar > div.fc-view-container > div > table > thead > tr > td > div > table > thead > tr")
          .prepend("<th>PERSONE</th>");
          @foreach($workers as $worker)
            $("#calendar > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr")
            .prepend('<tr class="noBorders"><td> {{$worker->name}} </td></tr>');
            $('#calendar > div.fc-view-container > div > table > tbody')
            .append('<tr><td class="fc-widget-content"><div class="fc-scroller fc-day-grid-container" style="overflow: hidden; height: 1390px;"><div class="fc-day-grid fc-unselectable"><div class="fc-row fc-week fc-widget-content" style="height: 1390px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-past" data-date="2019-06-09"></td><td class="fc-day fc-widget-content fc-mon fc-past" data-date="2019-06-10"></td><td class="fc-day fc-widget-content fc-tue fc-past" data-date="2019-06-11"></td><td class="fc-day fc-widget-content fc-wed fc-today fc-state-highlight" data-date="2019-06-12"></td><td class="fc-day fc-widget-content fc-thu fc-future" data-date="2019-06-13"></td><td class="fc-day fc-widget-content fc-fri fc-future" data-date="2019-06-14"></td><td class="fc-day fc-widget-content fc-sat fc-future" data-date="2019-06-15"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div></div></div></td></tr>');
          @endforeach
        });
      }
    </script>
    <script>
        $(document).ready(function() {
            // page is now ready, initialize the calendar...
            $('#calendar').fullCalendar({
              header: {
                  left: 'prev,next today',
                  center: 'title',
                  right: 'month,basicWeek,basicDay'
              },
              defaultView: 'basicWeek',
              aspectRatio: 1.5,
              // put your options and callbacks here
              // events : [
              //     @foreach($tasks as $task)
              //     {
              //         title : '{{ $task->operator }}',
              //         start : '{{ $task->date }}'
              //     },
              //     @endforeach
              // ]
            })
        });
    </script>
  </body>
  
</html>