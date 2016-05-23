@extends(cockpit_content_container())


@section('content')
    <div class="wrapper">
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3>Edit {{ $model_reflection->name() }}</h3>

                <div class="panel-actions">
                </div>
            </div>
            <div class="panel-body">
                {!! cockpit_form()->model($model, ['method' => 'PUT']) !!}
                @include(cockpit_html()->viewKey('form-content-edit'))
                {!! cockpit_form()->close() !!}
            </div>
        </div>
    </div>

@endsection