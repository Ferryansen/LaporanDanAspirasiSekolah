@extends('layouts.mainpage')

@section('title')
    Aspirasi Saya
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

        .labelCompleted {
          background: darkseagreen;
          width: 100%;
          display: block;
          padding-left: 10px;
          color: white;
          border-color: yellowgreen;
          margin-bottom: 10px;
          border-radius: 4px 0px 100px 4px;
        }

        .labelInProg {
          background: lightslategrey;
          width: 100%;
          display: block;
          padding-left: 10px;
          color: white;
          border-color: yellowgreen;
          margin-bottom: 10px;
          border-radius: 4px 0px 100px 4px;
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
      <li class="breadcrumb-item"><a href="{{ route('aspirations.myAspirations') }}">Aspirasi</a></li>
      <li class="breadcrumb-item active">Aspirasi Saya</li>
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
                @if ($aspirations->count() == 0)
                    <div class="container">
                        <span style="color: dimgray">Belum ada aspirasi</span>
                    </div>
                @endif
                @foreach($aspirations as $aspiration)
                      <tr>

                      <div class="post">
                            @if (Auth::user()->role == "student")
                              @if ($aspiration->status == "Completed")
                              <div class="col-9 col-md-3">
                                    <span class="labelCompleted" >Completed</span>
                              </div>
                              @elseif (in_array($aspiration->status, ['In Progress', 'Approved', 'Monitoring']))
                              <div class="col-9 col-md-3">
                                    <span class="labelInProg" >In Progress</span>
                              </div>
                              @endif
                            @endif
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
                                <div class="post-footer">
                                    <div class="actions">
                                
                                        @if ($aspiration->status == "Freshly Submitted")
                                            <div class="row justify-content-start">
                                                <div class="col-3 col-md-1">
                                                <a href="{{ route('aspirations.updateForm', ['id' => $aspiration->id]) }}">
                                                    <button type="button" class="btn btn-success">Ralat</button>
                                                </a>
                                                </div>
                                                <div class="col-3 col-md-1">
                                                    <form action="{{ route('aspirations.delete', ['id' => $aspiration->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        </tr>
                  @endforeach
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