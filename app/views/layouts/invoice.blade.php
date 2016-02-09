<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <!-- Assets at the end for faster page load -->

    {{HTML::script('assets/js/jquery-2.1.1.min.js')}}
    {{HTML::script('assets/js/bootstrap.min.js')}}
    {{HTML::script('assets/jqui/jquery-ui.min.js')}}
    {{HTML::script('assets/js/bootbox.min.js')}}
    {{HTML::script('assets/js/masterscript.js')}}
    
    {{HTML::style('assets/css/bootstrap.min.css')}}
    {{HTML::style('assets/jqui/jquery-ui.min.css')}}
    {{HTML::style('assets/jqui/jquery-ui.theme.min.css')}}
    {{HTML::style('assets/css/style_master.css')}}

    @yield('assets')
</body>
</html>
