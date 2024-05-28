@extends('layouts.mainpage')

@section('title')
    Manage Aspirasi
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Kelola Aspirasi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('aspirations.manageAspiration') }}">Aspirasi</a></li>
      <li class="breadcrumb-item active">Kelola Aspirasi</li>
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
        <br>
          <!-- Table with stripped rows -->
         <div class="table-container" style="overflow-x:auto; max-width: 100%">
          <table class="table" style="overflow-x:auto">
          @if (Auth::user()->role == "headmaster")
          <div class="col-auto d-flex align-items-center col-7 col-md-3" style="margin-top: 0.5rem">
          <select class="form-select" aria-label="Default select example" name="categoryStaffType" required onchange="window.location.href=this.value;">
            <option selected disabled>Pilih Kategori</option>
            @foreach ($categories as $category)
                @if (strpos($category->name, 'Lainnya') === false)
                    <option value="{{ route('aspirations.viewFilterCategory', ['category_id' => $category->id]) }}" {{ $category->id == $selectedCategoryId ? 'selected' : '' }}>{{ $category->name }}</option>
                @endif
            @endforeach
            @foreach ($categories as $category)
                @if (strpos($category->name, 'Lainnya') !== false)
                    <option value="{{ route('aspirations.viewFilterCategory', ['category_id' => $category->id]) }}" {{ $category->id == $selectedCategoryId ? 'selected' : '' }}>{{ $category->name }}</option>
                @endif
            @endforeach
        </select>
              </div>
              <br>
            @endif
          
            <thead>
                <tr>
                  <th>
                    <b>Judul</b>
                  </th>
                  <th>Status</th>
                  <th>Penanggung jawab</th>
                  <th></th>
                </tr>
            </thead>
                <tbody>
                  @foreach($aspirations as $aspiration)
                    @if ($aspiration->status != 'Freshly submitted')
                        <tr style="vertical-align: middle">
                            <td>
                                <a href="">{{ $aspiration->name }}</a>
                            </td>

                            @if (Auth::user()->role == "staff")
                                @if (in_array($aspiration->status, ['Approved', 'In Progress', 'Monitoring', 'Completed']))
                                <td>
                                    <form action="{{ route('aspiration.updateStatus') }}" method="POST" id="statusForm_{{ $aspiration->id }}">
                                        @csrf
                                        <div>
                                            <select style="border: none; padding-left:0" name="status" id="status_{{ $aspiration->id }}" class="form-select" required onchange="document.getElementById('statusForm_{{ $aspiration->id }}').submit()">
                                                <option value="Approved" {{ $aspiration->status == 'Approved' ? 'selected' : '' }}>Disetujui</option>
                                                <option value="In Progress" {{ $aspiration->status == 'In Progress' ? 'selected' : '' }}>Sedang diproses</option>
                                                <option value="Monitoring" {{ $aspiration->status == 'Monitoring' ? 'selected' : '' }}>Dalam pemantauan</option>
                                                <option value="Completed" {{ $aspiration->status == 'Completed' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                    </form>
                                </td>
                                @else
                                    @if ($aspiration->status == 'Freshly submitted')
                                    <td>Terkirim</td>
                                    @elseif ($aspiration->status == 'In review')
                                    <td>Sedang ditinjau</td>
                                    @elseif ($aspiration->status == 'Request Approval')
                                    <td>Menunggu persetujuan</td>
                                    @elseif ($aspiration->status == 'Approved')
                                    <td>Disetujui</td>
                                    @elseif ($aspiration->status == 'Rejected')
                                    <td>Ditolak</td>
                                    @elseif ($aspiration->status == 'In Progress')
                                    <td>Sedang diproses</td>
                                    @elseif ($aspiration->status == 'Monitoring')
                                    <td>Dalam pemantauan</td>
                                    @elseif ($aspiration->status == 'Completed')
                                    <td>Selesai</td>
                                    @endif
                                @endif

                                <td>
                                    @if (in_array($aspiration->status, ['Rejected', 'Completed']))
                                        @foreach($allUser as $user)
                                            @if($user->id == $aspiration->processedBy)
                                                {{ $user->name }}
                                            @endif
                                        @endforeach
                                    @else
                                    <form action="{{ route('aspiration.assign') }}" method="POST" id="assignForm_{{ $aspiration->id }}">
                                        @csrf
                                        <div>
                                            <select style="border: none; padding-left:0" name="user_id" id="user_{{ $aspiration->id }}" class="form-select" required onchange="document.getElementById('assignForm_{{ $aspiration->id }}').submit()">
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $user->id == $aspiration->processedBy ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                    </form>
                                    @endif
                                </td>

                                @if (in_array($aspiration->status, ['In review']))
                                    <td>
                                        <form action="{{ route('aspiration.updateStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                            <button type="submit" name="status" value="Request Approval" class="btn btn-success">Ajukan persetujuan</button>
                                            <button type="submit" name="status" value="Rejected" class="btn btn-danger">Tolak</button>
                                        </form>
                                    </td>
                                @else
                                    <td></td>
                                @endif

                            @else
                                @if ($aspiration->status == 'Freshly submitted')
                                <td>Terkirim</td>
                                @elseif ($aspiration->status == 'In review')
                                <td>Sedang ditinjau</td>
                                @elseif ($aspiration->status == 'Request Approval')
                                <td>Menunggu persetujuan</td>
                                @elseif ($aspiration->status == 'Approved')
                                <td>Disetujui</td>
                                @elseif ($aspiration->status == 'Rejected')
                                <td>Ditolak</td>
                                @elseif ($aspiration->status == 'In Progress')
                                <td>Sedang diproses</td>
                                @elseif ($aspiration->status == 'Monitoring')
                                <td>Dalam pemantauan</td>
                                @elseif ($aspiration->status == 'Completed')
                                <td>Selesai</td>
                                @endif

                                @foreach($allUser as $user)
                                    @if($user->id == $aspiration->processedBy)
                                        <td>{{ $user->name }}</td>
                                    @endif
                                @endforeach

                                @if($aspiration->status == 'Request Approval')
                                    <td>
                                    <form action="{{ route('aspiration.updateStatus') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                        <button type="submit" name="status" value="Approved" class="btn btn-success">Setujui</button>
                                        <button type="submit" name="status" value="Rejected" class="btn btn-danger">Tolak</button>
                                    </form>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endif
            
                        </tr>
                    @endif
                  @endforeach
              </tbody>
            </table>
           </div>
            <!-- End Table with stripped rows -->


            <br>

        </div>
      </div>
      
    </div>
  </div>
</section>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection

@section('script')
    
@endsection