@extends('cockpit::layouts.auth')

@section('content')
    <form method="POST" action="{{ route('cockpit::password.reset') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">

        @if (count($errors) > 0)
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="form-group">
            <label>{{ trans('validation.attributes.email') }}</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>{{ trans('validation.attributes.password') }}</label>
            <input type="password" class="form-control" name="password">
        </div>

        <div class="form-group">
            <label>{{ trans('validation.attributes.password_confirmation') }} </label>
            <input type="password" class="form-control" name="password_confirmation">
        </div>

        <div>
            <button class="btn btn-primary btn-block" type="submit">
                {{ trans('mezzo.general.auth.reset_password') }}
            </button>
        </div>
    </form>
@endsection