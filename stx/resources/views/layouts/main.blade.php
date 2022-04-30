<!DOCTYPE html>
<html lang="en">

<head>
    <title>Test</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <!-- <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon"> -->
    <!-- Google font--><link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">

    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">
    
    <!-- flag icon framework css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/pages/flag-icon/flag-icon.min.css') }}">
    
    <!-- Menu-Search css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/pages/menu-search/css/component.css') }}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables/datatables.min.css') }}">

    <link href="{{asset('plugins/ion.rangeslider/css/ion.rangeSlider.css')}}" rel="stylesheet" type="text/css" />
    @stack('css')

</head>

<body>
  <input type="hidden" name="base_path" id="base_path" value="{{ url('/') }}" />
    <!-- Pre-loader start -->
   <div class="theme-loader">
    <div class="ball-scale">
        <div class='contain'>
           <!--  <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div>
            <div class="ring"><div class="frame"></div></div> -->
        </div>
    </div>
</div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            @include('partials.header')

            <div class="pcoded-main-container">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Required Jquery -->
      <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
      
      <script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>

      <!-- <script type="text/javascript" src="{{ asset('popper.js/js/popper.min.js') }}"></script> -->
      <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
      <script src="{{ asset('bootstrap4/js/bootstrap.min.js') }}"></script>
       <script type="text/javascript" src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
      <script src="{{ asset('js/base.js') }}"></script>
     
      <!-- Custom js -->

      <script src="{{ asset('assets/js/pcoded-pk.min.js') }}"></script>
      
      <script src="{{ asset('assets/js/demo-12-pk.js') }}"></script>

      <script src="{{ asset('assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
      
      <!-- <script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script> -->
      <script type="text/javascript" src="{{ asset('assets/js/script-pk.js') }}"></script>
      <script src="{{asset('plugins/ion.rangeslider/js/ion.rangeSlider.min.js')}}" type="text/javascript"></script>
     
      @stack('js')

    </body>
</html>
