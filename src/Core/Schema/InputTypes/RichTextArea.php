<?php


namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;


class RichTextArea extends TextArea
{
    protected $htmlAttributes = [
        'ui-tinymce' => 'vm.tinymceOptions()',
        //'ng-model' => 'vm.contentBlockService.tinyMceModels()'
    ];


} 