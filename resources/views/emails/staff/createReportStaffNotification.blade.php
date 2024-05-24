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
                <td>: {{ $mailData['reportNo'] }}</td>
            </tr>
            <tr>
                <td>Judul</td>
                <td>: {{ $mailData['title'] }}</td>
            </tr>
            <tr>
                <td>Tanggal Pengaduan</td>
                <td>: {{ $mailData['date'] }}</td>
            </tr>
        </table>
    </p>
    <p>
        <a href="{{ route('student.reportDetail', $mailData['reportID']) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Laporan
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection