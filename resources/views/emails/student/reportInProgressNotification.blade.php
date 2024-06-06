@extends('layouts.email')

@section('title')
    Laporan Sedang Ditindaklanjuti!
@endsection

@section('logo')
    <img src="{{ $message->embed($pathToImage) }}" alt="SkolahKita Logo" style="max-height: 24px; margin-bottom: 8px;">
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Laporanmu "{{ $reportData['title'] }}" sedang ditindaklanjuti, loh. Oleh karena itu, yuk, bantu cek berkala tindak lanjutnya supaya hasil penyelesaiannya bisa maksimal!
    </p>
    <p>
        <a href="{{ route('student.reportDetail', $reportData['reportID']) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Tindak Lanjut
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection