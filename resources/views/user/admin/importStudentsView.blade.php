@extends('layouts.mainpage')

@section('title')
    Import Murid
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Daftar Pengguna Baru</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Manage Pengguna</a></li>
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Semua Pengguna</a></li>
            <li class="breadcrumb-item active">Import murid</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')
<section class="section">
  <h5 class="card-title">Import Murid</h5>

  <!-- General Form Elements -->
  <form action="{{ route('manage.users.importstudents.submit') }}" enctype="multipart/form-data" method="POST" novalidate>
    @csrf
    
    <div class="row mb-3">
        <label for="formFile" class="col-sm-2 col-form-label">File Upload</label>
        <div class="col-sm-10">
            <input class="form-control @error('file') is-invalid @enderror" name="file" type="file" id="formFile" accept=".xlsx">
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Import</button>
      </div>
    </div>

  </form><!-- End General Form Elements -->
</section>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection

@section('script')
    
@endsection