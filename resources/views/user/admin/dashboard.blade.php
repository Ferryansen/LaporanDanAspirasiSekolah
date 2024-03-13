@extends('layouts.mainpage')

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

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'Today', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">Today</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'This Month', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">This Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => 'This Year', 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">This Year</a></li>
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
                      <span class="text-success small pt-1 fw-bold">{{$persenLaporan}}%</span> <span class="text-muted small pt-2 ps-1">{{$statusLaporan}}</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'Today', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">Today</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'This Month', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">This Month</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => 'This Year', 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">This Year</a></li>
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
                      <span class="text-success small pt-1 fw-bold">{{$persenAspirasi}}%</span> <span class="text-muted small pt-2 ps-1">{{$statusAspirasi}}</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

                <div class="card-body">
                  <h5 class="card-title">Staff <span>| Now</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3" style="display:flex">
                      <h6>{{ $users->where('staffType_id', '!=', null)->count() }}</h6>
                      <span class="text-muted small pt-2 ps-1"> orang</span>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->


            <div class="col-lg-6">
              <div class="card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => 'Today', 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">Today</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => 'This Month', 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">This Month</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => 'This Year', 'aspirasiCategoryFilter' => $aspirasiCategoryFilter]) }}">This Year</a></li>
                </ul>
              </div>

              <div class="card-body pb-0">
                <h5 class="card-title">Laporan per kategori <span>| {{$laporanCategoryFilter}}</span></h5>

                <div id="laporanKategori" style="min-height: 300px;" class="echart"></div>
              </div>
            </div><!-- End Website Traffic -->
          </div>

          <div class="col-lg-6">
              <div class="card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => 'Today']) }}">Today</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => 'This Month']) }}">This Month</a></li>
                  <li><a class="dropdown-item" href="{{ route('dashboard.filtered', ['laporanCountFilter' => $laporanCountFilter, 'aspirasiCountFilter' => $aspirasiCountFilter, 'laporanCategoryFilter' => $laporanCategoryFilter, 'aspirasiCategoryFilter' => 'This Year']) }}">This Year</a></li>
                </ul>
              </div>

              <div class="card-body pb-0">
                <h5 class="card-title">Aspirasi per kategori <span>| {{$aspirasiCategoryFilter}}</span></h5>

                <div id="aspirasiKategori" style="min-height: 300px;" class="echart"></div>
              </div>
            </div><!-- End Website Traffic -->
          </div>


                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                // Assuming you have a variable $categories containing your categories
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
                                // Assuming you have a variable $categories containing your categories
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
                     
            <!-- Reports -->
            <!-- <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Reports <span>/Today</span></h5>

                  <div id="reportsChart"></div>
                </div>

              </div>
            </div> -->


            <!-- Top Selling -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="card-body pb-0">
                  <h5 class="card-title">Laporan terbaru</h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Judul</th>
                        <th scope="col">Tanggal dibuat</th>
                        <th scope="col">status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($reports->take(10) as $report)
                      <tr>
                        <td><a href="{{ route('student.reportDetail', $report->id) }}" class="text-primary fw-bold">{{ $report->name }}</a></td>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                        <td>{{ $report->status }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

         <!-- Budget Report -->
         <div class="card">
            <div class="card-body pb-0">
              <h5 class="card-title">Alokasi staff</h5>
                
              <div id="budgetChart" style="min-height: 400px;" class="echart"></div>
            </div>
          </div><!-- End Budget Report -->

          <!-- Recent Activity -->
          <div class="card">
            <div class="card-body pb-0">
                  <h5 class="card-title">Banyak di-upvote</h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Judul</th>
                        <th scope="col">Upvote</th>
                        <th scope="col">status</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php
                        $filteredAspirations = $aspirations->filter(function($aspiration) {
                            return $aspiration->upvote >= 1;
                        })->take(10);
                    @endphp
                      @foreach($filteredAspirations as $aspiration)
                      <tr>
                        <td><a href="{{ route('aspirations.details', ['aspirationId' => $aspiration->id]) }}" class="text-primary fw-bold">{{ $aspiration->name }}</a></td>
                        <td>{{ $aspiration->upvote }}</td>
                        <td>{{ $aspiration->status }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>

                </div>
          </div><!-- End Recent Activity -->

         

        </div><!-- End Right side columns -->

      </div>
    </section>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection

@section('script')
    {{-- Ini untuk line chart --}}
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

    {{-- Ini untuk status --}}
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        var staffTypes = @json($staffTypes);
        var radarIndicator = staffTypes.map(function (staffType) {
            return {
                name: staffType.name,
                max: 10 // You can adjust the scaling factor as needed
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
              // Assuming 'id' is the unique identifier for a report
              return laporanCategoryForFilter.some(function (filteredReport) {
                  return filteredReport.id === report.id;
              });
          });

          return {
              value: filteredReports.length,
              name: category.name
          };
          });
            echarts.init(document.querySelector("#laporanKategori")).setOption({
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
              // Assuming 'id' is the unique identifier for a report
              return aspirasiCategoryForFilter.some(function (filteredAspiration) {
                  return filteredAspiration.id === aspiration.id;
              });
          });

          return {
              value: filteredAspirations.length,
              name: category.name
          };
          });
            echarts.init(document.querySelector("#aspirasiKategori")).setOption({
            tooltip: {
                trigger: 'item'
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
                    fontWeight: 'bold'
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
@endsection