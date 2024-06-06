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
            text-align: left;
            border: 1px solid black;
            /* border-radius: 8px; */

        }
        th{
            text-align: center;
            background-color: black;
            color: white;
            font-weight: 600;

        }
        td{
            text-align: left;
            background-color: white;
            color: black;
            font-weight: 400;
        }
    </style>
</head>
<body>


    <table style="box-shadow: 5px 10px black;" class="table">
            <thead>
                <tr>
                    <th>Nomor Laporan</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Tanggal dibuat</th>
                    <th>Ditangani oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)

                    <tr>

                        <td>{{$report->reportNo}}</td>
                        <td>{{$report->name}}</td>
                        <td>{{$report->description}}</td>

                        @if ($report->status == "Approved")
                          <td>Disetujui</td>
                        @elseif ($report->status == "Rejected")
                          <td>Ditolak</td>  
                        @elseif ($report->status == "Cancelled")
                          <td>Dibatalkan</td>
                        @elseif ($report->status == "Freshly submitted")
                          <td>Terkirim</td>
                        @elseif ($report->status == "In review by staff")
                          <td>Sedang ditinjau</td>
                        @elseif ($report->status == "Request Approval")
                          <td>Menunggu persetujuan dari atasan</td>
                        @elseif ($report->status == "In Progress")
                          <td>Sedang ditindaklanjuti</td>
                        @elseif ($report->status == "Monitoring process")
                          <td>Dalam pemantauan</td>
                        @elseif ($report->status == "Completed")
                          <td>Selesai</td>
                        @endif

                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d F Y') }}</td>
                        @if($report->processedBy == null)
                            <td>Belum ditangani</td>
                        @elseif($report->status == "In review by staff" || $report->status == "Request Approval" || $report->status == "Approved")
                            <td>Belum ditangani</td>
                        @else
                            <td>{{ $report->processExecutor->name }}</td>
                        @endif
                    </tr>

                @endforeach
            </tbody>

      </table>


</body>
</html>