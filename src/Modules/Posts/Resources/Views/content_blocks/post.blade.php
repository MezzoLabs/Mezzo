@extends('modules.contents::block_container')

@section('content')
    <div class="form-group">
        <mezzo-relation-input {!! $block->form()->htmlAttributes('post') !!} ></mezzo-relation-input>
    </div>
@endsection