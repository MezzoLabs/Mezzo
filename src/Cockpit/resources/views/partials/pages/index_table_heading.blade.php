<thead class="resource-index-table-head">
<tr>
    <th></th>
    <th>
        <input type="checkbox" ng-model="vm.selectAll" ng-change="vm.updateSelectAll()">
    </th>
    <th></th>

    @foreach($module_page->columns() as $name => $column)
        <th ng-init="vm.addAttribute('{{ $column->name }}', '{{ $column->type }}', {!! str_replace('"', "'", $column->options->toJson()) !!})">
            {{ $column->title }}
            <span ng-if="vm.useSortings('{{ $column->name }}')" href="#" ng-click="vm.sortBy('{{ $column->name }}')"
                  class="sortby"><i
                        ng-class="vm.sortIcon('{{ $name }}')"></i></span>

            {{--
            @if(!$column->hasRelationAttribute())
                <input class="form-control input-sm" type="search"
                       ng-model="vm.attributes.{{ $name }}.filter">
            @else
                    {!! cockpit_form()->relationship($column->getAttribute(), ['ng-change' => 'vm.parent.filterChanged()']) !!}

            @endif
            --}}

        </th>
    @endforeach
</tr>
</thead>