<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-signin-client_id" content="633918814298-v8d33u7o1g88cih18vakv32q2bbukrrk.apps.googleusercontent.com">
    <title>Log in | Green to Green</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/custom-style.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-success mb-2">
        <div class="card-header text-center">
            <b class="text-center" style="font-size: 25px;color: #28a745;">Green-to-Green</b>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Masukan akun anda untuk memulai</p>

            <form action="" method="post" id="form-login">
                {{ csrf_field() }}
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Nama Pengguna / Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div id="fb-root"></div>
                <div class="social-auth-links text-center mt-2 mb-3">
                    <button type="submit" class="btn btn-block btn-success mb-2">
                        <i class="fa fa-sign-in-alt"></i> Masuk
                    </button>
                    <div class="g-signin2 mb-2" data-width="320" data-longtitle="true" data-onsuccess="onSignIn"></div>
                    <a href="{{ route(DBRoutes::authTwitterSignIn) }}" class="btn btn-block bg-blue-twitter mb-2">
                        <i class="fa fa-twitter mr-1"></i>
                        <span>Login dengan Twitter</span>
                    </a>
                    <a href="{{ route(DBRoutes::authSignUp) }}" class="btn btn-block btn-primary mb-2">
                        <span>Daftar Sekarang</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('dist/js/load.modal.js') }}"></script>
<script src="{{ asset('dist/js/app.js') }}"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v12.0&appId=613494736572894&autoLogAppEvents=1" nonce="UkjR5eeE"></script>
<script type="text/javascript">
    const $formLogin = $('#form-login').formSubmit({
        beforeSubmit: function(form) {
            form.setDisabled(true);
        },
        successCallback: function(res, form) {
            form.setDisabled(false);
            if(res.data !== undefined && res.data.redirect !== undefined)
                window.location.href = res.data.redirect;

            AlertNotif.toastr.response(res);
        },
        errorCallback: function(xhr, form) {
            form.setDisabled(false);
            AlertNotif.adminlte.error(DBMessage.ERROR_SYSTEM_MESSAGE, {
                title: DBMessage.ERROR_SYSTEM_TITLE
            });
        }
    });

    function onSignIn(googleUser) {
        const profile = googleUser.getBasicProfile();
        $formLogin.setDisabled(true);

        ServiceAjax.post("{{ route(DBRoutes::authGoogleSignIn) }}", {
            data: {
                _token: "{{ csrf_token() }}",
                full_name: profile.getName(),
                email: profile.getEmail(),
            },
            success: (res) => {
                $formLogin.setDisabled(false);
                if(res.data.redirect !== undefined)
                    window.location.href = res.data.redirect;
            },
            error: () => {
                $formLogin.setDisabled(false);
                AlertNotif.adminlte.error(DBMessage.ERROR_SYSTEM_MESSAGE, {
                    title: DBMessage.ERROR_SYSTEM_TITLE
                });
            }
        })
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '613494736572894',
            cookie     : true,
            xfbml      : true,
            version    : 'v12.0'
        });

        // FB.getLoginStatus(function(response) {
        //     console.log(response);
        // });
        FB.AppEvents.logPageView();
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            console.log(response);
            statusChangeCallback(response);
        });
    }
</script>
</body>
</html>
