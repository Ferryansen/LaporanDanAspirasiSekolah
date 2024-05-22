@extends('layouts.mainpage')

@section('title')
    Reported Aspiration View
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
  <h1>Aspirasi Bermasalah</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.manageAspirations') }}">Aspirasi</a></li>
      <li class="breadcrumb-item active">Aspirasi Bermasalah</li>
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
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
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
                  <th>Jumlah Pelapor</th>
                </tr>
              </thead>
              <tbody>
                @if ($aspirations->count() == 0)
                  <tr>
                      <td class="container" colspan="4" style="color: dimgray">Belum ada aspirasi bermasalah</td>
                  </tr>
                @endif
                @foreach($aspirations as $aspiration)
                  <tr>
                    <td>{{ $aspiration->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($aspiration->created_at)->format('d/m/y') }}</td>
                    <td>{{ $aspiration->status }}</td>
                    <td>{{ $aspiration->user_report_aspirations_count }}</td>
                    <td style="display: flex; justify-content: end;">
                      <a href="{{ route('aspirations.reported.details', ['aspiration_id' => $aspiration->id]) }}">
                          <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 24px;"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <!-- End Table with stripped rows -->
          
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