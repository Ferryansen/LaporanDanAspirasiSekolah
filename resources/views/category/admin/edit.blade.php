@extends('layouts.mainpage')

@section('title')
    Perbarui Kategori
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Perbarui Kategori</h1>
  <nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Manage Pengguna</a></li>
    <li class="breadcrumb-item"><a href="{{ route('categories.list') }}">Kategori Pengerjaan</a></li>
    <li class="breadcrumb-item active">Perbarui Kategori</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <h5 class="card-title">Masukkan Ralat Kategori</h5>

  <!-- General Form Elements -->
  <form action="{{ route('categories.update', $category->id) }}" enctype="multipart/form-data" method="POST">
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
            <option value={{ $staffType->id }} @if (old('categoryStaffType', $category->staffTypeId) == $staffType->id) {{ 'selected' }} @endif>{{ $staffType->name }}</option>
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