@extends('layouts.mainpage')

@section('title')
    Admin Dashboard
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Tipe Staf</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Staff Type</a></li>
            <li class="breadcrumb-item active">View List</li>
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
                        <h5 class="card-title">Daftar Tipe Staf</h5>
                        <a href="{{ route('admin.createStaffTypeForm') }}">
                            <button type="button" class="btn btn-primary">Tambah Staff Type</button>
                        </a>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col" class="col">Nama</th>
                                <th scope="col" class="col col-lg-2"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($staffTypes as $staffType)
                            <tr>
                                <td>{{ $staffType->name }}</td>
                                <td style="display: flex; justify-content:end;">
                                    <a href="{{ route('admin.updateStaffTypeForm', $staffType->id) }}">
                                        <button style="margin-right:5px" type="button" class="btn btn-primary">Update</button>
                                    </a>
                                    <form action="{{ route('admin.deleteStaffType', $staffType->id) }}" method="POST" style="display: inline; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                                <!-- <form action="{{ route('admin.deleteStaffType', $staffType->id) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <td>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </td>
                                </form> -->
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
        </div>
    </section>  
@endsection

