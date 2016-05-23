@extends('cockpit::pages.layouts.index')


@section('index_table_body_cell')


    <span ng-if="$first" style="margin-right: 5px; display: inline-block; width: 35px">
        <a href="" class="disabled" title="ID: @{{ model.id }}" ng-if="vm.displayAsLink(true, model)"
           ng-click="vm.editId(model.id)">
            <img width="35" ng-if="model.mainImage && $first" ng-src="@{{ model.mainImage.data.url }}?size=thumb"/>
        </a>
    </span>

    @parent

    <b ng-if="$first">
        <span ng-if="!model._is_published">
            {{ trans('mezzo.selects.state.private') }}
        </span>
    </b>

@endsection