@extends('layouts.app')

@section('content')
    <div class="inner cover">
        <h1 class="cover-heading">Welcome on the page.</h1>
        @if (Auth::guest())
            <p class="lead">In order to use our services please log in. If you don't have user, please register. If you want to read our terms and conditions, please click on the button below.</p>
            <p class="lead">
                <a href="/terms/latest" class="btn btn-lg btn-default">Learn more</a>
            </p>
        @else
            <p class="lead">You're already logged in. Please visit the Dashboard page and see the already existing users.</p>
            <p class="lead">
                <a href="/dashboard" class="btn btn-lg btn-default">Dashboard</a>
            </p>
        @endif
    </div>
@endsection
