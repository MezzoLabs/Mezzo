@extends(cockpit_content_container())

@section('content')
    <div class="wrapper row">
        <div class="col-md-3">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3>Add Option</h3>
                </div>
                {!! cockpit_form()->open(['route' => 'cockpit::option.store', 'method' => 'POST']) !!}
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
                    <h3>Options</h3>
                </div>
                <div class="panel-body">
                    <dib class="list-group">
                        @foreach($options as $option)
                            <div class="list-group-item">
                                <b>{{ $option->name }}:</b> {{ str_limit($option->value, 200)  }}

                                {!! cockpit_form()->open(['route' => ['cockpit::option.delete', $option->id], 'method' => 'DELETE', 'class' => 'form-inline']) !!}
                                <a href="" class="badge" onclick="form.submit();" .>Delete</a>
                                {!! cockpit_form()->close() !!}
                            </div>
                        @endforeach
                    </dib>
                </div>
            </div>
        </div>
    </div>

@endsection