@extends('layouts.mainpage')

@section('title')
    Manage Aspirasi
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Kelola Aspirasi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.manageAspiration') }}">Aspirasi</a></li>
      <li class="breadcrumb-item active">Kelola Aspirasi</li>
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
        <br>
          <!-- Table with stripped rows -->
         <div class="table-container" style="overflow-x:auto; max-width: 100%;">
          <table class="table" style="overflow-x:auto">
          @if (Auth::user()->role == "headmaster")
          <div class="col-auto d-flex align-items-center col-7 col-md-3" style="margin-top: 0.5rem">
            <select class="form-select" aria-label="Default select example" name="categoryStaffType" required onchange="window.location.href=this.value;">
                @php
                    $selectedCategory = session('selected_category', 'Semua kategori');
                @endphp
                <option value="{{ route('aspirations.manageAspiration') }}" {{ $selectedCategory == 'Semua kategori' ? 'selected' : '' }}>Semua kategori</option>
                @foreach ($categories as $category)
                    @if (strpos($category->name, 'Lainnya') === false)
                        <option value="{{ route('aspirations.viewFilterCategory', ['category_id' => $category->id]) }}" {{ $category->id == $selectedCategoryId ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endif
                @endforeach
                @foreach ($categories as $category)
                    @if (strpos($category->name, 'Lainnya') !== false)
                        <option value="{{ route('aspirations.viewFilterCategory', ['category_id' => $category->id]) }}" {{ $category->id == $selectedCategoryId ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endif
                @endforeach
            </select>
          </div>
            <br>
            @endif
          
            <thead>
                <tr>
                  <th>
                    <b>Judul</b>
                  </th>
                  <th>Status</th>
                  <th>Penanggung jawab</th>
                  <th></th>
                </tr>
            </thead>
                <tbody>
                    @php
                        $count = 0;
                    @endphp
                    @foreach($aspirations as $aspiration)
                        @if($aspiration->status != 'Freshly submitted')
                            @php
                                $count++;
                            @endphp
                        @endif
                    @endforeach
                    @if ($count == 0)
                        <tr>
                            <td class="container" colspan="4" style="color: dimgray;">Belum ada aspirasi yang dikelola</td>
                        </tr>
                    @endif
                  @foreach($aspirations as $aspiration)
                    @if ($aspiration->status != 'Freshly submitted')
                        <tr style="vertical-align: middle">
                            <td>
                                @if ($aspiration->status == 'Approved' || $aspiration->status == 'In Progress' || $aspiration->status == 'Monitoring' || $aspiration->status == 'Completed' || $aspiration->status == 'Rejected')
                                    <a href="{{ route('manage.aspiration.detail', ['aspiration_id' => $aspiration->id]) }}" style="text-decoration: underline">{{ $aspiration->name }}</a>
                                @else
                                    {{ $aspiration->name }}
                                @endif
                            </td>

                            @if (Auth::user()->role == "staff")
                                @if (in_array($aspiration->status, ['Approved', 'In Progress', 'Monitoring', 'Completed']))
                                <td>
                                    <form action="{{ route('aspiration.updateStatus') }}" method="POST" id="statusForm_{{ $aspiration->id }}">
                                        @csrf
                                        <div>
                                            <select style="border: none; padding-left:0" name="status" id="status_{{ $aspiration->id }}" class="form-select" required onchange="updateFormAndDisplayModal('{{ $aspiration->id }}')">
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
                                </td>
                                @else
                                    @if ($aspiration->status == 'Freshly submitted')
                                    <td>Terkirim</td>
                                    @elseif ($aspiration->status == 'In review')
                                    <td>Sedang ditinjau</td>
                                    @elseif ($aspiration->status == 'Request Approval')
                                    <td>Menunggu persetujuan</td>
                                    @elseif ($aspiration->status == 'Approved')
                                    <td>Disetujui</td>
                                    @elseif ($aspiration->status == 'Rejected')
                                    <td>Ditolak</td>
                                    @elseif ($aspiration->status == 'In Progress')
                                    <td>Sedang ditindaklanjuti</td>
                                    @elseif ($aspiration->status == 'Monitoring')
                                    <td>Dalam pemantauan</td>
                                    @elseif ($aspiration->status == 'Completed')
                                    <td>Selesai</td>
                                    @endif
                                @endif

                                <td>
                                    @if (in_array($aspiration->status, ['Rejected', 'Completed']))
                                        @foreach($allUser as $user)
                                            @if($user->id == $aspiration->processedBy)
                                                {{ $user->name }}
                                            @endif
                                        @endforeach
                                    @else
                                    <form action="{{ route('aspiration.assign') }}" method="POST" id="assignForm_{{ $aspiration->id }}">
                                        @csrf
                                        <div>
                                            <select style="border: none; padding-left:0" name="user_id" id="user_{{ $aspiration->id }}" class="form-select" required onchange="document.getElementById('assignForm_{{ $aspiration->id }}').submit()">
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $user->id == $aspiration->processedBy ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                    </form>
                                    @endif
                                </td>

                                @if (in_array($aspiration->status, ['In review']))
                                    <td style="display: flex; justify-content: flex-end;">
                                        <form action="{{ route('aspiration.updateStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                            <button type="submit" name="status" value="Request Approval" class="btn btn-success" style="margin-right: 10px">Ajukan persetujuan</button>
                                        </form>
                                            <button type="submit" name="status" value="Rejected" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#rejectAspirationModal_" . $aspiration->id}}">Tolak</button>
                                            {{-- Modal --}}
                                            <div class="modal fade" id="{{"rejectAspirationModal_" . $aspiration->id}}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" style="font-weight: 700">Masukkan alasan penolakan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="proof-form" action="{{ route('staff.rejectAspiration', $aspiration->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                        <div class="modal-body">
                                                        {{-- <div class="col-sm-12">
                                                            <input type="date" class="form-control @error('processEstimationDate') is-invalid @enderror" name="processEstimationDate" required>
                                                            @error('processEstimationDate')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div> --}}
                            
                                                        {{-- <br> --}}
                                                        
                                                        <div class="col-sm-12">
                                                            <textarea class="form-control" style="height: 100px" required name="rejectReason"></textarea>
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
                                            </div><!-- End Vertically centered Modal-->
                                    </td>
                                @else
                                    <td></td>
                                @endif

                            @else
                                @if ($aspiration->status == 'Freshly submitted')
                                <td>Terkirim</td>
                                @elseif ($aspiration->status == 'In review')
                                <td>Sedang ditinjau</td>
                                @elseif ($aspiration->status == 'Request Approval')
                                <td>Menunggu persetujuan</td>
                                @elseif ($aspiration->status == 'Approved')
                                <td>Disetujui</td>
                                @elseif ($aspiration->status == 'Rejected')
                                <td>Ditolak</td>
                                @elseif ($aspiration->status == 'In Progress')
                                <td>Sedang ditindaklanjuti</td>
                                @elseif ($aspiration->status == 'Monitoring')
                                <td>Dalam pemantauan</td>
                                @elseif ($aspiration->status == 'Completed')
                                <td>Selesai</td>
                                @endif

                                @foreach($allUser as $user)
                                    @if($user->id == $aspiration->processedBy)
                                        <td>{{ $user->name }}</td>
                                    @endif
                                @endforeach

                                @if($aspiration->status == 'Request Approval')
                                    <td style="display: flex; justify-content: flex-end">
                                    <form action="{{ route('aspiration.updateStatus') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                        <button type="submit" name="status" value="Approved" class="btn btn-success" style="margin-right: 10px">Setujui</button>
                                    </form>
                                        <button type="submit" name="status" value="Rejected" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#rejectAspirationModal_" . $aspiration->id}}">Tolak</button>
                                        {{-- Modal --}}
                                        <div class="modal fade" id="{{"rejectAspirationModal_" . $aspiration->id}}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" style="font-weight: 700">Masukkan alasan penolakan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form id="proof-form" action="{{ route('headmaster.rejectAspiration', $aspiration->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                    <div class="modal-body">
                                                    {{-- <div class="col-sm-12">
                                                        <input type="date" class="form-control @error('processEstimationDate') is-invalid @enderror" name="processEstimationDate" required>
                                                        @error('processEstimationDate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div> --}}
                        
                                                    {{-- <br> --}}
                                                    
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" style="height: 100px" required name="rejectReason"></textarea>
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
                                        </div><!-- End Vertically centered Modal-->                            
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endif
            
                        </tr>
                    @endif
                  @endforeach
              </tbody>
            </table>
           </div>
            <!-- End Table with stripped rows -->


            <br>

        </div>
      </div>
      
    </div>
  </div>
</section>
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