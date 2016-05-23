@extends(cockpit_content_container())

@section('wrapper_open')
    @include('cockpit::partials.pages.create_or_edit.wrapper_open')
@endsection

@section('form')
    @include('cockpit::partials.pages.create_or_edit.form')
@endsection

@section('wrapper_close')
    @include('cockpit::partials.pages.create_or_edit.wrapper_close')
@endsection

@section('content')
    @yield('wrapper_open')
    @yield('form')
    @yield('wrapper_close')
@endsection