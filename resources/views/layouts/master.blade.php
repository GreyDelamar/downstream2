<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <!-- <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap/dist/css/bootstrap.min.css"/>  -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link type="text/css" href="{{ URL::asset('css/app.css') }}" rel="stylesheet" >
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=7kbNm9OYmg">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=7kbNm9OYmg">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=7kbNm9OYmg">
    <link rel="manifest" href="/site.webmanifest?v=7kbNm9OYmg">
    <link rel="mask-icon" href="/safari-pinned-tab.svg?v=7kbNm9OYmg" color="#4a52e8">
    <link rel="shortcut icon" href="/favicon.ico?v=7kbNm9OYmg">
    <meta name="apple-mobile-web-app-title" content="Downstream">
    <meta name="application-name" content="Downstream">
    <meta name="msapplication-TileColor" content="#603cba">
    <meta name="theme-color" content="#8e93f1">
    <meta name="description" content="A free music collection and discovery service.">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    @yield('meta')

    @if(!Auth::guest())
    <meta name="xyz-token" content="{{ auth()->user()->api_token }}">
    @endif

    <title>Downstream</title>

    <style>

      .main-navbar {
        background-color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
      }

      .btn-primary {
        border-color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
        background-color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
      }

      .btn-outline-primary {
          color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
          background-color: transparent;
          background-image: none;
          border-color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
      }

      .btn-outline-primary:hover {
          color: {{ @Auth::user() ? Auth::user()->themeOption('secondary') : '#4a52e8' }} !important;
          background-color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
          border-color: {{ @Auth::user() ? Auth::user()->themeOption('secondary') : '#4a52e8' }} !important;
      }

      .footer {
        border-color: {{ @Auth::user() ? Auth::user()->themeOption('secondary') : '#4a52e8' }} !important;
        background-color: {{ @Auth::user() ? Auth::user()->themeOption('secondary') : '#4a52e8' }} !important;
      }

      .list-group-item.active {
        border-color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
        background-color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
      }

      .theme-primary-text {
        color: {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }} !important;
      }

      .card-header-playing {
        background: linear-gradient(123deg, {{ @Auth::user() ? Auth::user()->themeOption('primary') : '#4a52e8' }}, {{ @Auth::user() ? Auth::user()->themeOption('secondary') : '#4a52e8' }});
      }

      @if((@Auth::user() && Auth::user()->themeOption('id') == 'night_mode'))

      body {
        color: #FFFFFF;
        background-color: {{  Auth::user()->themeOption('background_color') }};
      }

      .main-navbar {  
          background-color: {{ @Auth::user() ? Auth::user()->themeOption('secondary') : '#4a52e8' }} !important;
      }

      .card {
        background-color: {{  Auth::user()->themeOption('secondary') }};
      }

      .jumbotron {
        display: none;
      }

      @endif
    </style>
  </head>

  <body>
    
    <div id="app">
        @include('layouts.includes.navbar')

        <div class="container-fluid">
          <!-- Vue Router View -->
          <router-view :key="$route.fullPath"></router-view>

          <!-- PHP Generated HTML -->
          <div id="hardContent">
            @yield('content')
          </div>

          <div id="modals" style="display: none;">
            <!-- Modals -->
            <b-modal :visible=false id="registerModal" :lazy=true :hide-header=true :hide-footer=true title="Register" title="Bootstrap-Vue">
              <h4>Please Register</h4>
              <p class="my-4">To collect items you must register first.</p>
              <p class="my-4">Your data is stored securely and never shared with 3rd parties.</p>
               <a href="/register" class="btn btn-info btn-lg btn-block">Register</a> 
            </b-modal>
          </div>
      </div>
      <!-- End App -->
      </div>

      <footer class=" text-muted text-center text-small">
        <div class="container">
          <div class="row justify-content-center">
              <div class="col-lg-6">
                  <ul class="list-inline">
                      <li class="list-inline-item"><a class="theme-primary-text" href="/privacy">Privacy</a></li>
                      <li class="list-inline-item"><a class="theme-primary-text" href="/tos">Terms</a></li>
                      <li class="list-inline-item"><a class="theme-primary-text" href="https://www.youtube.com/t/terms">YouTube's Terms of Service</a></li>
                      <li class="list-inline-item"><a class="theme-primary-text" href="/contact">Contact Us</a></li>
                    </ul>
                    <p class="mb-1">&copy; {{ date('Y') }} Downstream. All rights reserved.</p>       
                    <p class="text-muted">Season 1</p>                    
                    <a href="https://youtube.com"><img class="img-fluid" width="300" src="https://developers.google.com/youtube/images/developed-with-youtube-sentence-case-dark.png"/></a>
              </div>
          </div>
        </div>    
      </footer>
    
    <script>
    window.Laravel = <?php echo json_encode([
      'csrfToken' => csrf_token(),
    ]); ?>;

    window.config = {
      APP_LINK_URL: "<?php echo env('APP_LINK_URL'); ?>"
    };
    </script>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-111656856-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-111656856-1');
    </script>
  </body>
  
  
</html>
