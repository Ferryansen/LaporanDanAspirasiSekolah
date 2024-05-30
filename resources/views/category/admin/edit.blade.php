@extends('layouts.mainpage')

@section('title')
    Perbarui Kategori
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Perbarui Kategori</h1>
  <nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('categories.list') }}">Pengguna</a></li>
    <li class="breadcrumb-item"><a href="{{ route('categories.list') }}">Kategori Pengerjaan</a></li>
    <li class="breadcrumb-item active">Perbarui Kategori</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <!-- General Form Elements -->
  <form id="category-form" action="{{ route('categories.update', $category->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PATCH')
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="categoryName" value="{{old('categoryName', $category->name)}}" required>
      </div>
    </div>
    
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Staff Type</label>
      <div class="col-sm-10">
        <select class="form-select" aria-label="Default select example" name="categoryStaffType" required>
          <option selected disabled value>Pilih Tipe Staf</option>
          @foreach ($staffTypes as $staffType)
            <option value={{ $staffType->id }} @if (old('categoryStaffType', $category->staffType_id) == $staffType->id) {{ 'selected' }} @endif>{{ $staffType->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button id="sub-btn" type="submit" class="btn btn-primary">
          <span id="sub-text">Perbarui</span>
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