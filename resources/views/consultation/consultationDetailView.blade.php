@extends('layouts.mainpage')

@section('title')
  Detail Konsultasi
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Detail Konsultasi</h1>
  <nav>
    <ol class="breadcrumb">
      @if(Auth::user()->role == 'student')
        <li class="breadcrumb-item"><a href="{{ route('consultation.sessionList') }}">Konsultasi</a></li>
      @else
        <li class="breadcrumb-item"><a href="{{ route('consultation.seeAll') }}">Konsultasi</a></li>
      @endif
      <li class="breadcrumb-item active">Detail</li>
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
                    <div class="card-body">
                        <h5 class="card-title" style="margin: 0;">
                            {{$event->title}} ({{ $event->status }})
                        </h5>
                        <p>{!! nl2br(e($event->description)) !!}</p>
                        
                        <table>
                            <tr>
                                <td class="col-5">Konsultan</td>
                                <td>: {{ $event->consultBy->name }}</td>
                            </tr>
                            <tr>
                                <td class="col-5">Tanggal Mulai</td>
                                <td>: {{ \Carbon\Carbon::parse($event->start)->format('d/m/Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="col-5">Lokasi</td>
                                @if ($event->location == null)
                                    <td>: TBA</td>
                                @else
                                    @if ($event->is_online == true)
                                        @if ($event->status == 'Belum dimulai' || $event->status == 'Pindah jadwal' || $event->status == 'Sedang dimulai')
                                            <td>: <a href="{{ $event->location }}">Online <i class="fa-solid fa-link"></i></a></td>
                                        @else
                                            <td>: Online</td>
                                        @endif
                                    @else
                                        <td>: {{ $event->location }}</td>
                                    @endif
                                @endif
                            </tr>
                        </table>
                        @if (Auth::user()->role == 'student')
                            @if (in_array(Auth::id(), $event->attendees))
                                <a href="{{ route('consultation.removeAttendees', ['consultation_id' => $event->id]) }}">
                                    <button type="button" class="btn btn-danger" style="margin-top: 20px">Batal</button>
                                </a>
                            @else
                                <a href="{{ route('consultation.addAttendees', ['consultation_id' => $event->id]) }}">
                                    <button id="sub-btn" type="button" class="btn btn-primary" style="margin-top: 20px">
                                        <span id="sub-text">Daftar</span>
                                        <span id="load-animation" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
                                        <span id="load-text" style="display: none">Loading...</span>
                                    </button>
                                </a>
                            @endif
                        @endif
                        

                        @if ((Auth::user()->role == 'staff' || Auth::user()->role == 'headmaster') && ($event->status == 'Belum dimulai' || $event->status == 'Pindah jadwal' || $event->status == 'Sedang dimulai'))
                            <div style="display:flex; margin-top: 16px;">
                                <a href="{{ route('consultation.updateForm', ['consultation_id' => $event->id]) }}">
                                    <button style="margin-right:8px" type="button" class="btn btn-primary">Perbarui</button>
                                </a>
                                <button type="button" id="delete-user-button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelEventModal">Batalkan</button>
                                {{-- Modal --}}
                                <div class="modal fade" id="cancelEventModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="text-align: center;">
                                            <h5 class="modal-title" style="font-weight: 700">Yakin mau batalkan sesi ini?</h5>
                                            <div id="checked-count-info">para peserta akan segera diberi notifikasi pembatalan setelah ini</div>
                                        </div>
                                        <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                        <form class="w-100" action="{{ route('consultation.cancel', ['consultation_id' => $event->id]) }}" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            @method('PATCH')
        
                                            <button type="submit" class="btn btn-secondary w-100">Ya, batalkan</button>
                                        </form>
                                        </div>
                                    </div>
                                    </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const submitBtn = document.getElementById('sub-btn');
        const buttonText = document.getElementById('sub-text');
        const buttonSpinner = document.getElementById('load-animation');
        const loadingText = document.getElementById('load-text');

        submitBtn.addEventListener('click', function (event) {
            submitBtn.disabled = true;
            buttonText.style.display = 'none';
            buttonSpinner.style.display = 'inline-block';
            loadingText.style.display = 'inline-block';
        });
    });
</script>

@endsection