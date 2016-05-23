@extends('modules.contents::block_container')

@section('content')
    <div class="form-group">
        <label>{{ $fields['url']->title() }}</label>
        <input {!! $block->form()->htmlAttributes('url') !!} class="form-control"
               placeholder="https://vimeo.com/12345678">
    </div>
@endsection
