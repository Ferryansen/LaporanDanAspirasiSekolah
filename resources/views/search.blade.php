@extends('layouts.mainpage')

@section('title')
    Search Result View
@endsection

@section('pageTitle')
<div class="pagetitle">
  <h1>Search Result List</h1>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Hasil Pencarian Anda</h5>
         
          @if ($searchParams['data'] == "aspirations")
            <!-- Table with stripped rows -->
            <table class="table">
                <thead>
                    <tr>
                    <th>
                        <b>Judul Aspirasi</b>
                    </th>
                    <th data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                    <th>Status</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aspirations as $aspiration)
                    <tr>
                        <td>{{ $aspiration->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($aspiration->created_at)->format('d/m/y') }}</td>
                        <td>{{ $aspiration->status }}</td>
                        <td>
                        <a href="{{ route('aspirations.details', ['aspirationId' => $aspiration->id]) }}">
                            <button type="button" class="btn btn-info">Detail</button>
                        </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- End Table with stripped rows -->

            <div class="row mt-5">
                <div class="d-flex justify-content-end">
                    {{ $aspirations->withQueryString()->links() }}
                </div>
            </div>

          @elseif ($searchParams['data'] == "reports")
            <!-- Table with stripped rows -->
            <table class="table">
                <thead>
                    <tr>
                    <th>
                        <b>Judul Laporan</b>
                    </th>
                    <th data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                    <th>Status</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                        <td>{{ $report->status }}</td>
                        <td>
                        <a href="{{ route('student.reportDetail', ['id' => $report->id]) }}">
                            <button type="button" class="btn btn-info">Detail</button>
                        </a>
                        </td>
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

          @elseif ($searchParams['data'] == "1")
            @if ($reports->count() == 0 && $aspirations->count() != 0)
                <!-- Table with stripped rows -->
                <table class="table">
                    <thead>
                        <tr>
                        <th>
                            <b>Judul Aspirasi</b>
                        </th>
                        <th data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                        <th>Status</th>
                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aspirations as $aspiration)
                        <tr>
                            <td>{{ $aspiration->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($aspiration->created_at)->format('d/m/y') }}</td>
                            <td>{{ $aspiration->status }}</td>
                            <td>
                            <a href="{{ route('aspirations.details', ['aspirationId' => $aspiration->id]) }}">
                                <button type="button" class="btn btn-info">Detail</button>
                            </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- End Table with stripped rows -->

                <div class="row mt-5">
                    <div class="d-flex justify-content-end">
                        {{ $aspirations->withQueryString()->links() }}
                    </div>
                </div>
            @elseif ($reports->count() != 0 && $aspirations->count() == 0)
                <!-- Table with stripped rows -->
                <table class="table">
                    <thead>
                        <tr>
                        <th>
                            <b>Judul Laporan</b>
                        </th>
                        <th data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                        <th>Status</th>
                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                            <td>{{ $report->status }}</td>
                            <td>
                            <a href="{{ route('student.reportDetail', ['id' => $report->id]) }}">
                                <button type="button" class="btn btn-info">Detail</button>
                            </a>
                            </td>
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
            @else
                <!-- Table with stripped rows -->
                <table class="table">
                    <thead>
                        <tr>
                        <th>
                            <b>Judul Aspirasi</b>
                        </th>
                        <th data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                        <th>Status</th>
                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aspirations as $aspiration)
                        <tr>
                            <td>{{ $aspiration->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($aspiration->created_at)->format('d/m/y') }}</td>
                            <td>{{ $aspiration->status }}</td>
                            <td>
                            <a href="{{ route('aspirations.details', ['aspiration_id' => $aspiration->id]) }}">
                                <button type="button" class="btn btn-info">Detail</button>
                            </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- End Table with stripped rows -->
                <div class="row mt-5">
                    <div class="d-flex justify-content-end">
                        {{ $aspirations->withQueryString()->links() }}
                    </div>
                </div>

                <br>

                <!-- Table with stripped rows -->
                <table class="table">
                    <thead>
                        <tr>
                        <th>
                            <b>Judul Laporan</b>
                        </th>
                        <th data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                        <th>Status</th>
                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                            <td>{{ $report->status }}</td>
                            <td>
                            <a href="{{ route('student.reportDetail', ['id' => $report->id]) }}">
                                <button type="button" class="btn btn-info">Detail</button>
                            </a>
                            </td>
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
            @endif

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