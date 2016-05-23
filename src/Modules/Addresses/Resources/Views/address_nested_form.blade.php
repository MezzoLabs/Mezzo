{!! cockpit_form()->openNestedRelation($renderer->attribute()) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('validation.attributes.googlesearch') }}</label>
            <input type="text" class="google_search form-control"
                   data-mezzo-google-maps-search data-street="{{ $renderer->attribute()->naming() }}.street"
                   data-street-number="{{ $renderer->attribute()->naming() }}.street_extra"
                   data-postal-code="{{ $renderer->attribute()->naming() }}.zip" data-city="{{ $renderer->attribute()->naming() }}.city"
                   data-latitude="{{ $renderer->attribute()->naming() }}.latitude" data-longitude="{{ $renderer->attribute()->naming() }}.longitude" data-country="{{ $renderer->attribute()->naming() }}.country"/>
        </div>
        {!! $renderer->renderNested('latitude', ['attributes' => ['readonly' => 'readonly']]) !!}
        {!! $renderer->renderNested('longitude', ['attributes' => ['readonly' => 'readonly']]) !!}

    </div>
    <div class="col-md-6">

        <div style="width: 100%; height: 200px">
            <div mezzo-google-map data-latitude="address.latitude" data-longitude="address.longitude" style="width: 100%; height: 100%"></div>
        </div>


    </div>
    <div class="col-md-12">
        <hr/>
    </div>
    <div class="col-md-6">
        {!! $renderer->renderNested('addressee') !!}
    </div>
    <div class="col-md-6">

        {!! $renderer->renderNested('organization') !!}
    </div>
    <div class="col-md-9">
        {!! $renderer->renderNested('street') !!}
    </div>
    <div class="col-md-3">

        {!! $renderer->renderNested('street_extra') !!}
    </div>
    <div class="clearfix"></div>

    <div class="col-md-3">

        {!! $renderer->renderNested('zip') !!}
    </div>
    <div class="col-md-3">
        {!! $renderer->renderNested('city') !!}
    </div>
    <div class="col-md-3">
        {!! $renderer->renderNested('country') !!}
    </div>

    <div class="clearfix"></div>
    <div class="col-md-3">

        {!! $renderer->renderNested('phone') !!}
    </div>
    <div class="col-md-3">
        {!! $renderer->renderNested('fax') !!}
    </div>
    <div class="clearfix"></div>

</div>

{!! cockpit_form()->closeNestedRelation() !!}