<main id="content-container">
    @if (cockpit_html()->sectionExists('content-aside'))
    <div id="content-aside">
        @yield('content-aside')
    </div>
    @endif
    <div id="content-main">
        <div class="content">
            @yield('content')
        </div>
        @include('cockpit::layouts.default.content.footer')
    </div>
</main>
@include('cockpit::layouts.default.quickview')