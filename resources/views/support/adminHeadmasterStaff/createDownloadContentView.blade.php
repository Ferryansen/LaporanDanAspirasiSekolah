@extends('layouts.mainPage')

@section('title')
    Tambah File Baru
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Tambah File Baru</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('downloadcontent.seeall') }}">Bantuan</a></li>
            <li class="breadcrumb-item"><a href="{{ route('downloadcontent.seeall') }}">Pusat Download</a></li>
            <li class="breadcrumb-item active">Tambah File Baru</li>
            </ol>
        </nav>
    </div>
@endsection

@section('sectionPage')
<section class="section">
  <!-- General Form Elements -->
  <form id="download-form" action="{{ route('downloadcontent.create') }}" enctype="multipart/form-data" method="POST" novalidate>
    @csrf
    <div class="row mb-3">
      <label for="title" class="col-sm-2 col-form-label">Judul</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
        <label for="description" class="col-sm-2 col-form-label">Deskripsi</label>
        <div class="col-sm-10">
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 100px">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <label for="file" class="col-sm-2 col-form-label">Upload File</label>
        <div class="col-sm-10">
            <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file" accept=".doc,.docx,.pdf,.xls,.xlsx,.ppt,.pptx">
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button id="sub-btn" type="submit" class="btn btn-primary">
          <span id="sub-text">Tambah</span>
          <span id="load-animation" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
          <span id="load-text" style="display: none">Loading...</span>
        </button>
      </div>
    </div>

  </form><!-- End General Form Elements -->
</section>
@endsection

@section('script')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('download-form');
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