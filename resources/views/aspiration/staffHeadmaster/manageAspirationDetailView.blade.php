@extends('layouts.mainpage')

@section('title')
  Detail Realisasi Aspirasi
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Detail Realisasi Aspirasi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.manageAspiration') }}">Aspirasi</a></li>
      <li class="breadcrumb-item"><a href="{{ route('aspirations.manageAspiration') }}">Kelola Aspirasi</a></li>
      <li class="breadcrumb-item active">{{ $aspiration->name }}</li>
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
                        <h5 class="card-title" style="margin: 0;">Info Terkait Realisasi</h5>
                        
                        <table style="width: 100%">
                            <tr>
                                <td style="height:32px; vertical-align: middle;">Penanggung Jawab</td>
                                <td style="height:32px; text-align: right; vertical-align: middle;"><strong>{{ $aspiration->realizationExecutor->name }}</strong></td>
                            </tr>
                            @if ($aspiration->approvedBy != null)
                                <tr>
                                    <td style="vertical-align: middle;">Disetujui Oleh</td>
                                    <td style="text-align: right; vertical-align: middle;"><strong>{{ $aspiration->approver->name }}</strong></td>
                                </tr>
                            @endif
                            <tr>
                                <td style="vertical-align: middle;">Status</td>
                                <td style="vertical-align: middle; text-align: right">
                                    @if (Auth::user()->role == 'staff')
                                        @if (in_array($aspiration->status, ['Approved', 'In Progress', 'Monitoring', 'Completed']))
                                            <form action="{{ route('aspiration.updateStatus') }}" method="POST" id="statusForm_{{ $aspiration->id }}" style="display: flex; justify-content: flex-end;">
                                                @csrf
                                                <div>
                                                    <select style="width: 100%;" name="status" id="status_{{ $aspiration->id }}" class="form-select" required onchange="updateFormAndDisplayModal('{{ $aspiration->id }}')">
                                                        <option value="Approved" {{ $aspiration->status == 'Approved' ? 'selected' : '' }} {{ $aspiration->status == 'In Progress' || $aspiration->status == 'Monitoring' || $aspiration->status == 'Completed' ? 'disabled' : '' }}>Disetujui</option>
                                                        <option value="In Progress" {{ $aspiration->status == 'In Progress' ? 'selected' : '' }} {{ $aspiration->status == 'Completed' ? 'disabled' : '' }}>Sedang ditindaklanjuti</option>
                                                        <option value="Monitoring" {{ $aspiration->status == 'Monitoring' ? 'selected' : '' }} {{ $aspiration->status == 'Completed' ? 'disabled' : '' }}>Dalam pemantauan</option>
                                                        <option value="Completed" {{ $aspiration->status == 'Completed' ? 'selected' : '' }}>Selesai</option>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                            </form>

                                            <div class="modal fade" id="{{"completeAspirationModal_" . $aspiration->id}}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" style="font-weight: 700">Masukkan bukti penyelesaian</h5>
                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="proof-form" action="{{ route('finishAspiration', $aspiration->id) }}" method="POST" enctype="multipart/form-data">
                                                      @csrf
                                                      @method('PATCH')
                                                        <div class="modal-body">
                                                          <div class="col-sm-12">
                                                            <input accept=".png,.jpg,.jpeg,.webp,.mp4,.avi,.quicktime" multiple class="form-control @error('aspirationEvidences') is-invalid @enderror" type="file" id="formFile" name="aspirationEvidences[]" required>
                                                            @error('aspirationEvidences')
                                                                <div class="invalid-feedback">
                                                                    @if ($message == "validation.max.array")
                                                                        {{ "Maksimal 5 gambar yang di upload" }}
                                                                    @elseif ($message == "validation.mimes")
                                                                        {{ "Format file tidak didukung. Hanya file PNG, JPG, JPEG, WEBP untuk gambar dan MP4, AVI, QuickTime untuk video yang diperbolehkan." }}
                                                                    @elseif ($message == "validation.max")
                                                                        {{ "Ukuran file tidak boleh melebihi 40 MB." }}
                                                                    @else
                                                                        {{ $message }}
                                                                    @endif
                                                                </div>
                                                            @enderror
                                                          </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                          <button id="sub-btn-proof" type="submit" class="btn btn-primary">
                                                            <span id="sub-text">Simpan</span>
                                                            <span id="load-animation" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
                                                            <span id="load-text" style="display: none">Loading...</span>
                                                          </button>
                                                        </div>
                                                    </form>
                                                  </div>
                                                </div>
                                            </div>
                                        @else
                                            <strong>{{ $aspiration->status }}</strong>
                                        @endif
                                    @else
                                        <strong>{{ $aspiration->status }}</strong>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        
                    
                    </div>
                </div>
            </div>
        </div>

        @if ($aspiration->status == 'Completed')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="margin: 0">Bukti Penyelesaian</h5>
                            
                            @foreach($evidences as $evidence)
                                @if (strpos($evidence->image, 'ListImage') === 0)
                                    <img style="max-width: 100%;" src="{{ asset('storage/'.$evidence->image) }}" alt="{{ $evidence->name }}">
                                @elseif (strpos($evidence->video, 'ListVideo') === 0)
                                    <video style="max-width: 100%;" controls>
                                        <source src="{{ asset('storage/'.$evidence->video) }}" type="{{ getVideoMimeType($evidence->video) }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="margin: 0">Referensi Aspirasi ({{ $aspiration->aspirationNo }})</h5>
                        
                        <p>{{ $aspiration->name }}</p>
                        <p>{{ $aspiration->description }}</p>

                        <div class="kpi" style="display: flex; align-items: center;">
                            <div id="likes">
                                <i class="bi bi-hand-thumbs-up-fill icon-kpi"></i>
                                <span>{{ count($aspiration->likes) }}</span>
                            </div>

                            <div id="dislikes" style="margin: 0 16px">
                                <i class="bi bi-hand-thumbs-down-fill icon-kpi"></i>
                                <span>{{ count($aspiration->dislikes) }}</span>
                            </div>

                            <div id="comments" style="margin-left: 4px">
                                <i class="bi bi-chat-left-fill icon-kpi"></i>
                                <span>{{ count($aspiration->comments) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('css')
    <style>
        .icon-kpi {
            color: rgb(201, 201, 201);
        }
    </style>
@endsection

@section('script')

<script>
    function updateFormAndDisplayModal(aspirationId) {
        var selectedValue = document.getElementById('status_' + aspirationId).value;

        if (selectedValue === 'Completed') {
            var modalId = 'completeAspirationModal_' + aspirationId;
            document.getElementById('status_' + aspirationId).value = 'Monitoring';
            $('#' + modalId).modal('show');
        } else {
            document.getElementById('statusForm_' + aspirationId).submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('proof-form');
        const submitBtn = document.getElementById('sub-btn-proof');
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