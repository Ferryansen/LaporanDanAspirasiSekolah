@extends('layouts.email')

@section('title')
    Request Realisasi Aspirasi
@endsection

@section('logo')
    <img src="{{ $message->embed($pathToImage) }}" alt="SkolahKita Logo" style="max-height: 24px; margin-bottom: 8px;">
@endsection

@section('content')
    <p>Halo <b>{{ $receiverName }}</b>,</p>
    <p>
        Ada request realisasi aspirasi baru nih yang butuh approval kamu.
    </p>
    <p>
        <table>
            <tr>
                <td>No. Aspirasi</td>
                <td>: {{ $mailData['aspirationNo'] }}</td>
            </tr>
            <tr>
                <td>Judul</td>
                <td>: {{ $mailData['title'] }}</td>
            </tr>
            <tr>
                <td>Penanggung Jawab</td>
                <td>: {{ $mailData['relatedStaff'] }}</td>
            </tr>
        </table>
    </p>
    <p>
        <a href="{{ route('manage.aspiration.detail', $mailData['aspirationID']) }}" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Request
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection