<div class="list-group-item clearfix">
    <?php for ($x = 0; $x != $element->level; $x++) { echo "<span class='list-group-item__spacer'></span>"; } ?>

        @if($element->level > 0)
        <i class="ion-minus"></i> @endif <b>{{ $element->label }}</b>

        <small>{{ $element->slug }}</small>

    <div class="pull-right">
        <a data-mezzo-href-reload="1" href="{{ route('cockpit::category.destroy', $element->id) }}" class="btn btn-xs btn-danger"><i class="ion-backspace"></i></a>
    </div>
</div>
@foreach($element->children as $child)
    <?php $child->level = $element->level + 1; ?>
    @include('modules.categories::partials.nested_list', ['element' => $child])
@endforeach

