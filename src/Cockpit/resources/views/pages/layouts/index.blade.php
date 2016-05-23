@extends('cockpit::layouts.default.content.container')

@section('index_wrapper_open')
    @include('cockpit::partials.pages.index_wrapper_open')
@endsection

@section('index_actions')
    @include('cockpit::partials.pages.index_actions')
@endsection

@section('index_table')
    @include('cockpit::partials.pages.index_table')
@endsection

@section('index_wrapper_close')
    @include('cockpit::partials.pages.index_wrapper_close')
@endsection

@section('content')
    @yield('index_wrapper_open')
    @yield('index_actions')
    @yield('index_table')
    @yield('index_wrapper_close')
@endsection