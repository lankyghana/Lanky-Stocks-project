<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName($pageTitle ?? '') }}</title>

    <link type="image/png" href="{{ siteFavicon() }}" rel="shortcut icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/line-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/prism.css') }}" rel="stylesheet">

    @stack('style-lib')

    <link href="{{ asset($activeTemplateTrue . 'css/style.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/custom.css') }}" rel="stylesheet">

    @stack('style')

    <link href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs("base_color") }}&secondColor={{ gs("secondary_color") }}" rel="stylesheet">
</head>

<body>
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7048530393932855"
         crossorigin="anonymous"></script>
    <!-- Auto ads 25 -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-7048530393932855"
         data-ad-slot="5585309021"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>

    <div class="preloader">
        <div class="loader-p"></div>
    </div>

    <div class="body-overlay"></div>

    <div class="sidebar-overlay"></div>

    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    @include('Template::partials.header')

    <section class="dashboard-section section-bg pb-60 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex gap-3 flex-wrap align-items-center mb-4">
                        <span class="sidebar_menu_btn d-lg-none d-inline-flex mt-2"><i class="fas fa-bars"></i></span>
                        <div class="dashboard-wrapper__header mb-0">
                            <h4 class="title mb-0">{{ __($pageTitle) }}</h4>
                        </div>
                    </div>
                    <div class="dashboard-wrapper">
                        <div class="dashboard">
                            <div class="dashboard__inner flex-wrap">
                                <!-- Sidebar Menu -->
                                @include('Template::partials.sidenav')
                                <!-- Dashboard Right -->
                                <div class="dashboard__right">
                                    <!-- Dashboard Body-->
                                    <div class="dashboard-body">
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/prism.js') }}"></script>

    @stack('script-lib')

    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @include('partials.notify')
    @stack('script')
</body>

</html>
