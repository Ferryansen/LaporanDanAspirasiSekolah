<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Halaman Login</title>
  <link rel="icon" href="{{ asset('favicon_io/favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ asset('favicon_io/favicon-16x16.png') }}" sizes="192x192" type="image/png">
  <link rel="icon" href="{{ asset('favicon_io/favicon-32x32.png') }}" sizes="32x32" type="image/png">
  <link rel="icon" href="{{ asset('favicon_io/android-chrome-192x192.png') }}" sizes="192x192" type="image/png">
  <link rel="icon" href="{{ asset('favicon_io/android-chrome-512x512.png') }}" sizes="512x512" type="image/png">
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('template_assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('template_assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                  <img src="{{ asset('SkolahKitaLogo.png') }}" alt="SkolahKita Logo" style="max-height: 50px;">
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Yuk, Login ke Akunmu!</h5>
                    <p class="text-center small">Isi email dan password-mu yaa</p>
                  </div>

                  <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Email</label>
                      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Cookie::get('CookieEmail', '')) }}" id="yourEmail">
                      @error('email')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ Cookie::get('CookieEmail', '') }}" id="yourPassword">
                      @error('password')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>

                  </form>

                </div>
              </div>

              <div class="credits">
                &copy; Copyright <strong><span>XNOR</span></strong>. All Rights Reserved
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('template_assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('template_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template_assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('template_assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('template_assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('template_assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('template_assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('template_assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('template_assets/js/main.js') }}"></script>

</body>

</html>