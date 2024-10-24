<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pengguna</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:wght@300;400;500;700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    
    <!-- Custom CSS -->
    <style>
        /* Mengatur latar belakang halaman dengan gambar */
        body {
            background-image: url('{{ asset('photos/bg3.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333; /* Warna teks default lebih gelap */
        }

        .login-box {
            width: 500px; /* Membesarkan ukuran box */
            background-color: rgba(255, 255, 255, 0.9); /* Overlay putih transparan */
            padding: 40px; /* Padding lebih besar */
            border-radius: 15px; /* Membuat sudut kotak lebih halus */
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Efek bayangan */
        }
        .login-box .card {
            border: none; /* Menghilangkan border dari card */
            box-shadow: none; /* Menghilangkan bayangan default */
            background: transparent; /* Menghilangkan latar belakang jika diperlukan */
        }
        .login-box .card-header {
            background-color: #823460; /* Warna ungu */
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            color: #ffffff; /* Warna teks tetap putih di header */
            font-weight: 700; /* Berat font yang tebal */
            font-size: 28px; /* Ukuran font yang cukup besar */
            letter-spacing: 1px;
            padding: 10px;
            text-align: center;
            border-bottom: none; /* Menghilangkan garis bawah default */
            margin-top: -10px; /* Menggeser header untuk menutup elemen di belakang */
            margin-bottom: -10px;
        }
        .login-box .card-body {
            padding: 20px; /* Mengurangi padding jika terlalu besar */
        }
        .login-box .card-header a {
            text-decoration: none; /* Menghapus garis bawah link */
            outline: none; /* Menghapus outline default */
            color: #ffffff; /* Tetap putih untuk teks di header */
        }
        .login-box .card-header a:focus {
            outline: none; /* Menghapus outline saat elemen dalam keadaan fokus */
        }
        .login-box .login-box-msg, .login-box a, .login-box p {
            color: #333; /* Warna teks abu-abu tua untuk visibilitas lebih baik */
        }
        .btn-primary {
            background-color: #823460; /* Warna ungu */
            border-color: #823460;
            color: white;
            transition: background-color 0.3s ease;
            font-weight: 600;
            border-radius: 25px; /* Membuat tombol lebih bulat */
        }
        .btn-primary:hover {
            background-color: #682b4b; /* Warna ungu lebih gelap saat hover */
            border-color: #682b4b;
        }
        .input-group .form-control {
            border-radius: 25px; /* Membulatkan tepi input field */
            padding-left: 20px;
            font-size: 16px;
            color: #333; /* Teks input warna lebih gelap */
        }
        .input-group-text {
            border-radius: 25px; /* Membuat ikon lebih halus */
            background-color: #f7f7f7;
            color: #823460; /* Warna ungu untuk ikon */
        }

        .icheck-primary {
            position: relative;
            padding-left: 40px; /* Ruang untuk kotak checkbox */
            cursor: pointer; /* Ubah kursor saat hover */
        }

        .icheck-primary input + label::before {
            content: '';
            position: absolute;
            left: 100;
            top: 0;
            width: 25px; /* Lebar kotak */
            height: 20px; /* Tinggi kotak */
            border: 6px solid #823460; /* Warna border kotak */
            background: #fff; /* Latar belakang putih */
            border-radius: 4px; /* Sudut kotak sedikit membulat */
            transition: background 0.3s, border-color 0.3s; /* Transisi untuk efek hover */
        }

        /* Pastikan ini ada untuk teks checkbox */
        .icheck-primary label {
            color: #5d5151; /* Warna teks label checkbox */
            font-size: 15px; /* Ukuran font label */
        }

        .icheck-primary input {
            background-color: #07889B;
        }
        .login-box a {
            color: #16a6bc; /* Menyelaraskan warna link dengan tema */
        }
        .login-box a:hover {
            color: #18869d; /* Efek hover pada link */
        }
        .login-box p {
            font-size: 16px;
            margin-top: 20px;
        }
    </style>

    </style>
</head>
<body>
    <!-- Login Box -->
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>Twinkle</b>POS</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Login untuk masuk ke Sistem</p>
                <form method="POST" action="{{ url('login') }}" method="POST" id="form-login">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <p>Belum punya akun? <a href="{{ url('register') }}">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#form-login").validate({
                rules: {
                    level_id: { required: true },
                    username: { required: true, minlength: 4, maxlength: 20 },
                    password: { required: true, minlength: 5, maxlength: 20 }
                },
                submitHandler: function(form) { // ketika valid, maka bagian yg akan dijalankan
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) { // jika sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(function() {
                                    window.location = response.redirect;
                                });
                            } else { // jika error
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
</body>
</html>
