@extends('layouts.email')

@section('title')
    Penutupan Proses Tindak Lanjut
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Berhubung tindak lanjut "{{ $reportName }}" sudah melebihi tanggal estimasi selesai, laporannya akan ditutup terlebih dahulu yaa
    <p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection