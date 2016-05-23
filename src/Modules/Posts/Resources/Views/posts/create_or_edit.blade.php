@extends(cockpit_content_container())


@section('content')
    @include('cockpit::partials.pages.edit_wrapper_open')
    {!! cockpit_form()->open(['angular' => true]) !!}
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    @if($module_page->isType('create'))
                        @include('cockpit::partials.pages.create_heading')
                    @else
                        @include('cockpit::partials.pages.edit_heading')
                    @endif
                    <div class="panel-subtitle">
                        /@{{ vm.inputs.slug }}
                    </div>

                </div>
                <div class="panel-body">
                    @if($module_page->isType('create'))
                        @include(cockpit_html()->viewKey('form-content-create'), [
                            'hide_submit' => true, 'without' => ['categories','user_id', 'content_id', 'main_image_id', 'hero_image_id', 'published_at', 'slug', 'state', 'created_at']])
                    @else
                        @include(cockpit_html()->viewKey('form-content-edit'), [
                                'hide_submit' => true, 'without' => ['categories', 'user_id', 'content_id', 'main_image_id', 'hero_image_id', 'published_at', 'slug', 'state', 'created_at']])
                    @endif
                </div>
            </div>
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3>{{ Lang::get('validation.attributes.content.blocks') }}</h3>
                </div>
                {!! $model_reflection->schema()->attributes('content_id')->render() !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3>{{ Lang::get('mezzo.modules.posts.form.publish') }}</h3>
                </div>
                <div class="panel-body">
                    {!! $model_reflection->schema()->attributes('user_id')->render() !!}
                    {!! $model_reflection->schema()->attributes('main_image_id')->render() !!}
                    {!! $model_reflection->schema()->attributes('hero_image_id')->render() !!}
                    {!! $model_reflection->schema()->attributes('published_at')->render() !!}
                    {!! $model_reflection->schema()->attributes('created_at')->render() !!}
                    {!! $model_reflection->schema()->attributes('state')->render() !!}

                    @if($module_page->isType('create'))
                        <span class="is_published"></span>
                    @endif

                    @if($module_page->isType('edit'))
                        <a href="/artikel/@{{ vm.inputs.id }}" class="btn btn-link btn-xs btn-block" target="_blank"><i class="fa fa-eye"></i> Anzeigen</a>
                        <br/>
                    @endif

                    @if($module_page->isType('create'))
                        {!! cockpit_form()->submitCreate($model_reflection) !!}
                    @else
                        {!! cockpit_form()->submitEdit($model_reflection) !!}
                    @endif
                </div>



            </div>
            <div class="panel categories-panel panel-bordered">
                <div class="panel-heading">
                    <h3>{{ Lang::get('validation.attributes.categories') }}</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {!! $model_reflection->schema()->attributes('categories')->render(['wrap' => false]) !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
    {!! cockpit_form()->close() !!}
    @include('cockpit::partials.pages.edit_wrapper_close')

@endsection