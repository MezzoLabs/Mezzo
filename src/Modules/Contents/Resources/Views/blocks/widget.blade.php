@extends('modules.contents::block_container')

@section('content')
    <div class="form-group">
        <label for="options">{{ trans('mezzo.modules.contents.options.key') }}</label>
        <input {!! $block->form()->htmlAttributes('key') !!} class="form-control"/>

        <div class="form-group" style="margin-top: 20px;">
            <label for="options">{{ trans('mezzo.modules.contents.options.options') }}</label>
            <textarea class="form-control" name="{{ $block->optionInputName('options') }}"></textarea>
        </div>
    </div>
@endsection
