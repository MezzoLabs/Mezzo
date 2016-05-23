@extends(cockpit_content_container())

@section('content')
    <div class="wrapper row">
        <div class="col-md-3">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3>{{ trans('mezzo.general.creating') }} {{ trans_choice('mezzo.models.category', 1) }}</h3>
                </div>
                {!! cockpit_form()->open(['route' => 'cockpit::category.store', 'method' => 'POST']) !!}
                <div class="panel-body">
                    @include(cockpit_html()->viewKey('form-content-create'), [
                        'hide_submit' => false, 'without' => ['content_id', 'slug']])
                </div>
                {!! cockpit_form()->close() !!}
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3>{{ trans_choice('mezzo.models.category', 2) }}</h3>

                    <div class="panel-actions">
                    </div>
                </div>
                <div class="panel-body">

                    @foreach($category_groups as $group)
                        <div class="row">
                            <div class="col-md-6">
                                <h3>{{ $group->label }}</h3>
                            </div>
                            <div class="col-md-6 text-right label-group">
                                @foreach($group->modelClasses() as $modelClass)
                                    <span class="label label-default">{{ $modelClass }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="list-group">
                            @foreach($group->tree() as $category)
                                <?php $category->level = 0; ?>
                                @include('modules.categories::partials.nested_list', ['element' => $category])
                            @endforeach
                        </div>
                        <br/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection