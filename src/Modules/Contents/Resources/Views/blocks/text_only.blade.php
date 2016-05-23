@extends('modules.contents::block_container')

@section('content')
    <div class="form-group">
        <textarea
                {!! $block->form()->htmlAttributes('text') !!} class="form-control">@{{ block.fields.text  }}</textarea>
    </div>
@endsection


