@extends('layouts.mainpage')

@section('title')
    Profil Saya
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Profil Saya</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Home</a></li>
            <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')
    @php
        $user = Auth::user();
    @endphp

    <section class="section profile">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link {{ !$errors->any() && !session('successMessage') ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-overview">Detail</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link {{ $errors->any() || session('successMessage') ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-change-password">Ubah Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade {{ !$errors->any() && !session('successMessage') ? 'show active' : '' }} profile-overview" id="profile-overview">
                    <br>
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label ">Nama</div>
                        <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Email</div>
                        <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Nomor Telepon</div>
                        <div class="col-lg-9 col-md-8">{{ $user->phoneNumber }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Gender</div>
                        <div class="col-lg-9 col-md-8">
                            @if ($user->gender == "Male")
                                Pria
                            @else
                                Wanita
                            @endif    
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Tanggal Lahir</div>
                        <div class="col-lg-9 col-md-8">{{ \Carbon\Carbon::parse($user->birthDate)->format('d/m/Y') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Role</div>
                        <div class="col-lg-9 col-md-8">
                            @if ($user->role == "admin")
                                Admin
                            @elseif ($user->role == "student")
                                Murid
                            @elseif ($user->role == "headmaster")
                                Kepala Sekolah
                            @else
                                Staf
                            @endif
                        </div>
                    </div>

                    @if ($user->staffType != null)
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Tipe Staf</div>
                            <div class="col-lg-9 col-md-8">{{ $user->staffType->name }}</div>
                        </div>
                    @endif

                    @if ($user->isSuspended == true)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Kamu sedang ter-suspend dari fitur penyampaian aspirasi! Alasan suspend kamu adalah
                            <b>{{ $user->suspendReason }}</b>
                        </div>
                    @endif

                </div>

                <div class="tab-pane fade {{ $errors->any() || session('successMessage') ? 'show active' : '' }} pt-3" id="profile-change-password">
                    @if(session('successMessage'))
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            {{ session('successMessage') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('changepassword') }}" method="POST" enctype="multipart/form-data" method="POST" novalidate>
                        @csrf

                        <div class="row mb-3">
                        <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Password Sekarang</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="current_password" type="password" class="form-control @error('currPassword') is-invalid @enderror" id="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="new_password" class="col-md-4 col-lg-3 col-form-label">Password Baru</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="new_password" type="password" class="form-control @error('newPassword') is-invalid @enderror" id="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password Baru</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="new_password_confirmation" type="password" class="form-control @error('confirmPassword') is-invalid @enderror" id="new_password_confirmation">
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <br>
                        
                        <div class="row mb-3" id="short-change-btn">
                            <div class="col-md-4 col-lg-3"></div>
                            <div class="col-md-8 col-lg-9">
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </div>
                        </div>
                    </form>

                </div>

              </div>

            </div>
          </div>

        </div>
      </div>
    </section>
@endsection