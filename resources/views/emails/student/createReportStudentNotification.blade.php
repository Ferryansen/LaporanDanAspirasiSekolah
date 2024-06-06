@extends('layouts.email')

@section('title')
    Pengaduan Laporan Berhasil!
@endsection

@section('logo')
    <img src="{{ $message->embed($pathToImage) }}" alt="SkolahKita Logo" style="max-height: 24px; margin-bottom: 8px;">
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Terima kasih atas pengaduannya!
    </p>
    <p>
        <table>
            <tr>
                <td>No. Laporan</td>
                <td>: {{ $reportData['reportNo'] }}</td>
            </tr>
            <tr>
                <td>Judul</td>
                <td>: {{ $reportData['title'] }}</td>
            </tr>
            <tr>
                <td>Tanggal Pengaduan</td>
                <td>: {{ $reportData['date'] }}</td>
            </tr>
        </table>
    </p>
    <p>Saat ini, laporanmu sudah kami terima dan akan segera dicek. Nanti kami akan kabari lagi terkait perkembangan laporan kamu yaa.<br><br>Cheers,<br>Admin SkolahKita</p>
@endsection