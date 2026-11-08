<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo-meta :model="$seoModel ?? ($post ?? ($category ?? ($page ?? ($tag ?? null))))" />

    <!-- Fonts -->
    @php
        $primaryFont = theme_option('primary_font', setting('primary_font', 'Roboto'));
        $fontParam = urlencode($primaryFont) . ':ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap';
    @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ $fontParam }}" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ get_object_image(setting('favicon', '/storage/uploads/gst-logo-01.png')) }}">

    <style>
        body, h1, h2, h3, h4, h5, h6, input, button, select, textarea, .navigation, .site-header {
            font-family: '{{ $primaryFont }}', sans-serif !important;
        }
    </style>

    @if ($customCss = setting('custom_css'))
    <style>
        {!! $customCss !!}
    </style>
    @endif

    <!-- Google Tag Manager -->
    @if ($gtmId = setting('gtm_id', 'GTM-MHFCDPPW'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif
    <!-- End Google Tag Manager -->

    <!-- Theme Stylesheets -->
    <link rel="stylesheet" href="{{ theme_asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('plugins/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/odometer-theme-default.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/owl.transitions.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/style_custom.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('css/style.integration.css') }}">

    @if ($customHeader = setting('custom_header_scripts'))
        {!! $customHeader !!}
    @endif

    @stack('styles')
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    @if ($gtmId = setting('gtm_id', 'GTM-MHFCDPPW'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <!-- End Google Tag Manager (noscript) -->

    <div class="page-wrapper">
        <!-- start preloader -->
        <div class="preloader">
            <div class="loader">
                <div class="gear two">
                    <svg viewBox="0 0 100 100" fill="#131e4a">
                        <path d="M97.6,55.7V44.3l-13.6-2.9c-0.8-3.3-2.1-6.4-3.9-9.3l7.6-11.7l-8-8L67.9,20c-2.9-1.7-6-3.1-9.3-3.9L55.7,2.4H44.3l-2.9,13.6 c-3.3,0.8-6.4,2.1-9.3,3.9l-11.7-7.6l-8,8L20,32.1c-1.7,2.9-3.1,6-3.9,9.3L2.4,44.3v11.4l13.6,2.9c0.8,3.3,2.1,6.4,3.9,9.3 l-7.6,11.7l8,8L32.1,80c2.9,1.7,6,3.1,9.3,3.9l2.9,13.6h11.4l2.9-13.6c3.3-0.8,6.4-2.1,9.3-3.9l11.7,7.6l8-8L80,67.9 c1.7-2.9,3.1-6,3.9-9.3L97.6,55.7z M50,65.6c-8.7,0-15.6-7-15.6-15.6s7-15.6,15.6-15.6s15.6,7,15.6,15.6S58.7,65.6,50,65.6z"></path>
                    </svg>
                </div>
                <div class="gear three">
                    <svg viewBox="0 0 100 100" fill="#fd5f17">
                        <path d="M97.6,55.7V44.3l-13.6-2.9c-0.8-3.3-2.1-6.4-3.9-9.3l7.6-11.7l-8-8L67.9,20c-2.9-1.7-6-3.1-9.3-3.9L55.7,2.4H44.3l-2.9,13.6 c-3.3,0.8-6.4,2.1-9.3,3.9l-11.7-7.6l-8,8L20,32.1c-1.7,2.9-3.1,6-3.9,9.3L2.4,44.3v11.4l13.6,2.9c0.8,3.3,2.1,6.4,3.9,9.3 l-7.6,11.7l8,8L32.1,80c2.9,1.7,6,3.1,9.3,3.9l2.9,13.6h11.4l2.9-13.6c3.3-0.8,6.4-2.1,9.3-3.9l11.7,7.6l8-8L80,67.9 c1.7-2.9,3.1-6,3.9-9.3L97.6,55.7z M50,65.6c-8.7,0-15.6-7-15.6-15.6s7-15.6,15.6-15.6s15.6,7,15.6,15.6S58.7,65.6,50,65.6z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <!-- end preloader -->

        @include('layouts.partials.header')

        @yield('content')

        @include('layouts.partials.footer')
    </div>
    <!-- end of page-wrapper -->

    <!-- Floating Phone Button -->
    <style>
        #phone {
            -webkit-animation: PHONE-ANIMATION 3s infinite;
            animation: PHONE-ANIMATION 3s infinite;
            color: #58CD64;
            border: none; 
            visibility: visible; 
            bottom: 100px; 
            right: 25px; 
            position: fixed; 
            width: 50px; 
            z-index: 2147483644; 
            height: 50px;
        }
        #phone:before {
            font-family: FontAwesome;
            content: "\f095";
            font-size: 50px;
        }
        @keyframes PHONE-ANIMATION {
            0%, 100% { transform: rotate(0deg); }
            10%, 30%, 50%, 70%, 90% { transform: rotate(5deg); }
            20%, 40%, 60%, 80% { transform: rotate(-5deg); }
        }
    </style>
    <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('hotline_main', '+84963189981')) }}" aria-label="Gọi điện"><div id="phone"></div></a>

    <!-- Facebook Customer Chat Plugin -->
    @if ($fbPageId = setting('fb_page_id', '100367992250913'))
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml            : true,
                version          : 'v18.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <div class="fb-customerchat" attribution="setup_tool" page_id="{{ $fbPageId }}"></div>
    @endif

    <!-- Scripts -->
    <script src="{{ theme_asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ theme_asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ theme_asset('plugins/overflow-text.js') }}"></script>
    <script src="{{ theme_asset('plugins/jquery.parallax-1.1.3.js') }}"></script>
    <script src="{{ theme_asset('js/custom.min.js') }}"></script>
    <script src="{{ theme_asset('js/ripple.js') }}"></script>
    <script src="{{ theme_asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ theme_asset('js/jquery-plugin-collection.js') }}"></script>
    <script src="{{ theme_asset('js/script.js') }}"></script>

    @if (session()->has('success_msg'))
        <script type="text/javascript">
            swal('{{ __('Success') }}', "{{ session('success_msg') }}", 'success');
        </script>
    @endif

    @if (session()->has('error_msg'))
        <script type="text/javascript">
            swal('{{ __('Error') }}', "{{ session('error_msg') }}", 'error');
        </script>
    @endif

    @if ($customFooter = setting('custom_footer_scripts'))
        {!! $customFooter !!}
    @endif

    @stack('scripts')
</body>
</html>

