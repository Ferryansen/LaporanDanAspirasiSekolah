@extends('layouts.email')

@section('title')
    Realisasi Aspirasi Selesai!
@endsection

@section('content')
    <p>Halo <b>UserName</b>,</p>
    <p>
        Ada Aspirasi "AspirationName" nih yang baru aja diselesaikan.
    </p>
    <p>
        <table>
            <tr>
                <td>No. Aspirasi</td>
                <td>: 123456</td>
            </tr>
            <tr>
                <td>Judul</td>
                <td>: testinggg</td>
            </tr>
            <tr>
                <td>Penanggung Jawab</td>
                <td>: Mr. Invincible</td>
            </tr>
        </table>
    </p>
    <p>
        <a href="#" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Realisasi
        </a>
    </p>
    <p>
        Semoga dari realisasi ini, kegiatan sehari-hari kita semua di sekolah bisa menjadi lebih baik ya!
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection