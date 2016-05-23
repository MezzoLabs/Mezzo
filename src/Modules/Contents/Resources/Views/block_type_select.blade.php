<div style="display: flex">
    <div style="display: flex; flex-grow: 1; flex-direction: column;">
        <div class="content-blocks" style="flex-grow: 1" ui-sortable="vm.contentBlockService.sortableOptions"
             ng-model="vm.contentBlockService.contentBlocks">
            <div class="content-block @{{ block.cssClass }}" ng-repeat="block in vm.contentBlockService.contentBlocks">
                <div class="content-block-heading">
                    <b>@{{ block.sort }}. @{{ block.title }}</b>

                    <div class="content-block-actions">
                        <a class="" href="#"><i class="ion-ios-gear"  ng-click="vm.contentBlockService.contentBlockOptionsDialog(block.nameInForm)"></i></a>
                        <a href="#"><i class="ion-arrow-move"></i></a>
                        <a href="#"><i class="ion-ios-close-empty" ng-click="vm.contentBlockService.removeContentBlock(block.nameInForm)"></i></a>
                    </div>
                </div>
                <div class="content-block-body" mezzo-compile-content-block="block.template"></div>
            </div>
        </div>

    </div>
    <div style="display: flex; flex-grow: 0; flex-direction: column; padding: 10px;">
        <div class="list-group">
            @foreach(\MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\ContentBlockTypeRegistrar::make()->all() as $blockType)
                <button type="button" class="list-group-item"
                        ng-click="vm.contentBlockService.addContentBlock('{{ addslashes($blockType->key()) }}', '{{ $blockType->hash() }}', '{{ $blockType->title() }}')">
                    <i class="{{ $blockType->icon() }}"></i>
                    {{ $blockType->title() }}
                </button>
            @endforeach
        </div>
    </div>

</div>