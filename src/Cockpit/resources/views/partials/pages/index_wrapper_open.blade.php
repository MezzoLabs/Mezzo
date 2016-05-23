<div class="wrapper"
     ng-init="vm.init('{{ $model_reflection->slug() }}', {!! str_replace('"', "'", $module_page->defaultIncludes()->toJson()) !!}, {!! str_replace('"', "'", $module_page->frontendOption()->toJson()) !!})">
