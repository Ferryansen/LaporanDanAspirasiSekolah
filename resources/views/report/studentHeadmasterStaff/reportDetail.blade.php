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
    {{-- @if (Auth::user()->role == "staff")
    @endif --}}
    
    @if (Auth::user()->role == "staff")
        @if ($report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
          <div class="col-3 col-md-1" align="start">
            <a href="{{ $link }}"><button type="button" id="chat-btn" class="btn btn-primary">Chat</button></a>
          </div>
        @endif
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
        @if ($report->status == "Approved")
            <div class="col-3 col-md-12" align="end">
              <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="{{"#processReportModal_" . $report->id}}">Mulai Proses</button>
              {{-- Modal --}}
              <div class="modal fade" id="{{"processReportModal_" . $report->id}}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" style="font-weight: 700">Masukkan tanggal estimasi dan prioritas</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('processReport', $report->id) }}" method="POST">
                      @csrf
                      @method('PATCH')
                        <div class="modal-body">
                          <div class="col-sm-12">
                            <input type="date" class="form-control @error('processEstimationDate') is-invalid @enderror" name="processEstimationDate" required>
                            @error('processEstimationDate')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>

                          <br>
                          
                          <div class="col-sm-12">
                            <select class="form-select" aria-label="Default select example" required name="priority">
                              <option selected disabled value>Pilih Prioritas</option>
                              <option value="1">High</option>
                              <option value="2">Medium</option>
                              <option value="3">Low</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div><!-- End Vertically centered Modal-->
            </div>

        @elseif ($report->status == "In Progress")
            <div class="col-3 col-md-11" align="end">
                <form action="{{ route('monitoringReport', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')
                    <button type="submit" class="btn btn-success">Mulai Monitoring</button>
                </form>
            </div>
        @elseif ($report->status == "Monitoring process")
          <div class="col-3 col-md-11" align="end">
                  <form action="{{ route('finishReport', $report->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                      <button type="submit" class="btn btn-success">Selesaikan</button>
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
  
  </div>

  <br>

  <div class="col-lg-12">
      <div class="card">
        @if($report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center">
            <div class="reportNo">
                {{$report->reportNo}}
              </div>

              <div class="reportPIC">
                <div class="reportProcess" style="color: black; font-weight: 700">
                  Ditangani oleh: {{$report->processExecutor->name}}
                </div>
                <div class="reportEstimation" style="color: black">
                  Estimasi: {{ \Carbon\Carbon::parse($report->processEstimationDate)->format('d/m/y') }}
                </div>
              </div>
            </div>
          @else
            <div class="card-header">{{$report->reportNo}}</div>
          @endif
            <div class="card-body">
                <h5 class="card-title">{{$report->name}}</h5>
                <td>{{$report->description}}</td>
                <br>
                @if ($report->evidences->isEmpty())
                    Tidak ada bukti
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
                <br>
            </div>
        </div>
      </div>
    </div>

@elseif ((Auth::user()->role == "student"))

  @if ($report->user_id == Auth::user()->id)
    {{-- Laporan di reject --}}
    @if ($report->status == "Rejected")
      <br>
      <h3 style="color: #dc3545; font-weight: bold">Maaf, Laporan Anda Ditolak</h3>
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
            <p class="text">Laporan terkirim</p>
          </li>

          <li>
            <i class="icon uil uil-eye"></i>
            @if ($report->status == "In review by staff" || $report->status == "In review to headmaster" || $report->status == "Approved" 
            || $report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
              <div class="progressing two active">
                <p>2</p>
                <i class="uil uil-eye"></i>
              </div>
            @else
              <div class="progressing two">
                <p>2</p>
                <i class="uil uil-eye"></i>
              </div>
            @endif
            <p class="text">Sedang ditinjau</p>
          </li>
  
          <li>
            <i class="icon uil uil-check"></i>
            @if ($report->status == "Approved" || $report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
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
            <p class="text">Disetujui</p>
          </li>
          

          <li>
            <i class="icon uil uil-spinner-alt"></i>
            @if ($report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
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
            <p class="text">Sedang diproses</p>
          </li>
  
          <li>
            <i class="icon uil uil-file-check-alt"></i>
            @if($report->status == "Completed")
              <div class="progressing five active">
                <p>5</p>
                <i class="uil uil-check"></i>
              </div>
            @else
              <div class="progressing five">
                <p>5</p>
                <i class="uil uil-check"></i>
              </div>
            @endif
            <p class="text">Selesai</p>
          </li>
  
        </ul>
      </div>
    @endif

    <br>

    @if ($report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
      <a href="{{ $link }}"><button style="margin-bottom: 2rem" type="button" class="btn btn-primary">Chat</button></a>
    @endif

    @if ($report->status == "Freshly submitted")
      <div class="row justify-content-end">
        {{-- <div class="col-3 col-md-1" align="end">
          <a href="{{ route('student.updateReportForm', $report->id) }}">
            <button type="button" class="btn btn-success">Ralat</button>
          </a>
        </div> --}}
        <div class="col-3 col-md-1" align="end">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#cancelReportModal_" . $report->id}}" style="display: inline; margin: 0;">
              Batal
            </button>

            {{-- Modal --}}
            <div class="modal fade" id="{{"cancelReportModal_" . $report->id}}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align: center;">
                    <h5 class="modal-title" style="font-weight: 700">Yakin mau batalin laporan ini?</h5>
                    Laporan yang udah dibatalkan akan sulit untuk dikembalikan seperti semula
                    </div>
                    <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                    <form class="w-100" action="{{ route('student.cancelReport', $report->id) }}" method="POST">
                      @csrf
                      @method('PATCH')

                        <button type="submit" class="btn btn-secondary w-100">Ya, batal</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
      </div>
      
      <br>

    @endif
      <div class="col-lg-12">
        <div class="card">
          @if($report->status == "In Progress" || $report->status == "Monitoring process" || $report->status == "Completed")
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center">
            <div class="reportNo">
                {{$report->reportNo}}
              </div>

              <div class="reportPIC">
                <div class="reportProcess" style="color: black; font-weight: 700">
                  Ditangani oleh: {{$report->processExecutor->name}}
                </div>
                <div class="reportEstimation" style="color: black">
                  Estimasi: {{ \Carbon\Carbon::parse($report->processEstimationDate)->format('d/m/y') }}
                </div>
              </div>
            </div>
          @else
            <div class="card-header">{{$report->reportNo}}</div>
          @endif
              <div class="card-body">
                  <h5 class="card-title">{{$report->name}}</h5>
                  <td>{{$report->description}}</td>
                  <br>
                  @if ($report->evidences->isEmpty())
                    Tidak ada bukti
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

@endif



@endsection

@section('script')
<script>
      document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any() && session('reportId') == $report->id)
          var modal = new bootstrap.Modal(document.getElementById('processReportModal_{{ $report->id }}'));
          modal.show();
        @endif
      });

      const reportID = <?php echo json_encode($report->id); ?>;
      const redirectUrl = <?php echo json_encode($link); ?>;
      var csrfToken = $('meta[name="csrf-token"]').attr('content');

      $('#chat-btn').click(function() {
          $.ajax({
              url: '{{ route('openChat.notif') }}',
              type: 'POST',
              data: {
                  reportID: reportID,
              },
              headers: {
                  'X-CSRF-TOKEN': csrfToken 
              },
              success: function(response) {
                window.location.href = redirectUrl;
              },
              error: function(response) {
                alert('Ada error di proses pengiriman email :(\nTolong coba lagi yaa');
              }
          });
      });



    </script>
@endsection