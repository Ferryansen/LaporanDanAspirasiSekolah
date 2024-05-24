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
      <li class="breadcrumb-item"><a href="{{ route('aspirations.manageAspiration') }}">Kelola Aspirasi</a></li>
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
                        <h5 class="card-title" style="margin: 0;">Info Terkait Realisasi</h5>
                        
                        <table style="width: 100%">
                            <tr>
                                <td style="height:32px; vertical-align: middle;">Penanggung Jawab</td>
                                <td style="height:32px; text-align: right; vertical-align: middle;"><strong>{{ $aspiration->realizationExecutor->name }}</strong></td>
                            </tr>
                            @if ($aspiration->approvedBy != null)
                                <tr>
                                    <td style="vertical-align: middle;">Disetujui Oleh</td>
                                    <td style="text-align: right; vertical-align: middle;"><strong>{{ $aspiration->approver->name }}</strong></td>
                                </tr>
                            @endif
                            <tr>
                                <td style="vertical-align: middle;">Status</td>
                                <td style="vertical-align: middle; text-align: right">
                                    @if (in_array($aspiration->status, ['Approved', 'In Progress', 'Monitoring', 'Completed']))
                                        <form action="{{ route('aspiration.updateStatus') }}" method="POST" id="statusForm_{{ $aspiration->id }}" style="display: flex; justify-content: flex-end;">
                                            @csrf
                                            <div>
                                                <select style="width: 100%;" name="status" id="status_{{ $aspiration->id }}" class="form-select" required onchange="document.getElementById('statusForm_{{ $aspiration->id }}').submit()">
                                                    <option value="Approved" {{ $aspiration->status == 'Approved' ? 'selected' : '' }} {{ $aspiration->status == 'In Progress' || $aspiration->status == 'Monitoring' || $aspiration->status == 'Completed' ? 'disabled' : '' }}>Approved</option>
                                                    <option value="In Progress" {{ $aspiration->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="Monitoring" {{ $aspiration->status == 'Monitoring' ? 'selected' : '' }}>Monitoring</option>
                                                    <option value="Completed" {{ $aspiration->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                        </form>
                                    @else
                                        {{ $aspiration->status }}
                                    @endif
                                </td>
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
                        <h5 class="card-title" style="margin: 0">Referensi Aspirasi</h5>
                        
                        <table style="width: 100%">
                            <tr>
                                <td style="height:32px; vertical-align: middle;">No. Aspirasi</td>
                                <td style="height:32px; text-align: right; vertical-align: middle;"><strong>{{ $aspiration->aspirationNo }}</strong></td>
                            </tr>
                            <tr>
                                <td style="height:32px; vertical-align: middle;">Judul</td>
                                <td style="height:32px; text-align: right; vertical-align: middle;"><strong>{{ $aspiration->name }}</strong></td>
                            </tr>
                            <tr>
                                <td style="height:32px; vertical-align: middle;">Deskripsi</td>
                                <td style="height:32px; text-align: right; vertical-align: middle;"><strong>{{ $aspiration->description }}</strong></td>
                            </tr>
                            <tr>
                                <td style="height:32px; vertical-align: middle;">Likes</td>
                                <td style="height:32px; text-align: right; vertical-align: middle;"><strong>{{ count($aspiration->likes) . ' Orang' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="height:32px; vertical-align: middle;">Komentar</td>
                                <td style="height:32px; text-align: right; vertical-align: middle;"><strong>{{ count($aspiration->comments) . ' Orang'}}</strong></td>
                            </tr>
                            
                        </table>
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