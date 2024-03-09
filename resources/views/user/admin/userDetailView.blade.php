@extends('layouts.mainpage')

@section('title')
    Detail Pengguna
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Detail Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Manage Pengguna</a></li>
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Semua Pengguna</a></li>
            <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')
    <section class="section profile">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link {{ !$errors->has('suspendReason') ? ' active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-overview">Detail</button>
                </li>

                @if ($currUser->role == "student")
                    <li class="nav-item">
                    <button class="nav-link {{ $errors->has('suspendReason') ? ' active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-activity">Aktivitas Aspirasi</button>
                    </li>
                @endif

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-setting">Pengaturan</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                    <br>
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label ">Nama</div>
                        <div class="col-lg-9 col-md-8">{{ $currUser->name }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Email</div>
                        <div class="col-lg-9 col-md-8">{{ $currUser->email }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Nomor Telepon</div>
                        <div class="col-lg-9 col-md-8">{{ $currUser->phoneNumber }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Gender</div>
                        <div class="col-lg-9 col-md-8">
                            @if ($currUser->gender == "Male")
                                Pria
                            @else
                                Wanita
                            @endif    
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Tanggal Lahir</div>
                        <div class="col-lg-9 col-md-8">{{ $currUser->birthDate }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Role</div>
                        <div class="col-lg-9 col-md-8">
                            @if ($currUser->role == "student")
                                Murid
                            @elseif ($currUser->role == "headmaster")
                                Kepala Sekolah
                            @else
                                Staf
                            @endif
                        </div>
                    </div>

                    <br>

                    <div style="display:flex; margin-bottom: 10px">
                        <a href="{{ route('manage.users.update', $currUser->id) }}">
                            <button style="margin-right:8px" type="button" class="btn btn-primary">Perbarui</button>
                        </a>
                        <form action="{{ route('manage.users.delete', $currUser->id) }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>


                @if ($currUser->role == "student")
                    <div class="tab-pane fade {{ $errors->has('suspendReason') ? ' show active' : '' }} profile-overview" id="profile-activity">
                        <h5 class="card-title pb-0">Rekap</h5>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Aspirasi yang Dimiliki</div>
                            <div class="col-lg-9 col-md-8">
                                @php
                                    echo $currUser->aspirations()->count();
                                @endphp
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Total Aspirasi Bermasalah</div>
                            <div class="col-lg-9 col-md-8">
                                @php
                                    echo $problematicAspirations;
                                @endphp
                            </div>
                        </div>

                        <h5 class="card-title pb-0">Status</h5>
                        
                        @if ($currUser->isSuspended == false)
                            <p>Saat ini, pengguna dapat melakukan penyampaian aspirasi tanpa adanya kendala</p>
                            
                            <button type="button" class="btn btn-primary" style="margin-right:8px" data-bs-toggle="modal" data-bs-target="#suspendUserModal">
                                Suspend Pengguna
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="suspendUserModal" tabindex="-1" role="dialog" aria-labelledby="reportAspirationModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reportAspirationModalLabel">Suspend Pengguna</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Report Aspiration Form -->
                                    <form action="{{ route('manage.users.suspend', $currUser->id) }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group">
                                            <label for="suspendReason">Alasan Suspend:</label>
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
                                        var modal = new bootstrap.Modal(document.getElementById('suspendUserModal'));
                                        modal.show();
                                    });
                                </script>
                            @endif
                        @else
                            <p>Saat ini, pengguna telah ter-suspend, sehingga tidak dapat melakukan penyampaian aspirasi</p>
                            <form action="{{ route('manage.users.suspend', $currUser->id) }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PATCH')
                                    <button type="submit" style="margin-right:8px" class="btn btn-primary">Cabut Suspend</button>
                            </form>
                        @endif
                    </div>
                @endif
                

                <div class="tab-pane fade" id="profile-setting">
                    <h5 class="card-title pb-0">Reset Password</h5>
                    <p>Perlu diingat bahwa password pengguna akan kembali menjadi default.</p>
                    <form action="{{ route('manage.users.reset.password', $currUser->id) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('PATCH')
                            <button type="submit" style="margin-right:8px" class="btn btn-primary">Reset Password</button>
                    </form>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

@endsection