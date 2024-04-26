@extends('layouts.mainpage')

@section('title')
    Reported Aspiration View
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
          <h5 class="card-title">Aspirasi Bermasalah</h5>

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
                @foreach($aspirations as $aspiration)
                  <tr>
                    <td>{{ $aspiration->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($aspiration->created_at)->format('d/m/y') }}</td>
                    <td>{{ $aspiration->status }}</td>
                    <td>{{ $aspiration->user_report_aspirations_count }}</td>
                    <td>
                      <a href="{{ route('aspirations.reported.details', ['aspiration_id' => $aspiration->id]) }}">
                        <button type="button" class="btn btn-info">Info</button>
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