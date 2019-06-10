<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
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
  <body>

    @if(Auth::user())
        <div>Ciao {{@Auth::user()->name}}</div>
    @endif

    <div id='calendar'></div>
    <script>
      $(document).ready(function() {
        @foreach($workers as $worker)
          $('#calendar')
          .append('<div class="fc-view-container" style=""><div class="fc-view fc-month-view fc-basic-view" style=""><table><thead class="fc-head"><tr><td class="fc-head-container fc-widget-header"><div class="fc-row fc-widget-header"><table><thead><tr><th class="fc-day-header fc-widget-header fc-sun"><span>Sun</span></th><th class="fc-day-header fc-widget-header fc-mon"><span>Mon</span></th><th class="fc-day-header fc-widget-header fc-tue"><span>Tue</span></th><th class="fc-day-header fc-widget-header fc-wed"><span>Wed</span></th><th class="fc-day-header fc-widget-header fc-thu"><span>Thu</span></th><th class="fc-day-header fc-widget-header fc-fri"><span>Fri</span></th><th class="fc-day-header fc-widget-header fc-sat"><span>Sat</span></th></tr></thead></table></div></td></tr></thead><tbody class="fc-body"><tr><td class="fc-widget-content"><div class="fc-scroller fc-day-grid-container" style="overflow: hidden; height: 1090px;"><div class="fc-day-grid fc-unselectable"><div class="fc-row fc-week fc-widget-content" style="height: 181px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-other-month fc-past" data-date="2019-05-26"></td><td class="fc-day fc-widget-content fc-mon fc-other-month fc-past" data-date="2019-05-27"></td><td class="fc-day fc-widget-content fc-tue fc-other-month fc-past" data-date="2019-05-28"></td><td class="fc-day fc-widget-content fc-wed fc-other-month fc-past" data-date="2019-05-29"></td><td class="fc-day fc-widget-content fc-thu fc-other-month fc-past" data-date="2019-05-30"></td><td class="fc-day fc-widget-content fc-fri fc-other-month fc-past" data-date="2019-05-31"></td><td class="fc-day fc-widget-content fc-sat fc-past" data-date="2019-06-01"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-top fc-sun fc-other-month fc-past" data-date="2019-05-26"><span class="fc-day-number">26</span></td><td class="fc-day-top fc-mon fc-other-month fc-past" data-date="2019-05-27"><span class="fc-day-number">27</span></td><td class="fc-day-top fc-tue fc-other-month fc-past" data-date="2019-05-28"><span class="fc-day-number">28</span></td><td class="fc-day-top fc-wed fc-other-month fc-past" data-date="2019-05-29"><span class="fc-day-number">29</span></td><td class="fc-day-top fc-thu fc-other-month fc-past" data-date="2019-05-30"><span class="fc-day-number">30</span></td><td class="fc-day-top fc-fri fc-other-month fc-past" data-date="2019-05-31"><span class="fc-day-number">31</span></td><td class="fc-day-top fc-sat fc-past" data-date="2019-06-01"><span class="fc-day-number">1</span></td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 181px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-past" data-date="2019-06-02"></td><td class="fc-day fc-widget-content fc-mon fc-past" data-date="2019-06-03"></td><td class="fc-day fc-widget-content fc-tue fc-past" data-date="2019-06-04"></td><td class="fc-day fc-widget-content fc-wed fc-past" data-date="2019-06-05"></td><td class="fc-day fc-widget-content fc-thu fc-past" data-date="2019-06-06"></td><td class="fc-day fc-widget-content fc-fri fc-past" data-date="2019-06-07"></td><td class="fc-day fc-widget-content fc-sat fc-past" data-date="2019-06-08"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-top fc-sun fc-past" data-date="2019-06-02"><span class="fc-day-number">2</span></td><td class="fc-day-top fc-mon fc-past" data-date="2019-06-03"><span class="fc-day-number">3</span></td><td class="fc-day-top fc-tue fc-past" data-date="2019-06-04"><span class="fc-day-number">4</span></td><td class="fc-day-top fc-wed fc-past" data-date="2019-06-05"><span class="fc-day-number">5</span></td><td class="fc-day-top fc-thu fc-past" data-date="2019-06-06"><span class="fc-day-number">6</span></td><td class="fc-day-top fc-fri fc-past" data-date="2019-06-07"><span class="fc-day-number">7</span></td><td class="fc-day-top fc-sat fc-past" data-date="2019-06-08"><span class="fc-day-number">8</span></td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 181px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-past" data-date="2019-06-09"></td><td class="fc-day fc-widget-content fc-mon fc-today fc-state-highlight" data-date="2019-06-10"></td><td class="fc-day fc-widget-content fc-tue fc-future" data-date="2019-06-11"></td><td class="fc-day fc-widget-content fc-wed fc-future" data-date="2019-06-12"></td><td class="fc-day fc-widget-content fc-thu fc-future" data-date="2019-06-13"></td><td class="fc-day fc-widget-content fc-fri fc-future" data-date="2019-06-14"></td><td class="fc-day fc-widget-content fc-sat fc-future" data-date="2019-06-15"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-top fc-sun fc-past" data-date="2019-06-09"><span class="fc-day-number">9</span></td><td class="fc-day-top fc-mon fc-today fc-state-highlight" data-date="2019-06-10"><span class="fc-day-number">10</span></td><td class="fc-day-top fc-tue fc-future" data-date="2019-06-11"><span class="fc-day-number">11</span></td><td class="fc-day-top fc-wed fc-future" data-date="2019-06-12"><span class="fc-day-number">12</span></td><td class="fc-day-top fc-thu fc-future" data-date="2019-06-13"><span class="fc-day-number">13</span></td><td class="fc-day-top fc-fri fc-future" data-date="2019-06-14"><span class="fc-day-number">14</span></td><td class="fc-day-top fc-sat fc-future" data-date="2019-06-15"><span class="fc-day-number">15</span></td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 181px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-future" data-date="2019-06-16"></td><td class="fc-day fc-widget-content fc-mon fc-future" data-date="2019-06-17"></td><td class="fc-day fc-widget-content fc-tue fc-future" data-date="2019-06-18"></td><td class="fc-day fc-widget-content fc-wed fc-future" data-date="2019-06-19"></td><td class="fc-day fc-widget-content fc-thu fc-future" data-date="2019-06-20"></td><td class="fc-day fc-widget-content fc-fri fc-future" data-date="2019-06-21"></td><td class="fc-day fc-widget-content fc-sat fc-future" data-date="2019-06-22"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-top fc-sun fc-future" data-date="2019-06-16"><span class="fc-day-number">16</span></td><td class="fc-day-top fc-mon fc-future" data-date="2019-06-17"><span class="fc-day-number">17</span></td><td class="fc-day-top fc-tue fc-future" data-date="2019-06-18"><span class="fc-day-number">18</span></td><td class="fc-day-top fc-wed fc-future" data-date="2019-06-19"><span class="fc-day-number">19</span></td><td class="fc-day-top fc-thu fc-future" data-date="2019-06-20"><span class="fc-day-number">20</span></td><td class="fc-day-top fc-fri fc-future" data-date="2019-06-21"><span class="fc-day-number">21</span></td><td class="fc-day-top fc-sat fc-future" data-date="2019-06-22"><span class="fc-day-number">22</span></td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 181px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-future" data-date="2019-06-23"></td><td class="fc-day fc-widget-content fc-mon fc-future" data-date="2019-06-24"></td><td class="fc-day fc-widget-content fc-tue fc-future" data-date="2019-06-25"></td><td class="fc-day fc-widget-content fc-wed fc-future" data-date="2019-06-26"></td><td class="fc-day fc-widget-content fc-thu fc-future" data-date="2019-06-27"></td><td class="fc-day fc-widget-content fc-fri fc-future" data-date="2019-06-28"></td><td class="fc-day fc-widget-content fc-sat fc-future" data-date="2019-06-29"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-top fc-sun fc-future" data-date="2019-06-23"><span class="fc-day-number">23</span></td><td class="fc-day-top fc-mon fc-future" data-date="2019-06-24"><span class="fc-day-number">24</span></td><td class="fc-day-top fc-tue fc-future" data-date="2019-06-25"><span class="fc-day-number">25</span></td><td class="fc-day-top fc-wed fc-future" data-date="2019-06-26"><span class="fc-day-number">26</span></td><td class="fc-day-top fc-thu fc-future" data-date="2019-06-27"><span class="fc-day-number">27</span></td><td class="fc-day-top fc-fri fc-future" data-date="2019-06-28"><span class="fc-day-number">28</span></td><td class="fc-day-top fc-sat fc-future" data-date="2019-06-29"><span class="fc-day-number">29</span></td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 185px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-future" data-date="2019-06-30"></td><td class="fc-day fc-widget-content fc-mon fc-other-month fc-future" data-date="2019-07-01"></td><td class="fc-day fc-widget-content fc-tue fc-other-month fc-future" data-date="2019-07-02"></td><td class="fc-day fc-widget-content fc-wed fc-other-month fc-future" data-date="2019-07-03"></td><td class="fc-day fc-widget-content fc-thu fc-other-month fc-future" data-date="2019-07-04"></td><td class="fc-day fc-widget-content fc-fri fc-other-month fc-future" data-date="2019-07-05"></td><td class="fc-day fc-widget-content fc-sat fc-other-month fc-future" data-date="2019-07-06"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-top fc-sun fc-future" data-date="2019-06-30"><span class="fc-day-number">30</span></td><td class="fc-day-top fc-mon fc-other-month fc-future" data-date="2019-07-01"><span class="fc-day-number">1</span></td><td class="fc-day-top fc-tue fc-other-month fc-future" data-date="2019-07-02"><span class="fc-day-number">2</span></td><td class="fc-day-top fc-wed fc-other-month fc-future" data-date="2019-07-03"><span class="fc-day-number">3</span></td><td class="fc-day-top fc-thu fc-other-month fc-future" data-date="2019-07-04"><span class="fc-day-number">4</span></td><td class="fc-day-top fc-fri fc-other-month fc-future" data-date="2019-07-05"><span class="fc-day-number">5</span></td><td class="fc-day-top fc-sat fc-other-month fc-future" data-date="2019-07-06"><span class="fc-day-number">6</span></td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div></div></div></td></tr></tbody></table></div></div>');
          $("#calendar > div.fc-view-container > div > table > tbody")
          .append('<p> {{@Auth::user()->name}} </p>');
        @endforeach
      });
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