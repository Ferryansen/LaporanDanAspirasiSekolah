@extends('layouts.mainpage')

@section('title')
    Admin Dashboard
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Staff Type</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Staff Type</a></li>
            <li class="breadcrumb-item active">Update Staff Type</li>
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
                <h5 class="card-title">Ralat Staff Type</h5>

                <!-- General Form Elements -->
                <form action="{{ route('admin.updateStaffType', $staffType->id)}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row mb-3">
                    <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="staffTypeName" name="staffTypeName" value="{{ old('staffTypeName', $staffType->name) }}">
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