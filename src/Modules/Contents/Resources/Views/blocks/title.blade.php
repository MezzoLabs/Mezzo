@extends('modules.contents::block_container')

@section('content')
    <div class="form-group">
        <input {!! $block->form()->htmlAttributes('title') !!} class="form-control"/>
    </div>
@endsection


