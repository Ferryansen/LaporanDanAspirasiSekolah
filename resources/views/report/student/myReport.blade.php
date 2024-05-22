@extends('layouts.mainpage')

@section('title')
    Laporan Saya
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

                        <!-- Default Table -->
                        <table class="table">
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
                            @foreach ($reports as $report)
                            <tr>
                                <td>{{ $report->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                                @if ($report->status == "In review by staff" || $report->status == "In review to headmaster")
                                    <td>Freshly submitted</td>
                                @elseif ($report->status == "Monitoring process")
                                    <td>In Progress</td>
                                @else
                                    <td>{{ $report->status }}</td>
                                @endif
                                <td style="display: flex; justify-content: end;">
                                    <a href="{{ route('student.reportDetail', $report->id) }}">
                                        <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 24px;"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- End Default Table Example -->

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