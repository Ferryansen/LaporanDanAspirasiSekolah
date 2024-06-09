@extends('layouts.mainpage')

@section('title')
    Search Result View
@endsection

@section('breadcrumb')
<div class="pagetitle">
  <h1>Hasil Pencarian</h1>
</div>
@endsection

@section('pageTitle')
<div class="pagetitle">
  <h1>Hasil Pencarian</h1>
</div>
@endsection

@section('sectionPage')
<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Laporan</h5>

          @if ($searchParams['data'] == "reports")
            <!-- Table with stripped rows -->
            <div class="report-container">
                <table class="table">
                    <colgroup>
                        <col style="min-width: 200px;">
                        <col style="min-width: 200px;">
                        <col style="min-width: 100px;">
                    </colgroup>
                    <thead>
                        <tr>
                        <th style="min-width: 200px;">
                            <b>Judul Laporan</b>
                        </th>
                        <th style="min-width: 200px;" data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                        <th style="min-width: 100px;">Status</th>
                        <th style="text-align: right">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                            @if (Auth::user()->role != 'student')
                                @if ($report->status == "Approved")
                                    <td>Disetujui oleh {{ $report->approvalBy }}</td>
                                @elseif ($report->status == "Rejected")
                                    <td>Ditolak oleh {{ $report->approvalBy }}</td>  
                                @elseif ($report->status == "Cancelled")
                                    <td>Dibatalkan</td>
                                @elseif ($report->status == "Freshly submitted")
                                    <td>Terkirim</td>
                                @elseif ($report->status == "In review by staff")
                                    <td>Sedang ditinjau oleh {{ $report->lastUpdatedBy }}</td>
                                @elseif ($report->status == "In review to headmaster")
                                    <td>Menunggu persetujuan dari atasan oleh {{ $report->lastUpdatedBy }}</td>
                                @elseif ($report->status == "In Progress")
                                    <td>Sedang ditindaklanjuti oleh {{ $report->lastUpdatedBy }}</td>
                                @elseif ($report->status == "Monitoring process")
                                    <td>Dalam pemantauan oleh {{ $report->lastUpdatedBy }}</td>
                                @elseif ($report->status == "Completed")
                                    <td>Selesai oleh {{ $report->lastUpdatedBy }}</td>
                                @endif
                            @else
                                @if ($report->status == "Approved")
                                    <td>Disetujui</td>
                                @elseif ($report->status == "Rejected")
                                    <td>Ditolak</td>  
                                @elseif ($report->status == "Cancelled")
                                    <td>Dibatalkan</td>
                                @elseif ($report->status == "Freshly submitted")
                                    <td>Terkirim</td>
                                @elseif ($report->status == "In review by staff" || $report->status == "In review to headmaster")
                                    <td>Sedang ditinjau</td>
                                @elseif ($report->status == "In Progress" || $report->status == "Monitoring process")
                                    <td>Sedang ditindaklanjuti</td>
                                @elseif ($report->status == "Completed")
                                    <td>Selesai</td>
                                @endif
                            @endif
                            <td style="text-align: right">
                                <a href="{{ route('student.reportDetail', $report->id) }}">
                                    <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 24px;"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table with stripped rows -->

            @if ($reports->hasPages())
                <div class="row mt-5">
                    <div class="d-flex justify-content-end">
                        {{ $reports->withQueryString()->links() }}
                    </div>
                </div>
            @endif

          @elseif ($searchParams['data'] == "1")
            @if ($reports->count() != 0)
                <!-- Table with stripped rows -->
                <div class="report-container">
                    <table class="table">
                        <colgroup>
                            <col style="min-width: 200px;">
                            <col style="min-width: 200px;">
                            <col style="min-width: 100px;">
                        </colgroup>
                        <thead>
                            <tr>
                            <th style="min-width: 200px;">
                                <b>Judul Laporan</b>
                            </th>
                            <th style="min-width: 200px;" data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                            <th style="min-width: 100px;">Status</th>
                            <th style="text-align: right">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                                @if (Auth::user()->role != 'student')
                                    @if ($report->status == "Approved")
                                        <td>Disetujui oleh {{ $report->approvalBy }}</td>
                                    @elseif ($report->status == "Rejected")
                                        <td>Ditolak oleh {{ $report->approvalBy }}</td>  
                                    @elseif ($report->status == "Cancelled")
                                        <td>Dibatalkan</td>
                                    @elseif ($report->status == "Freshly submitted")
                                        <td>Terkirim</td>
                                    @elseif ($report->status == "In review by staff")
                                        <td>Sedang ditinjau oleh {{ $report->lastUpdatedBy }}</td>
                                    @elseif ($report->status == "In review to headmaster")
                                        <td>Menunggu persetujuan dari atasan oleh {{ $report->lastUpdatedBy }}</td>
                                    @elseif ($report->status == "In Progress")
                                        <td>Sedang ditindaklanjuti oleh {{ $report->lastUpdatedBy }}</td>
                                    @elseif ($report->status == "Monitoring process")
                                        <td>Dalam pemantauan oleh {{ $report->lastUpdatedBy }}</td>
                                    @elseif ($report->status == "Completed")
                                        <td>Selesai oleh {{ $report->lastUpdatedBy }}</td>
                                    @endif
                                @else
                                    @if ($report->status == "Approved")
                                        <td>Disetujui</td>
                                    @elseif ($report->status == "Rejected")
                                        <td>Ditolak</td>  
                                    @elseif ($report->status == "Cancelled")
                                        <td>Dibatalkan</td>
                                    @elseif ($report->status == "Freshly submitted")
                                        <td>Terkirim</td>
                                    @elseif ($report->status == "In review by staff" || $report->status == "In review to headmaster")
                                        <td>Sedang ditinjau</td>
                                    @elseif ($report->status == "In Progress" || $report->status == "Monitoring process")
                                        <td>Sedang ditindaklanjuti</td>
                                    @elseif ($report->status == "Completed")
                                        <td>Selesai</td>
                                    @endif
                                @endif
                                <td style="text-align: right">
                                    <a href="{{ route('student.reportDetail', $report->id) }}">
                                        <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 24px;"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- End Table with stripped rows -->
                @if ($reports->hasPages())
                    <div class="row mt-5">
                        <div class="d-flex justify-content-end">
                            {{ $reports->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="report-container">
                    <table class="table">
                        <colgroup>
                            <col style="min-width: 200px;">
                            <col style="min-width: 200px;">
                            <col style="min-width: 100px;">
                        </colgroup>
                        <thead>
                            <tr>
                            <th style="min-width: 200px;">
                                <b>Judul Laporan</b>
                            </th>
                            <th style="min-width: 200px;" data-type="date" data-format="YYYY/DD/MM">Tanggal Pembuatan</th>
                            <th style="min-width: 100px;">Status</th>
                            <th style="text-align: right">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                                @if (Auth::user()->role != 'student')
                                    @if ($report->status == "Approved")
                                        <td>Disetujui oleh {{ $report->approvalBy }}</td>
                                    @elseif ($report->status == "Rejected")
                                        <td>Ditolak oleh {{ $report->approvalBy }}</td>  
                                    @elseif ($report->status == "Cancelled")
                                        <td>Dibatalkan</td>
                                    @elseif ($report->status == "Freshly submitted")
                                        <td>Terkirim</td>
                                    @elseif ($report->status == "In review by staff")
                                        <td>Sedang ditinjau oleh {{ $report->lastUpdatedBy }}</td>
                                    @elseif ($report->status == "In review to headmaster")
                                        <td>Menunggu persetujuan dari atasan oleh {{ $report->lastUpdatedBy }}</td>
                                    @elseif ($report->status == "In Progress")
                                        <td>Sedang ditindaklanjuti oleh {{ $report->lastUpdatedBy }}</td>
                                    @elseif ($report->status == "Monitoring process")
                                        <td>Dalam pemantauan oleh {{ $report->lastUpdatedBy }}</td>
                                    @elseif ($report->status == "Completed")
                                        <td>Selesai oleh {{ $report->lastUpdatedBy }}</td>
                                    @endif
                                @else
                                    @if ($report->status == "Approved")
                                        <td>Disetujui</td>
                                    @elseif ($report->status == "Rejected")
                                        <td>Ditolak</td>  
                                    @elseif ($report->status == "Cancelled")
                                        <td>Dibatalkan</td>
                                    @elseif ($report->status == "Freshly submitted")
                                        <td>Terkirim</td>
                                    @elseif ($report->status == "In review by staff" || $report->status == "In review to headmaster")
                                        <td>Sedang ditinjau</td>
                                    @elseif ($report->status == "In Progress" || $report->status == "Monitoring process")
                                        <td>Sedang ditindaklanjuti</td>
                                    @elseif ($report->status == "Completed")
                                        <td>Selesai</td>
                                    @endif
                                @endif
                                <td style="text-align: right">
                                    <a href="{{ route('student.reportDetail', $report->id) }}">
                                        <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 24px;"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- End Table with stripped rows -->

                @if ($reports->hasPages())
                    <div class="row mt-5">
                        <div class="d-flex justify-content-end">
                            {{ $reports->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            @endif

          @endif
          
        </div>
      </div>
      
    </div>
  </div>
</section>

@if (Auth::user()->isSuspended == false)

    @if(session('loading', false))
      <div class="loading-screen">
          <div class="loading-spinner"></div>
      </div>
    @endif

    @if($failMessage)
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ $failMessage }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
    
          <div class="card">
            <div class="card-body">

                <h5 class="card-title" style="margin: 0;">Aspirasi</h5>
                <br>
              <!-- Table with stripped rows -->
              <table class="table">
                  <tbody>
                    @if ($aspirations->count() == 0)
                        <div class="container">
                            <span style="color: dimgray">Belum ada aspirasi</span>
                        </div>
                    @endif
                  @foreach($aspirations as $aspiration)
                    @if ($aspiration->isPinned == true)
                      @if ($aspiration->status != 'Canceled')
                      <tr>

                      <div class="post">
                              @if ($aspiration->status == "Completed")
                              <div class="col-9 col-md-3">
                                    <span class="labelCompleted" >Selesai</span>
                              </div>
                              @elseif (in_array($aspiration->status, ['In Progress', 'Monitoring']))
                              <div class="col-9 col-md-3">
                                    <span class="labelInProg" >Sedang ditindaklanjuti</span>
                              </div>
                              @endif
                            <div class="post-header">
                                <div class="uploader-info">
                                    <span class="uploader-name">Anonymus</span> 
                                    <span>•</span>
                                    @php
                                        $formattedDate = \Carbon\Carbon::parse($aspiration->created_at)->locale('id')->translatedFormat('d F Y');
                                    @endphp
                                    <span class="upload-time">{{$formattedDate}}</span>
                                    <span>
                                    @if (in_array(Auth::user()->role, ['staff', 'headmaster']))
                                      @if ($aspiration->isPinned == true)
                                          <a href="{{ route('unpinAspiration', ['id' => $aspiration->id]) }}"><span><i class="bi bi-pin-angle-fill"></i></span></a>
                                      @elseif ($aspiration->isPinned == false)
                                          <a href="{{ route('unpinAspiration', ['id' => $aspiration->id]) }}"><span><i class="bi bi-pin-angle"></i></span></a>
                                      @endif
                                    @else
                                      <i class="bi bi-pin-angle-fill"></i>

                                    @endif
                                    </span>
                                </div>
                                
                            </div>
                            <div class="post-body">
                            <div class="post-title">{{$aspiration->name}}</div>
                                <p>{{$aspiration->description}}</p>
                            </div>
                            @if (in_array(Auth::user()->role, ['staff']))
                              @if($aspiration->category->staffType_id == Auth::user()->staffType_id || strpos($aspiration->category->name, "Lainnya") !== false)
                                @if ($aspiration->status == 'Freshly submitted')
                                  <div class="col-9 col-md-3">
                                    <form action="{{ route('aspiration.updateProcessedBy') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                        <button type="submit" class="btn btn-primary">Kelola aspirasi ini</button>
                                    </form>
                                  </div>
                                @endif
                                @if ($aspiration->processedBy == Auth::user()->id)
                                  <div class="warn">Anda sedang memproses aspirasi ini.</div>
                                  @endif
                              @endif
                            @endif
                            <div class="post-footer">
                              <div class="actions">
                                <form action="{{ route('aspirations.react', $aspiration) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    
                                    <button type="submit" name="reaction" value="like"
                                            class="{{ $aspiration->reactions()->where('user_id', Auth::id())->where('reaction', 'like')->exists() ? 'activeLike' : '' }}">
                                            <i class="{{ $aspiration->reactions()->where('user_id', Auth::id())->where('reaction', 'like')->exists() ? 'bi bi-hand-thumbs-up-fill' : 'bi bi-hand-thumbs-up' }}"><span> {{ $aspiration->reactions()->where('reaction', 'like')->count()}}</span></i>
                                    </button>
                                    
                                    <button style="margin-left: -14px;" type="submit" name="reaction" value="dislike"
                                            class="{{ $aspiration->reactions()->where('user_id', Auth::id())->where('reaction', 'dislike')->exists() ? 'activeDislike' : '' }}">
                                            <i class="{{ $aspiration->reactions()->where('user_id', Auth::id())->where('reaction', 'dislike')->exists() ? 'bi bi-hand-thumbs-down-fill' : 'bi bi-hand-thumbs-down' }}"><span> {{ $aspiration->reactions()->where('reaction', 'dislike')->count()}}</span></i>
                                    </button>
                                </form>

                                <div class="overlay" id="overlay-{{ $aspiration->id }}"></div>
                                  <div class="comment-popup" id="popup-{{ $aspiration->id }}">
                                    <div class="comment-content">
                                      <div class="top-content">
                                        <h3 style="vertical-align: middle; font-weight: 600; color: #012970">Comment</h3>
                                        <button class="close-btn" data-aspiration-id="{{ $aspiration->id }}"><i class="fas fa-times"></i></button>
                                      </div>
                                      @if ($aspiration->comments()->count() == 0)
                                      <div class="container">
                                          <span style="color: dimgray">Belum ada komentar</span>
                                      </div>
                                      @endif
                                        <!-- Comment content will be displayed here -->
                                        @foreach ($aspiration->comments as $comment)
                                            {{-- Display only standalone comments --}}
                                            @if ($comment->parent_id === null)
                                                <div class="comment-container">
                                                    <div class="comment">
                                                      @if ($comment->user->role == "student")
                                                          <span style="color: #012970;">
                                                              <strong style="font-size: smaller">{{ $comment->user->role }}</strong>
                                                              <i class="bi bi-person-fill" style="color: #012970; cursor:default"></i>
                                                          </span>
                                                      @else
                                                          <span style="color: forestgreen">
                                                              <strong style="font-size: smaller">{{ $comment->user->role }}</strong>
                                                              <i class="bi bi-wrench" style="color: forestgreen; cursor:default"></i>
                                                          </span>
                                                      @endif
                                                        <p style="margin-bottom: 0">{{ $comment->body }}</p>
                                                        <button class="show-reply-form" data-comment-id="{{ $comment->id }}">Reply</button>
                                                        <div class="reply-form hidden">
                                                            
                                                            <form action="{{ route('comments.reply', $comment) }}" method="POST">
                                                                @csrf
                                                                <i style="margin-left:10px; vertical-align: super; cursor: initial; color: dimgray" class="fas fa-level-up fa-rotate-90"></i>
                                                                <textarea name="body" placeholder="Reply to this comment"></textarea>
                                                                <button style="position: relative; background: #012970; border: none; border-radius: 3px" type="submit"><i style="display: contents; color: white" class="fas fa-arrow-up"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    {{-- Display replies --}}
                                                    <div class="replies-wrapper">
                                                        @foreach ($comment->replies as $reply)
                                                            <div class="reply">
                                                            @if ($reply->user->role == "student")
                                                                <span style="color: #012970;">
                                                                    <strong style="font-size: smaller">{{ $reply->user->role }}</strong>
                                                                    <i class="bi bi-person-fill" style="color: #012970; cursor:default"></i>
                                                                </span>
                                                            @else
                                                                <span style="color: forestgreen">
                                                                    <strong style="font-size: smaller">{{ $reply->user->role }}</strong>
                                                                    <i class="bi bi-wrench" style="color: forestgreen; cursor:default"></i>
                                                                </span>
                                                            @endif
                                                                <p>{{ $reply->body }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                  <hr>

                                                </div>
                                            @endif
                                        @endforeach

                                        {{-- Form to add a new standalone comment --}}
                                        <div class="comment-form">
                                          <form action="{{ route('comments.store', $aspiration) }}" method="POST">
                                              @csrf
                                              <textarea name="body" placeholder="Write a comment"></textarea>
                                              <button style="position: relative; background: #012970; border: none; border-radius: 3px" type="submit"><i style="display: contents; color: white" class="fas fa-arrow-up"></i></button>
                                          </form>
                                        </div>
                                        
                                    </div>
                                </div>


                                <!-- Button to trigger the popup -->
                                <a style="margin-left: -7px;" href="#" class="comment-button" data-aspiration-id="{{ $aspiration->id }}">
                                    <i class="bi bi-chat-left"><span>  {{$aspiration->comments()->count()}}</span></i>
                                </a>

                                @if (Auth::user()->role == "student")
                                  @if ( $aspiration->isReportedByCurrentUser() )
                                    <i style="font-size: medium; cursor: default" class="bi bi-exclamation-triangle-fill text-danger"><span style="font-size:smaller"> Reported</span></i>
                                  @else
                                    
                                  {{-- Report Section --}}
                                    <!-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportAspirationModal">
                                      Report Aspiration
                                    </button> -->
                                    <a href="#" data-bs-toggle="modal" data-bs-target="{{ "#reportAspirationModal_". $aspiration->id }}">
                                        <i style="font-size: medium" class="bi bi-exclamation-triangle"></i>
                                    </a>
                                    
                                    <br>

                                    <!-- Modal -->
                                    <div class="modal fade" id="{{ "reportAspirationModal_". $aspiration->id }}" tabindex="-1" role="dialog" aria-labelledby="reportAspirationModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="reportAspirationModalLabel">Report Aspiration</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                            <!-- Report Aspiration Form -->
                                            <form action="{{ route('aspirations.reported.create', ['aspirationId' => $aspiration->id]) }}" method="POST">
                                              @csrf
                                              <div class="form-group">
                                                <label for="reportAspirationReason">Alasan melaporkan:</label>
                                                <textarea class="form-control" id="reportAspirationReason" name="reportAspirationReason" rows="3" required></textarea>
                                              </div>

                                              <br>

                                              <button type="submit" class="btn btn-danger">Submit Report</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>  
                                  @endif
                                @endif
                              </div>
                          </div>
                        </tr>
                      @endif
                    @endif
                  @endforeach


                  @foreach($aspirations as $aspiration)
                    @if ($aspiration->isPinned == false)
                      @if ($aspiration->status != 'Canceled')
                        <tr>
                          <div class="post">
                              @if ($aspiration->status == "Completed")
                              <div class="col-9 col-md-3">
                                    <span class="labelCompleted" >Selesai</span>
                              </div>
                              @elseif (in_array($aspiration->status, ['In Progress', 'Monitoring']))
                              <div class="col-9 col-md-3">
                                    <span class="labelInProg" >Sedang ditindaklanjuti</span>
                              </div>
                              @endif
                            <div class="post-header">
                                <div class="uploader-info">
                                    <span class="uploader-name">Anonymus</span> 
                                    <span>•</span>
                                    @php
                                        $formattedDate = \Carbon\Carbon::parse($aspiration->created_at)->locale('id')->translatedFormat('d F Y');
                                    @endphp
                                    <span class="upload-time">{{$formattedDate}}</span>
                                    <span>
                                    @if (in_array(Auth::user()->role, ['staff', 'headmaster']))
                                      @if ($aspiration->isPinned == true)
                                          <a href="{{ route('unpinAspiration', ['id' => $aspiration->id]) }}"><span><i class="bi bi-pin-angle-fill"></i></span></a>
                                      @elseif ($aspiration->isPinned == false)
                                          <a href="{{ route('pinAspiration', ['id' => $aspiration->id]) }}"><span><i class="bi bi-pin-angle"></i></span></a>
                                      @endif
                                    @endif
                                    </span>
                                </div>
                                
                            </div>
                            <div class="post-body">
                            <div class="post-title">{{$aspiration->name}}</div>
                                <p>{{$aspiration->description}}</p>
                            </div>
                            @if (in_array(Auth::user()->role, ['staff']))
                              @if($aspiration->category->staffType_id == Auth::user()->staffType_id || strpos($aspiration->category->name, "Lainnya") !== false)
                                @if ($aspiration->status == 'Freshly submitted')
                                  <div class="col-9 col-md-3">
                                    <form action="{{ route('aspiration.updateProcessedBy') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="aspiration_id" value="{{ $aspiration->id }}">
                                        <button type="submit" class="btn btn-primary">Kelola aspirasi ini</button>
                                    </form>
                                  </div>
                                @endif
                                @if ($aspiration->processedBy == Auth::user()->id)
                                  <div class="warn">Anda sedang memproses aspirasi ini.</div>
                                  @endif
                              @endif
                            @endif
                            <div class="post-footer">
                              <div class="actions">
                                <form action="{{ route('aspirations.react', $aspiration) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    
                                    <button type="submit" name="reaction" value="like"
                                            class="{{ $aspiration->reactions()->where('user_id', Auth::id())->where('reaction', 'like')->exists() ? 'activeLike' : '' }}">
                                            <i class="{{ $aspiration->reactions()->where('user_id', Auth::id())->where('reaction', 'like')->exists() ? 'bi bi-hand-thumbs-up-fill' : 'bi bi-hand-thumbs-up' }}"><span> {{ $aspiration->reactions()->where('reaction', 'like')->count()}}</span></i>
                                    </button>
                                    
                                    <button style="margin-left: -14px;" type="submit" name="reaction" value="dislike"
                                            class="{{ $aspiration->reactions()->where('user_id', Auth::id())->where('reaction', 'dislike')->exists() ? 'activeDislike' : '' }}">
                                            <i class="{{ $aspiration->reactions()->where('user_id', Auth::id())->where('reaction', 'dislike')->exists() ? 'bi bi-hand-thumbs-down-fill' : 'bi bi-hand-thumbs-down' }}"><span> {{ $aspiration->reactions()->where('reaction', 'dislike')->count()}}</span></i>
                                    </button>
                                </form>

                                <div class="overlay" id="overlay-{{ $aspiration->id }}"></div>
                                  <div class="comment-popup" id="popup-{{ $aspiration->id }}">
                                    <div class="comment-content">
                                      <div class="top-content">
                                        <h3 style="vertical-align: middle; font-weight: 600; color: #012970">Comment</h3>
                                        <button class="close-btn" data-aspiration-id="{{ $aspiration->id }}"><i class="fas fa-times"></i></button>
                                      </div>
                                      @if ($aspiration->comments()->count() == 0)
                                      <div class="container">
                                          <span style="color: dimgray">Belum ada komentar</span>
                                      </div>
                                      @endif
                                        <!-- Comment content will be displayed here -->
                                        @foreach ($aspiration->comments as $comment)
                                            {{-- Display only standalone comments --}}
                                            @if ($comment->parent_id === null)
                                                <div class="comment-container">
                                                    <div class="comment">
                                                    @if ($comment->user->role == "student")
                                                        <span style="color: #012970;">
                                                            <strong style="font-size: smaller">{{ $comment->user->role }}</strong>
                                                            <i class="bi bi-person-fill" style="color: #012970; cursor:default"></i>
                                                        </span>
                                                    @else
                                                        <span style="color: forestgreen">
                                                            <strong style="font-size: smaller">{{ $comment->user->role }}</strong>
                                                            <i class="bi bi-wrench" style="color: forestgreen; cursor:default"></i>
                                                        </span>
                                                    @endif
                                                        <p style="margin-bottom: 0">{{ $comment->body }}</p>
                                                        <button class="show-reply-form" data-comment-id="{{ $comment->id }}">Reply</button>
                                                        <div class="reply-form hidden">
                                                            
                                                            <form action="{{ route('comments.reply', $comment) }}" method="POST">
                                                                @csrf
                                                                <i style="margin-left:10px; vertical-align: super; cursor: initial; color: dimgray" class="fas fa-level-up fa-rotate-90"></i>
                                                                <textarea name="body" placeholder="Reply to this comment"></textarea>
                                                                <button style="position: relative; background: #012970; border: none; border-radius: 3px" type="submit"><i style="display: contents; color: white" class="fas fa-arrow-up"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    {{-- Display replies --}}
                                                    <div class="replies-wrapper">
                                                        @foreach ($comment->replies as $reply)
                                                            <div class="reply">
                                                            @if ($reply->user->role == "student")
                                                                <span style="color: #012970">
                                                                    <strong style="font-size: smaller">{{ $reply->user->role }}</strong>
                                                                    <i class="bi bi-person-fill" style="color: #012970; cursor:default"></i>
                                                                </span>
                                                            @else
                                                                <span style="color: forestgreen">
                                                                    <strong style="font-size: smaller">{{ $reply->user->role }}</strong>
                                                                    <i class="bi bi-wrench" style="color: forestgreen; cursor:default"></i>
                                                                </span>
                                                            @endif
                                                                <p>{{ $reply->body }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                  <hr>

                                                </div>
                                            @endif
                                        @endforeach

                                        {{-- Form to add a new standalone comment --}}
                                        <div class="comment-form">
                                          <form action="{{ route('comments.store', $aspiration) }}" method="POST">
                                              @csrf
                                              <textarea name="body" placeholder="Write a comment"></textarea>
                                              <button style="position: relative; background: #012970; border: none; border-radius: 3px" type="submit"><i style="display: contents; color: white" class="fas fa-arrow-up"></i></button>
                                          </form>
                                        </div>
                                        
                                    </div>
                                </div>


                                <!-- Button to trigger the popup -->
                                <a style="margin-left: -7px;" href="#" class="comment-button" data-aspiration-id="{{ $aspiration->id }}">
                                    <i class="bi bi-chat-left"><span>  {{$aspiration->comments()->count()}}</span></i>
                                </a>

                                @if (Auth::user()->role == "student")
                                  @if ( $aspiration->isReportedByCurrentUser() )
                                    <i style="font-size: medium; cursor: default" class="bi bi-exclamation-triangle-fill text-danger"><span style="font-size:smaller"> Reported</span></i>
                                  @else
                                    
                                  {{-- Report Section --}}
                                    <!-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportAspirationModal">
                                      Report Aspiration
                                    </button> -->
                                    <a href="#" data-bs-toggle="modal" data-bs-target="{{ "#reportAspirationModal_". $aspiration->id }}">
                                        <i style="font-size: medium" class="bi bi-exclamation-triangle"></i>
                                    </a>
                                    
                                    <br>

                                    <!-- Modal -->
                                    <div class="modal fade" id="{{ "reportAspirationModal_". $aspiration->id }}" tabindex="-1" role="dialog" aria-labelledby="reportAspirationModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="reportAspirationModalLabel">Report Aspiration</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                            <!-- Report Aspiration Form -->
                                            <form action="{{ route('aspirations.reported.create', ['aspirationId' => $aspiration->id]) }}" method="POST">
                                              @csrf
                                              <div class="form-group">
                                                <label for="reportAspirationReason">Alasan melaporkan:</label>
                                                <textarea class="form-control" id="reportAspirationReason" name="reportAspirationReason" rows="3" required></textarea>
                                              </div>

                                              <br>

                                              <button type="submit" class="btn btn-danger">Submit Report</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  @endif
                                @endif
                              </div>
                          </div>
                        </tr>
                      @endif
                    @endif
                  @endforeach
                  </tbody>
                </table>
                <!-- End Table with stripped rows -->



                @if ($aspirations->hasPages())
                  <div class="row mt-5">
                    <div class="d-flex justify-content-end">
                        {{ $aspirations->withQueryString()->links() }}
                    </div>
                  </div>
                @endif
              
            </div>
          </div>
          
        </div>
      </div>
    </section>
  @else
    <section class="section">
      <div class="row">
        <div class="card">
            <div class="card-body text-center">
              <h5 class="card-title mb-0 pb-0 text-danger">{{ $message }}</h5>
            </div>
          </div>
      </div>
    </section>
  @endif
@endsection

@section('css')
<style>
    .posts-container {
        width: 80%;
        margin: 0 auto;
        padding-top: 20px;
    }

    .post {
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-bottom: 20px;
        padding: 10px;
    }

    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .uploader-name {
        font-weight: bold;
    }
    
    .upload-time{
      color: grey;
    }

    .post-title {
        margin-bottom: 10px;
    }

    .post-footer {
        margin-top: 10px;
    }

    .actions i {
        margin-right: 10px;
        font-style: normal;
        
        padding: 5px 10px 5px 0px;
        /* background-color: grey; */
        color: grey;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .actions i:hover {
        color: #0056b3;
    }

    .comment-popup {
        display: none;
        position: fixed;
        bottom: 0;
        right: 0;
        background-color: white;
        border: 1px solid #ccc;
        padding: 20px;
        z-index: 100000; /* Ensure popup appears above other content */
        width: 100%; /* Adjust width as needed */
        height: 100vh; /* Full height of viewport */
        overflow-y: auto; /* Enable vertical scrolling if content exceeds height */
    }

    .close-btn {
        position: absolute;
        top: 13px;
        right: 10px;
        cursor: pointer;
        border: none;
        background: none;
        font-size: 1.5rem
    }

    .comment-content {
        height: auto; /* Full height of viewport */
        overflow-y: auto; /* Enable scrolling if content exceeds max-height */
        padding-bottom: 50px;
        padding-top:60px;
        font-family: "Nunito", sans-serif;
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
        z-index: 9998; /* Ensure overlay appears below popup */
    }

    .hidden{
      display: none;
    }

    .comment-container {
        margin-bottom: 20px;
    }

    .reply-form{
        width: 100%;
        padding-top: 4px;
        padding-bottom: 20px;
    }

    .reply-form textarea {
        width: calc(100% - 90px); /* Adjust the width of the textarea */
        vertical-align: middle;

    }

    .reply-form button {
        vertical-align: middle;
        margin-left: 5px

    }

    .reply {
        margin-left: 30px; /* Shift all replies to the right */
    }

    .replies-wrapper {
        margin-left: 30px; /* Shift the container of replies to the right */
    }

    .comment-form {
        position: fixed; /* Position the form absolute */
        bottom: 0; /* Align the form to the bottom */
        right: 0;
        width: 100%;
        padding: 20px; /* Add padding for better spacing */
        background-color: #f6f9ff; /* Optional: add background color for better visibility */
    }

    .comment-form textarea {
        width: calc(100% - 40px); /* Adjust the width of the textarea */
        vertical-align: middle;
    }

    .comment-form button {
        margin-left: 10px; /* Add some space between textarea and button */
        vertical-align: middle;
    }

    .top-content{
        position: fixed; /* Position the form absolute */
        height: 60px;
        top: 0; /* Align the form to the bottom */
        right: 0;
        width: 100%;
        padding: 20px; /* Add padding for better spacing */
        background-color: #f6f9ff;
        z-index:100;
    }

    .show-reply-form {
        border: none;
        background: none;
        padding: 0;
        font-size: smaller;
        color: dimgray;
        cursor: pointer; /* Add cursor to indicate it's clickable */
    }

    .show-reply-form:hover {
        color: #012970;
    }

    button.activeLike i{
        color: green;
    }

    button.activeDislike i{
      color: red
    }

    button {
      background: none;
      border: none;
      cursor: pointer;
    }

    .labelCompleted {
      background: darkseagreen;
      width: 100%;
      display: block;
      padding-left: 10px;
      color: white;
      border-color: yellowgreen;
      margin-bottom: 10px;
      border-radius: 4px 0px 100px 4px;
    }

    .labelInProg {
      background: lightslategrey;
      width: 100%;
      display: block;
      padding-left: 10px;
      color: white;
      border-color: yellowgreen;
      margin-bottom: 10px;
      border-radius: 4px 0px 100px 4px;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loading-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    .warn {
        margin-top: 15px;
        font-size: smaller;
        color: green;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .report-container {
        overflow-x: auto;
        max-width: 100%;
    }
</style>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
     var buttons = document.querySelectorAll('.comment-button');
 
     buttons.forEach(function(button) {
         button.addEventListener('click', function (event) {
             event.preventDefault();
             var aspirationId = this.getAttribute('data-aspiration-id');
             var popup = document.getElementById('popup-' + aspirationId);
             var overlay = document.getElementById('overlay-' + aspirationId);
 
             popup.style.display = 'block';
             overlay.style.display = 'block'; // Show the overlay
             document.body.style.overflow = 'hidden'; // Disable scrolling on the body
 
             var closeBtn = popup.querySelector('.close-btn');
             closeBtn.addEventListener('click', function () {
                 popup.style.display = 'none';
                 overlay.style.display = 'none'; // Hide the overlay
                 document.body.style.overflow = ''; // Enable scrolling on the body
             });
 
             overlay.addEventListener('click', function () {
                 popup.style.display = 'none';
                 overlay.style.display = 'none'; // Hide the overlay
                 document.body.style.overflow = ''; // Enable scrolling on the body
             });
         });
     });
 
     // Check session data and display popup if set
     var aspirationId = <?php echo json_encode(session('aspiration_id', null)); ?>;
 
 // Check if the comment popup should be open and if aspirationId is not null
 // Check if the comment popup should be open and if aspirationId is not null
 if (aspirationId && <?php echo json_encode(session('comment_popup_open', false)); ?>) {
     // Select the popup and overlay elements based on the aspiration ID
     var currentlyOpenPopup = document.getElementById('popup-' + aspirationId);
     var currentlyOpenOverlay = document.getElementById('overlay-' + aspirationId);
 
     setTimeout(delayedPopup, 1500);
 
     function delayedPopup() {
       currentlyOpenPopup.style.display = 'block';
       currentlyOpenOverlay.style.display = 'block';
       document.body.style.overflow = 'hidden';
     }
 
 
     // Select the close button and overlay within the context of currentlyOpenPopup
     var closeBtn = currentlyOpenPopup.querySelector('.close-btn');
     var overlay = currentlyOpenOverlay;
 
     // Add event listeners to the close button and overlay
     closeBtn.addEventListener('click', function () {
         currentlyOpenPopup.style.display = 'none';
         currentlyOpenOverlay.style.display = 'none'; // Hide the overlay
         document.body.style.overflow = ''; // Enable scrolling on the body
         window.location.href = "{{ route('clear-session-data') }}";
 
         // Send AJAX request to delete session data
         
     });
 
     overlay.addEventListener('click', function () {
         currentlyOpenPopup.style.display = 'none';
         currentlyOpenOverlay.style.display = 'none'; // Hide the overlay
         document.body.style.overflow = ''; // Enable scrolling on the body
         window.location.href = "{{ route('clear-session-data') }}";
 
       
     });
 }
 });
 
 </script>
 
 <script>
     document.addEventListener('DOMContentLoaded', function() {
         document.querySelectorAll('.show-reply-form').forEach(function(button) {
             button.addEventListener('click', function() {
                 const replyForm = this.nextElementSibling;
                 replyForm.classList.toggle('hidden');
             });
         });
     });
 </script>
@endsection