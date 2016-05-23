@extends('cockpit::layouts.auth')

@section('content')
<form method="POST" action="{{ route('cockpit::register') }}">
    {!! csrf_field() !!}

    <div>
        <label>Name</label>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
    </div>

    <div>
        <label>{{ trans('validation.attributes.email') }}</label>
        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
    </div>

    <div>
        <label>{{ trans('validation.attributes.password') }}</label>
        <input type="password" class="form-control" name="password">
    </div>

    <div>
        <label>{{ trans('validation.attributes.password_confirmation') }}</label>
        <input type="password" class="form-control" name="password_confirmation">
    </div>

    <div>
        <button class="btn btn-primary btn-block" type="submit">{{ trans('mezzo.general.auth.register') }}</button>
    </div>
</form>
<ul>
    <li><a href="{{ route('cockpit::login') }}">Login</a></li>
</ul>
@endsection