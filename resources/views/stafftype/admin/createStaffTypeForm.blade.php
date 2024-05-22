@extends('layouts.mainpage')

@section('title')
    Tambah Tipe Staf
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Tambah Tipe Staf</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.staffTypeList') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Tambah Tipe Staf</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection


@section('sectionPage')

    <section class="section">
        <!-- General Form Elements -->
        <form action="{{ route('admin.createStaffType')}}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row mb-3">
            <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="staffTypeName" name="staffTypeName" value="{{ old('staffTypeName') }}">
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