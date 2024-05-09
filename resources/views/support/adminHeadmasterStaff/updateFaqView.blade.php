@extends('layouts.mainpage')

@section('title')
    Perbarui FAQ
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Perbarui FAQ</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('faq.seeall') }}">Bantuan</a></li>
            <li class="breadcrumb-item"><a href="{{ route('faq.seeall') }}">FAQ</a></li>
            <li class="breadcrumb-item active">Perbarui FAQ</li>
            </ol>
        </nav>
    </div>
@endsection

@section('sectionPage')
<section class="section">
  <!-- General Form Elements -->
  <form action="{{ route('faq.update', $currFaq->id) }}" enctype="multipart/form-data" method="POST" novalidate>
    @csrf
    @method('PATCH')
    <div class="row mb-3">
      <label for="inputQuestion" class="col-sm-2 col-form-label">Pertanyaan</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('question') is-invalid @enderror" name="question" value="{{ old('question') != null ? old('question') : $currFaq->question }}">
        @error('question')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
        <label for="inputAnswer" class="col-sm-2 col-form-label">Jawaban</label>
        <div class="col-sm-10">
            <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" style="height: 100px">{{ old('answer') != null ? old('answer') : $currFaq->answer }}</textarea>
            @error('answer')
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
    
@endsection

@section('script')
    
@endsection