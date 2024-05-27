@extends('layouts.mainpage')

@section('title')
    Laporan Saya
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
            text-align:justify;
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
        <h1>Laporan Saya</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href={{ route('report.student.myReport') }}>Laporan</a></li>
            <li class="breadcrumb-item active">Laporan Saya</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="margin-top: 24px">
                        <a href="{{ route('student.createReportForm') }}">
                            <button type="button" class="btn btn-primary"><i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Tambah Laporan</button>
                        </a>
                
                          <br>
                          <br>

                        <div class="table-container">
                            <!-- Default Table -->
                            <table class="table" style="vertical-align: middle">
                                <thead>
                                <tr>
                                    <th>
                                        <b>Judul</b>
                                    </th>
                                    <th data-type="date" data-format="YYYY/DD/MM">Tanggal dibuat</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if ($reports->count() == 0)
                                <tr>
                                    <td class="container" colspan="4" style="color: dimgray">Belum ada laporan</td>
                                </tr>
                                @endif
                                @foreach ($reports as $report)
                                <tr>
                                    @if($report->isUrgent == true)
                                        <td>{{ $report->name }} <i class="fa-sharp fa-solid fa-circle-exclamation fa-lg" style="color: #BB2D3B"></i></td>
                                    @else
                                        <td>{{ $report->name }}</td>    
                                    @endif
    
                                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                                    @if ($report->status == "Approved")
                                        <td>Disetujui</td>
                                    @elseif ($report->status == "Rejected")
                                        <td>Ditolak</td>  
                                    @elseif ($report->status == "Cancelled")
                                        <td>Dibatalkan</td>
                                    @elseif ($report->status == "Freshly submitted")
                                        <td>Terkirim</td>
                                    @elseif ($report->status == "In review by staff" || $report->status == "In review to headmaster")
                                        <td>Sedang ditinjau</td>
                                    @elseif ($report->status == "In Progress" || $report->status == "Monitoring process")
                                        <td>Sedang diproses</td>
                                    @elseif ($report->status == "Completed")
                                        <td>Selesai</td>
                                    @endif
    
                                    <td>
                                        <a href="{{ route('student.reportDetail', $report->id) }}">
                                            <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 24px;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!-- End Default Table Example -->
                        </div>

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