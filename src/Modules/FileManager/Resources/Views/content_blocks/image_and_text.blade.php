@extends('modules.contents::block_container')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label>{{ $fields['text']->title() }}</label>
                <textarea
                        {!! $block->form()->htmlAttributes('text') !!} class="form-control"></textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>{{ $fields['image']->title() }}</label>
                {!! $formBuilder->filePicker($block->inputName('image'), new \App\ImageFile(), ['multiple' => false, 'attributes'=> ['data-value' => '@{{ block.fields.image }}']]) !!}
            </div>
        </div>
    </div>

    <div class="form-group"><label>{{ trans('mezzo.modules.contents.options.image_position') }}</label> <br/>

        <div class="radio-inline">
            <label><input type="radio"
                          ng-checked="@{{ !block.options.image_position || block.options.image_position == 'left' }}"
                          class="" name="{{ $block->optionInputName('image_position') }}"
                          value="left">{{ trans('mezzo.modules.contents.options.image_left') }}</label>
        </div>
        <div class="radio-inline">
            <label><input type="radio" ng-checked="@{{ block.options.image_position == 'right' }}" class=""
                          name="{{ $block->optionInputName('image_position') }}"
                          value="right">{{ trans('mezzo.modules.contents.options.image_right') }}</label>
        </div>
        <div class="radio-inline">
            <label><input type="radio" ng-checked="@{{ block.options.image_position == 'above' }}" class=""
                          name="{{ $block->optionInputName('image_position') }}"
                          value="above">{{ trans('mezzo.modules.contents.options.image_above') }}</label>
        </div>
        <div class="radio-inline">
            <label><input type="radio" ng-checked="@{{ block.options.image_position == 'below' }}" class=""
                          name="{{ $block->optionInputName('image_position') }}"
                          value="below">{{ trans('mezzo.modules.contents.options.image_below') }}</label>
        </div>
    </div>
@endsection

