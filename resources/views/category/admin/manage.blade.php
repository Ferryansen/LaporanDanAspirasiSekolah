@extends('layouts.mainpage')

@section('title')
    Kategori Pengerjaan
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
  <h1>Pengguna</h1>
  <nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Pengguna</a></li>
    <li class="breadcrumb-item active">Kategori Pengerjaan</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Daftar Kategori Laporan dan Aspirasi</h5>

          <a href="{{ route('categories.addForm') }}">
            <button type="button" class="btn btn-primary">Tambah Kategori</button>
          </a>

          <br>
          <br>

          <!-- Table with stripped rows -->
          <table class="table">
            <thead>
              <tr>
                <th>
                  <b>Name</b>
                </th>
                <th>Update</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              @if ($categories->count() == 0)
                  <tr>
                      <td class="container" colspan="3" style="color: dimgray">Belum ada kategori</td>
                  </tr>
              @endif
              @foreach ($categories as $category)
                <tr>
                  <td>{{ $category->name}}</td>
                  <td>
                    <a href="{{ route('categories.updateForm', ['id' => $category->id]) }}">
                      <button type="button" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                    </a>
                  </td>
                  <td>
                    <form action="{{ route('categories.delete', ['id' => $category->id]) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

          <div class="row mt-5">
            <div class="d-flex justify-content-end">
                {{ $categories->withQueryString()->links() }}
            </div>
          </div>

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