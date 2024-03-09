@extends('layouts.mainpage')

@section('title')
    Semua Pengguna
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Semua Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Manage Pengguna</a></li>
            <li class="breadcrumb-item active">Semua Pengguna</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')
    @if(session('successMessage'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ session('successMessage') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Pengguna</h5>
                        <a href="{{ route('manage.users.register') }}">
                            <button type="button" class="btn btn-primary">Daftar Pengguna Baru</button>
                        </a>
                        <a href="{{ route('manage.users.importstudents') }}">
                            <button type="button" class="btn btn-primary">Import Murid</button>
                        </a>

                        <br>
                        <br>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if ($user->isSuspended == true)
                                        Ter-suspend
                                    @else
                                        Aktif
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('manage.users.detail', $user->id) }}">
                                        <button type="button" class="btn btn-primary">Detail</button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    <!-- End Default Table Example -->

                        <div class="row mt-5">
                            <div class="d-flex justify-content-end">
                                {{ $users->withQueryString()->links() }}
                            </div>
                
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>  


@endsection