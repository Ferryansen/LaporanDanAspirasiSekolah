<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index PDF</title>
    <style>
        table, th, td{
            padding: 2px;
            margin: 2px;
            text-align: center;
            border: 1px solid black;
            border-radius: 8px;

        }
        th{
            text-align: center;
            background-color: black;
            color: white;
            font-weight: 600;

        }
        td{
            text-align: center;
            background-color: white;
            color: black;
            font-weight: 400;
        }
    </style>
</head>
<body>


    <table style="box-shadow: 5px 10px black;" class="table">
        @if($aspirations != null)
            <thead>
            <tr>
                <th>Nomor Aspirasi</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Tanggal dibuat</th>
                <th>Bukti</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($aspirations as $aspiration)

                    <tr>

                        <td>{{$aspiration->aspirationNo}}</td>
                        <td>{{$aspiration->name}}</td>
                        <td>{{$aspiration->description}}</td>
                        <td>{{$aspiration->status}}</td>
                        <td>{{ \Carbon\Carbon::parse($aspiration->created_at)->format('d/m/y') }}</td>
                        <th><img src="{{ public_path().'\storage/'.$aspiration->evidence }}" style="width: 150px;"></th>

                    </tr>

                @endforeach
            </tbody>
          @else
            <thead>
                <tr>
                    <th>Nomor Laporan</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Tanggal dibuat</th>
                    <th>Bukti</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)

                    <tr>

                        <td>{{$report->report_no}}</td>
                        <td>{{$report->name}}</td>
                        <td>{{$report->description}}</td>
                        <td>{{$report->status}}</td>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                        <th><img src="{{ public_path().'\storage/'.$report->evidence }}" style="width: 150px;"></th>

                    </tr>

                @endforeach
            </tbody>
        @endif

      </table>


</body>
</html>