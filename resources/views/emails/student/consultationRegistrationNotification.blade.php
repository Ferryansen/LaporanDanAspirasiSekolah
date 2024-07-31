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
                @php
                    $startDate = \Carbon\Carbon::parse($consultationData['date']);
                    $endDate = \Carbon\Carbon::parse($consultationData['endDate']);

                    $differenceInMinutes = $endDate->diffInMinutes($startDate);

                    $isHours = $differenceInMinutes >= 60;

                    $displayUnit = $isHours ? 'jam' : 'menit';
                    $displayValue = $isHours ? floor($differenceInMinutes / 60) : $differenceInMinutes;
                @endphp
                <td>Jadwal Konsultasi</td>
                <td>: {{ \Carbon\Carbon::parse($consultationData['date'])->format('d/m/Y, H:i') }} ({{ $displayValue . ' ' . $displayUnit }})</td>
            </tr>
        </table>
    </p>
    <p>Sambil nunggu konsultasinya dimulai, yuk siapin apa aja yang mau kamu bahas.<br><br>Cheers,<br>Admin SkolahKita</p>
@endsection