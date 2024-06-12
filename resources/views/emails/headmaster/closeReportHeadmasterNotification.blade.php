@extends('layouts.email')

@section('title')
    Penutupan Proses Tindak Lanjut
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Berhubung tindak lanjut "{{ $reportName }}" sudah melebihi tanggal estimasi selesai, laporannya akan ditutup terlebih dahulu nih.
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
                <td>Penanggung Jawab</td>
                <td>: {{ $reportData['relatedStaff'] }}</td>
            </tr>
        </table>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection