@extends('layouts.mainPage')

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
      <li class="breadcrumb-item active">Perbarui Aspirasi</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <form id="aspiration-form" action="{{ route('aspirations.update', $aspiration->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PATCH')
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Judul</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('aspirationName') is-invalid @enderror" name="aspirationName" value="{{old('aspirationName', $aspiration->name)}}" required>
        @error('aspirationName')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Deskripsi</label>
      <div class="col-sm-10">
        <textarea class="form-control @error('aspirationDescription') is-invalid @enderror" style="height: 100px" name="aspirationDescription" required>{{old('aspirationDescription', $aspiration->description)}}</textarea>
        @error('aspirationDescription')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Kategori</label>
      <div class="col-sm-10">
      <select class="form-select @error('aspirationCategory') is-invalid @enderror" aria-label="Default select example" name="aspirationCategory" required>
        <option selected disabled value>Pilih Kategori Aspirasi</option>
        @foreach ($categories as $category)
            @if (strpos($category->name, 'Lainnya') === false)
                <option value="{{ $category->id }}" @if (old('aspirationCategory', $aspiration->category_id) == $category->id) selected @endif>{{ $category->name }}</option>
            @endif
        @endforeach
        @foreach ($categories as $category)
            @if (strpos($category->name, 'Lainnya') !== false)
                <option value="{{ $category->id }}" @if (old('aspirationCategory', $aspiration->category_id) == $category->id) selected @endif>{{ $category->name }}</option>
            @endif
        @endforeach
    </select>
        @error('aspirationCategory')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button id="sub-btn" type="submit" class="btn btn-primary">
          <span id="sub-text">Simpan</span>
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
        const form = document.getElementById('aspiration-form');
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