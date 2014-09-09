<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    
    <!-- Assets at the end for faster page load -->

    {{HTML::script('assets/js/jquery-2.1.1.min.js')}}
    {{HTML::style('assets/css/bootstrap.min.css')}}

    {{HTML::script('assets/js/script.js')}}
    {{HTML::style('assets/css/style_master.css')}}

    @yield('assets')
</body>
</html>