@extends('cockpit::pages.layouts.create_or_edit')

@section('main_panel.actions')
    <a class="highlight" href="/mezzo/user/user/edit/@{{ vm.modelId }}"><i
                class="ion-arrow-return-left"></i></a>
@endsection

@section('main_panel.body:before')
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a data-target="#main" href="#" aria-controls="home" role="tab"
                                                  data-toggle="tab">
                <i class="ion-ios-location"></i>
                {{ trans('mezzo.modules.addresses.types.main') }}</a></li>
        <li role="presentation"><a data-target="#shipping" href="#" aria-controls="home" role="tab" data-toggle="tab">
                <i class="ion-paper-airplane"></i>
                {{ trans('mezzo.modules.addresses.types.shipping') }}</a>
        </li>
        <li role="presentation"><a data-target="#billing" href="#" aria-controls="address" role="tab"
                                   data-toggle="tab">
                <i class="ion-card"></i>
                {{ trans('mezzo.modules.addresses.types.billing') }}</a>
        </li>
    </ul>
    @parent
@endsection

@section('form_content')

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
            {!! $model_reflection->schema()->attributes('address_id')->render() !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="shipping">
            {!! $model_reflection->schema()->attributes('shipping_address_id')->render() !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="billing">
            {!! $model_reflection->schema()->attributes('billing_address_id')->render() !!}
        </div>
    </div>
    <br/>

@endsection