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

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Laporan Saya</h5>

                        <a href="{{ route('student.createReportForm') }}">
                            <button type="button" class="btn btn-primary">Tambah Laporan</button>
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
                                <td>
                                <a href="{{ route('student.reportDetail', $report->id) }}">
                                    <button type="button" class="btn btn-info">Detail</button>
                                </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    <!-- End Default Table Example -->

                    <div class="row mt-5">
                        <div class="d-flex justify-content-end">
                            {{ $reports->withQueryString()->links() }}
                        </div>
            
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </section>  


@endsection