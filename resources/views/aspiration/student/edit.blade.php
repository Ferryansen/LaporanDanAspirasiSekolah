@extends('layouts.mainpage')

@section('title')
    Perbarui Aspirasi
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Perbarui Aspirasi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.myAspirations') }}">Aspirasi</a></li>
      <li class="breadcrumb-item"><a href="{{ route('aspirations.myAspirations') }}">Aspirasi Saya</a></li>
      <li class="breadcrumb-item"><a href="{{ route('aspirations.publicAspirations') }}">Aspirasi Publik</a></li>
      <li class="breadcrumb-item active">Perbarui Aspirasi</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <!-- General Form Elements -->
  <form action="{{ route('aspirations.update', $aspiration->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PATCH')
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Judul</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="aspirationName" value="{{old('aspirationName', $aspiration->name)}}" required>
      </div>
    </div>
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Deskripsi</label>
      <div class="col-sm-10">
        <textarea class="form-control" style="height: 100px" name="aspirationDescription" required>{{old('aspirationDescription', $aspiration->description)}}</textarea>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Kategori</label>
      <div class="col-sm-10">
        <select class="form-select" aria-label="Default select example" name="aspirationCategory" required>
          <option selected disabled value>Pilih Kategori Aspirasi</option>
          @foreach ($categories as $category)
            <option value={{ $category->id }} @if (old('aspirationCategory', $aspiration->category_id) == $category->id) {{ 'selected' }} @endif>{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Simpan</button>
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