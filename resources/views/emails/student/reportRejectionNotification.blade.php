@extends('layouts.email')

@section('title')
    Laporan Ditolak
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Yahhh.. laporanmu soal "{{ $reportName }}" ditolak nih :&#40;
    </p>
    <p>
        Meskipun ditolak, laporanmu tetap akan menjadi masukan bagi sekolah kok. Jadi, gak usah patah semangat ya, yang penting kamu udah berusaha!
    </p>
    <p>
        Cheers,<br>Admin SkolahKita
    </p>
@endsection