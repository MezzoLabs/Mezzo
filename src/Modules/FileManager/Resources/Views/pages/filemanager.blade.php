@extends(cockpit_content_container())

@section('content-aside')
        <!-- Categories -->
<ul class="nav nav-pills nav-stacked">
    <li ng-repeat="category in vm.categories" ng-class="vm.isActive(category)">
        <a href="" ng-click="vm.selectCategory(category)">
                <span style="display: inline-block; width: 20px">
                    <span ng-class="category.icon"></span>
                </span>
            <span ng-bind="category.label"></span>
        </a>
    </li>
</ul>
<!-- Categories -->

<!-- Order by -->
<section class="well">
    <div class="form-group">
        <label for="order-by">{{ trans('mezzo.modules.filemanager.order_by') }}</label>
        <select id="order-by"
                class="form-control"
                ng-model="vm.orderBy"
                ng-change="vm.doOrder()"
                ng-options="key as value for (key , value) in vm.orderOptions">
        </select>
    </div>
    <div class="form-group">
        <label for="search">{{ trans('mezzo.modules.filemanager.search') }}</label>
        <!-- Search -->
        <input type="search" class="form-control" placeholder="{{ trans('mezzo.modules.filemanager.search') }}"
               ng-model="vm.search">
        <!-- Search -->
    </div>

</section>
<!-- Order by -->

<section>
    <!-- Refresh -->
    <button type="button" class="btn btn-secondary btn-block" ng-click="vm.refresh()"
            ng-disabled="vm.loading">
        <span class="ion-loop"></span> {{ trans('mezzo.modules.filemanager.refresh') }}
    </button>
    <!-- Refresh -->
</section>
@endsection

@section('content')
    <div class="mezzo__filemanager_container">
        <!-- Move Modal -->
        <div id="move-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content modal-sm">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('mezzo.modules.filemanager.moving') }} "<span
                                    ng-bind="vm.selected.title"></span>" {{ trans('mezzo.modules.filemanager.to') }}...
                        </h4>
                    </div>
                    <div class="modal-body">
                        <script type="text/ng-template" id="node.html">
                            <button type="button" class="btn btn-default btn-xs" ng-bind="folder.title"
                                    ng-click="vm.moveTo(folder)"></button>
                            <ul style="list-style-type: none; padding-left: 20px">
                                <li ng-repeat="folder in folder.files" ng-include="'node.html'"
                                    ng-if="folder.isFolder"></li>
                            </ul>
                        </script>
                        <div ng-include="'node.html'" ng-init="folder = vm.library"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Move Modal -->

        <div class="panel panel-bordered" style="margin-bottom: 0; border-left: 3px solid #eee;">
            <div class="panel-body">

                <div class="btn-group">
                    <!-- Upload -->
                    <button type="button" class="btn btn-primary" ng-model="vm.filesToUpload" ngf-multiple="true" ngf-select="vm.upload($file)">
                    <span style="display: inline-block; width: 20px">
                        <span class="ion-ios-cloud-upload"></span>
                    </span>
                        <span style="display: inline-block">{{ trans('mezzo.modules.filemanager.upload') }}</span>
                    </button>
                    <!-- Upload -->
                    <!-- Add Folder -->
                    <button type="button" class="btn btn-default" ng-click="vm.addFolderPrompt()">
                    <span style="display: inline-block; width: 20px">
                        <span class="ion-ios-folder"></span>
                    </span>
                        <span style="display: inline-block">{{ trans('mezzo.modules.filemanager.add_folder') }}</span>
                    </button>
                    <!-- Add Folder -->
                    <!-- Move -->
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#move-modal"
                            ng-disabled="!vm.fileIsSelected()">
                    <span style="display: inline-block; width: 20px">
                        <span class="ion-arrow-swap"></span>
                    </span>
                        <span style="display: inline-block">{{ trans('mezzo.modules.filemanager.move') }}</span>
                    </button>
                    <!-- Move -->
                    <!-- Rename -->
                    <button type="button" class="btn btn-default" ng-disabled="!vm.fileIsSelected()"
                            ng-click="vm.showRenamePrompt()">
                    <span style="display: inline-block; width: 20px">
                        <span class="ion-edit"></span>
                    </span>
                        <span style="display: inline-block">{{ trans('mezzo.general.rename') }}</span>
                    </button>
                    <!-- Rename -->
                    <!-- Delete -->
                    <button type="button" class="btn btn-default" ng-click="vm.deleteFiles()"
                            ng-disabled="!vm.fileIsSelected()">
                    <span style="display: inline-block; width: 20px">
                        <span class="ion-trash-a"></span>
                    </span>
                    </button>
                    <!-- Delete -->
                </div>


            </div>
        </div>

        <!-- Folder Navigation -->
        <ol class="breadcrumb" style="margin-bottom: 0; border-left: 3px solid #eee;">
            <li ng-bind="vm.search" ng-if="vm.search"></li>
            <li ng-bind="vm.category.label" ng-if="vm.showCategoryAsFolderHierarchy()"></li>
            <li ng-repeat="folder in vm.folderHierarchy()" ng-class="{ active: $last }"
                ng-if="vm.showFolderHierarchy()">
                <a href="" ng-bind="folder.title" ng-click="vm.enterFolder(folder)" ng-if="!$last"></a>
                <span ng-bind="folder.title" ng-if="$last"></span>
            </li>
        </ol>
        <!-- Folder Navigation -->

        <!-- Files -->
        <table class="files table table-hover table-responsive">
            <tbody>

            <tr ng-repeat="file in vm.sortedFiles()"
                class="file"
                ng-click="vm.selectFile(file)"
                ng-class="{ danger: file === vm.selected }"
                mezzo-draggable="@{{ !file.isFolder }}"
                mezzo-droppable="@{{ file.isFolder }}"
                data-index="@{{ $index }}">
                <td style="width: 20px">
                    <span class="ion-ios-folder" ng-show="file.isFolder"></span>
                    <span ng-class="file.icon()" ng-hide="file.isFolder"></span>
                </td>
                <td>
                    <a href="" style="color: #555555" ng-bind="file.name" ng-click="vm.enterFolder(file)"
                       ng-show="file.isFolder"></a>
                    <span ng-bind="file.title" ng-hide="file.isFolder"></span>
                </td>
                <td>
                    <span ng-bind="file.created_at" ng-hide="file.isFolder"></span>
                </td>
                <td>
                    <span ng-show="file.isFolder" ng-bind="vm.items(file)"></span>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- Files -->
    </div>

@endsection

@section('quickview_title')
    {{ trans('mezzo.modules.filemanager.quickview.title') }}
@endsection

@section('quickview_content')
    <div class="section" ng-if="vm.selected.thumbnail()">
        <img style="padding: 3px;" class="img-responsive" ng-src="@{{ vm.selected.thumbnail('small') }}"/>
    </div>
    <hr/>

    <div class="section section-file-info wrapper">
        <p class="attribute-info">
            <label>{{ trans('mezzo.modules.filemanager.quickview.name') }}</label>
            <input class="form-control" type="text" disabled value="@{{ vm.selected.name }}"/>
        </p>
        <p class="attribute-info">
            <label>{{ trans('mezzo.modules.filemanager.quickview.created_at') }}</label>
            <input class="form-control" type="text" disabled value="@{{ vm.selected.created_at }}"/>
        </p>
        <p class="attribute-info">
            <label>{{ trans('mezzo.modules.filemanager.quickview.folder') }}</label>
            <input class="form-control" type="text" disabled value="@{{ vm.selected.displayFolderPath() }}"/>
        </p>
        <p class="attribute-info">
            <label>{{ trans('mezzo.modules.filemanager.quickview.link') }}</label>
        <div class="input-group">
            <input class="form-control" style="height: 38px;" type="text" disabled value="@{{ vm.selected.url }}"/>
            <span class="input-group-btn">
                <a class="btn btn-info" target="_blank" ng-href="@{{ vm.selected.url }}"><i
                            class="fa fa-chevron-right"></i></a>
            </span>
        </div><!-- /input-group -->

        </p>
    </div>
    <hr/>
    <div class="section section-addon-inputs  wrapper">
        <div class="form-group" ng-if="vm.selected.isImage()">
            <label>{{ cockpit_form()->title('imagefile', 'caption') }}</label>
            <input ng-model="vm.selected.addon.caption" name="caption"
                   class="form-control" {!! cockpit_form()->attributes('imagefile', 'caption') !!} >
        </div>
        <input type="submit" ng-click="vm.submitAddon()" class="btn btn-primary btn-block">
    </div>

@endsection