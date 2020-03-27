@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Accesso avvenuto, ciao <strong>{{ Auth::user()->name }}</strong>
                </div>
                <div class="card-body">
                    <a>Planning</a><br>
                    <a>Planning auto</a>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
