@extends('layouts.email')

@section('title')
    Konfirmasi Kehadiranmu Sekarang
@endsection

@section('logo')
    <img src="{{ $message->embed($pathToImage) }}" alt="SkolahKita Logo" style="max-height: 24px; margin-bottom: 8px;">
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        konsultasi harian untuk minggu depan sudah tersedia nih. Yuk, cek jadwal konsultasinya sekarang supaya bisa langsung kamu konfirmasi!
    </p>
    <p>
        <a href="{{ route('consultation.seeAll') }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Jadwal Konsultasi
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection