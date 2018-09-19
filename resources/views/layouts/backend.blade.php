<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="_token" content="{{ csrf_token() }}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>@yield('title')</title>
        <!-- Bootstrap -->
        <link id="siteicon" rel="shortcut icon" type="images/favicon" href="{{ asset('img/backend/favicon.ico') }}">
        <link href="{{ asset('css/backend/bootstrap-datetimepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('css/backend/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/backend/font-awesome.css') }}" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet'>
        <link href="{{ asset('css/backend/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
        <link href="{{ asset('css/backend/fullcalendar.css') }}" rel="stylesheet">
        <link href="{{ asset('css/backend/fullcalendar.print.css') }}" rel="stylesheet" media='print'>
        <link href="{{ asset('css/backend/dataTables.bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/backend/select2.css') }}" rel="stylesheet">
        <link href="{{ asset('css/backend/intlTelInput.min.css') }}" rel="stylesheet">
    	<link href="{{ asset('css/backend/site.css') }}" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="preloader">
        	<div id="status">&nbsp;</div>
        </div>
        <nav class="navbar navbar-default">
            <!-- Brand and toggle get grouped for better mobile display -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#responsive_menu" aria-expanded="false"><i class="fa fa-reorder"></i><i class="fa fa-remove"></i></button>
            <a class="navbar-brand" href="{{ route('dashboard') }}"><img src="{{ asset('img/backend/logo.png') }}" class="img-responsive" alt="smspower logo"></a>
        	<div class="navbar_header">
                @if (!Auth::user()->hasRole('postpaid'))
                <div class="sms_balance">
                    <i class="fa fa-suitcase"></i>
                    <strong>Credit:</strong> {{ Auth::user()->creditbalance() }}
                </div>
                @endif
                <!-- Start Admin Links -->
                <div class="dropdown navbar-right">
                    <div class="adminMenuLinks">
                        <a class="dropdown-toggle" id="adminMenuLinks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-sun-o"></i> {{ Auth::user()->name }}<span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="adminMenuLinks">
                            <li><a href="{{ route('users.show', Auth::user()->slug) }}"><i class="fa fa-user"></i> View Profile</a></li>
                            <li><a href="{{ route('change-password') }}"><i class="fa fa-lock"></i> Change Password</a></li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-fw fa-sign-out"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- End Admin Links -->
        	</div>
        </nav>

        <!-- Start Navigation -->  
        @include('backend/partials/sidebar-menu')
        <!-- End Navigation -->  

        <!-- Start Container --> 
        @yield('content')
        <!-- End Container --> 

        <!-- Start Footer -->
        <div class="footer navbar-fixed-bottom">
            <footer>
                Copyright &copy; {{ date('Y') }} smspower.co.ke
                <div id="back_top"><i class="fa fa-chevron-up"></i></div>
            </footer>
        </div>
        <!-- End Footer -->  

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script type="text/javascript" src="{{ asset('js/backend/jquery.min.js') }}"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script type="text/javascript" src="{{ asset('js/backend/bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/bootstrap-datetimepicker.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/jquery.mCustomScrollbar.concat.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/moment.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/moment-timezone-with-data.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/fullcalendar.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/select2.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/jquery.dataTables.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/dataTables.bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/bootstrap-filestyle.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/jquery.smscharcount.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/intlTelInput.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/custom.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/backend/sms_counter.js') }}"></script>
        @stack('scripts')
        <script>
            $('div.alert').not('.alert-important').delay(5000).fadeOut(500);
        </script>
    </body>
</html>
