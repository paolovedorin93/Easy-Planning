<!DOCTYPE html>
<html lang='it'>
    <head>
        <title>Planning | Le mie attività</title>
        @include('include.head')
    </head>
    <body>
        @include('include.nav')
        <h3 class="headTitle">LE MIE ATTIVITÀ</h3>
        <div class="container center bord">
            <script>
                let date, dateToAdd;
            </script>
            @foreach($tasks as $task)
                <div class="box">
                    <a href="{{ action('PlanningController@edit', $task->id) }}">
                        <div>
                            {{ $task->activity }}
                        </div>
                        <div class="date{{ $task->id }}"></div>
                        <script>
                            date = new Date('{{ $task->date }}');
                            dateToAdd = date.toLocaleString('it-IT');
                            dateToAdd = dateToAdd.toString().substring(0,dateToAdd.length - 10)
                            $(".date{{ $task->id }}").append(dateToAdd);
                        </script>
                        <div>
                            @if($task->hour == 0)
                                <span>Mattino</span>
                            @else
                                <span>Pomeriggio</span>
                            @endif
                            <span>- {{ $task->type }} </span>
                        </div>
                    </a>
                </div>
            @endforeach
            <a class="backTo" href="{{ action('PlanningController@index') }}">Torna al planning</a>
        </div>
    </body>
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
</html>