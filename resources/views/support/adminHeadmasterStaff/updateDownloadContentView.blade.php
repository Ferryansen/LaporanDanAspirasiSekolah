@extends('layouts.mainpage')

@section('title')
    Perbarui File
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Perbarui File</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('downloadcontent.seeall') }}">Bantuan</a></li>
            <li class="breadcrumb-item"><a href="{{ route('downloadcontent.seeall') }}">Pusat Download</a></li>
            <li class="breadcrumb-item active">Perbarui File</li>
            </ol>
        </nav>
    </div>
@endsection

@section('sectionPage')
<section class="section">
  <!-- General Form Elements -->
  <form action="{{ route('downloadcontent.update', $currDownloadContent->id) }}" enctype="multipart/form-data" method="POST" novalidate>
    @csrf
    @method('PATCH')
    <div class="row mb-3">
      <label for="title" class="col-sm-2 col-form-label">Judul</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') != null ? old('title') : $currDownloadContent->title }}">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
        <label for="description" class="col-sm-2 col-form-label">Deskripsi</label>
        <div class="col-sm-10">
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 100px">{{ old('description') != null ? old('description') : $currDownloadContent->description }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <label for="file" class="col-sm-2 col-form-label">Upload File</label>
        <div class="col-sm-10">
            @if(file_exists(public_path().'\storage/'.$currDownloadContent->file))
            <a href="{{ asset('storage/'.$currDownloadContent->file) }}" download>
                    (File saat ini)
                </a>
            @else
                (File error)
            @endif
            <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file" accept=".doc,.docx,.pdf,.xls,.xlsx,.ppt,.pptx">
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Perbarui</button>
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