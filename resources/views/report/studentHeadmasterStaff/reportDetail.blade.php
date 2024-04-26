@extends('layouts.mainpage')

@section('title')
Detail Laporan
@endsection

@section('css')
<link href="{{ asset('template_assets/css/detailAspiration.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Detail Laporan</h1>
  <nav>
    <ol class="breadcrumb">
      @if (Auth::user()->role == "student")
        <li class="breadcrumb-item"><a href="{{ route('report.student.myReport') }}">Laporan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('report.student.myReport') }}">Laporan Saya</a></li>
      @else
        <li class="breadcrumb-item"><a href="{{ route('report.adminHeadmasterStaff.manageReport') }}">Laporan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('report.adminHeadmasterStaff.manageReport') }}">Manage Laporan</a></li>
      @endif
      <li class="breadcrumb-item active">Detail Laporan</li>
    </ol>
  </nav>
</div>
@endsection

@section('sectionPage')

@if (Auth::user()->role == "headmaster" || Auth::user()->role == "staff")
  <div class="row">
    @if (Auth::user()->role == "staff")
        @if ($report->status == "Freshly submitted")
            {{-- <div class="col-6 col-md-10">
                <form action="{{ route('staff.requestApprovalReport', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')
                    <button type="submit" class="btn btn-success">Request Approval ke head</button>
                </form>
            </div> --}}
            <div class="col-3 col-md-11" align="end">
                <form action="{{ route('staff.reviewReport', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')
                    <button type="submit" class="btn btn-success">Review</button>
                </form>
            </div>
            <div class="col-3 col-md-1" align="end">
                <form action="{{ route('staff.rejectReport', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>

        @elseif ($report->status == "In review by staff")
          <div class="col-6 col-md-11" align="end">
            <form action="{{ route('headmaster.reviewReport', $report->id) }}" method="POST">
            @csrf
            @method('PATCH')
                <button type="submit" class="btn btn-success">Review ke Headmaster</button>
            </form>
          </div>

          <div class="col-3 col-md-1" align="end">
            <form action="{{ route('staff.approveReport', $report->id) }}" method="POST">
            @csrf
            @method('PATCH')
                <button type="submit" class="btn btn-success">Approve</button>
            </form>
          </div>

        @endif
    @elseif (Auth::user()->role == "headmaster")
        @if ($report->status == "In review to headmaster")
            <div class="col-3 col-md-11" align="end">
                <form action="{{ route('headmaster.approveReport', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
            <div class="col-3 col-md-1" align="end">
                <form action="{{ route('headmaster.rejectReport', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>
        @endif
    @endif
  
    @if ($report->status == "Approved")
        <div class="col-3 col-md-12" align="end">
            <form action="{{ route('processReport', $report->id) }}" method="POST">
            @csrf
            @method('PATCH')
                <button type="submit" class="btn btn-success">Mulai Proses</button>
            </form>
        </div>
    @elseif ($report->status == "In Progress")
        <div class="col-3 col-md-12" align="end">
            <form action="{{ route('monitoringReport', $report->id) }}" method="POST">
            @csrf
            @method('PATCH')
                <button type="submit" class="btn btn-success">Mulai Monitoring</button>
            </form>
        </div>
    @elseif ($report->status == "Monitoring process")
    <div class="col-3 col-md-12" align="end">
            <form action="{{ route('finishReport', $report->id) }}" method="POST">
            @csrf
            @method('PATCH')
                <button type="submit" class="btn btn-success">Selesaikan</button>
            </form>
        </div>
    @endif
  </div>

  <br>

  <div class="col-lg-12">
      <div class="card">
        <div class="card-header">{{$report->reportNo}}</div>
            <div class="card-body">
                <h5 class="card-title">{{$report->name}}</h5>
                <td>{{$report->description}}</td>
                <br>
                @if ($report->evidences->isEmpty())
                    No evidence available
                  @else
                      @foreach($report->evidences as $evidence)
                          @if (strpos($evidence->image, 'ListImage') === 0)
                              <!-- Display image -->
                              <img style="max-width: 100%; margin-top: 20px" src="{{ asset('storage/'.$evidence->image) }}" alt="{{ $evidence->name }}">
                          @elseif (strpos($evidence->video, 'ListVideo') === 0)
                              {{-- @php
                                  dd($evidence->video);
                              @endphp --}}
                              <!-- Display video -->
                              <video style="max-width: 100%; margin-top: 20px" controls>
                                  <source src="{{ asset('storage/'.$evidence->video) }}" type="{{ getVideoMimeType($evidence->video) }}">
                                  Your browser does not support the video tag.
                              </video>
                          @endif
                      @endforeach
                  @endif
            </div>
        </div>
      </div>
    </div>

@elseif ((Auth::user()->role == "student"))

  @if ($report->user_id == Auth::user()->id)
    {{-- Laporan di reject --}}
    @if ($report->status == "Rejected")
      <br>
      <h3>Maaf, Laporan Anda Ditolak</h3>
    @else 
      {{-- Laporan di terima (Progress Bar) --}}
      <div class="progress-bar">
        <ul class="ul-progress-bar">
  
          <li>
            <i class="icon uil uil-clipboard-notes"></i>
            @if ($report->status == "Freshly submitted" || $report->status == "Approved" || $report->status == "In review by staff" || $report->status == "In review to headmaster" || $report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
              <div class="progressing one active">
                  <p>1</p>
                  <i class="uil uil-check"></i>
              </div>
            @else
              <div class="progressing one">
                <p>1</p>
                <i class="uil uil-check"></i>
              </div>
            @endif
            <p class="text">Report Submitted</p>
          </li>
  
          <li>
            <i class="icon uil uil-check"></i>
            @if ($report->status == "Approved" || $report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
              <div class="progressing two active">
                <p>2</p>
                <i class="uil uil-check"></i>
              </div>
            @else
              <div class="progressing two">
                <p>2</p>
                <i class="uil uil-check"></i>
              </div>
            @endif
            <p class="text">Approved</p>
          </li>
  
          <li>
            <i class="icon uil uil-spinner-alt"></i>
            @if ($report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
              <div class="progressing three active">
                <p>3</p>
                <i class="uil uil-check"></i>
              </div>
            @else
              <div class="progressing three">
                <p>3</p>
                <i class="uil uil-check"></i>
              </div>
            @endif
            <p class="text">In Progress</p>
          </li>
  
          {{-- <li>
            <i class="icon uil uil-eye"></i>
            @if ($report->status == "Monitoring" || $report->status == "Completed")
              <div class="progressing four active">
                <p>4</p>
                <i class="uil uil-check"></i>
              </div>
            @else
              <div class="progressing four">
                <p>4</p>
                <i class="uil uil-check"></i>
              </div>
            @endif
            <p class="text">Monitoring</p>
          </li> --}}
  
          <li>
            <i class="icon uil uil-file-check-alt"></i>
            @if($report->status == "Completed")
              <div class="progressing four active">
                <p>4</p>
                <i class="uil uil-check"></i>
              </div>
            @else
              <div class="progressing four">
                <p>4</p>
                <i class="uil uil-check"></i>
              </div>
            @endif
            <p class="text">Completed</p>
          </li>
  
        </ul>
      </div>
    @endif

    <br>

    @if ($report->status == "Freshly submitted")
      <div class="row justify-content-end">
        {{-- <div class="col-3 col-md-1" align="end">
          <a href="{{ route('student.updateReportForm', $report->id) }}">
            <button type="button" class="btn btn-success">Ralat</button>
          </a>
        </div> --}}
        <div class="col-3 col-md-1" align="end">
            <form action="{{ route('student.cancelReport', $report->id) }}" method="POST">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-danger">Batal</button>
            </form>
        </div>
      </div>
      
      <br>

      @endif
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">{{$report->reportNo}}</div>
              <div class="card-body">
                  <h5 class="card-title">{{$report->name}}</h5>
                  <td>{{$report->description}}</td>
                  <br>
                  @if ($report->evidences->isEmpty())
                    No evidence available
                  @else
                      @foreach($report->evidences as $evidence)
                          @if (strpos($evidence->image, 'ListImage') === 0)
                              <!-- Display image -->
                              <img style="max-width: 100%; margin-top: 20px" src="{{ asset('storage/'.$evidence->image) }}" alt="{{ $evidence->name }}">
                          @elseif (strpos($evidence->video, 'ListVideo') === 0)
                              {{-- @php
                                  dd($evidence->video);
                              @endphp --}}
                              <!-- Display video -->
                              <video style="max-width: 100%; margin-top: 20px" controls>
                                  <source src="{{ asset('storage/'.$evidence->video) }}" type="{{ getVideoMimeType($evidence->video) }}">
                                  Your browser does not support the video tag.
                              </video>
                          @endif
                      @endforeach
                  @endif
              </div>
          </div>
        </div>
      </div>
  @endif

  {{-- @else
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">{{$report->reportNo}}</div>
            <div class="card-body">
                <h5 class="card-title">{{$report->name}}</h5>
                <td>{{$report->description}}</td>
                <br>
                @if ($report->evidences->isEmpty())
                  No evidence available
                @else
                    @foreach($report->evidences as $evidence)
                        @if (strpos($evidence->image, 'ListImage') === 0)
                            <!-- Display image -->
                            <img style="max-width: 100%; margin-top: 20px" src="{{ asset('storage/'.$evidence->image) }}" alt="{{ $evidence->name }}">
                        @elseif (strpos($evidence->video, 'ListVideo') === 0)
                            <!-- Display video -->
                            <video style="max-width: 100%; margin-top: 20px" controls>
                                <source src="{{ asset('storage/'.$evidence->video) }}" type="{{ getVideoMimeType($evidence->video) }}">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
      </div>
    </div>
  @endif --}}

@endif

@endsection

@section('script')
    
@endsection

@section('js')

@endsection

<?php

  function getVideoMimeType($videoPath) {
      $extension = pathinfo($videoPath, PATHINFO_EXTENSION);
      
      switch ($extension) {
          case 'mp4':
              return 'video/mp4';
          case 'avi':
              return 'video/avi';
          case 'mov':
              return 'video/quicktime';
      }
  }
?>