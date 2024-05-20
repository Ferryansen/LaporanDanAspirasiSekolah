@extends('layouts.email')

@section('title')
    Request Realisasi Aspirasi
@endsection

@section('content')
    <p>Halo <b>UserName</b>,</p>
    <p>
        Ada request realisasi aspirasi baru nih yang butuh approval kamu.
    </p>
    <p>
        <table>
            <tr>
                <td>No. Aspirasi</td>
                <td>: 123456</td>
            </tr>
            <tr>
                <td>Judul</td>
                <td>: lolll</td>
            </tr>
            <tr>
                <td>Penanggung Jawab</td>
                <td>: Mr.Invincible</td>
            </tr>
        </table>
    </p>
    <p>
        <a href="#" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Request
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection