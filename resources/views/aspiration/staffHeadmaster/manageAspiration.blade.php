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
          <table class="table" style="overflow-x:auto">
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
                                                <option value="Approved" {{ $aspiration->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="In Progress" {{ $aspiration->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="Monitoring" {{ $aspiration->status == 'Monitoring' ? 'selected' : '' }}>Monitoring</option>
                                                <option value="Completed" {{ $aspiration->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                    </form>
                                </td>
                                @else
                                <td>{{ $aspiration->status }}</td>
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
                                            <button type="submit" name="status" value="Request Approval" class="btn btn-success">Request Approve</button>
                                            <button type="submit" name="status" value="Rejected" class="btn btn-danger">Reject</button>
                                        </form>
                                    </td>
                                @else
                                    <td></td>
                                @endif

                            @else
                                <td>{{ $aspiration->status }}</td>

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
                                        <button type="submit" name="status" value="Approved" class="btn btn-success">Approve</button>
                                        <button type="submit" name="status" value="Rejected" class="btn btn-danger">Reject</button>
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