@extends('cockpit::layouts.default.content.container')

@section('content-aside')
    <div ng-controller="ModelBuilderController as vm">
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#add-field-tab" data-toggle="tab">Add Field</a>
            </li>
            <li ng-class="{ disabled: vm.modelBuilder.selectedField === null }">
                <a href="#edit-field-tab" data-toggle="tab">Edit Field</a>
            </li>
        </ul>
        <!-- Tabs -->

        <br>

        <!-- Tab Content -->
        <div class="tab-content" style="padding: 5px; overflow-x: hidden">

            <!-- Add Field Tab -->
            <div id="add-field-tab" class="tab-pane fade in active">

                <!-- Save -->
                <button type="button" class="btn btn-success btn-block">
                    <span class="ion-checkmark-circled pull-right"></span>
                    Save
                </button>
                <!-- Save -->

                <hr>

                <!-- Add Buttons -->
                <div class="btn-group-vertical btn-block">
                    <button type="button" class="btn btn-default" ng-repeat="button in vm.buttons" ng-click="vm.modelBuilder.addField(button.component)">
                        <span class="pull-right" ng-class="button.icon"></span>
                        <span ng-bind="button.label"></span>
                    </button>
                </div>
                <!-- Add Buttons -->

            </div>
            <!-- Add Field Tab -->

            <!-- Edit Field Tab -->
            <div id="edit-field-tab" class="tab-pane fade">

                <div mezzo-compile="vm.modelBuilder.selectedField.optionsDirective"></div>

                <hr>

                <label>Validation Rules</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="e.g. required" ng-model="vm.modelBuilder.validationRule" mezzo-enter="vm.modelBuilder.addValidationRule()">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" ng-click="vm.modelBuilder.addValidationRule()" ng-disabled="!vm.modelBuilder.validationRule">
                        <span class="ion-plus"></span>
                    </button>
                </span>
                </div>
                <ul>
                    <li ng-repeat="rule in vm.modelBuilder.selectedField.options.validationRules">
                        <a href="" class="validation-rule" ng-click="vm.modelBuilder.removeValidationRule(rule)">
                            <span ng-bind="rule"></span> <span class="validation-rule-times">&times;</span>
                        </a>
                    </li>
                </ul>

                <hr>

                <button type="button" class="btn btn-danger btn-block" ng-click="vm.modelBuilder.deleteField(vm.modelBuilder.selectedField)">
                    <span class="ion-close pull-right"></span>
                    Delete field
                </button>

            </div>
            <!-- Edit Field Tab -->

        </div>
        <!-- Tab Content -->
    </div>
@endsection

@section('content')
    <div ng-controller="ModelBuilderController as vm">
        <div sv-root sv-part="vm.modelBuilder.fields" style="padding: 10px">
            <div ng-repeat="field in vm.modelBuilder.fields" ng-click="vm.modelBuilder.selectField(field)" ng-class="{ 'well well-sm': field === vm.modelBuilder.selectedField }" sv-element>
                <div mezzo-compile="field.mainDirective"></div>
            </div>
        </div>
    </div>
@endsection
