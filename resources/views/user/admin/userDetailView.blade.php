@extends('layouts.mainPage')

@section('title')
    Detail Pengguna
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Pengguna</a></li>
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Urus Pengguna</a></li>
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
                        <div class="col-lg-9 col-md-8">{{ \Carbon\Carbon::parse($currUser->birthDate)->format('d/m/Y') }}</div>
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
                        <button type="button" id="delete-user-button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">Hapus</button>
                        {{-- Modal --}}
                        <div class="modal fade" id="deleteUserModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="text-align: center;">
                                    <h5 class="modal-title" style="font-weight: 700">Yakin mau hapus pengguna ini?</h5>
                                    <div id="checked-count-info">pengguna yang sudah terhapus akan sulit untuk dikembalikan seperti semula</div>
                                </div>
                                <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                <form class="w-100" action="{{ route('manage.users.delete', $currUser->id) }}" method="POST">
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

                <div class="tab-pane fade" id="profile-setting">
                    <h5 class="card-title pb-0">Reset Password</h5>
                    <p>Perlu diingat bahwa password pengguna akan kembali menjadi default.</p>
                    <form action="{{ route('manage.users.reset.password', $currUser->id) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('PATCH')
                            <button type="submit" style="margin-right:8px" class="btn btn-primary">Reset Sekarang</button>
                    </form>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

@endsection