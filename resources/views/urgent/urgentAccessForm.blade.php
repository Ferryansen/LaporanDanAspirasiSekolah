<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Laporan Urgent</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  {{-- Fonts --}}
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  {{-- Other JS --}}
  <link href="{{ asset('web_assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('web_assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('web_assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('web_assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('web_assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('web_assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('web_assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  {{-- Main CSS --}}
  <link href="{{ asset('web_assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <span>SkolahKita</span>
                </a>
              </div>

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Yuk, Masukkan Kode Aksesnya!</h5>
                    <p class="text-center small">Kode aksesnya dapat kamu temukan di SMS</p>
                  </div>

                  <form class="row g-3 needs-validation" method="POST" action="{{ route('urgent.accessCheck', $urgentAccess) }}" novalidate>
                    @csrf

                    <div class="col-12">
                      <input type="text" name="accessCode" class="form-control @error('accessCode') is-invalid @enderror" value="{{ old('accessCode') }}" id="accessCode" placeholder="XXXXXX" maxlength="6">
                      @error('accessCode')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Masuk</button>
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
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  {{-- Other JS --}}
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('web_assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('web_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('web_assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('web_assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('web_assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('web_assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('web_assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('web_assets/vendor/php-email-form/validate.js') }}"></script>

  {{-- Main JS --}}
  <script src="{{ asset('web_assets/js/main.js') }}"></script>

</body>

</html>
