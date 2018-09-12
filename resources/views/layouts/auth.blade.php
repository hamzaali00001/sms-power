<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="_token" content="{{ csrf_token() }}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>@yield('title')</title>
        <!-- Bootstrap -->
        <link id="siteicon" rel="shortcut icon" type="images/favicon" href="{{ asset('img/backend/favicon.ico') }}">
        <link href="{{ asset('css/backend/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/backend/font-awesome.css') }}" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
    	<link href="{{ asset('css/backend/site.css') }}" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="sms_login">
        <div id="preloader">
        	<div id="status">&nbsp;</div>
        </div>
        <nav class="navbar navbar-default">
            <a class="navbar-brand" href="{{ route('login') }}"><img src="{{ asset('img/backend/logo.png') }}" class="img-responsive" alt="smspower logo"></a>
        </nav>
        <!-- Start Container -->
        @yield('content')
        <!-- End Container --> 

        <!-- Start Footer -->
        <div class="footer navbar-fixed-bottom">
            <footer>
                Copyright &copy; {{ date('Y') }} smspower.co.ke
            </footer>
        </div>
        <!-- End Footer -->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script type="text/javascript" src="{{ asset('js/backend/jquery.min.js') }}"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script type="text/javascript" src="{{ asset('js/backend/bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/custom.js') }}"></script>
        @yield('scripts')
    </body>
</html>