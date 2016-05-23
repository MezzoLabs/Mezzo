<input type="hidden" name="{{ $block->propertyInputName('name') }}" value="@{{ block.handle }}">
<input type="hidden" name="{{ $block->propertyInputName('class') }}" value="{{ $block->key() }}">
<input type="hidden" name="{{ $block->propertyInputName('id') }}" value="@{{ block.id }}">
<input type="hidden" name="{{ $block->propertyInputName('sort') }}" value="@{{ block.sort }}">

@yield('content', 'Empty content.')