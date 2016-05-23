@extends(cockpit_content_container())

@section('content')
    <div class="wrapper">
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3>New {{ $model_reflection->name() }}</h3>

                <div class="panel-actions">
                </div>
            </div>
            <div class="panel-body">
                {!! cockpit_form()->open() !!}
                @include(cockpit_html()->viewKey('form-content-create'))
                {!! cockpit_form()->close() !!}

            </div>
        </div>
    </div>
@endsection