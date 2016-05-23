@extends('cockpit::layouts.default.partial_layouts.index_filters')

@section('filters_form')
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>{{ trans('mezzo.general.search') }}</label>
                <input type="search" name="q" class="form-control"/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>{{ trans('validation.attributes.user_id') }}</label>
                <select name="user_id" class="form-control">
                    <option value="">-</option>
                    @foreach($backendUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>{{ trans('validation.attributes.is_published') }}</label>
                <select name="scopes[isPublished][0]" class="form-control">
                    <option value="">{{ trans('mezzo.selects.state.all') }}</option>
                    <option value="1">{{ trans('mezzo.selects.state.published') }}</option>
                    <option value="0">{{ trans('mezzo.selects.state.private') }}</option>
                </select>
            </div>
        </div>
    </div>
@endsection