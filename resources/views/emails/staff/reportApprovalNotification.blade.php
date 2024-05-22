@extends('layouts.email')

@section('title')
    Proses Laporan Diterima!
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        request-mu untuk proses laporan "{{ $reportData['title'] }}" sudah di-approve nih. Yuk, segera kita tuntaskan permasalahan yang sedang dialami murid!
    </p>
    <p>
        <a href="{{ route('student.reportDetail', $reportData['reportID']) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Proses Laporan
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection