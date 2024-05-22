@extends('layouts.mainpage')

@section('title')
    Tipe Staf
@endsection

@section('css')
  <style>
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
        <h1>Tipe Staf</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.staffTypeList') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Tipe Staf</li>
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
                        <a href="{{ route('admin.createStaffTypeForm') }}">
                            <button type="button" class="btn btn-primary"><i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Tambah Tipe Staf</button>
                        </a>

                        <br>
                        <br>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col" class="col">Nama</th>
                                <th scope="col" class="col col-lg-2"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($staffTypes->count() == 0)
                                <tr>
                                    <td class="container" colspan="2" style="color: dimgray">Belum ada tipe staff</td>
                                </tr>
                            @endif
                            @foreach ($staffTypes as $staffType)
                            <tr>
                                <td>{{ $staffType->name }}</td>
                                <td style="display: flex; justify-content:end;">
                                    <a href="{{ route('admin.updateStaffTypeForm', $staffType->id) }}">
                                        <button type="button" style="margin-right:8px" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></button>
                                    </a>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#deleteStaffTypeModal_" . $staffType->id}}" style="display: inline; margin: 0;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
              
                                    {{-- Modal --}}
                                    <div class="modal fade" id="{{"deleteStaffTypeModal_" . $staffType->id}}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" style="text-align: center;">
                                            <h5 class="modal-title" style="font-weight: 700">Yakin mau hapus tipe staf ini?</h5>
                                            Data yang udah terhapus akan sulit untuk dikembalikan seperti semula
                                            </div>
                                            <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                            <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                            <form class="w-100" action="{{ route('admin.deleteStaffType', $staffType->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
            
                                                <button type="submit" class="btn btn-secondary w-100">Ya, hapus</button>
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
                        <!-- End Default Table Example -->
                        
                        @if ($staffTypes->hasPages())
                            <div class="row mt-5">
                                <div class="d-flex justify-content-end">
                                    {{ $staffTypes->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>  
@endsection

