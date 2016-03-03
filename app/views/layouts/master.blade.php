<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>POS</title>

    <!-- CSS Dependencies -->
    {{HTML::style('assets/css/bootstrap.min.css')}}

</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          {{ link_to('/dashboard', 'POS System (' . Session::get('branch') . ")", array('class' => 'navbar-brand')) }}
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#" id="menu-toggle">Show Sidebar</a></li>
            <li>{{ link_to('/dashboard', 'Home') }}</li>
            <li class="divider"></li>
            <li>{{ link_to('/settings', 'Settings') }}</li>
            <li>{{ link_to('/acct/chpw', Auth::user() -> name) }}</li>
            <li>{{ link_to('/logout', 'Logout') }}</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar toggled">
          <ul class="nav nav-sidebar">
            <li>{{ link_to('/dashboard', 'Overview') }}</li>
            <li>{{ link_to('/items', 'Items') }}</li>
            <li>{{ link_to_route('sale', 'Sales') }}</li>
            <li>{{ link_to('/transfer', 'Stock Transfer') }}</li>
          </ul>
          <!-- <ul class="nav nav-sidebar">
            <li>{{ link_to('/oldsale', 'Old Sales') }}</li>
          </ul> -->
          <ul class="nav nav-sidebar">
            <li>{{ link_to_route('sales-report', 'Sales Report') }}</li>
            <li>{{link_to_route('inv-report', 'Inventory Report')}}</a></li>
            <li>{{link_to_route('trans-report', 'Transfers Report')}}</a></li>
            @if(Auth::user()->role <= 2)
                <li>{{link_to_route('get-invoice', 'Sales Invoices')}}</a></li>
                <li>{{link_to_route('get-dinvoice', 'Delivery Invoices')}}</a></li>
            @endif
          </ul>
          <!-- <ul class="nav nav-sidebar">
              <li>{{ link_to('/backup', 'Backup Database') }}</li>
              <li>{{ link_to('/backup', 'Restore Database') }}</li>
          </ul> -->
          @if(Auth::user()->role <= 2)
            <ul class="nav nav-sidebar">
              <li>{{ link_to_route('create-account', 'Create Account') }}</li>
            </ul>
          @endif
          @if(Auth::user()->role == 1)
            <ul class="nav nav-sidebar">
              <li>{{ link_to_route('view-logs', 'Logs') }}</li>
            </ul>
          @endif
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          @yield('content')
        </div>
      </div>
    </div>

    <!-- Assets at the end for faster page load -->

    {{HTML::script('assets/js/jquery-2.1.1.min.js')}}
    {{HTML::script('assets/js/bootstrap.min.js')}}
    {{HTML::script('assets/jqui/jquery-ui.min.js')}}
    {{HTML::script('assets/js/bootbox.min.js')}}
    {{HTML::script('assets/js/masterscript.js')}}

    {{HTML::style('assets/jqui/jquery-ui.min.css')}}
    {{HTML::style('assets/jqui/jquery-ui.theme.min.css')}}
    {{HTML::style('assets/css/style_master.css')}}

    @yield('assets')

    <div id="div-report"></div>

</body>
</html>
