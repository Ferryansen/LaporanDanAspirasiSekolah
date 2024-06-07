@extends('layouts.email')

@section('title')
    Ada Info Konsultasi Baru!
@endsection

@section('logo')
    <img src="{{ $message->embed($pathToImage) }}" alt="SkolahKita Logo" style="max-height: 24px; margin-bottom: 8px;">
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
                    <td>: {{ \Carbon\Carbon::parse($consultationData['date'])->format('d/m/Y, H:i') }}</td>
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