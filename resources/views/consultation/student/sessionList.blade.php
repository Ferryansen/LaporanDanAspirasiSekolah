@extends('layouts.mainpage')

@section('title')
    Daftar Konsultasi
@endsection

@section('css')
    <style>
        .posts-container {
            width: 80%;
            margin: 0 auto;
            padding-top: 20px;
        }

        .post {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 10px;
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .uploader-name {
            font-weight: bold;
        }
        
        .upload-time{
          color: grey;
        }

        .post-title {
            margin-bottom: 10px;
        }

        .post-footer {
            margin-top: 10px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 30px;
        }
    </style>
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Aspirasi Saya</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.myAspirations') }}">Kolsultasi</a></li>
      <li class="breadcrumb-item active">Daftar sesi</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage') 
  @if (Auth::user()->isSuspended == false)
  <section class="section">
      <div class="row">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-body" style="margin-top: 24px">
                      <!-- Table with stripped rows -->
                      <table class="table">
                          <tbody>
                              @if ($consultaions->count() == 0)
                              <tr>
                                  <td colspan="3">
                                      <div class="container">
                                          <span style="color: dimgray">Belum sesi konsultasi yang tersedia</span>
                                      </div>
                                  </td>
                              </tr>
                              @else
                              @foreach($consultaions as $consultaion)
                              <tr>
                                  <td>
                                      <div class="post">
                                          <div class="post-header">
                                              <div class="uploader-info">
                                                  <span class="uploader-name">You</span> 
                                                  <span>•</span>
                                                  @php
                                                  $formattedDate = \Carbon\Carbon::parse($aspiration->created_at)->locale('id')->translatedFormat('d F Y');
                                                  @endphp
                                                  <span class="upload-time">{{$formattedDate}}</span>
                                              </div>
                                          </div>
                                          <div class="post-body">
                                              <div class="post-title">{{$aspiration->name}}</div>
                                              <p>{{$aspiration->description}}</p>
                                          </div>
                                          @if ($aspiration->status == "Freshly submitted")
                                          <div class="post-footer">
                                              <div class="actions">
                                                  <div class="col-3 col-md-2  d-flex">
                                                    <div style="margin-right: 5px;">
                                                        <a href="{{ route('aspirations.updateForm', ['id' => $aspiration->id]) }}">
                                                            <button type="button" class="btn btn-warning" style="margin-right: 5px;"><i class="fa-solid fa-pen-to-square"></i></button>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#deleteAspirationModal_" . $aspiration->id}}">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                          {{-- Modal --}}
                                                          <div class="modal fade" id="{{"deleteAspirationModal_" . $aspiration->id}}" tabindex="-1">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                  <div class="modal-header border-0">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <div class="modal-body" style="text-align: center;">
                                                                    <h5 class="modal-title" style="font-weight: 700">Yakin mau hapus aspirasi ini?</h5>
                                                                    Data yang udah terhapus akan sulit untuk dikembalikan seperti semula
                                                                  </div>
                                                                  <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                                                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                                                    <form class="w-100" action="{{ route('aspirations.delete', ['id' => $aspiration->id]) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
              
                                                                        <button type="submit" class="btn btn-secondary w-100">Ya, hapus</button>
                                                                    </form>
                                                                  </div>
                                                                </div>
                                                            </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          @endif
                                      </div>
                                  </td>
                              </tr>
                              @endforeach
                              @endif
                          </tbody>
                      </table>
                      <!-- End Table with stripped rows -->
                      @if ($aspirations->hasPages())
                      <div class="row mt-5">
                          <div class="d-flex justify-content-end">
                              {{ $aspirations->withQueryString()->links() }}
                          </div>
                      </div>
                      @endif
                  </div>
              </div>
          </div>
      </div>
  </section>
  @else
    <section class="section">
      <div class="row">
        <div class="card">
            <div class="card-body text-center">
              <h5 class="card-title mb-0 pb-0 text-danger">{{ $message }}</h5>
            </div>
          </div>
      </div>
    </section>
  @endif
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection

@section('js')

@endsection