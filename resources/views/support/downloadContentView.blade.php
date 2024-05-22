@extends('layouts.mainpage')

@section('title')
    Pusat Download
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
        <h1>Pusat Download</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('downloadcontent.seeall') }}">Bantuan</a></li>
            <li class="breadcrumb-item active">Pusat Download</li>
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
                        @if (Auth::user()->role != 'student')
                            <a href="{{ route('downloadcontent.createForm') }}">
                                <button type="button" class="btn btn-primary">Tambah File Baru</button>
                            </a>
                            <br>
                            <br>
                        @endif
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Judul</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($downloadContents->count() == 0)
                                <tr>
                                    <td class="container" colspan="3" style="color: dimgray">Belum ada file</td>
                                </tr>
                            @endif
                            @foreach ($downloadContents as $downloadContent)
                            <tr>
                                <td>{{ $downloadContent->title }}</td>
                                <td>{{ $downloadContent->description }}</td>
                                <td style="display: flex; justify-content: end;">
                                    @if(file_exists(public_path().'\storage/'.$downloadContent->file))
                                        <a href="{{ asset('storage/'.$downloadContent->file) }}" style="margin: 0 4px" download>
                                            <button type="button" class="btn btn-primary">Download</button>
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-primary" style="margin: 0 4px" disabled>File Error</button>
                                    @endif

                                    @if (Auth::user()->role != 'student')
                                        <a href="{{ route('downloadcontent.updateForm', $downloadContent->id) }}" style="margin: 0 4px">
                                            <button type="button" class="btn btn-primary">Perbarui</button>
                                        </a>
                                        <button style="margin: 0 4px" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#deleteDownloadContentModal_" . $downloadContent->id}}">
                                            Hapus
                                        </button>

                                        {{-- Modal --}}
                                        <div class="modal fade" id="{{"deleteDownloadContentModal_" . $downloadContent->id}}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="text-align: center;">
                                                <h5 class="modal-title" style="font-weight: 700">Yakin mau hapus file ini?</h5>
                                                Data yang udah terhapus akan sulit untuk dikembalikan seperti semula
                                                </div>
                                                <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                                <form class="w-100" action="{{ route('downloadcontent.delete', ['id' => $downloadContent->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-secondary w-100">Ya, hapus</button>
                                                </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        @if ($downloadContents->hasPages())
                            <div class="row mt-5">
                                <div class="d-flex justify-content-end">
                                    {{ $downloadContents->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>  
@endsection

@section('css')
    
@endsection

@section('script')

@endsection