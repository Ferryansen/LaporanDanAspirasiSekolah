@extends('layouts.mainPage')

@section('title')
    Kategori Pengerjaan
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
  <h1>Kategori Pengerjaan</h1>
  <nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('categories.list') }}">Pengguna</a></li>
    <li class="breadcrumb-item active">Kategori Pengerjaan</li>
    </ol>
  </nav>
</div>
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
          <a href="{{ route('categories.addForm') }}">
            <button type="button" class="btn btn-primary"><i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Tambah Kategori</button>
          </a>

          <br>
          <br>

          <div class="table-container">
            <!-- Table with stripped rows -->
            <table class="table" style="vertical-align: middle">
              <thead>
                <tr>
                  <th scope="col">
                    <b>Nama</b>
                  </th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                @if ($categories->count() == 0)
                    <tr>
                        <td class="container" colspan="2" style="color: dimgray">Belum ada kategori</td>
                    </tr>
                @endif
                @foreach ($categories as $category)
                  <tr>
                    <td>{{ $category->name}}</td>
                    <td style="text-align: right">
                      <a href="{{ route('categories.updateForm', ['id' => $category->id]) }}" style="margin: 0 4px">
                        <button type="button" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></button>
                      </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#deleteCategoryModal_" . $category->id}}" style="display: inline; margin: 0 4px;">
                          <i class="bi bi-trash-fill"></i>
                        </button>
  
                        {{-- Modal --}}
                        <div class="modal fade" id="{{"deleteCategoryModal_" . $category->id}}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="text-align: center;">
                                <h5 class="modal-title" style="font-weight: 700">Yakin mau hapus kategori ini?</h5>
                                Data yang udah terhapus akan sulit untuk dikembalikan seperti semula
                                </div>
                                <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                <form class="w-100" action="{{ route('categories.delete', ['id' => $category->id]) }}" method="POST">
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
            <!-- End Table with stripped rows -->
          </div>

          @if ($categories->hasPages())
            <div class="row mt-5">
              <div class="d-flex justify-content-end">
                  {{ $categories->withQueryString()->links() }}
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

@section('js')
    
@endsection

@section('script')
    
@endsection