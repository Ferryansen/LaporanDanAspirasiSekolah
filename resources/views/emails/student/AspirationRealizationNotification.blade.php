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
        <a href="#" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Realisasi "Rute masih ambigu"
        </a>
    </p>
    <p>
        Terima kasih ya atas bantuan yang telah kamu berikan. Semoga dari realisasi ini, kegiatan sehari-hari kita semua di sekolah bisa menjadi lebih baik ya!
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection