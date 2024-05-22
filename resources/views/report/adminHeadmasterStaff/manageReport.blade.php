@extends('layouts.mainpage')

@section('title')
    Kelola Laporan
@endsection

@section('css')
  <style>
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
              <div class="col-3">
                <select class="form-select" aria-label="Default select example" name="categoryStaffType" required onchange="window.location.href=this.value;">
                  <option selected disabled value>Pilih Status</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Freshly submitted']) }}">Freshly submitted</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'In review by staff']) }}">In review by staff</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'In review to headmaster']) }}">In review to headmaster</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Approved']) }}">Approved</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Rejected']) }}">Rejected</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Cancelled']) }}">Cancelled</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Monitoring process']) }}">Monitoring process</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'In Progress']) }}">In Progress</option>
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterStatus', ['status' => 'Completed']) }}">Completed</option>
                </select>
              </div>
            </div>
            
            <br>

            @if ($filterTitle != null)
              <h5>{{ $filterTitle }}</h5>
            @endif

          @else
            <div class="row">
              <div class="col-3">
                <select class="form-select" aria-label="Default select example" name="categoryStaffType" required onchange="window.location.href=this.value;">
                  <option selected disabled value>Pilih Kategori</option>
                  @foreach ($categories as $category)
                    <option value="{{ route('report.adminHeadmasterStaff.manageReportFilterCategory', ['category_id' => $category->id]) }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <br>

            @if ($filterTitle != null)
              <h5><b>{{ $filterTitle }}</b></h5>
            @endif
          @endif

          <br>

          <!-- Table with stripped rows -->
          <table class="table">
            <thead>
                <tr>
                  <th>
                    <b>Judul</b>
                  </th>
                  <th data-type="date" data-format="YYYY/DD/MM">Tanggal dibuat</th>
                  <th>Status</th>
                  @if (Auth::user()->role == "admin")
                  <th></th>
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
                   
                      <td>{{ $report->name }}</td>
                      <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                      @if ($report->status == "Approved" || $report->status == "Rejected")
                        <td>{{ $report->status }} by {{ $report->approvalBy }}</td>
                      @elseif ($report->status == "Cancelled" || $report->status == "Freshly submitted")
                        <td>{{ $report->status }}</td>
                      @else
                        <td>{{ $report->status }} by {{ $report->lastUpdatedBy }}</td>
                      @endif

                      @if (Auth::user()->role == "admin")
                      <td style="display: flex; justify-content: end;">
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
                        <td style="display: flex; justify-content: end;">
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

            @if ($reports->hasPages())
              <div class="row mt-5">
                <div class="d-flex justify-content-end">
                    {{ $reports->withQueryString()->links() }}
                </div>
              </div>
            @endif
          
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