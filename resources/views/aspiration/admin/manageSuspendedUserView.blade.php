@extends('layouts.mainpage')

@section('title')
    Daftar Suspend
@endsection

@section('css')
    <style>
        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .container {
            text-align: center; /* Center the text within the td */
            color: dimgray;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Daftar Suspend</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Aspirasi</a></li>
            <li class="breadcrumb-item active">Daftar Suspend</li>
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
                    <div class="card-body" style="margin-top: 24px">
                        <!-- Default Table -->
                        <div class="table-container">
                            <table class="table" style="vertical-align: middle">
                                <thead>
                                <tr>
                                    <th scope="col">No. Pengguna</th>
                                    <th scope="col">Alasan</th>
                                    <th scope="col">Tanggal Efektif</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if ($suspendedUsers->count() == 0)
                                    <tr>
                                        <td class="container" colspan="4" style="color: dimgray">Belum ada pengguna ter-suspend</td>
                                    </tr>
                                @endif
                                @foreach ($suspendedUsers as $user)
                                <tr>
                                    <td>{{ $user->userNo }}</td>
                                    <td>{{ $user->suspendReason }}</td>
                                    <td>{{ $user->suspendDate }}</td>
                                    <td style="text-align: right">
                                        <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#unsuspendUserModal"><i class="bi bi-trash-fill"></i></button>
                                        {{-- Modal --}}
                                        <div class="modal fade" id="unsuspendUserModal" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="text-align: center;">
                                                <h5 class="modal-title" style="font-weight: 700">Yakin mau cabut suspend pengguna ini?</h5>
                                                Pengguna akan dapat menyampaikan aspirasi kembali
                                                </div>
                                                <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Tidak</button>
                                                <form class="w-100" action="{{ route('manage.users.unsuspend', $user->id) }}" enctype="multipart/form-data" method="POST">
                                                    @csrf
                                                    @method('PATCH')
    
                                                    <button type="submit" class="btn btn-primary w-100">Ya, cabut</button>
                                                </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($suspendedUsers->hasPages())
                            <div class="row mt-5">
                                <div class="d-flex justify-content-end">
                                    {{ $suspendedUsers->withQueryString()->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>  
@endsection

@section('script')

@endsection