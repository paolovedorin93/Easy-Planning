<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
    <link type="javascript" src="../js/script.js" />

    <script src='../packages/core/main.js'></script>
    <script src='../packages/daygrid/main.js'></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          plugins: [ 'dayGrid' ]
        });

        calendar.render();
      });

    </script>
  </head>
  <body>

    @if(Auth::user())
        <div>Ciao {{@Auth::user()->name}}</div>
    @endif

    <div id='calendar' onclick="printConsole()"></div>

    
  </body>
  <script type="text/javascript">
    function printConsole() {
        let date;
        let myDom = $("#calendar > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td.fc-day");
        myDom.on('click',function(e){
            date = $(this)[0].dataset.date;
            console.log("date: ", date);
            $(this).attr('href','activity');
        });
        console.log("myDom: ", myDom);
    }
  </script>
</html>