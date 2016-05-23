@extends('cockpit::layouts.default.content.container')


@section('content')

    <div class="wrapper">
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3>Logs</h3>

                <div class="panel-actions">
                </div>
            </div>
            <div class="panel-body">

                <h4>Logfiles</h4>

                <div class="list-group">
                    @foreach($all_logs as $log)
                        <a href="{{ Request::url() }}?log={{ urlencode($log->getRealPath()) }}"
                           class="list-group-item">{{ $log->getRealPath() }}</a>
                    @endforeach
                </div>

                <h4>Active Log</h4>
                @if($active_log)
                    <p>
                        <b>Path: </b> {{ $active_log['file']->getRealPath() }}
                    </p>
                    <p>
                        <b>Lines: </b> {{ count($active_log['lines']) }}
                    </p>

                    @foreach($active_log['parts'] as $part)
                    <textarea wrap="soft" @if(strlen($part) > 500) style="height: 300px;" @endif class="form-control">
                        {!! trim($part) !!}
                    </textarea>
                        <br/>
                    @endforeach

                @endif


            </div>
        </div>
    </div>
@endsection
