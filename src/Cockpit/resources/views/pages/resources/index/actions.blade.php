<!-- Search -->
<input type="search" class="form-control pull-right" style="display: inline-block; width: 200px"
       placeholder="Search" ng-model="vm.searchText">
<!-- Search -->

<div class="btn-group">
    <!-- Add new -->
    <a ui-sref="resource-create" class="btn btn-primary" ng-click="vm.create()">
        <span class="ion-plus"></span>
        Add new
    </a>
    <!-- Add new -->

    <!-- Edit -->
    <button type="button" class="btn btn-default" ng-disabled="!vm.canEdit()" ng-click="vm.edit()">
        <span class="ion-edit"></span>
        Edit
    </button>
    <!-- Edit -->

    <!-- Delete -->
    <button type="button" class="btn btn-default" ng-disabled="!vm.canRemove()" ng-click="vm.remove()">
        <span class="ion-trash-b"></span>
        Delete
        <span class="badge" ng-bind="vm.countSelected()"></span>
    </button>
</div>

<!-- Deletion progress -->
<div class="progress" style="display: inline-block; width: 200px; margin-top: auto; margin-bottom: auto"
     ng-show="vm.removing">
    <div class="progress-bar progress-bar-striped active" style="width: 100%">
        Deleting <span ng-bind="vm.removing"></span> models...
    </div>
</div>
<!-- Deletion progress -->