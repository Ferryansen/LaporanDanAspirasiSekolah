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
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block">SkolahKita</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
          <input type="text" id="search-input" class="form-control" placeholder="Search">
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('template_assets/img/profile-img.png') }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ Auth::user()->name }}</h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('myprofile') }}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span id="clickableLogout">Sign Out</span>
              </a>
            </li>

          </ul>
        </li>

      </ul>
    </nav>

  </header>

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      @if (Auth::user()->role == "headmaster" || Auth::user()->role == "staff")
        <li class="nav-item">
          <a class="nav-link " href="{{ route('dashboard') }}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
          </a>
        </li>
      @endif

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#report-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="report-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if (Auth::user()->role == "student")
            <li>
              <a href="{{ route('report.student.myReport') }}">
                <i class="bi bi-circle"></i><span>My Report</span>
              </a>
            </li>
          @else
            <li>
              <a href="{{ route('report.adminHeadmasterStaff.manageReport') }}">
                <i class="bi bi-circle"></i><span>Urus Laporan</span>
              </a>
            </li>
          @endif
        </ul>
      </li><!-- End Components Nav -->
      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#aspiration-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Aspirasi</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="aspiration-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if (Auth::user()->role == "student")
          <li>
            <a href="{{ route('aspirations.myAspirations') }}">
              <i class="bi bi-circle"></i><span>Aspirasi Saya</span>
            </a>
          </li>
          <li>
            <a href="{{ route('aspirations.publicAspirations') }}">
              <i class="bi bi-circle"></i><span>Aspirasi Publik</span>
            </a>
          </li>
          @else
          <li>
            <a href="{{ route('aspirations.manageAspirations') }}">
              <i class="bi bi-circle"></i><span>Urus Aspirasi</span>
            </a>
          </li>
            @if (Auth::user()->role == "admin")
              <li>
                <a href="{{ route('aspirations.reported') }}">
                  <i class="bi bi-circle"></i><span>Aspirasi Bermasalah</span>
                </a>
              </li>
              <li>
                <a href="{{ route('aspirations.suspended.list') }}">
                  <i class="bi bi-circle"></i><span>Daftar Suspend</span>
                </a>
              </li>
            @endif
          @endif
        </ul>
      </li><!-- End Components Nav -->

      @if (Auth::user()->role == "admin")
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-person"></i><span>Pengguna</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="users-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('manage.users.seeall') }}">
                <i class="bi bi-circle"></i><span>Urus Pengguna</span>
              </a>
            </li>
            <li>
              <a href="{{ route('admin.staffTypeList') }}">
                <i class="bi bi-circle"></i><span>Tipe Staf</span>
              </a>
            </li>
            <li>
              <a href="{{ route('categories.list') }}">
                <i class="bi bi-circle"></i><span>Kategori Pengerjaan</span>
              </a>
            </li>
          </ul>
        </li>
      @endif
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    @yield('breadcrumb')
    
    @yield('sectionPage')

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Which project is it?</h1>
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
                  
              <form action="{{ route('searching') }}">
                  <div class="modal-body">
                      <div class="form-floating mb-3">
                          <input type="text"
                              class="form-control bg-transparent"
                              id="name" placeholder="Judul Laporan/Aspirasi" name="name" value="{{ isset($searchParams['name']) ? $searchParams['name'] : '' }}">
                          <label class="text-primary" for="name">Judul Laporan/Aspirasi</label>
                      </div>

                      @php
                          $currentYear = strftime("%Y", time());
                      @endphp

                      <div class="form-floating mb-3">
                          <select class="form-select" id="floatingSelect" name="year" aria-label="Floating label select example">
                              <option value="1" {{ isset($searchParams['year']) && $searchParams['year'] == 1 ? 'selected' : '' }}>Lihat semua tahun!</option>
                              @for ($year = 2022; $year <= $currentYear; $year++)
                                  <option value="{{ $year }}" {{ isset($searchParams['year']) && $searchParams['year'] == $year ? 'selected' : '' }}>{{ $year }}</option>
                              @endfor
                          </select>
                          <label class="text-primary" for="floatingSelect">Tahun Pembuatan</label>
                      </div>
                      
                      <div class="form-floating mb-3">
                          <select class="form-select" id="data" name="data" aria-label="data">
                              <option value="1" >Lihat semua data!</option>
                              <option value="aspirations" {{ isset($searchParams['data']) && $searchParams['data'] == 'aspirations' ? 'selected' : '' }}>Aspirations</option>
                              <option value="reports" {{ isset($searchParams['data']) && $searchParams['data'] == 'reports' ? 'selected' : '' }}>Reports</option>
                          </select>
                          <label class="text-primary" for="data">Data</label>
                      </div>

                      <div class="form-floating mb-3">
                          <select class="form-select" id="status" name="status" aria-label="status">
                              <option value="1" >Lihat semua status!</option>
                              <option value="Freshly submitted" >Freshly submitted</option>
                              @if(Auth::user()->role != "student")
                                <option value="In review by staff" >In review by staff</option>
                                <option value="In review to headmaster" >In review to headmaster</option>
                              @endif
                              <option value="Approved" >Approved</option>
                              <option value="Rejected" >Rejected</option>
                              <option value="Cancelled" >Cancelled</option>
                              @if(Auth::user()->role != "student")
                                <option value="Monitoring process" >Monitoring process</option>
                              @endif
                              <option value="In Progress" >In Progress</option>
                              <option value="Completed">Completed</option>
                          </select>
                          <label class="text-primary" for="project-status">Status</label>
                      </div>
                  </div>

                  <div class="modal-footer border-0">
                      <button type="submit" class="w-100 btn btn-primary submit-search">Search</button>
                  </div>
              </form>
          </div>
      </div>
  </div>

  @if (session()->has("errorSearch"))
    <div class="alert alert-danger" id="error" role="alert">
        {{ session()->get("errorSearch") }}
    </div>
  @endif

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>XNOR</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Developed for thesis purposes
    </div>
  </footer><!-- End Footer -->

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
</body>

</html>