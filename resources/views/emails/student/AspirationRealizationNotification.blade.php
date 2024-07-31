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
    <p>
        Untuk meningkatkan layanan, penilaian dan feedback kamu akan sangat bermanfaat bagi kami.
        Mohon kesediaannya untuk mengisi survey pada link berikut:
        <br>
        https://docs.google.com/forms/d/e/1FAIpQLSd4Hf5zoweEYRBChRMc0JhGeJtojc1vXO5HnLR7NlVUnlum-Q/viewform?usp=sf_link
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection