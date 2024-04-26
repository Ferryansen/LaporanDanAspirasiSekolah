@extends('layouts.mainpage')

@section('title')
Detail Aspirasi Bermasalah
@endsection

@section('css')

@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Aspirasi Bermasalah</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.manageAspirations') }}">Aspirasi</a></li>
      <li class="breadcrumb-item"><a href="{{ route('aspirations.reported') }}">Aspirasi Bermasalah</a></li>
      <li class="breadcrumb-item active">Detail Aspirasi Bermasalah</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')

<br>

@if(session('successMessage'))
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
  <div class="card-header">{{ $aspiration->aspirationNo }}</div>
  <div class="card-body">
    <h5 class="card-title">{{ $aspiration->name }}</h5>
    @if ($aspiration->evidence == null)
      [Tidak ada Evidence]
    @else
      @if(file_exists(public_path().'\storage/'.$aspiration->evidence))
      <img src="{{ asset('storage/'.$aspiration->evidence) }}">
      @else
      <img src="{{ $aspiration->evidence }}">
      @endif
    @endif
    <br>
    <td>{{ $aspiration->description }}</td>

    <br>
    <br>

    <h6 style="font-weight: bold;">Status Penyumbang Aspirasi:</h6>
    <p>
      @if ($aspiration->user->isSuspended)
          Ter-Suspend
      @else
          Aktif
      @endif
    </p>

    <h6 style="font-weight: bold;">Alasan Pelaporan:</h6>
    <ol type="1" id="reasons-list" style="margin-bottom: 0; padding-left: 16px;">
      @foreach ($reportReasons->take(3) as $reason)
          <li>{{ $reason }}</li>
      @endforeach
    </ol>

    @if ($reportReasons->count() > 3)
      <button style="display: block; margin-left: 16px; padding-left: 0;  font-weight: 600; background: none; border: none; cursor: pointer;" class="text-primary" id="see-more-btn" onclick="showMoreReasons()">Lihat Semua</button>  
    @endif
    <button style="display: none; margin-left: 16px; padding-left: 0; font-weight: 600; background: none; border: none; cursor: pointer;" class="text-primary" id="see-less-btn" onclick="showLessReasons()">Lihat Sedikit</button>
  </div>
</div>
    
<div class="card">
  <div class="card-body">
    <h5 class="card-title pb-0">Tindakan</h5>
    <p>Tindak lanjuti aspirasi bermasalah ini.</p>
    <div style="display: flex; align-items: center;">
      @if (!$aspiration->user->isSuspended)
        <button style="margin-right: 8px; pointer-events: auto;" type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#suspendRelatedUserModal">
          Suspend Pengguna Terkait
        </button>
        
        <!-- Modal -->
        <div class="modal fade" id="suspendRelatedUserModal" tabindex="-1" role="dialog" aria-labelledby="reportAspirationModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
              <div class="modal-header border-0">
                <h5 class="modal-title" id="reportAspirationModalLabel">Suspend Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <!-- Report Aspiration Form -->
                  <form action="{{ route('manage.users.suspend', $aspiration->user->id) }}" enctype="multipart/form-data" method="POST">
                      @csrf
                      @method('PATCH')
                      <div class="form-group">
                          <label for="suspendReason">Alasan:</label>
                          <textarea class="form-control @error('suspendReason') is-invalid @enderror" id="suspendReason" name="suspendReason" rows="3"></textarea>
                          @error('suspendReason')
                              <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                      </div>

                      <br>

                      <button type="submit" class="btn btn-primary">Suspend</button>
                  </form>
              </div>
              </div>
          </div>
        </div>

        @if($errors->has('suspendReason'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var modal = new bootstrap.Modal(document.getElementById('suspendRelatedUserModal'));
                    modal.show();
                });
            </script>
        @endif
      @endif 

      <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReportedAspirationModal">
        Hapus Aspirasi
      </button>

      {{-- Modal --}}
      <div class="modal fade" id="deleteReportedAspirationModal" tabindex="-1">
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
              <form class="w-100" action="{{ route('aspirations.reported.delete', ['id' => $aspiration->id]) }}" method="POST">
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
    
@endsection

@section('script')
<script>
  function showMoreReasons() {
    const list = document.getElementById("reasons-list");
    const moreButton = document.getElementById("see-more-btn");
    const lessButton = document.getElementById("see-less-btn");
    
    list.innerHTML = '';
    moreButton.style.display = "none";
    lessButton.style.display = "block";

    @foreach ($reportReasons as $reason)
        var li = document.createElement("li");
        li.textContent = "{{ $reason }}";
        list.appendChild(li);
    @endforeach
  }

  function showLessReasons() {
    const list = document.getElementById("reasons-list");
    const moreButton = document.getElementById("see-more-btn");
    const lessButton = document.getElementById("see-less-btn");
    
    list.innerHTML = '';
    moreButton.style.display = "block";
    lessButton.style.display = "none";

    @foreach ($reportReasons->take(3) as $reason)
        var li = document.createElement("li");
        li.textContent = "{{ $reason }}";
        list.appendChild(li);
    @endforeach
  }
</script>
@endsection

@section('js')

@endsection
