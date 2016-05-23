<div class="clearfix">
    <div class="pull-right">
        <uib-pagination last-text="{{ Lang::get('mezzo.general.pagination.last') }}"
                        first-text="{{ Lang::get('mezzo.general.pagination.first') }}" force-ellipses="true"
                        boundary-links="true" max-size="vm.pagination.size" total-items="vm.totalCount"
                        previous-text="<" next-text=">" ng-model="vm.currentPage" items-per-page="vm.perPage"
                        ng-change="vm.pageChanged()"></uib-pagination>
    </div>
    <div class="pull-right" style="margin-top: 28px; padding-right: 10px;">
        <small>@{{ vm.perPage * (vm.currentPage - 1) + 1 }} - @{{ vm.perPage * (vm.currentPage) + 1 }} / @{{ vm.totalCount }}</small>
    </div>
</div>