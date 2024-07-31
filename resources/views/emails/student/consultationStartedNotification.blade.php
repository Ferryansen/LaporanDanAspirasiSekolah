@extends('layouts.email')

@section('title')
    Konsultasi Sudah Dimulai!
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Konsultasi "{{ $consultationData['title'] }}" sudah dimulai nih!
    </p>
    <p>
        <table>
            <tr>
                <td>Jadwal Konsultasi</td>
                <td>: {{ \Carbon\Carbon::parse($consultationData['date'])->format('d/m/Y, H:i') }}</td>
            </tr>
            <tr>
                <td>Konsultan</td>
                <td>: {{ $consultationData['consultant'] }}</td>
            </tr>
            @if ($consultationData['is_online'] == false)
                <td>Lokasi</td>
                <td>: {{ $consultationData['location'] }}</td>
            @endif
        </table>
    </p>
    @if ($consultationData['is_online'] == true)
        <p>
            <a href="{{ $consultationData['location'] }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
                Masuk Ruangan
            </a>
        </p>
    @endif
    <p>Yuk, langsung masuk ruangan konsultasinya. Jangan buat yang lain menunggu yaa<br><br>Cheers,<br>Admin SkolahKita</p>
@endsection