<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

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
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            background: rgba(58,52,91,0.6);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .container {
            padding: 40px;
        }

        .card {
            background: rgba(113, 85, 122, 0.3);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .btn {
            background: var(--middle-purple);
            padding: 10px 20px;
            border-radius: 20px;
            color: white;
            text-decoration: none;
        }

        .btn:hover {
            background: var(--queen-pink);
            color: var(--jacarta);
        }
    </style>
</head>

<body>

<div class="topbar">
    <div class="logo">TIXORA ADMIN</div>
    <a href="/admin/logout" class="btn">Logout</a>
</div>

<div class="container">

    <h1 style="color:white;">Dashboard Admin</h1>

    <div class="card">
        <h3>Total Event: {{ $totalEvent }}</h3>
    </div>

    <div class="card">
        <a href="/admin/logout">Logout</a>
    </div>

</div>

</body>
</html>
