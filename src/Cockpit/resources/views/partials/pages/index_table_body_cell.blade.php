<a href="" class="disabled" title="ID: @{{ model.id }}"
   ng-if="vm.displayAsLink($first, model)" ng-click="vm.editId(model.id)"
   ng-bind="value"></a>
<span ng-if="!vm.displayAsLink($first, model)" ng-bind="value"></span>
