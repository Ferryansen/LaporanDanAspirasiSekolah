@extends('layouts.mainpage')

@section('title')
    Kelola Konsultasi
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Kelola Konsultasi</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('consultation.seeAll') }}">Konsultasi</a></li>
            <li class="breadcrumb-item active">Kelola Konsultasi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection

@section('sectionPage')
    @if(session('successMessage'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ session('successMessage') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="margin-top: 24px">
                        {{-- <div id="actions-user" style="display: flex; justify-content: space-between;">
                            
                        </div> --}}
                        <div id='calendar'></div>

                        <br>
                        <br>
                    </div>
                </div>

            </div>
        </div>
    </section>  
@endsection

@section('css')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/main.min.css' rel='stylesheet' />
    
    <style>
        .fc .fc-toolbar button {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .fc .fc-toolbar button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .fc .fc-toolbar .fc-button-primary:disabled {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .fc-daygrid-day:hover {
            cursor: pointer;
            background-color: #e7f1ff
        }

        .event-status-selesai {
            background-color: #198754 !important;
            color: white !important;
            border-color: green !important;
        }

        .event-status-sedang-dimulai {
            background-color: #FFC107 !important; 
            color: black !important;
            border-color: yellow !important;
        }

        .event-status-menunggu {
            background-color: #D9DADB !important;
            color: black !important;
            border-color: yellow !important;
        }

        .event-status-dibatalkan {
            background-color: #BB2D3B !important; 
            color: white !important;
            border-color: red !important;
        }
    </style>
@endsection

@section('script')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/locales/id.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                buttonText: {
                    today: 'Hari Ini'
                },
                events: '/consultation/manage/all',
                selectable: true,
                select: function(info) {
                    var startDate = new Date(info.startStr);
                    startDate.setHours(15, 0, 0);
                    var startDateStr = startDate.toISOString().slice(0, 19).replace('T', ' ');

                    var endDate = new Date(startDate);
                    endDate.setHours(16, 0, 0);
                    var endDateStr = endDate.toISOString().slice(0, 19).replace('T', ' ');

                    var url = '/consultation/manage/createConsultation?start=' + encodeURIComponent(startDateStr) + '&end=' + encodeURIComponent(endDateStr);
                    window.location.href = url;
                },
                eventClick: function(info) {
                    var event = info.event;
                    var eventId = event.id;

                    var url = '/consultation/detail/' + eventId;
                    window.location.href = url;
                },
                eventDidMount: function(info) {
                    var title = info.event.title;
                    var status = info.event.extendedProps.status;
                    var tooltip = `Judul: ${title}\nStatus: ${status}`;

                    info.el.setAttribute('title', tooltip);

                    if (status === 'Selesai') {
                        info.el.classList.add('event-status-selesai');
                    } else if (status === 'Sedang dimulai') {
                        info.el.classList.add('event-status-sedang-dimulai');
                    } else if ((status === 'Belum dimulai' || status === 'Pindah jadwal')) {
                        info.el.classList.add('event-status-menunggu');
                    } else if (status === 'Dibatalkan') {
                        info.el.classList.add('event-status-dibatalkan');
                    }
                }
            });

            calendar.render();

            function handleCellClick(event) {
                var dateStr = event.currentTarget.getAttribute('data-date');
                if (dateStr) {
                    var info = { startStr: dateStr };
                    calendar.trigger('select', info);
                }
            }

            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                document.querySelectorAll('.fc-daygrid-day').forEach(function(dayCell) {
                    dayCell.addEventListener('touchend', handleCellClick);
                });
            }
        });
    </script>
@endsection