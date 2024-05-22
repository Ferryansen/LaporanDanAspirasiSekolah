@extends('layouts.email')

@section('title')
    Ada Respon Baru!
@endsection

@section('content')
    <p>Halo <b>UserName</b>,</p>
    <p>
        Ada respon nih terkait laporan "ReportName"
    </p>
    <p>
        Yuk, segera balas responnya agar permasalahan ini cepat selesai!
    </p>
    <p>
        <a href="#" class="btn btn-primary" style="display: block; width: 100%; margin: 0 auto; padding: 10px 0; text-align: center; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Cek Respon
        </a>
    </p>
    <p>Cheers,<br>Admin SkolahKita</p>
@endsection