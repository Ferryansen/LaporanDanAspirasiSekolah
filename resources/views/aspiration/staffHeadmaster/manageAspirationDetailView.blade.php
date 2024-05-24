@extends('layouts.mainpage')

@section('title')
  Detail Realisasi Aspirasi
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Detail Realisasi Aspirasi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.myAspirations') }}">Aspirasi</a></li>
      <li class="breadcrumb-item"><a href="#">Kelola Aspirasi</a></li>
      <li class="breadcrumb-item active">{{ $aspiration->name }}</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Info Terkait Realisasi</h5>
                        
                        <table style="width: 100%">
                            <tr >
                                <td>Penanggung Jawab</td>
                                <td>{{ $aspiration->name }}</td>
                            </tr>
                        </table>
                        
                    
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bukti Penyelesaian</h5>
            
                    <br>
                    <br>
                        
                    
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Referensi Aspirasi</h5>
            
                    <br>
                    <br>
                        
                    
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')

<script>
    
</script>

@endsection