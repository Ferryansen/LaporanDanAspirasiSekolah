<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  {{-- Font Awesome --}}
  <link rel="stylesheet" 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('template_assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('template_assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('template_assets/css/style.css') }}" rel="stylesheet">
  @yield('css')

  {{-- Jquery --}}
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>

<body>
    <div class="container" style="max-width: 600px; margin-top: 8px">
        <div class="pagetitle">
            <h1>Kejadian Urgent</h1>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detail</h5>
                    <div style="display: flex; justify-content: space-between">
                        <p>No. Laporan</p>
                        <p><strong>{{ $urgentAccess->report->reportNo }}</strong></p>
                    </div>
                    <div style="display: flex; justify-content: space-between">
                        <p>Kejadian</p>
                        <p><strong>{{ $urgentAccess->report->name }}</strong></p>
                    </div>
                    <div style="display: flex; justify-content: space-between">
                        <p>Deskripsi</p>
                        <p><strong>{{ $urgentAccess->report->description }}</strong></p>
                    </div>
                    <div style="display: flex; justify-content: space-between">
                        <p>Pembuat Laporan</p>
                        <p><strong>{{ $urgentAccess->report->user->name }}</strong></p>
                    </div>
                    <div style="display: flex; justify-content: space-between">
                        <p>Tanggal Kejadian</p>
                        <p><strong>{{ \Carbon\Carbon::parse($urgentAccess->report->created_at)->format('d/m/Y') }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bukti</h5>
                    @foreach($urgentAccess->report->evidences as $evidence)
                        @if (strpos($evidence->image, 'ListImage') === 0)
                            <img style="max-width: 100%;" src="{{ asset('storage/'.$evidence->image) }}" alt="{{ $evidence->name }}">
                        @elseif (strpos($evidence->video, 'ListVideo') === 0)
                            <video style="max-width: 100%;" controls>
                                <source src="{{ asset('storage/'.$evidence->video) }}" type="{{ getVideoMimeType($evidence->video) }}">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>

        <?php
            function getVideoMimeType($videoPath) {
                $extension = pathinfo($videoPath, PATHINFO_EXTENSION);
                
                switch ($extension) {
                    case 'mp4':
                        return 'video/mp4';
                    case 'avi':
                        return 'video/avi';
                    case 'mov':
                        return 'video/quicktime';
                }
            }
        ?>
    </div>
  <main id="main" class="main">
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
  <script src="https://kit.fontawesome.com/f98710255c.js" crossorigin="anonymous"></script>

  <!-- Main JS File -->
  <script src="{{ asset('template_assets/js/main.js') }}"></script>
  <script>
        document.getElementById('search-input').addEventListener('click', function () {
            const searchModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            searchModal.show();
        });
  </script>
  @yield('script')
  @yield('js')

  {{-- Jquery --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</body>

</html>