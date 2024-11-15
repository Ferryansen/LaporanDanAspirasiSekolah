@extends('layouts.mainPage')

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

        .filterConsultation::-webkit-scrollbar {
            display: none;
        }

        .filterConsultation {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Konsultasi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('consultation.mySession') }}">Konsultasi</a></li>
      <li class="breadcrumb-item active">Sesi yang diikuti</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage') 
  <section class="section">
      <div class="row">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-body" style="margin-top: 24px">
                      <table class="table">
                          <tbody style="border: white">
                            <div class="d-grid gap-2 d-md-block d-flex filterConsultation" style="margin-left: 8px; margin-bottom: 20px; overflow-x: auto; white-space: nowrap;">
                                <a href="{{ $typeSorting !== 'UpComing' ? route('consultation.mySession.sorting', ['typeSorting' => 'UpComing']) : route('consultation.mySession') }}" class="btn btn-secondary" style="background-color: {{ $typeSorting === 'UpComing' ? '#8DA5EA' : '#fff' }}; color: {{ $typeSorting === 'UpComing' ? '#fff' : '#8F8F8F' }}; border: 1; border-color: #8F8F8F; border-radius: 20px;">Akan datang</a>
                                <a href="{{ $typeSorting !== 'OnGoing' ? route('consultation.mySession.sorting', ['typeSorting' => 'OnGoing']) : route('consultation.mySession') }}" class="btn btn-secondary" style="background-color: {{ $typeSorting === 'OnGoing' ? '#8DA5EA' : '#fff' }}; color: {{ $typeSorting === 'OnGoing' ? '#fff' : '#8F8F8F' }}; border-color: #8F8F8F; border-radius: 20px; margin-left: 2px;">Berlangsung</a>
                                <a href="{{ $typeSorting !== 'End' ? route('consultation.mySession.sorting', ['typeSorting' => 'End']) : route('consultation.mySession') }}" class="btn btn-secondary" style="background-color: {{ $typeSorting === 'End' ? '#8DA5EA' : '#fff' }}; color: {{ $typeSorting === 'End' ? '#fff' : '#8F8F8F' }}; border-color: #8F8F8F; border-radius: 20px; margin-left: 2px;">Berakhir</a>
                            </div>
                              @if ($consultations->count() == 0)
                              <tr>
                                  <td colspan="3">
                                      <div class="container">
                                          <span style="color: dimgray">Belum ada sesi yang terdaftar</span>
                                      </div>
                                  </td>
                              </tr>
                              @else
                              @foreach($consultations as $consultation)
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
                                              <div class="desc"><i class="bi bi-person" style="padding-right: 5px"></i> {{$consultation->is_confirmed == true ? $consultation->consultBy->name : 'Belum terkonfirmasi'}}</div>
                                              @if ($consultation->is_online == 1 && $consultation->is_confirmed == true)
                                                @if ($consultation->location != null)
                                                <a href="{{ $consultation->location }}" target="_blank">
                                                    <button type="button" class="btn btn-primary" style="margin-top: 10px"><i class="bi bi-door-open" style="margin-right: 8px"></i>Masuk ruangan</button>
                                                </a>
                                                @else
                                                    <button type="button" class="btn btn-secondary" style="margin-top: 10px"><i class="bi bi-door-open" style="margin-right: 8px" disabled></i>Ruang Meet TBA</button>
                                                @endif
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
                              @endforeach
                              @endif
                          </tbody>
                      </table>
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
    
@endsection