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
                eventDidMount: function(info) {
                    var attendees = info.event.extendedProps.attendees ? info.event.extendedProps.attendees.join(', ') : '';
                    var consultant = info.event.extendedProps.consultant;
                    var status = info.event.extendedProps.status;
                    var location = info.event.extendedProps.location;
                    var is_private = info.event.extendedProps.is_private ? 'Yes' : 'No';
                    var is_online = info.event.extendedProps.is_online ? 'Yes' : 'No';
                    var description = info.event.extendedProps.description;

                    var tooltip = `Consultant: ${consultant}\nAttendees: ${attendees}\nStatus: ${status}\nLocation: ${location}\nPrivate: ${is_private}\nOnline: ${is_online}\nDescription: ${description}`;
                    info.el.setAttribute('title', tooltip);
                }
            });

            calendar.render();
        });
    </script>
@endsection