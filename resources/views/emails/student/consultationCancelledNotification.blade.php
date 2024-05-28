@extends('layouts.email')

@section('title')
    Pembatalan Konsultasi
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Yahh, konsultasi "{{ $consultationData['title'] }}" dibatalin nih :&#40;
    </p>
    <p>Meskipun dibatalkan, jangan cemas! Kamu masih bisa daftar ke konsultasi serupa di kemudian hari. Ditunggu yaa!!<br><br>Cheers,<br>Admin SkolahKita</p>
@endsection