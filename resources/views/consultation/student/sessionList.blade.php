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
            position: relative;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
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

        .desc {
            margin-bottom: 7px;
            font-size: smaller;
            color: dimgrey;
        }

        .warn {
            margin-top: 15px;
            font-size: smaller;
            color: green;
        }

        .post-footer {
            position: absolute;
            top: 50%;
            right: 20px; /* Adjust as necessary */
            transform: translateY(-50%);
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
  <h1>Konsultasi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.myAspirations') }}">Konsultasi</a></li>
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
                          <tbody  style="border: white">
                            <div class="d-grid gap-2 d-md-block d-flex" style="padding-left: 15px; margin-bottom: 20px">
                                <a href="{{ route('consultation.sessionList.sorting', ['typeSorting' => 'UpComing']) }}" class="btn btn-secondary" style="background-color: {{ $typeSorting === 'UpComing' ? '#8DA5EA' : '#fff' }}; color: {{ $typeSorting === 'UpComing' ? '#fff' : '#8F8F8F' }}; border: 1; border-color: #8F8F8F; border-radius: 20px;">Akan datang</a>
                                <a href="{{ route('consultation.sessionList.sorting', ['typeSorting' => 'OnGoing']) }}" class="btn btn-secondary" style="background-color: {{ $typeSorting === 'OnGoing' ? '#8DA5EA' : '#fff' }}; color: {{ $typeSorting === 'OnGoing' ? '#fff' : '#8F8F8F' }}; border-color: #8F8F8F; border-radius: 20px;">Berlangsung</a>
                            </div>
                              @if ($consultations->count() == 0)
                              <tr>
                                  <td colspan="3">
                                      <div class="container">
                                          <span style="color: dimgray">Belum ada sesi konseling yang tersedia</span>
                                      </div>
                                  </td>
                              </tr>
                              @else
                                @foreach($consultations as $consultation)
                                    @if($consultation->status !== "Dibatalkan")
                                    <tr>
                                        <td>
                                            <div class="post">
                                                <div class="post-header">
                                                    <div class="uploader-info">
                                                        <span class="uploader-name">{{$consultation->title}}</span> 
                                                    </div>
                                                </div>
                                                <div class="post-body">
                                                        @php
                                                            $formattedDate = \Carbon\Carbon::parse($consultation->start)->locale('id')->translatedFormat('d F Y');
                                                            $formattedTimeStart = \Carbon\Carbon::parse($consultation->start)->format('H:i');
                                                            $formattedTimeEnd = \Carbon\Carbon::parse($consultation->end)->format('H:i');
                                                        @endphp
                                                    <div class="desc"><i class="bi bi-calendar" style="padding-right: 5px"></i> {{$formattedDate}}</div>
                                                    <div class="desc"><i class="bi bi-clock" style="padding-right: 5px"></i> {{$formattedTimeStart}} - {{$formattedTimeEnd}}</div>
                                                    <div class="desc"><i class="bi bi-person" style="padding-right: 5px"></i> {{$consultation->consultBy->name}}</div>
                                                    @if(in_array(Auth::id(), $consultation->attendees))
                                                    <div class="warn">Anda sudah terdaftar dalam sesi ini.</div>
                                                    @endif
                                                </div>
                                                <div class="post-footer">
                                                    <a href="{{ route('consultation.detail', $consultation->id) }}" class="detail-button">
                                                        <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 24px;"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                              @endif
                          </tbody>
                      </table>
                      <!-- End Table with stripped rows -->
                      @if ($consultations->hasPages())
                      <div class="row mt-5">
                          <div class="d-flex justify-content-end">
                              {{ $consultations->withQueryString()->links() }}
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