@extends('layouts.mainPage')

@section('title')
    Admin Dashboard
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-8">
          <div class="row">

          @if($currUserRole == 'headmaster')

          {{-- ini laporan --}}
          <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'Today', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">Today</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'This Month', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'This Year', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Laporan <span>| {{$laporanCountFilter}}</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ count($laporanForFilter) }}</h6>
                      <span class="text-success small pt-1 fw-bold">{{number_format($persenLaporan, 2)}}%</span> <span class="text-muted small pt-2 ps-1">{{$statusLaporan}}</span>

                    </div>
                  </div>
                </div>

              </div>
            </div>

          {{-- ini aspirasi --}}

            <div class="col-xxl-6 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'Today', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">Today</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'This Month', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'This Year', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Aspirasi <span>| {{$aspirasiCountFilter}}</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-lightbulb"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ count($aspirasiForFilter) }}</h6>
                      <span class="text-success small pt-1 fw-bold">{{number_format($persenAspirasi, 2)}}%</span> <span class="text-muted small pt-2 ps-1">{{$statusAspirasi}}</span>

                    </div>
                  </div>
                </div>

              </div>
            </div>
          @else

          {{-- ini laporan --}}

              <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'Today', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">Today</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'This Month', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'This Year', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Laporan <span>| {{$laporanCountFilter}}</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ count($laporanForFilter) }}</h6>
                      <span class="text-success small pt-1 fw-bold">{{number_format($persenLaporan, 2)}}%</span> <span class="text-muted small pt-2 ps-1">{{$statusLaporan}}</span>

                    </div>
                  </div>
                </div>

              </div>
            </div>

          {{-- ini aspirasi --}}

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'Today', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">Today</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'This Month', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'This Year', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Aspirasi <span>| {{$aspirasiCountFilter}}</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-lightbulb"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ count($aspirasiForFilter) }}</h6>
                      <span class="text-success small pt-1 fw-bold">{{number_format($persenAspirasi, 2)}}%</span> <span class="text-muted small pt-2 ps-1">{{$statusAspirasi}}</span>

                    </div>
                  </div>
                </div>

              </div>
            </div>

          {{-- ini konsultasi --}}

            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

              <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'Today', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => 'Today']) }}">Today</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'This Month', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => 'This Month']) }}">This Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'This Year', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => 'This Year']) }}">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Konsultasi <span>| {{$konsultasiFilter}}</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-chat-text"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $konsultasiCount }}</h6>
                      <span class="text-success small pt-1 fw-bold">{{number_format($persenKonsultasi, 2)}}%</span> <span class="text-muted small pt-2 ps-1">{{$statusKonsultasi}}</span>
                    </div>
                  </div>

                </div>
              </div>

            </div>
          @endif

          {{-- ini status --}}

            <div class="col-lg-12">
              <div class="card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => 'Today', 'konsultasiFilter' => $konsultasiFilter]) }}">Today</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => 'This Month', 'konsultasiFilter' => $konsultasiFilter]) }}">This Month</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => 'This Year', 'konsultasiFilter' => $konsultasiFilter]) }}">This Year</a></li>
                </ul>
              </div>

              <div class="card-body pb-0">
                <h5 class="card-title">Status <span>| {{$statusFilter}}</span></h5>

                <div id="aspirationStatusChart" style="min-height: 300px;" class="echart"></div>
              </div>
            </div>
          </div>

          {{-- xxxxxxxxxxxxxxxxxxxxxxxxxxx --}}
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var labels = @json($categories->pluck('name')->toArray());
                                var data = @json($categories->map(function ($category) {
                                    return $category->reports->count();
                                })->toArray());

                                new Chart(document.querySelector('#barChart'), {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Jumlah laporan',
                                            data: data,
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(255, 159, 64, 0.2)',
                                                'rgba(255, 205, 86, 0.2)',
                                                'rgba(75, 192, 192, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                                'rgba(153, 102, 255, 0.2)',
                                                'rgba(201, 203, 207, 0.2)'
                                            ],
                                            borderColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(255, 159, 64)',
                                                'rgb(255, 205, 86)',
                                                'rgb(75, 192, 192)',
                                                'rgb(54, 162, 235)',
                                                'rgb(153, 102, 255)',
                                                'rgb(201, 203, 207)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var labelss = @json($categories->pluck('name')->toArray());
                                var datas = @json($categories->map(function ($category) {
                                    return $category->aspirations->count();
                                })->toArray());

                                new Chart(document.querySelector('#aspirasi'), {
                                    type: 'bar',
                                    data: {
                                        labels: labelss,
                                        datasets: [{
                                            label: 'Jumlah aspirasi',
                                            data: datas,
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(255, 159, 64, 0.2)',
                                                'rgba(255, 205, 86, 0.2)',
                                                'rgba(75, 192, 192, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                                'rgba(153, 102, 255, 0.2)',
                                                'rgba(201, 203, 207, 0.2)'
                                            ],
                                            borderColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(255, 159, 64)',
                                                'rgb(255, 205, 86)',
                                                'rgb(75, 192, 192)',
                                                'rgb(54, 162, 235)',
                                                'rgb(153, 102, 255)',
                                                'rgb(201, 203, 207)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        },
                                        legend: {
                                            display: false
                                        }
                                    }
                                });
                            });
                        </script>

          {{-- xxxxxxxxxxxxxxxxxxxxxxxxxxx --}}

          {{-- ini laporan terbaru --}}
                     
            <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="card-body pb-0">
                  <h5 class="card-title">Laporan terbaru</h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Judul</th>
                        <th scope="col">Tanggal dibuat</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($reports->take(10) as $report)
                      <tr>
                        <td><a href="{{ route('student.reportDetail', $report->id) }}" class="text-primary fw-bold">{{ $report->name }}</a></td>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                        @if ($report->status == "Approved")
                          <td>Disetujui</td>
                        @elseif ($report->status == "Rejected")
                          <td>Ditolak</td>  
                        @elseif ($report->status == "Cancelled")
                          <td>Dibatalkan</td>
                        @elseif ($report->status == "Freshly submitted")
                          <td>Terkirim</td>
                        @elseif ($report->status == "In review by staff")
                          <td>Sedang ditinjau</td>
                        @elseif ($report->status == "Request Approval")
                          <td>Menunggu persetujuan dari atasan</td>
                        @elseif ($report->status == "In Progress")
                          <td>Sedang ditindaklanjuti</td>
                        @elseif ($report->status == "Monitoring process")
                          <td>Dalam pemantauan</td>
                        @elseif ($report->status == "Completed")
                          <td>Selesai</td>
                        @elseif ($report->status == "Closed")
                          <td>Ditutup</td>  
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>

                </div>

              </div>
            </div>

          </div>
        </div>



        <div class="col-lg-4">

        {{-- ini laporan per kategori --}}

        <div class="card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => 'Today', 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">Today</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => 'This Month', 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Month</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => 'This Year', 'aspirasiCategoryFilter' => $aspirasiCategoryFilter, 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Year</a></li>
                </ul>
              </div>

              <div class="card-body pb-0">
                <h5 class="card-title">Laporan per kategori <span>| {{$laporanCategoryFilter}}</span></h5>

                <div id="laporanKategori" style="min-height: 450px; margin-bottom: -5rem" class="echart"></div>
              </div>
            </div>

        {{-- ini aspirasi per kategori --}}

         <div class="card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => 'Today', 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">Today</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => 'This Month', 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Month</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => 'This Year', 'statusFilter' => $statusFilter, 'konsultasiFilter' => $konsultasiFilter]) }}">This Year</a></li>
                </ul>
              </div>

              <div class="card-body pb-0">
                <h5 class="card-title">Aspirasi per kategori <span>| {{$aspirasiCategoryFilter}}</span></h5>

                <div id="aspirasiKategori" style="min-height: 450px; margin-bottom: -5rem;" class="echart"></div>
              </div>
            </div>

        {{-- ini aspirasi disukai --}}

        
          <div class="card">
            <div class="card-body pb-0">
                  <h5 class="card-title">Aspirasi yang disukai</h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Judul</th>
                        <th scope="col">Like</th>
                        <th scope="col">status</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php
                      $filteredAspirations = $aspirations->filter(function($aspiration) {
                          return $aspiration->likes_count >= 1;
                      });

                      $sortedAspirations = $filteredAspirations->sortByDesc('likes_count');

                      $topAspirations = $sortedAspirations->take(10);
                    @endphp
                      @foreach($topAspirations as $aspiration)
                      <tr>
                        <td>{{ $aspiration->name }}</a></td>
                        <td>{{ $aspiration->reactions()->where('reaction', 'like')->count()}}</td>
                        @if ($aspiration->status == 'Freshly submitted')
                          <td>Terkirim</td>
                          @elseif ($aspiration->status == 'In review')
                          <td>Sedang ditinjau</td>
                          @elseif ($aspiration->status == 'Request Approval')
                          <td>Menunggu persetujuan</td>
                          @elseif ($aspiration->status == 'Approved')
                          <td>Disetujui</td>
                          @elseif ($aspiration->status == 'Rejected')
                          <td>Ditolak</td>
                          @elseif ($aspiration->status == 'In Progress')
                          <td>Sedang ditindaklanjuti</td>
                          @elseif ($aspiration->status == 'Monitoring')
                          <td>Dalam pemantauan</td>
                          @elseif ($aspiration->status == 'Completed')
                          <td>Selesai</td>
                          @elseif ($aspiration->status == "Closed")
                          <td>Ditutup</td>  
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>

                </div>
          </div>
        </div>

      </div>
    </section>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection

@section('script')

    {{-- xxxxxxxxxxxxxx--}}
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#reportsChart"), {
          series: [{
            name: 'Sales',
            data: [31, 40, 28, 51, 42, 82, 56],
          }, {
            name: 'Revenue',
            data: [11, 32, 45, 32, 34, 52, 41]
          }, {
            name: 'Customers',
            data: [15, 11, 32, 18, 9, 24, 11]
          }],
          chart: {
            height: 350,
            type: 'area',
            toolbar: {
              show: false
            },
          },
          markers: {
            size: 4
          },
          colors: ['#4154f1', '#2eca6a', '#ff771d'],
          fill: {
            type: "gradient",
            gradient: {
              shadeIntensity: 1,
              opacityFrom: 0.3,
              opacityTo: 0.4,
              stops: [0, 90, 100]
            }
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'smooth',
            width: 2
          },
          xaxis: {
            type: 'datetime',
            categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
          },
          tooltip: {
            x: {
              format: 'dd/MM/yy HH:mm'
            },
          }
        }).render();
      });
    </script>

    {{-- xxxxxxxxxxxxxxxxxxxxxxxxxxxx --}}
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        var staffTypes = @json($staffTypes);
        var radarIndicator = staffTypes.map(function (staffType) {
            return {
                name: staffType.name,
                max: 10
            };
        });


        var datas = @json($staffTypes->map(function ($staffType) {
            return optional($staffType->users)->count() ?? 0;
        })->toArray());

          var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
          legend: {
              data: ['Allocated Budget', 'Actual Spending']
          },
          radar: {
              shape: 'circle',
              indicator: radarIndicator
          },
          series: [{
              name: 'Budget vs spending',
              type: 'radar',
              data: [{
                  value: datas,
                  name: 'Staff'
              },
              ]
          }]
          });
      });
    </script>

    {{-- Ini untuk Pie Chart --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        var categories = @json($categories);
        var laporanCategoryForFilter = @json($laporanCategoryForFilter);

        var pieChartData = categories.map(function (category) {
            var filteredReports = category.reports.filter(function (report) {
                return laporanCategoryForFilter.some(function (filteredReport) {
                    return filteredReport.id === report.id;
                });
            });

            return {
                value: filteredReports.length,
                name: category.name
            };
        });

        var totalReports = pieChartData.reduce((sum, category) => sum + category.value, 0);

        echarts.init(document.querySelector("#laporanKategori")).setOption({
            tooltip: {
                trigger: 'item',
                formatter: function (params) {
                    var percentage = ((params.value / totalReports) * 100).toFixed(2);
                    return `${params.seriesName}<br>${params.name} ${params.value} (${percentage}%)`;
                }
            },
            legend: {
                top: '5%',
                left: 'center'
            },
            series: [{
                name: 'Jumlah laporan',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '18',
                        fontWeight: 'bold',
                        formatter: function (params) {
                            var percentage = ((params.value / totalReports) * 100).toFixed(2);
                            return `${params.name} ${params.value} (${percentage}%)`;
                        }
                    }
                },
                labelLine: {
                    show: false
                },
                data: pieChartData
            }]
            
        });

    });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
    var categories = @json($categories);
    var aspirasiCategoryForFilter = @json($aspirasiCategoryForFilter);

    var pieChartData = categories.map(function (category) {
        var filteredAspirations = category.aspirations.filter(function (aspiration) {
            return aspirasiCategoryForFilter.some(function (filteredAspiration) {
                return filteredAspiration.id === aspiration.id;
            });
        });

        return {
            value: filteredAspirations.length,
            name: category.name
        };
    });

    var totalAspirations = pieChartData.reduce((sum, category) => sum + category.value, 0);

    echarts.init(document.querySelector("#aspirasiKategori")).setOption({
        tooltip: {
            trigger: 'item',
            formatter: function (params) {
                var percentage = ((params.value / totalAspirations) * 100).toFixed(2);
                return `${params.seriesName}<br>${params.name} ${params.value} (${percentage}%)`;
            }
        },
        legend: {
            top: '5%',
            left: 'center'
        },
        series: [{
            name: 'Jumlah aspirasi',
            type: 'pie',
            radius: ['40%', '70%'],
            avoidLabelOverlap: false,
            label: {
                show: false,
                position: 'center'
            },
            emphasis: {
                label: {
                    show: true,
                    fontSize: '18',
                    fontWeight: 'bold',
                    formatter: function (params) {
                        var percentage = ((params.value / totalAspirations) * 100).toFixed(2);
                        return `${params.name} ${params.value} (${percentage}%)`;
                    }
                }
            },
            labelLine: {
                show: false
            },
            data: pieChartData
            }]
        });
    });
    </script>

{{-- xxxxxxxxxxxxxxxxxxxxxxxxxxx --}}

<script>
  document.addEventListener("DOMContentLoaded", () => {
    var reports = @json($reports);

    var statusOptions = ["Not Checked","canceled", "completed", "rejected", "approved", "in review"];

    function createChartData() {
      return statusOptions.map(status => ({
        name: status,
        value: reports.filter(report => report.status === status).length
      }));
    }

    var chart = echarts.init(document.querySelector("#reportStatusChart"));

    var option = {
      tooltip: {
        trigger: 'item'
      },
      legend: {
        top: '5%',
        left: 'center'
      },
      series: [{
        name: 'Jumlah laporan',
        type: 'pie',
        radius: ['40%', '70%'],
        avoidLabelOverlap: false,
        label: {
          show: false,
          position: 'center'
        },
        emphasis: {
          label: {
            show: true,
            fontSize: '18',
            fontWeight: 'bold'
          }
        },
        labelLine: {
          show: false
        },
        data: createChartData()
      }]
    };

    chart.setOption(option);

    document.querySelectorAll(".dropdown-item").forEach(item => {
      item.addEventListener("click", function(event) {
        event.preventDefault(); 
        var filter = this.textContent.trim();
        if (filter === "Today") {
        } else if (filter === "This Month") {
        } else if (filter === "This Year") {
        }
        chart.setOption({
          series: [{
            data: createChartData()
          }]
        });
      });
    });
  });
    </script>

{{-- ini untuk status --}}

<script>
  document.addEventListener("DOMContentLoaded", () => {
    var aspirations = @json($aspirasiStatusFilter);
    var reportsObj = @json($laporanStatusFilter);
    var currUserRole = @json($currUserRole);
    var reports = Object.values(reportsObj);
    var statusOptions;
    var statusOptionsView;

    var statusOptionsView = [];

    if (currUserRole === "headmaster") {

      var statusOptions = [
      'Request Approval',
      'In Progress',
      'Completed',
      'Rejected',
      'Closed'
    ];

      statusOptionsView = [
        'Menunggu persetujuan',
        'Sedang diproses',
        'Selesai',
        'Ditolak',
        'Ditutup'
      ];
    } else {
      var statusOptions = [
      'Freshly submitted',
      'In review',
      'Request Approval',
      'Approved',
      'In Progress',
      'Monitoring',
      'Completed',
      'Rejected',
      'Closed'
    ];

      statusOptionsView = [
        'Terkirim',
        'Sedang ditinjau',
        'Menunggu persetujuan',
        'Disetujui',
        'Sedang diproses',
        'Dalam pemantauan',
        'Selesai',
        'Ditolak',
        'Ditutup'
      ];
    }

    function mapStatus(status){
      if(status.includes('In review')){
        return 'In review';
      }
      else if(status.includes('Monitoring')){
        return 'Monitoring';
      }

      return status;
    };

    function createChartDataAsp() {
      return statusOptions.map(status => ({
        name: status,
        value: aspirations.filter(aspiration => aspiration.status === status).length
      }));
    }

    function createChartDataRep() {
      return statusOptions.map(status => ({
        name: status,
        value: reports.filter(report => mapStatus(report.status) === status).length
      }));
    }

    var chartDataAsp = createChartDataAsp();
    var chartDataRep = createChartDataRep();

    var chart = echarts.init(document.querySelector("#aspirationStatusChart"));

    var option = {
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow'
        }
      },
      legend: {
        data: ['Aspirasi', 'Laporan']
      },
      grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
      },
      xAxis: {
        type: 'value',
        boundaryGap: [0, 0.01]
      },
      yAxis: {
        type: 'category',
        data: statusOptionsView
      },
      series: [
        {
          name: 'Aspirasi',
          type: 'bar',
          data: chartDataAsp.map(item => item.value)
        },
        {
          name: 'Laporan',
          type: 'bar',
          data: chartDataRep.map(item => item.value)
        }
      ]
    };

    chart.setOption(option);
  });
</script>

@endsection