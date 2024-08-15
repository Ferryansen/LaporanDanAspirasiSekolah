@extends('layouts.mainPage')

@section('title')
    Buat Laporan Murid
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Buat Laporan Murid</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('consultation.seeAll') }}">Konsultasi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('consultation.detail', $consultation->id) }}">Detail</a></li>
                <li class="breadcrumb-item active">Buat Laporan Murid</li>
            </ol>
        </nav>
    </div>
@endsection

@section('sectionPage')

    <section class="section">
        <form id="reg-report-form" action="{{ route('report.consultationCreate')}}" enctype="multipart/form-data" method="POST">

            @csrf
            <div class="row mb-3">
            <label for="inputText" class="col-sm-2 col-form-label">Murid Terkait</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="relatedStudent" name="relatedStudent" required value="{{ $relatedStudent->name }}" disabled>
            </div>
            </div>
            <input type="text" name="studentID" value="{{ $relatedStudent->id }}" hidden>

            <div class="row mb-3">
            <label for="inputText" class="col-sm-2 col-form-label">Asal Konsultasi</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="consultation" name="consultation" required value="{{ $consultation->title }}" disabled>
            </div>
            </div>
            <input type="text" name="consultationID" value="{{ $consultation->id }}" hidden>

            <div class="row mb-3">
            <label for="inputText" class="col-sm-2 col-form-label">Judul</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="reportName" name="reportName" required value="{{ old('reportName') }}">
            </div>
            </div>

            <div class="row mb-3">
            <label for="inputPassword" class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-10">
                <textarea class="form-control @error('reportDescription') is-invalid @enderror" style="height: 100px" id="reportDescription" required name="reportDescription">{{ old('reportDescription') }}</textarea>
                @error('reportDescription')
                    <div class="invalid-feedback">
                        {{ "Maksimal 200 Karakter" }} 
                    </div>
                @enderror
            </div>
            </div>
            
            <div class="row mb-3">
                <label for="inputNumber" class="col-sm-2 col-form-label">Upload Bukti Gambar (Maksimal: 5 bukti)</label>
                <div class="col-sm-10">
                    <input accept=".png,.jpg,.jpeg,.webp" multiple class="form-control @error('reportEvidences') is-invalid @enderror" type="file" id="formFile" name="reportEvidences[]">
                    @error('reportEvidences')
                        <div class="invalid-feedback">
                            @if ($message == "validation.max.array")
                                {{ "Maksimal 5 gambar yang di upload" }}
                            @endif
                        </div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="inputNumber" class="col-sm-2 col-form-label">Upload Bukti Video (Opsional & Maksimal: 1 bukti)</label>
                <div class="col-sm-10">
                    <input accept=".mp4,.avi,.quicktime" max="40960" class="form-control @error('reportEvidenceVideo') is-invalid @enderror" type="file" id="reportEvidenceVideo" name="reportEvidenceVideo">
                </div>
            </div>
            
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-10">
                    <select  name="reportCategory" class="form-select" aria-label="Default select example" required>
                        <option selected disabled value>Pilih Kategori Laporan</option>
                        @php
                            $lainnyaCategory = null;
                        @endphp
                        @foreach ($categories as $category)
                            @if (strpos($category->name, "Lainnya") !== false)
                                @php
                                    $lainnyaCategory = $category;
                                @endphp
                            @else
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                        @if ($lainnyaCategory)
                            <option value="{{ $lainnyaCategory->id }}">{{ $lainnyaCategory->name }}</option>
                        @endif
                    </select>
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

@section('script')
<script>
    $("#sub-btn").click(function(e) {
      var reportEvidenceVideo = document.getElementById("reportEvidenceVideo");
            let size = reportEvidenceVideo.files[0].size; 
            if (size > 41943040) {
                alert("Ukuran video tidak boleh melebihi 40 Mb yaa");
                e.preventDefault(); 
            }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('reg-report-form');
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