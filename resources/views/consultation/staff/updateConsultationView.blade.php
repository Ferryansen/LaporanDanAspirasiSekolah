@extends('layouts.mainPage')

@section('title')
    Perbarui Konsultasi
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Perbarui Konsultasi</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('consultation.seeAll') }}">Konsultasi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('consultation.seeAll') }}">Kelola Konsultasi</a></li>
            <li class="breadcrumb-item active">Perbarui Konsultasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')
@if(session('errorMessage'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('errorMessage') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
<section class="section">
    <form id="consultation-form" action="{{ route('consultation.update', ['consultation_id' => $event->id]) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="row mb-3">
            <label for="title" class="col-sm-2 col-form-label">Judul</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') != null ? old('title') : $event->title }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-10">
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 100px" required>{{ old('description') != null ? old('description') : $event->description }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <fieldset class="row mb-3 @error('consultationVisibility') is-invalid @enderror">
            <legend class="col-form-label col-sm-2 pt-0">Visibilitas</legend>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="consultationVisibility" id="publicConsultation" value="public" {{ old('consultationVisibility') != null ? (old('consultationVisibility') == 'public' ? 'checked' : '') : ($event->is_private == '0' ? 'checked' : '') }} required>
                    <label class="form-check-label" for="publicConsultation">
                        Publik
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="consultationVisibility" id="privateConsultation" value="private" {{ old('consultationVisibility') != null ? (old('consultationVisibility') == 'private' ? 'checked' : '') : ($event->is_private == '1' ? 'checked' : '') }} required>
                    <label class="form-check-label" for="privateConsultation">
                        Tersembunyi
                    </label>
                </div>
            </div>
            @error('consultationVisibility')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </fieldset>

        @if ($event->attendeeLimit != null)
            <div class="row mb-3">
                <label for="attendeeLimit" class="col-sm-2 col-form-label">Limit Peserta</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control @error('attendeeLimit') is-invalid @enderror" name="attendeeLimit" value="{{ old('attendeeLimit') != null ? old('attendeeLimit') : $event->attendeeLimit }}" min="{{ count($event->attendees) > 1 ? count($event->attendees) : '1' }}" required>
                    @error('attendeeLimit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif


        <div class="row mb-3">
            <label for="startDateTime" class="col-sm-2 col-form-label">Jadwal</label>
            <div class="col-sm-10">
                <div id="dateTime-input" class="input-group">
                    <div class="flex-grow-1">
                        <input type="datetime-local" class="form-control @error('startDateTime') is-invalid @enderror" id="startDateTime" name="startDateTime" value="{{ $event->start ? $event->start : '' }}">
                        @error('startDateTime')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <i id="arrow-right" class="bi bi-arrow-right" style="margin: 0 8px"></i>
                    <i id="arrow-down" class="bi bi-arrow-down"></i>
                    <div class="flex-grow-1">
                        <input type="datetime-local" class="form-control @error('endDateTime') is-invalid @enderror" id="endDateTime" name="endDateTime" value="{{ $event->end ? $event->end : '' }}">
                        @error('endDateTime')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <fieldset class="row mb-3 @error('consultationType') is-invalid @enderror">
            <legend class="col-form-label col-sm-2 pt-0">Tipe</legend>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="consultationType" id="onlineConsultation" value="online" {{ old('consultationType') != null ? (old('consultationType') == 'online' ? 'checked' : '') : ($event->is_online == '1' ? 'checked' : '') }} required>
                    <label class="form-check-label" for="onlineConsultation">
                        Online
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="consultationType" id="offlineConsultation" value="offline" {{ old('consultationType') != null ? (old('consultationType') == 'offline' ? 'checked' : '') : ($event->is_online == '0' ? 'checked' : '') }} required>
                    <label class="form-check-label" for="offlineConsultation">
                        Offline
                    </label>
                </div>
            </div>
            @error('consultationType')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </fieldset>
        
        <div class="row mb-3" id="locationField">
            <label for="location" id="location-label" class="col-sm-2 col-form-label">Lokasi (Opsional)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ $event->location ? $event->location : '' }}">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
              <button id="sub-btn" type="submit" class="btn btn-primary">
                  <span id="sub-text">Perbarui Sekarang</span>
                  <span id="load-animation" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
                  <span id="load-text" style="display: none">Loading...</span>
              </button>
            </div>
          </div>
    </form>
</section>
@endsection

@section('css')
    <style>
        #dateTime-input {
            display: flex;
            align-items: center;
        }

        #arrow-down {
            display: none;
        }

        @media (max-width: 680px) {
            #dateTime-input {
                display: block;
            }

            #arrow-right {
                display: none;
            }

            #arrow-down {
                display: inline-block;
                text-align: center
            }
        }

        .form-control.attendees + .select2-container--default .select2-selection--multiple {
            border-color: #ced4da;
        }

        .form-control.attendees + .select2-container--default .select2-selection--multiple:focus {
            border-color: #86b7fe; 
        }

        .form-control.attendees + .select2-container--default .select2-dropdown {
            border-color: #ced4da;
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('consultation-form');
            const submitBtn = document.getElementById('sub-btn');
            const buttonText = document.getElementById('sub-text');
            const buttonSpinner = document.getElementById('load-animation');
            const loadingText = document.getElementById('load-text');

            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                buttonText.style.display = 'none';
                buttonSpinner.style.display = 'inline-block';
                loadingText.style.display = 'inline-block';
            });
        });
    </script>
@endsection