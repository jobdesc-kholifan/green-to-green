<?php

use App\Helpers\Collections\Users\UserCollection;
use App\Models\Masters\Config;
use App\Models\Masters\User;

$guardsAdmin = [DBTypes::roleAdministrator, DBTypes::roleSuperuser];

$user = new UserCollection(User::foreignWith(null)
    ->with([
        'role' => function($query) {
            Config::foreignWith($query);
        }
    ])
    ->addSelect('role_id')
    ->find(auth()->id())
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-signin-client_id" content="633918814298-v8d33u7o1g88cih18vakv32q2bbukrrk.apps.googleusercontent.com">
    <title>Green to Green</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/custom-style.css') }}">
    @stack('script-header')
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <span class="brand-text font-weight-light">Green To Green</span>
            </a>
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <ul class="navbar-nav">
                    @if(in_array($user->getRole()->getSlug(), $guardsAdmin))
                    <li class="nav-item">
                        <a href="{{ route(DBRoutes::administrator) }}" class="nav-link">Admin Page</a>
                    </li>
                    @endif
                </ul>
            </div>
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="javascript:Auth.signOut()">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="content-wrapper">
        @yield('content')
    </div>

    @include('skins.footer')
</div>
<div class="g-signin2 d-none" data-width="320" data-longtitle="true" data-onsuccess="onSignIn"></div>

<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('dist/js/custom-select.js') }}"></script>
<script src="{{ asset('dist/js/load.modal.js') }}"></script>
<script src="{{ asset('dist/js/jquery-classes.js') }}"></script>
<script src="{{ asset('dist/js/app.js') }}"></script>
@stack('script-footer')
<script type="text/javascript">
    Auth.routes.logout = "{{ route(DBRoutes::authLogout) }}";
</script>
</body>
</html>
