@extends('layouts.email')

@section('title')
    Pendaftaran Konsultasi Berhasil!
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Pendaftaran konsultasimu sudah berhasil nih!
    </p>
    <p>
        <table>
            <tr>
                <td>Judul</td>
                <td>: {{ $consultationData['title'] }}</td>
            </tr>
            <tr>
                <td>Jadwal Konsultasi</td>
                <td>: {{ $consultationData['date'] }}</td>
            </tr>
        </table>
    </p>
    <p>Sambil nunggu konsultasinya dimulai, yuk siapin apa aja yang mau kamu bahas.<br><br>Cheers,<br>Admin SkolahKita</p>
@endsection