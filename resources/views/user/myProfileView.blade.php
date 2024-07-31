@extends('layouts.mainPage')

@section('title')
    Profil Saya
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Profil Saya</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
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
                  <button class="nav-link {{ ($errors->has('urgent_phone_number') || session('updateUrgentSuccessMessage') || (!$errors->any() && !session('updateUrgentSuccessMessage')) ) && !($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation')) && !session('changePassSuccessMessage') ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-overview">Detail</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link {{ ($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation') || session('changePassSuccessMessage')) && !( $errors->has('urgent_phone_number') || session('updateUrgentSuccessMessage')) ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-change-password">Ubah Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade {{ ($errors->has('urgent_phone_number') || session('updateUrgentSuccessMessage') || (!$errors->any() && !session('updateUrgentSuccessMessage'))) && !($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation')) && !session('changePassSuccessMessage') ? 'show active' : '' }} profile-overview" id="profile-overview">
                    <br>
                    @if(session('updateUrgentSuccessMessage'))
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        {{ session('updateUrgentSuccessMessage') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
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

                    @if ($user->role == 'student')
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Nomor Telepon Urgent</div>
                            <div class="col-lg-9 col-md-8" id="urgentPhoneValue" style="{{ $errors->has('urgent_phone_number') ? 'display: none;' : '' }}">
                                @php
                                    if ($user->urgentPhoneNumber == null) {
                                        echo 'Belum terdaftar';
                                    } else {
                                        echo $user->urgentPhoneNumber;
                                    }
                                @endphp
                                <i class="bi bi-pencil-square text-primary" id="update-urgent" style="cursor: pointer;"></i>
                            </div>
                            <div class="col-lg-9 col-md-8" id="urgentPhoneForm" style="{{ $errors->has('urgent_phone_number') ? '' : 'display: none;' }}">
                                <form id="updateForm" action="{{ route('student.updateUrgentPhoneNum') }}" method="POST" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="input-group">
                                        <div style="display: flex;">
                                            <div style="margin-right: 4px">
                                                <input type="text" class="form-control @error('urgent_phone_number') is-invalid @enderror" value="{{ old('urgent_phone_number') ?? $user->urgentPhoneNumber ?? '08' }}" name="urgent_phone_number" required>
                                                @error('urgent_phone_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">Perbarui</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endif

                    @if ($user->staffType != null)
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Tipe Staf</div>
                            <div class="col-lg-9 col-md-8">{{ $user->staffType->name }}</div>
                        </div>
                    @endif

                    @if ($user->isSuspended == true)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Kamu sedang ter-suspend dari fitur penyampaian aspirasi!<br><br>Alasan suspend kamu adalah
                            <b>{{ $user->suspendReason }}</b>
                        </div>
                    @endif

                </div>

                <div class="tab-pane fade {{ ($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation') || session('changePassSuccessMessage')) && !( $errors->has('urgent_phone_number') || session('updateUrgentSuccessMessage')) ? 'show active' : '' }} pt-3" id="profile-change-password">
                    @if(session('changePassSuccessMessage'))
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            {{ session('changePassSuccessMessage') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form id="passUpdateForm" action="{{ route('changepassword') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PATCH')

                        <div class="row mb-3">
                        <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Password Sekarang</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="new_password" class="col-md-4 col-lg-3 col-form-label">Password Baru</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password Baru</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="new_password_confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation">
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <br>
                        
                        <div class="row mb-3" id="short-change-btn">
                            <div class="col-md-4 col-lg-3"></div>
                            <div class="col-md-8 col-lg-9">
                                <button id="sub-btn" type="submit" class="btn btn-primary">
                                    <span id="sub-text">Ubah Password</span>
                                    <span id="load-animation" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
                                    <span id="load-text" style="display: none">Loading...</span>
                                </button>
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

@section('script')
    <script>
        document.getElementById('update-urgent').addEventListener('click', function() {
            document.getElementById('urgentPhoneValue').style.display = 'none';
            document.getElementById('urgentPhoneForm').style.display = 'block';
        });

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('passUpdateForm');
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