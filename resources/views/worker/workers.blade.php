<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts & Style -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href='../packages/core/main.css' rel='stylesheet' />
        <link href='../packages/daygrid/main.css' rel='stylesheet' />
        <link href="../public/css/aspect.css" rel="stylesheet">
        <link type="javascript" src="../js/script.js" />
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
        <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
        <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />
        <link href="http://127.0.0.2/Easy-Planning/public/css/app.css" rel="stylesheet">

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <table>
                <th>
                    <td>Amministratore</td>
                    <td>Sospeso</td>
                </th>
                <form method="post" action="{{ route('post') }}">
                    {{ csrf_field() }}
                    @foreach($workers as $worker)
                        <tr class="contentTable">
                            <td class="nameWorker">{{ $worker['name'] }}</td>
                            <td class="inputWorker"><input type="checkbox" value="{{ $worker->admin }}" name="admin"></td>
                            <td class="inputWorker"><input type="checkbox" value="{{ $worker->suspended }}" name="suspended"></td>
                        </tr>
                    @endforeach
                    <button type="submit" class="btn btn-info">save</button>
                </form>
            </table>
        </div>
    </body>
</html>
