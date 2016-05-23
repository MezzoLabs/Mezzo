<nav id="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-header clearfix">
            <div class="pull-left sidebar-logo-wrap"><img
                        src="/mezzolabs/mezzo/cockpit/img/mezzo/logo_sidebar.png"/></div>
            <div class="pull-left sidebar-logotext-wrap"><b>Mezzo</b></div>
            <div class="sidebar-pin-wrap"><i class="sidebar-pin fa fa-dot-circle-o"></i></div>
        </div>
        <div class="sidebar-content sidebar-padding">
            @foreach(mezzo()->moduleCenter()->visibleGroups() as $group )
                <h3>{{ $group->label() }}</h3>
                <ul class="nav-main">

                    @foreach($group->visibleModules() as $module )

                        <li class="{{ cockpit_html()->css('sidebar', $module) }}">
                            <a href="mezzo/{{ $module->uri() }}" data-mezzo-href-prevent>
                                <i class="{{ $module->options('icon') }}"></i>
                                <span>{{ $module->title() }}</span>

                                @if($module->pages()->filterVisibleInNavigation()->count() > 0)
                                    <span class="dropdown"></span>
                                @endif

                            </a>
                            <ul>
                                @foreach($module->pages()->filterAllowed()->sortByOrder() as $page)
                                    <li>
                                        <a @if(!$page->isVisibleInNavigation())) style="display:none;"
                                           @endif href="mezzo/{{ $page->uri() }}" data-mezzo-register-state
                                           data-action="{{ $page->action()}}" data-uri="{{ $page->uri() }}"
                                           data-page="{{ $page->name() }}"
                                           data-slug="{{ $page->slug() }}"
                                           data-mezzo-href-reload="{{ (!$page->isRenderedByFrontend())? 1 : 0 }}">
                                            <span>{{ $page->title() }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>


            @endforeach


        </div>
    </div>
</nav>