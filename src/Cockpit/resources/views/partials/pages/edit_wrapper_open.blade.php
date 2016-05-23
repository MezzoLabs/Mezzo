<div class="wrapper"
     ng-init="vm.init('{!! $model_reflection->name() !!}',  {!! str_replace('"', "'", $model_reflection->defaultIncludes('edit', (isset($includes)) ? $includes : [])->toJson()) !!})">
       