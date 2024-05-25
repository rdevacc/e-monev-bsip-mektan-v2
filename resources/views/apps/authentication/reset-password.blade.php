<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Reset Password | E-Monev BBPSI Mektan</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('admin/assets/img/logo-kementan.png') }}" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="flex-column justify-content-center text-center py-4">
                <div>
                    <img src="{{ asset('admin/assets/img/logo-kementan.png') }}" alt="logo-kementan" width="150" height="150">
                </div>
                <div class="pt-1 text-center">
                    <a href="#" class="logo d-flex align-items-center w-auto">
                        <span class="d-flex d-lg-block">E-Monev BBPSI Mektan</span>
                    </a>
                </div>
              </div><!-- End Logo -->
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-4">
                    <h5 class="card-title text-center pb-0 fs-4">Reset Your Password</h5>
                    <p class="text-center small">Enter your new password</p>
                  </div>
                  @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                  <form action="{{ route('password.update') }}" class="row" method="POST">
                    @csrf
                    <input type="hidden" name="token" id="token" value="{{$token}}">
                    <div class="row pb-2">
                      <div class="col-11">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            autocomplete="off" required>
                            @error('email')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                      </div>
                    </div>
                    <div class="row pb-2">
                      <div class="col-11">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="off" required>
                            @error('password')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                      </div>
                      <div class="col-1 d-flex justify-content-center align-items-end" id="password_wrap">
                        <button class="btn" type="button" id="password_btn">
                            <i class="bi bi-eye-fill" id="show_eye"></i>
                            <i class="bi bi-eye-slash-fill d-none" id="hide_eye"></i>
                        </button>
                      </div>
                    </div>
                    <div class="row pb-4">
                      <div class="col-11">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            autocomplete="off" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                      </div>
                      <div class="col-1 d-flex justify-content-center align-items-end">
                        <button class="btn" type="button">
                            <i class="bi bi-eye-fill" id="confirmed_show_eye"></i>
                            <i class="bi bi-eye-slash-fill d-none" id="confirmed_hide_eye"></i>
                        </button>
                      </div>
                  </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Submit New Password</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="credits">
                Designed by <a href="#">Muhammad Rizky A.M</a>
              </div>
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('admin/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Custom JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
      // Password Input
      $(document).ready(function(){
          // Password Input   
          $('#show_eye').on('click', function() {
              togglePassword();
          });
  
          $('#hide_eye').on('click', function() {
              togglePassword();
          });

          // Retype Password Input
          $('#confirmed_show_eye').on('click', function() {
              toggleRePassword();
          });

          $('#confirmed_hide_eye').on('click', function() {
              toggleRePassword();
          });
          
      });

      function togglePassword() {
          const passwordInput = $('#password');

          if (passwordInput[0].type === "password") {
              console.log('click show eye');
              $('#hide_eye').removeClass('d-none');
              $('#show_eye').addClass('d-none');
              $('#password').attr('type', 'text');
          } else {
              console.log('click hide eye');
              $('#show_eye').removeClass('d-none');
              $('#hide_eye').addClass('d-none');
              $('#password').attr('type', 'password');
          }
      }

      function toggleRePassword() {
          const passwordInput = $('#password_confirmation');

          if (passwordInput[0].type === "password") {
              console.log('click confirmed show eye');
              $('#confirmed_hide_eye').removeClass('d-none');
              $('#confirmed_show_eye').addClass('d-none');
              $('#password_confirmation').attr('type', 'text');
          } else {
              console.log('click confirmed hide eye');
              $('#confirmed_show_eye').removeClass('d-none');
              $('#confirmed_hide_eye').addClass('d-none');
              $('#password_confirmation').attr('type', 'password');
          }
      }
  </script>

  <!-- Template Main JS File -->
  <script src="{{ asset('admin/assets/js/main.js') }}"></script>

</body>

</html>