@extends('layouts.mainpage')

@section('title')
    Tambah FAQ Baru
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Tambah FAQ Baru</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('faq.seeall') }}">Bantuan</a></li>
            <li class="breadcrumb-item"><a href="{{ route('faq.seeall') }}">FAQ</a></li>
            <li class="breadcrumb-item active">Tambah FAQ Baru</li>
            </ol>
        </nav>
    </div>
@endsection

@section('sectionPage')
<section class="section">
  <!-- General Form Elements -->
  <form action="{{ route('faq.create') }}" enctype="multipart/form-data" method="POST" novalidate>
    @csrf
    <div class="row mb-3">
      <label for="inputQuestion" class="col-sm-2 col-form-label">Pertanyaan</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('question') is-invalid @enderror" name="question" value="{{ old('question') }}">
        @error('question')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
        <label for="inputAnswer" class="col-sm-2 col-form-label">Jawaban</label>
        <div class="col-sm-10">
            <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" style="height: 100px">{{ old('answer') }}</textarea>
            @error('answer')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Tambah</button>
      </div>
    </div>

  </form><!-- End General Form Elements -->
</section>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection

@section('script')
    
@endsection