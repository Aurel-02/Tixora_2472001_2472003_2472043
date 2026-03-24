<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --jacarta: #3A345B;
            --queen-pink: #F3C8DD;
            --middle-purple: #D183A9;
            --old-lavender: #71557A;
            --brown-chocolate: #4B1535;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--jacarta), var(--brown-chocolate));
            color: var(--queen-pink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: rgba(113, 85, 122, 0.25);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 20px;
            padding: 45px 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeIn 0.8s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-size: 2.8rem;
            font-weight: 700;
            letter-spacing: 3px;
            color: var(--queen-pink);
            text-shadow: 0 0 15px rgba(243, 200, 221, 0.6);
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 35px;
            color: var(--queen-pink);
            font-weight: 300;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(243, 200, 221, 0.9);
            padding-left: 5px;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(243, 200, 221, 0.3);
            border-radius: 12px;
            color: #fff;
            outline: none;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control::placeholder {
            color: rgba(243, 200, 221, 0.4);
            font-weight: 300;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--middle-purple);
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.4);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .btn-primary {
            background: var(--middle-purple);
            border: 2px solid var(--middle-purple);
            color: var(--jacarta);
            font-weight: 700;
        }

        .btn-primary:hover {
            transform: scale(1.02);
        }

        .divider {
            margin: 25px 0;
            display: flex;
            align-items: center;
            color: rgba(243, 200, 221, 0.6);
            font-size: 0.9rem;
        }
        
        .divider::before, 
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(243, 200, 221, 0.2);
            margin: 0 15px;
        }

        .btn-outline {
            background: transparent;
            border: 1px dashed rgba(243, 200, 221, 0.4);
            color: rgba(243, 200, 221, 0.8);
            font-weight: 500;
        }

        .btn-outline:hover {
            border-style: solid;
            border-color: var(--queen-pink);
            color: var(--queen-pink);
            background: rgba(243, 200, 221, 0.05);
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 35px 25px;
            }
            .logo {
                font-size: 2.4rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo">TIXORA</div>
        <p class="subtitle">Log in to Your Concert Experience</p>
        
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control" placeholder="uname@email.com" value="{{ old('email') }}">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
                @if($errors->has('loginError'))
                    <div style="color: #ffb3b3; font-size: 0.85rem; margin-top: 8px; text-align: left; padding-left: 5px;">
                        {{ $errors->first('loginError') }}
                    </div>
                @endif
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <div class="divider">or</div>
        
        <a href="#signup" class="btn btn-outline">Sign Up for Tixora</a>
        
    </div>

</body>
</html>
