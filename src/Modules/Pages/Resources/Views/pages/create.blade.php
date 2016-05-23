@extends(cockpit_content_container())


@section('content')

    <div class="wrapper" ng-init="vm.init('{{ $model_reflection->name() }}')">
        {!! cockpit_form()->open(['angular' => true]) !!}
        <div class="panel panel-bordered">
            <div class="panel-heading">
                @include('cockpit::partials.pages.create_heading')
            </div>
            <div class="panel-body">
                @include(cockpit_html()->viewKey('form-content-create'), [
                        'hide_submit' => true, 'without' => ['content_id', 'slug']])
            </div>
        </div>

        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3>Content</h3>

                <div class="panel-actions">
                </div>
            </div>
            @include('modules.contents::block_type_select')

            <div class="panel panel-bordered">
                {!! cockpit_form()->submitCreate($model_reflection) !!}
            </div>


            {!! cockpit_form()->close() !!}
        </div>
    </div>

@endsection