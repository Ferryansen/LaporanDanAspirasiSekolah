@extends('layouts.email')

@section('title')
    Tindak Lanjut Laporan Selesai!
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Laporanmu soal "{{ $reportData['title'] }}" sudah diselesaikan nih.
    </p>
    <p>
        <a href="{{ route('student.reportDetail', $reportData['reportID']) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Penyelesaian
        </a>
    </p>
    <p>
        Semoga dari penyelesaian ini, kegiatan sehari-hari kita semua di sekolah bisa menjadi lebih baik ya!
    </p>
    <p>
        Untuk meningkatkan layanan, penilaian dan feedback kamu akan sangat bermanfaat bagi kami.
        Mohon kesediaannya untuk mengisi survey pada link berikut:
        <br>
        https://docs.google.com/forms/d/e/1FAIpQLSd4Hf5zoweEYRBChRMc0JhGeJtojc1vXO5HnLR7NlVUnlum-Q/viewform?usp=sf_link
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection