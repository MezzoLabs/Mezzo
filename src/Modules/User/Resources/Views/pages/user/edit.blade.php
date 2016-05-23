@extends(cockpit_view('edit.page.layout'))

@section('form')
    <div class="row">
        <div class="col-md-8">
            @parent
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <a href="{!! angular_route('cockpit::user.subscriptions', 'vm.modelId') !!}"
                       class="btn btn-block btn-default"><i class="ion-bookmark"></i> {{ trans_choice('mezzo.models.subscription', 2) }}</a>

                    <a href="{!! angular_route('cockpit::user.addresses.edit', 'vm.modelId') !!}"
                       class="btn btn-block btn-default"><i
                                class="ion-ios-location"></i> {{ trans_choice('mezzo.models.address', 2) }}</a>

                    <a href="{{ route('cockpit::order.index') }}?user_id=@{{ vm.modelId }}"
                       class="btn btn-block btn-default"><i
                                class="ion-ios-list-outline"></i> {{ trans_choice('mezzo.models.order', 2) }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection