@extends('modules.contents::block_container')

@section('content')
    <div class="form-group">
        <label>{{ $fields['images']->title() }}</label>
        {!! $formBuilder->filePicker($block->inputName('images'), new \App\ImageFile(), ['multiple' => true, 'attributes'=> ['data-value' => '@{{ block.fields.images }}']]) !!}
    </div>
    <div class="form-group">
        <label>{{ trans('mezzo.modules.contents.options.image_display') }}</label>
        <select class="form-control" name="{{ $block->optionInputName('display') }}">
            <option ng-selected="block.options.display == 'grid'" value="grid">{{ trans('mezzo.modules.contents.options.image_grid') }}</option>
            <option ng-selected="block.options.display == 'list'" value="list">{{ trans('mezzo.modules.contents.options.image_list') }}</option>
            <option ng-selected="block.options.display == 'lightbox'" value="lightbox">{{ trans('mezzo.modules.contents.options.image_lightbox') }}</option>
            <option ng-selected="block.options.display == 'slider'"
                    value="slider">{{ trans('mezzo.modules.contents.options.image_slider') }}</option>
        </select>
    </div>
@endsection
