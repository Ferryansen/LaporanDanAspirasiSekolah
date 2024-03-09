@extends('layouts.mainpage')

@section('title')
    Admin Dashboard
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Add Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('report.student.myReport') }}">Report</a></li>
                <li class="breadcrumb-item"><a href="{{ route('report.student.myReport') }}">My Report</a></li>
                <li class="breadcrumb-item active">Add Report</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                <h5 class="card-title">Buat laporan</h5>

                <!-- General Form Elements -->
                <form action="{{ route('student.createReport')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row mb-3">
                    <label for="inputText" class="col-sm-2 col-form-label">Judul</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="reportName" name="reportName" required value="{{ old('reportName') }}">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" style="height: 100px" id="reportDescription" required name="reportDescription" value="{{ old('reportDescription') }}"></textarea>
                    </div>
                    </div>
                   
                    
                    <div class="row mb-3">
                    <label for="inputNumber" class="col-sm-2 col-form-label">Upload Bukti (Maksimal: 5 bukti)</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="file" id="formFile" name="reportEvidences[]" required>
                    </div>
                    </div>
                   
                    <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <select  name="reportCategory" class="form-select" aria-label="Default select example" name="reportCategory" required>
                            <option selected disabled value>Pilih Kategori Laporan</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </div>

                </form><!-- End General Form Elements -->

                </div>
            </div>

            </div>

            
@endsection