@extends('layouts.mainpage')

@section('title')
    Tambah Kategori
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Tambah Kategori</h1>
  <nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('categories.list') }}">Pengguna</a></li>
    <li class="breadcrumb-item"><a href="{{ route('categories.list') }}">Kategori Pengerjaan</a></li>
    <li class="breadcrumb-item active">Tambah Kategori</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <!-- General Form Elements -->
  <form id="category-form" action="{{ route('categories.create') }}" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="categoryName" required>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Tipe Staf</label>
      <div class="col-sm-10">
        <select class="form-select" aria-label="Default select example" name="categoryStaffType" required>
          <option selected disabled value>Pilih Tipe Staf</option>
            @foreach ($staffTypes as $staffType)
              <option value={{ $staffType->id }}>{{ $staffType->name }}</option>
            @endforeach
        </select>

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
            const form = document.getElementById('category-form');
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