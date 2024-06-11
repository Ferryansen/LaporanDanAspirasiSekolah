@extends('layouts.email')

@section('title')
    Laporan Ditutup
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Karena satu dan lain hal, laporanmu soal "{{ $reportName }}" kami tutup dulu ya
    </p>
    <p>
        Meskipun laporan ini telah ditutup, kamu tetap bisa mengadukan laporan yang sama kok (Dengan catatan, masalah yang mengganggu masih ada nih). Jadi, gak usah patah semangat ya!
    </p>
    <p>
        Cheers,<br>Admin SkolahKita
    </p>
@endsection