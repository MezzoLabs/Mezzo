<div id="view-overlay" class=""></div>
<div id="quickview" data-mezzo-quickview>
    <div class="quickview-heading">
        <h3>@yield('quickview_title')</h3>
        <button class="btn btn-xs btn-default btn-close" data-mezzo-quickview-close>
            <i class="ion-close"></i>
        </button>
    </div>
    <div class="quickview-content">
        @yield('quickview_content')
    </div>

</div>