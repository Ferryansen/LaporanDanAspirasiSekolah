@extends('layouts.email')

@section('title')
    Laporan Ditutup
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Laporanmu soal "{{ $reportData['title'] }}" telah ditutup dengan alasan "{{ $reportData['closedReason'] }}"
    </p>
    <p>
        Meskipun laporan ini telah ditutup, kamu tetap bisa mengadukan laporan yang sama kok (Dengan catatan, masalah yang mengganggu masih ada nih). Jadi, gak usah patah semangat ya!
    </p>
    <p>
        Cheers,<br>Admin SkolahKita
    </p>
@endsection