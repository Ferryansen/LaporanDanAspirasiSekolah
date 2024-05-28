@extends('layouts.email')

@section('title')
    Ada Info Konsultasi Baru!
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Ada info baru nih soal konsultasi "{{ $consultationData['title'] }}"!
    </p>
    <p>
        <table>
            @if ($consultationData['date'] != null)
                <tr>
                    <td>Jadwal konsultasi</td>
                    <td>: {{ $consultationData['date'] }}</td>
                </tr>
            @endif
            @if ($consultationData['is_online'] != null)
                <tr>
                    <td>Jadwal konsultasi</td>
                    <td>: {{ $consultationData['is_online'] }}</td>
                </tr>
            @endif
            @if ($consultationData['location'] != null)
                <tr>
                    <td>Lokasi</td>
                    <td>: {{ $consultationData['location'] }}</td>
                </tr>
            @endif
        </table>
    </p>
    <p>Sambil nunggu konsultasinya dimulai, yuk siapin apa aja yang mau kamu bahas.<br><br>Cheers,<br>Admin SkolahKita</p>
@endsection