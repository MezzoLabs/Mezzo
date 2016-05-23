@extends(cockpit_content_container())


@section('content')

    <div class="wrapper">
        <!-- Bottom Container -->
        <div class="panel panel-bordered">
            <div class="panel-heading">
                <h3>{{ $model_reflection->pluralTitle() }}</h3>
            </div>
            <div class="panel-body">
                {!! cockpit_form()->open(['method' => 'GET', 'route' => 'cockpit::role.index']) !!}
                <div class="form-group">
                    {!! cockpit_form()->select('role', $roles->lists('label', 'name'), $role->name, ['class' => 'form-control']) !!}
                </div>
                <input type="submit" class="btn btn-primary"/>

                {!! cockpit_form()->close() !!}
            </div>
            <div class="panel-heading">
                <h3>{{ trans_choice('mezzo.models.permission', 2) }}</h3>
            </div>
            <div class="panel-body">
                {!! cockpit_form()->open(['method' => 'PUT', 'route' => ['cockpit::role.update', $role->id] ]) !!}
                <div class="row">
                    <?php $i = 0 ?>
                    @foreach($permissions->groupBy('model') as $permissionsGroup)
                        <div class="col-md-4">
                            @if($permissionsGroup->first()->model)
                                <h3>{{ space_case(mezzo()->model($permissionsGroup->first()->model)->name()) }}:</h3>
                            @else
                                <h3>Other:</h3>
                            @endif

                            @foreach($permissionsGroup as $permission)
                                <div class="checkbox">
                                    <label><input type="checkbox"
                                                  @if($role->hasPermission($permission)) checked="checked"
                                                  @endif class="" name="permissions[{{ $permission->id }}]"
                                                  /> {{ $permission->label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @if($i % 3 == 2)
                            <div class="clearfix"></div> @endif
                        <?php $i++ ?>
                    @endforeach
                </div>

                <div class="text-right">
                    <input type="submit" value="{{ trans('mezzo.general.edit') }}" class="btn btn-primary"/>
                </div>

                {!! cockpit_form()->close() !!}

            </div>

        </div>
    </div>


    </div>
@endsection