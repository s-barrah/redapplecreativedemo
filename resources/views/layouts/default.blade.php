<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/ico" />

    <title>@yield('title')</title>


    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" media="screen, projection">

    <!-- Animate.css style -->
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom/styles.css') }}">


    <script type="text/javascript">var baseurl = "<?php echo url('/'); ?>";</script>
</head>
<body id="@yield('pageID')">

<div id="load"></div>

<div class="">
    <!-- Static navbar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">Red Apple Demo</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li>{{ Html::linkRoute('home', 'Home')  }}</li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>{{ Html::linkRoute('playlists', 'Playlists')  }}</li>
                    <li>{{ Html::linkRoute('tracks', 'Tracks')  }}</li>
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>
</div>

@yield('content')

<!-- JQuery scripts
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- JQuery local fallback-->
<script>window.jQuery || document.write('<script type="text/javascript" src="{{ asset('js/jquery-2.1.1/jquery.min.js') }}"><\/script>')</script>

<!-- Bootstrap core JavaScript CDN-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- Bootstrap JS local fallback-->
<script>if(typeof($.fn.modal) === 'undefined') document.write('<script type="text/javascript" src="{{ asset('js/bootstrap/bootstrap.min.js') }}"><\/script>')</script>

<!-- Bootstrap CSS local fallback-->
<div id="boostrapCssTest" class="hidden"></div>
<script>
    $(function () {
       if($('#boostrapCssTest').is(':visible')){
           $("head").prepend('<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"><link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}"><link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}"><link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">')
       }
    });
</script>

<!-- DataTables CDN-->
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>

<!-- DataTables local fallback-->
<script>window.jQuery || document.write('<script type="text/javascript" src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"><\/script><script type="text/javascript" src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"><\/script><script type="text/javascript" src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"><\/script>')</script>


<!-- Bootstrap Validator -->
<script type="text/javascript" src="{{ asset('js/bootstrap/bootstrapValidator.min.js') }}"></script>

<!-- Custom Scripts -->
<script src="{{ asset('js/custom/script.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/custom/dataTables.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/custom/functions.js') }}" type="text/javascript"></script>

</body>
</html>