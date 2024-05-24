@extends('layouts.email')

@section('title')
    Realisasi Aspirasi Selesai!
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Aspirasi soal "{{ $mailData['title'] }}" sudah terealisasi nih.
    </p>
    <p>
        Terima kasih ya atas bantuan yang telah kamu berikan. Semoga dari realisasi ini, kegiatan sehari-hari kita semua di sekolah bisa menjadi lebih baik ya!
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection