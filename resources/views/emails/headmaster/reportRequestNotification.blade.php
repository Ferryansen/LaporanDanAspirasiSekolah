@extends('layouts.email')

@section('title')
    Request Tindak Lanjut Laporan
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Ada request tindak lanjut laporan baru nih yang butuh persetujuan kamu.
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
    <p>
        <a href="{{ route('student.reportDetail', $reportData['reportID']) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Request
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection