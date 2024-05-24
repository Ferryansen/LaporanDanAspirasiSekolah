@extends('layouts.mainpage')

@section('title')
    Tambah Pengguna Baru
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Tambah Pengguna Baru</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Pengguna</a></li>
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Urus Pengguna</a></li>
            <li class="breadcrumb-item active">Tambah Pengguna Baru</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')
<section class="section">
  <!-- General Form Elements -->
  <form id="user-form" action="{{ route('manage.users.register.submit') }}" enctype="multipart/form-data" method="POST" novalidate>
    @csrf
    <div class="row mb-3">
      <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="inputText" class="col-sm-2 col-form-label">Nomor Telepon</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('phoneNumber') is-invalid @enderror" value="{{ old('phoneNumber') !== null ? old('phoneNumber') : '08' }}" name="phoneNumber" required>
            @error('phoneNumber')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <fieldset class="row mb-3 @error('gender') is-invalid @enderror">
        <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
        <div class="col-sm-10">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="male" value="Male" {{ old('gender') == 'Male' ? 'checked' : '' }}>
                <label class="form-check-label" for="male">
                Pria
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="female" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}>
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
            <div class="input-group">
                <input type="text" class="date-picker form-control @error('birthDate') is-invalid @enderror" name="birthDate" value="{{ old('birthDate') }}" placeholder="DD/MM/YYYY">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="bi bi-calendar"></i>
                    </span>
                </div>
                @error('birthDate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Role</label>
        <div class="col-sm-10">
        <select class="form-select @error('role') is-invalid @enderror" id="roleSelection" name="role" aria-label="Default select example">
            <option selected disabled>Pilih Role Pengguna</option>
            <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Murid</option>
            <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>Kepala Sekolah</option>
            <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>Staf</option>
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
                <option value="{{ $staffType->id }}">{{ $staffType->name }}</option>
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
        <button id="sub-btn" type="submit" class="btn btn-primary">
            <span id="sub-text">Tambah</span>
            <span id="load-animation" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
            <span id="load-text" style="display: none">Loading...</span>
        </button>
      </div>
    </div>

  </form><!-- End General Form Elements -->
</section>
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

            console.log(initialValue);

            if (initialValue == '3') {
                staffTypeContainer.style.display = 'flex';
            }
        }

        $(document).ready(function() {
            $('.date-picker').datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: function(dateText) {
                    $(this).val(dateText);
                }
            });

            $('.date-picker').on('input', function() {
                var val = this.value.replace(/\D/g, '');
                if(val.length > 2) {
                    this.value = val.slice(0, 2) + '/' + val.slice(2, 4) + '/' + val.slice(4, 8);
                } else if(val.length > 4) {
                    this.value = val.slice(0, 2) + '/' + val.slice(2, 4) + '/' + val.slice(4, 8);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('user-form');
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