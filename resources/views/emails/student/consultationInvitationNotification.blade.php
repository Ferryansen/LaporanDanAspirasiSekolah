@extends('layouts.email')

@section('title')
    Ajakan Konsultasi
@endsection

@section('logo')
    <img src="{{ $message->embed($pathToImage) }}" alt="SkolahKita Logo" style="max-height: 24px; margin-bottom: 8px;">
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Kamu diajak untuk ikut sesi konsultasi "{{ $consultationData['title'] }}" nih.
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
            <tr>
                <td>Konsultan</td>
                <td>: {{ $consultationData['consultant'] }}</td>
            </tr>
        </table>
    </p>
    <p>
        <a href="{{ route('consultation.detail', ['consultation_id' => $consultationData['ID']]) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Konsultasi
        </a>
    </p>
    <p>Sebelum join konsultasinya, yuk siapin apa aja yang mau kamu bahas.<br><br>Cheers,<br>Admin SkolahKita</p>
@endsection