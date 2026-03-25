<!DOCTYPE html>
<html>
<head>
    <title>Admin - Events</title>

    <style>
        :root {
            --jacarta: #3A345B;
            --queen-pink: #F3C8DD;
            --middle-purple: #D183A9;
            --old-lavender: #71557A;
            --brown-chocolate: #4B1535;
        }

        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--jacarta), var(--brown-chocolate));
            color: var(--queen-pink);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            padding: 20px 40px;
            background: rgba(0,0,0,0.2);
        }

        .container {
            padding: 40px;
        }

        h2 {
            color: white;
        }

        .btn {
            background: var(--middle-purple);
            padding: 10px 18px;
            border-radius: 20px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .btn:hover {
            background: var(--queen-pink);
            color: var(--jacarta);
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background: var(--old-lavender);
            color: white;
        }

        tr:nth-child(even) {
            background: rgba(255,255,255,0.05);
        }

        .action a {
            margin: 0 5px;
            font-size: 13px;
        }

        .logout {
            background: #ff6b6b;
        }
    </style>
</head>

<body>

<div class="topbar">
    <div><b>TIXORA ADMIN</b></div>
    <a href="/admin/logout" class="btn logout">Logout</a>
</div>

<div class="container">

    <h2>Daftar Event</h2>

    <a href="/admin/events/create" class="btn">+ Tambah Event</a>

    <table>
        <tr>
            <th>Nama Event</th>
            <th>Lokasi</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>

        @foreach($events as $event)
            <tr>
                <td>{{ $event->nama_event }}</td>
                <td>{{ $event->lokasi_event }}</td>
                <td>{{ $event->tanggal_pelaksanaan }}</td>
                <td class="action">
                    <a href="/admin/events/{{ $event->id }}/edit" class="btn">Edit</a>
                    <a href="/admin/events/{{ $event->id }}/delete" class="btn logout">Hapus</a>
                </td>
            </tr>
        @endforeach

    </table>

</div>

</body>
</html>
