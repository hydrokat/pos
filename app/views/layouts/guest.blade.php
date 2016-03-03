<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>POS System</title>
    {{HTML::style('assets/css/bootstrap.min.css',[], !App::isLocal())}}
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <!-- Assets at the end for faster page load -->

    {{HTML::script('assets/js/jquery-2.1.1.min.js',[], !App::isLocal())}}

    {{HTML::script('assets/js/script.js',[], !App::isLocal())}}
    {{HTML::style('assets/css/style_master.css',[], !App::isLocal())}}

    @yield('assets')
</body>
</html>
