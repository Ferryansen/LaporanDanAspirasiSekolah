@extends('layouts.email')

@section('title')
    Laporan Baru!
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Ada pengaduan baru nih!
    </p>
    <p>Yuk, segera cek laporannya dan berikan penanganan terbaik kita terhadap permasalahan murid.</p>
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
    <p>
        <a href="{{ route('student.reportDetail', $reportData['reportID']) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Laporan
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection