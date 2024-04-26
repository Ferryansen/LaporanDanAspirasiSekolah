@extends('layouts.mainpage')

@section('title')
    Perbarui Pengguna
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Pengguna</a></li>
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Urus Pengguna</a></li>
            <li class="breadcrumb-item active">Perbarui Penguna</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')
<section class="section">
  <h5 class="card-title">Perbarui Pengguna</h5>

  <!-- General Form Elements -->
  <form action="{{ route('manage.users.update.submit', $currUser->id) }}" enctype="multipart/form-data" method="POST" novalidate>
    @csrf
    @method('PATCH')
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') != null ? old('name') : $currUser->name }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') != null ? old('email') : $currUser->email }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="inputText" class="col-sm-2 col-form-label">Nomor Telepon</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('phoneNumber') is-invalid @enderror" value="{{ old('phoneNumber') !== null ? old('phoneNumber') : $currUser->phoneNumber }}" name="phoneNumber" required>
            @error('phoneNumber')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <fieldset class="row mb-3 @error('gender') is-invalid @enderror">
        <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
        <div class="col-sm-10">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="male" value="Male" {{ old('gender') != null ? (old('name') == 'Male' ? 'checked' : '') : ($currUser->gender == 'Male' ? 'checked' : '') }}>
                <label class="form-check-label" for="male">
                Pria
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="female" value="Female" {{ old('gender') != null ? (old('name') == 'Female' ? 'checked' : '') : ($currUser->gender == 'Female' ? 'checked' : '') }}>
                <label class="form-check-label" for="female">
                Wanita
                </label>
            </div>
        </div>
        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </fieldset>

    <div class="row mb-3">
        <label for="inputDate" class="col-sm-2 col-form-label">Tanggal Lahir</label>
        <div class="col-sm-10">
        <input type="date" class="form-control @error('birthDate') is-invalid @enderror" name="birthDate" value="{{ old('birthDate') != null ? old('birthDate') : $currUser->birthDate }}">
        @error('birthDate')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Role</label>
        <div class="col-sm-10">
        <select class="form-select @error('role') is-invalid @enderror" id="roleSelection" name="role" aria-label="Default select example">
            <option selected disabled>Pilih Role Pengguna</option>
            <option value="1" {{ old('role') != null ? (old('role') == '1' ? 'selected' : '') : ($currUser->role == 'student' ? 'selected' : '') }}>Murid</option>
            <option value="2" {{ old('role') != null ? (old('role') == '2' ? 'selected' : '') : ($currUser->role == 'headmaster' ? 'selected' : '') }}>Kepala Sekolah</option>
            <option value="3" {{ old('role') != null ? (old('role') == '3' ? 'selected' : '') : ($currUser->role == 'staff' ? 'selected' : '') }}>Staf</option>
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        </div>
    </div>

    <div class="row mb-3" id="staffTypeInput" style="display: none;">
        <label class="col-sm-2 col-form-label">Tipe Staf</label>
        <div class="col-sm-10">
        <select class="form-select @error('staffType') is-invalid @enderror" id="staffTypeSelection" name="staffType" aria-label="Default select example">
            <option selected disabled>Pilih Tipe Staf</option>

            @foreach ($staffTypes as $staffType)
                <option value="{{ $staffType->id }}" {{ old('staffType') != null ? (old('staffType') == $staffType->id ? 'selected' : '') : ($currUser->staffType_id == $staffType->id ? 'selected' : '') }}>{{ $staffType->name }}</option>
            @endforeach
        </select>
        @error('staffType')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Perbarui</button>
      </div>
    </div>

  </form><!-- End General Form Elements -->
</section>
@endsection

@section('css')
    
@endsection

@section('js')
    <script>
        var roleSelect = document.getElementById('roleSelection');
        var staffTypeContainer = document.getElementById('staffTypeInput');
        var staffTypeSelection = document.getElementById('staffTypeSelection');

        roleSelect.addEventListener('change', function () {
            if (roleSelect.value === '3') {
                staffTypeContainer.style.display = 'flex';
            } else {
                staffTypeContainer.style.display = 'none';
                staffTypeSelection.selectedIndex = 0;
            }
        });

        window.onload = function(){
            var staffTypeSelection = document.getElementById('roleSelection');
            var initialValue = staffTypeSelection.value;

            if (initialValue == '3') {
                staffTypeContainer.style.display = 'flex';
            }
        }
    </script>
@endsection

@section('script')
    
@endsection