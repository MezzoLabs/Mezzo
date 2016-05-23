@extends('cockpit::layouts.default.content.container')


@section('content')

    <div class="wrapper">
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3>Models</h3>

                <div class="panel-actions">
                </div>
            </div>
            <div class="panel-body">
                <h3>Mezzo</h3>
                <table class="table table-responsive">
                    <tr>
                        <th>Name</th>
                        <th>Module</th>
                        <th>Repository</th>
                        <th>Attributes</th>

                    </tr>
                    @foreach($models['mezzo'] as $model)
                        <tr id="{{ $model->name() }}">
                            <td>
                                <b>{{ $model->name() }}</b> ({{ $model->className() }})
                                <br/>
                                <small>
                                    1: {{ $model->title() }}<br/>
                                    n: {{ $model->pluralTitle() }}<br/>
                                </small>

                            </td>
                            @if($model->hasModule())
                                <td title="{{ get_class($model->module()) }}">{{ class_basename(get_class($model->module())) }}</td>
                            @else
                                <td>
                                    <small>No Module</small>
                                </td>
                            @endif
                            @if($model->instance()->hasRepository())
                                <td title="{{ get_class($model->instance()->repository()) }}">{{ class_basename($model->instance()->repository()) }}</td>
                            @else
                                <td>
                                    <small>No Repository</small>
                                </td>
                            @endif
                            <td>
                                <table class="table table-bordered">

                                    @foreach($model->attributes() as $attribute)
                                        <tr>

                                            <th>{{ $attribute->name() }}</th>
                                            <td title="{{ get_class($attribute->type()) }}">
                                                {{ class_basename(get_class($attribute->type())) }}

                                                @if($attribute->isRelationAttribute())
                                                    <i>--({{ $attribute->naming() }})--></i>

                                                    <a href="#{{ $attribute->otherRelationSide()->modelReflection()->name() }}">{{ $attribute->otherRelationSide()->modelReflection()->name() }}</a>
                                                @endif

                                            </td>
                                        </tr>

                                    @endforeach
                                </table>

                            </td>


                        </tr>
                    @endforeach
                </table>

                <h3>Eloquent</h3>
                <table class="table table-responsive">
                    @foreach($models['eloquent'] as $model)
                        <tr>
                            <th>{{ $model->name() }}</th>
                        </tr>
                    @endforeach
                </table>


            </div>
        </div>
    </div>
@endsection
