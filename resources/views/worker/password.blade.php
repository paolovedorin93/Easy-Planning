<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('include.head')
    <body>
        @include('include.nav')
        @if(Auth::guest() || Auth::user()->admin==0)
        <div class="container centeredText">Non hai diritti di amministratore per poter modificare le impostazioni utente</div>
        @else
        <div class="flex-center position-ref full-height">
            <p>OLD PASSWORD</p>
            <p>NEW PASSWORD</p>
        </div>
        @endif
    </body>
</html>
