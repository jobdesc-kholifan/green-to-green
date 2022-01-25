<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Step 1 - Pendaftaran | Green to Green</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-success mb-2">
        <div class="card-header text-center">
            <b class="text-center" style="font-size: 25px;color: #28a745;">Registrasi</b>
        </div>
        <div class="card-body">
            <form action="{{ route(DBRoutes::authCompleteSignUp) }}" method="post" id="form-register">
                {{ csrf_field() }}
                <div class="form-group">Untuk melanjutkan proses registrasi masukan nama pengguna dan kata sandi</div>
                <div class="input-group mb-3">
                    <label for="input-fullname" class="d-none">Nama Lengkap</label>
                    <input type="text" name="full_name" id="input-fullname" class="form-control" placeholder="Nama Lengkap" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <label for="input-email" class="d-none"></label>
                        <input type="text" id="input-email" class="form-control" name="email" placeholder="Email" data-toggle="validation" data-url="{{ route(DBRoutes::authCheck) }}" data-label="email" data-field="email" required>
                        <div class="input-group-append">
                            <div class="input-group-text" data-action="icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                    <small class="text-danger" data-action="message"></small>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <label for="input-username" class="d-none"></label>
                        <input type="text" name="user_name" id="input-username" class="form-control" placeholder="Nama Pengguna" data-toggle="validation" data-url="{{ route(DBRoutes::authCheck) }}" data-label="nama pengguna" data-field="user_name" required>
                        <div class="input-group-append">
                            <div class="input-group-text" data-action="icon">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <small class="text-danger" data-action="message"></small>
                </div>
                <div class="input-group mb-3">
                    <label for="input-password" class="d-none"></label>
                    <input type="password" name="password" id="input-password" class="form-control" placeholder="Kata Sandi" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="social-auth-links text-center mt-2 mb-3">
                    <button type="submit" class="btn btn-block btn-success mb-1">
                        <i class="fa fa-sign-in-alt"></i>
                        <span>Daftar</span>
                    </button>
                    <a href="{{ route(DBRoutes::authSignIn) }}" class="btn btn-link">
                        <span>Sudah punya akun? Login</span>
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
<script src="{{ asset('dist/js/jquery-classes.js') }}"></script>
<script src="{{ asset('dist/js/actions.js') }}"></script>
<script type="text/javascript">

    const $formLogin = $('#form-register').formSubmit({
        beforeSubmit: function(form) {
            let isValid = FormComponents.validation.isValid();
            if(isValid)
                form.setDisabled(true);

            return isValid;
        },
        successCallback: function(res, form) {
            form.setDisabled(false);
            if(res.data.redirect !== undefined)
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

    FormComponents.validation.init();
</script>
</body>
</html>
