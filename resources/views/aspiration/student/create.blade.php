@extends('layouts.mainpage')

@section('title')
    Tambah Aspirasi
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Tambah Aspirasi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.myAspirations') }}">Aspirasi</a></li>
      <li class="breadcrumb-item"><a href="{{ route('aspirations.myAspirations') }}">Aspirasi Saya</a></li>
      <li class="breadcrumb-item"><a href="{{ route('aspirations.publicAspirations') }}">Aspirasi Publik</a></li>
      <li class="breadcrumb-item active">Tambah Aspirasi</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <h5 class="card-title">Masukkan Aspirasi Anda</h5>

  <!-- General Form Elements -->
  <form action="{{ route('aspirations.create') }}" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Judul</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="aspirationName" required>
      </div>
    </div>
    <div class="row mb-3">
      <label for="inputPassword" class="col-sm-2 col-form-label">Deskripsi</label>
      <div class="col-sm-10">
        <textarea class="form-control" style="height: 100px" name="aspirationDescription" required></textarea>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Kategori</label>
      <div class="col-sm-10">
        <select class="form-select" aria-label="Default select example" name="aspirationCategory" required>
          <option selected disabled value>Pilih Kategori Aspirasi</option>
          @foreach ($categories as $category)
            <option value={{ $category->id }}>{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Tambah</button>
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