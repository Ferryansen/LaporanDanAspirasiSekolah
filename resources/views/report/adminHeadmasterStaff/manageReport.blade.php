@extends('layouts.mainpage')

@section('title')
    Urus Laporan
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
  <h1>Laporan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('report.adminHeadmasterStaff.manageReport') }}">Laporan</a></li>
      <li class="breadcrumb-item active">Urus Laporan</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Manage Laporan</h5>

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
                      <td>
                          <form action="{{ route('admin.deleteReport', $report->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                          </form>
                        </td>
                      @else
                        <td>
                          <a href="{{ route('student.reportDetail', $report->id) }}">
                            <button type="button" class="btn btn-info">Detail</button>
                          </a>
                        </td>
                      @endif
                    </tr>
                  @endforeach
              </tbody>
            </table>
            <!-- End Table with stripped rows -->

            <div class="row mt-5">
              <div class="d-flex justify-content-end">
                  {{ $reports->withQueryString()->links() }}
              </div>
            </div>

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