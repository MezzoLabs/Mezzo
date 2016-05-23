@extends('cockpit::layouts.default.content.container')


@section('content')

    <div class="wrapper">
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3>Routes</h3>

                <div class="panel-actions">
                </div>
            </div>
            <div class="panel-body">

                <h4>Application routes</h4>

                <div class="table-responsive">
                    {!! cockpit_html()->table($applicationRoutes) !!}
                </div>

                <h4>API Routes</h4>

                <div class="table-responsive">
                    {!! cockpit_html()->table($apiRoutes) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
