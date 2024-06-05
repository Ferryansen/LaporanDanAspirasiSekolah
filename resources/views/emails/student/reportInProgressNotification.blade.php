@extends('layouts.email')

@section('title')
    Laporan Sedang ditindaklanjuti!
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Laporanmu "{{ $reportData['title'] }}" Sedang ditindaklanjuti, loh. Oleh karena itu, yuk, bantu cek berkala prosesnya supaya hasil penyelesaiannya bisa maksimal!
    </p>
    <p>
        <a href="{{ route('student.reportDetail', $reportData['reportID']) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Proses
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection