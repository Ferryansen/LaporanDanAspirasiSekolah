@extends('layouts.mainpage')

@section('title')
    Admin Dashboard
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Add Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('report.student.myReport') }}">Report</a></li>
                <li class="breadcrumb-item"><a href="{{ route('report.student.myReport') }}">My Report</a></li>
                <li class="breadcrumb-item active">Add Report</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                <h5 class="card-title">Buat laporan</h5>

                <!-- General Form Elements -->
                <form action="{{ route('student.createReport')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row mb-3">
                    <label for="inputText" class="col-sm-2 col-form-label">Judul</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="reportName" name="reportName" required value="{{ old('reportName') }}">
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('reportDescription') is-invalid @enderror" style="height: 100px" id="reportDescription" required name="reportDescription" value="{{ old('reportDescription') }}"></textarea>
                        @error('reportDescription')
                            <div class="invalid-feedback">
                                {{ "Maksimal 200 Karakter" }} 
                            </div>
                        @enderror
                    </div>
                    </div>
                   
                    {{-- @if($errors->any())
                    <ul class="alert alert-warning">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @endif --}}
                    
                    <div class="row mb-3">
                        <label for="inputNumber" class="col-sm-2 col-form-label">Upload Bukti Gambar (Maksimal: 5 bukti)</label>
                        <div class="col-sm-10">
                            <input accept=".png,.jpg,.jpeg,.webp" multiple class="form-control @error('reportEvidences') is-invalid @enderror" type="file" id="formFile" name="reportEvidences[]" required>
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
                        <select  name="reportCategory" class="form-select" aria-label="Default select example" name="reportCategory" required>
                            <option selected disabled value>Pilih Kategori Laporan</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>

                    <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button id="sub-btn" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </div>

                </form><!-- End General Form Elements -->

                </div>
            </div>

            </div>

            
@endsection

@section('script')
<script>
    $("#sub-btn").click(function(e) {
      var reportEvidenceVideo = document.getElementById("reportEvidenceVideo");
            let size = reportEvidenceVideo.files[0].size; 
            if (size > 41943040) {
                alert("Video Size is exceeding 40 Mb");
                e.preventDefault(); 
            }
    });
</script>
@endsection