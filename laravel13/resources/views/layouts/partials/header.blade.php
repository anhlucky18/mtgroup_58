<!-- Start header -->
<header id="header" class="site-header header-style-1">
    <nav class="navigation navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="open-btn">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ get_object_image(theme_option('logo', setting('theme-ripple-logo', '/storage/uploads/gst-logo-01.png'))) }}" alt="{{ setting('site_title', 'Minh Thành Group') }}" style="max-height: {{ setting('logo_height', '50') }}px; width: auto;" height="{{ setting('logo_height', '50') }}">
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse navigation-holder">
                <button class="close-navbar"><i class="ti-close"></i></button>
                <x-navigation-menu location="main-menu" />
            </div><!-- end of nav-collapse -->
            <div class="search-contact">
                <div class="header-search-area" style="display: inline-flex;">
                    <a class="call" id="call-mtg" href="tel:{{ preg_replace('/[^0-9+]/', '', setting('hotline_main', '+84963189981')) }}">
                        <i class="fi flaticon-call"></i>
                        <h5 id="phone-number">{{ setting('hotline_main', '+84-963189981') }}</h5>
                    </a>
                    <div class="header-search-form">
                        <form class="form quick-search" action="{{ route('public.search') }}" method="GET">
                            <div>
                                <input type="text" name="q" class="form-control" placeholder="{{ __('Type to search...') }}" value="{{ request('q') }}">
                            </div>
                            <button type="submit" class="btn"><i class="ti-search"></i></button>
                        </form>
                        <div class="search-result"></div>
                    </div>
                    <div>
                        <button class="btn open-btn"><i class="ti-search"></i></button>
                    </div>
                </div>
                <div class="language">
                    <div class="switch-lang">
                        <x-language-switcher />
                    </div>
                </div>
            </div>
        </div><!-- end of container -->
    </nav>
</header>
<!-- end of header -->

