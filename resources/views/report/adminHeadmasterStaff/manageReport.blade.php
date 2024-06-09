@extends('layouts.mainpage')

@section('title')
    Kelola Laporan
@endsection

@section('css')
  <style>

    .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
    table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .container {
            text-align: center; /* Center the text within the td */
            color: dimgray;
        }
  </style>
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Kelola Laporan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('report.adminHeadmasterStaff.manageReport') }}">Laporan</a></li>
      <li class="breadcrumb-item active">Kelola Laporan</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
@if(session('successMessage'))
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<section class="section">
  <div class="row">
    <div class="col-lg-12" style="margin-bottom: 0;">

      <div class="card">
        <div class="card-body" style="margin-top: 24px">
          @if (Auth::user()->role == "staff")
            <div class="row">
              <div class="col-7 col-md-3">
                @php
                    $selectedStatus = session('selected_status', 'Semua status');
                @endphp
                <select class="form-select" aria-label="Default select example" name="categoryStaffType" required onchange="window.location.href=this.value;">
                  <option value="{{ route('report.adminHeadmasterStaff.manageReport') }}" {{ $selectedStatus == 'Semua status' ? 'selected' : '' }}>Semua status</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Freshly submitted']) }}" {{ $selectedStatus == 'Freshly submitted' ? 'selected' : '' }}>Terkirim</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'In review by staff']) }}" {{ $selectedStatus == 'In review by staff' ? 'selected' : '' }}>Sedang ditinjau</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Request Approval']) }}" {{ $selectedStatus == 'Request Approval' ? 'selected' : '' }}>Menunggu persetujuan dari atasan</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Approved']) }}" {{ $selectedStatus == 'Approved' ? 'selected' : '' }}>Disetujui</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Rejected']) }}" {{ $selectedStatus == 'Rejected' ? 'selected' : '' }}>Ditolak</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Cancelled']) }}" {{ $selectedStatus == 'Cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Monitoring process']) }}" {{ $selectedStatus == 'Monitoring process' ? 'selected' : '' }}>Dalam pemantauan</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'In Progress']) }}" {{ $selectedStatus == 'In Progress' ? 'selected' : '' }}>Sedang ditindaklanjuti</option>
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Completed']) }}" {{ $selectedStatus == 'Completed' ? 'selected' : '' }}>Selesai</option>
                </select>
              </div>
            </div>
            
            <br>

          @else
          <div class="row d-flex justify-content-between align-items-center">
            <div class="col-7 col-md-auto d-flex align-items-center mb-3 mb-md-0" style="margin-top: 0.5rem">
              <select class="form-select w-100 w-md-auto" aria-label="Default select example" name="categoryStaffType" required onchange="window.location.href=this.value;">
                @php
                    $lainnyaCategory = null;
                    $selectedCategory = session('selected_category', 'Semua kategori');
                @endphp
                <option value="{{ route('report.adminHeadmasterStaff.manageReport') }}" {{ $selectedCategory == 'Semua kategori' ? 'selected' : '' }}>Semua kategori</option>
                @foreach ($categories as $category)
                  @if (strpos($category->name, "Lainnya") !== false)
                    @php
                      $lainnyaCategory = $category;
                    @endphp
                  @else
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterCategory', ['category_id' => $category->id]) }}" {{ $selectedCategory == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                  @endif
                @endforeach
                @if ($lainnyaCategory)
                  <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterCategory', ['category_id' => $lainnyaCategory->id]) }}" {{ $selectedCategory == $lainnyaCategory->name ? 'selected' : '' }}>{{ $lainnyaCategory->name }}</option>
                @endif
              </select>
            </div>
          
            <div class="col-12 col-md-auto d-flex align-items-center mt-3 mt-md-0">
              @if (Auth::user()->role == "headmaster")
                @if ($filterTitle == null)
                  <a href="{{ route('convertReport') }}">
                    <button type="button" class="btn btn-success w-100 w-md-auto">Export Data Laporan</button>
                  </a>
                @else
                  <a href="{{ route('convertCategoryReport', ['category_id' => $categoryNow]) }}">
                    <button type="button" class="btn btn-success w-100 w-md-auto">Export Data Laporan</button>
                  </a>
                @endif
              @endif
            </div>
          </div>
          
            
            <br>

          @endif

          <br>

          <div class="table-container">
            <!-- Table with stripped rows -->
            <table class="table" style="vertical-align: middle">
              <colgroup>
                <col style="min-width: 200px;">
                <col style="min-width: 200px;">
                <col style="min-width: 100px;">
                <col style="min-width: 100px;">
              </colgroup>

              <thead>
                  <tr>
                    <th style="min-width: 200px;">
                      <b>Judul</b>
                    </th>
                    <th style="min-width: 200px;" data-type="date" data-format="YYYY/DD/MM">@sortablelink('created_at', 'Tanggal dibuat')</th>
                    <th style="min-width: 100px;">Status</th>
                    <th style="min-width: 100px;">@sortablelink('priority', 'Prioritas')</th>
                    @if (Auth::user()->role != "admin")
                      <th style="text-align: right">Detail</th>
                    @else
                      <th></th>
                    @endif
                    </tr>
                  </thead>
                  <tbody>
                  
                  @if ($reports->count() == 0)
                    <tr>
                        <td class="container" colspan="4" style="color: dimgray">Belum ada laporan</td>
                    </tr>
                  @endif
                    @foreach($reports as $report)
                      <tr>
                        @if($report->isUrgent == true)
                          <td>{{ $report->name }} <i class="fa-sharp fa-solid fa-circle-exclamation fa-lg" style="color: #BB2D3B"></i></td>
                        @else
                          <td>{{ $report->name }}</td>    
                        @endif
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                        @if ($report->status == "Approved")
                          <td>Disetujui oleh {{ $report->approvalBy }}</td>
                        @elseif ($report->status == "Rejected")
                          <td>Ditolak oleh {{ $report->approvalBy }}</td>  
                        @elseif ($report->status == "Cancelled")
                          <td>Dibatalkan</td>
                        @elseif ($report->status == "Freshly submitted")
                          <td>Terkirim</td>
                        @elseif ($report->status == "In review by staff")
                          <td>Sedang ditinjau oleh {{ $report->lastUpdatedBy }}</td>
                        @elseif ($report->status == "Request Approval")
                          <td>Menunggu persetujuan dari atasan oleh {{ $report->lastUpdatedBy }}</td>
                        @elseif ($report->status == "In Progress")
                          <td>Sedang ditindaklanjuti oleh {{ $report->lastUpdatedBy }}</td>
                        @elseif ($report->status == "Monitoring process")
                          <td>Dalam pemantauan oleh {{ $report->lastUpdatedBy }}</td>
                        @elseif ($report->status == "Completed")
                          <td>Selesai oleh {{ $report->lastUpdatedBy }}</td>
                        @endif

                        @if($report->priority == "1")
                          <td><span style="background-color: #BB2D3B; color: white; padding: 5px; border-radius: 10%">High</span></td>
                        @elseif($report->priority == "2")
                          <td><span style="background-color: #FFC107; color: black; padding: 5px; border-radius: 10%">Medium</span></td>
                        @elseif($report->priority == "3")
                          <td><span style="background-color: #198754; color: white; padding: 5px; border-radius: 10%">Low</span></td>
                        @else
                          <td><span style="background-color: #D9DADB; color: black; padding: 5px; border-radius: 10%">Not set</span></td>
                        @endif
  
                        @if (Auth::user()->role == "admin")
                        <td style="text-align: right">
                          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#deleteReportModal_" . $report->id}}">
                            <i class="bi bi-trash-fill"></i>
                          </button>
  
                          {{-- Modal --}}
                          <div class="modal fade" id="{{"deleteReportModal_" . $report->id}}" tabindex="-1">
                              <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                  <div class="modal-header border-0">
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body" style="text-align: center;">
                                  <h5 class="modal-title" style="font-weight: 700">Yakin mau hapus laporan ini?</h5>
                                  Data yang udah terhapus akan sulit untuk dikembalikan seperti semula
                                  </div>
                                  <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                  <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                  <form class="w-100" action="{{ route('admin.deleteReport', $report->id) }}" method="POST">
                                      @csrf
                                      @method('DELETE')
  
                                      <button type="submit" class="btn btn-secondary w-100">Ya, hapus</button>
                                  </form>
                                  </div>
                              </div>
                              </div>
                          </div>
                        </td>
                        @else
                          <td style="text-align: right">
                            <a href="{{ route('student.reportDetail', $report->id) }}">
                                <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 24px;"></i>
                            </a>
                          </td>
                        @endif
                      </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- End Table with stripped rows -->
          </div>

            @if ($reports->hasPages())
              <div class="row mt-5">
                <div class="d-flex justify-content-end">
                    {{ $reports->withQueryString()->links() }}
                </div>
              </div>
            @endif

            <br>

            {{-- @if (Auth::user()->role == "headmaster")
              @if ($filterTitle == null)
                <a href="{{ route('convertReport') }}">
                  <button type="button" class="btn btn-success">Export Data Laporan</button>
                </a>
              
              @else
                <a href="{{ route('convertCategoryReport', ['category_id' => $categoryNow]) }}">
                  <button type="button" class="btn btn-success">Export Data Laporan</button>
                </a>  
              @endif
            @endif --}}
          
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
    
@endsection