@extends('layouts.mainPage')

@section('title')
    Import Murid
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Import Murid</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Pengguna</a></li>
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Urus Pengguna</a></li>
            <li class="breadcrumb-item active">Import murid</li>
            </ol>
        </nav>
    </div>
@endsection

@section('sectionPage')
<section class="section">
  <div class="row mb-3">
    <div class="col-sm-12">
        <a href="{{ asset('web_assets/import/TemplateImport.xlsx') }}" class="btn btn-success">Download Template</a>
    </div>
  </div>
  <br>
  <form id="user-form" action="{{ route('manage.users.importstudents.submit') }}" enctype="multipart/form-data" method="POST" novalidate>
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
        <button id="sub-btn" type="submit" class="btn btn-primary">
          <span id="sub-text">Import</span>
          <span id="load-animation" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
          <span id="load-text" style="display: none">Loading...</span>
        </button>
      </div>
    </div>

  </form>
</section>
@endsection

@section('script')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('user-form');
            const submitBtn = document.getElementById('sub-btn');
            const buttonText = document.getElementById('sub-text');
            const buttonSpinner = document.getElementById('load-animation');
            const loadingText = document.getElementById('load-text');

            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                buttonText.style.display = 'none';
                buttonSpinner.style.display = 'inline-block';
                loadingText.style.display = 'inline-block';
            });
        });
    </script>
@endsection