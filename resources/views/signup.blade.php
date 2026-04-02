<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Sign Up</title>
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
            padding: 40px 20px;
        }

        .signup-container {
            width: 100%;
            max-width: 550px;
            background: rgba(113, 85, 122, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 28px;
            padding: 45px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.8s ease-out forwards;
            position: relative;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--queen-pink);
            text-shadow: 0 0 15px rgba(243, 200, 221, 0.4);
            margin-bottom: 5px;
            text-transform: uppercase;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .subtitle {
            font-size: 0.95rem;
            opacity: 0.7;
            margin-bottom: 35px;
            color: var(--queen-pink);
            font-weight: 300;
            text-align: center;
        }

        /* Role Selection Styling */
        .step-content {
            transition: all 0.4s ease;
        }

        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .role-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(243, 200, 221, 0.15);
            border-radius: 20px;
            padding: 25px 20px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .role-card:hover {
            background: rgba(243, 200, 221, 0.1);
            border-color: var(--middle-purple);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .role-card.active {
            background: rgba(209, 131, 169, 0.2);
            border-color: var(--middle-purple);
            box-shadow: 0 0 20px rgba(209, 131, 169, 0.3);
        }

        .role-icon {
            width: 60px;
            height: 60px;
            background: rgba(243, 200, 221, 0.1);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s;
        }

        .role-card:hover .role-icon, .role-card.active .role-icon {
            background: var(--middle-purple);
            color: #fff;
            transform: scale(1.1);
        }

        .role-info h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: #fff;
        }

        .role-info p {
            font-size: 0.8rem;
            line-height: 1.4;
            opacity: 0.6;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            color: rgba(243, 200, 221, 0.9);
            padding-left: 5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 12px;
            color: #fff;
            outline: none;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--middle-purple);
            box-shadow: 0 0 15px rgba(209, 131, 169, 0.3);
        }

        .error-msg {
            color: #ffb3b3;
            font-size: 0.75rem;
            margin-top: 5px;
            padding-left: 5px;
        }

        .btn-main {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--middle-purple), #e194ba);
            border: none;
            border-radius: 50px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(209, 131, 169, 0.3);
            margin-top: 10px;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(209, 131, 169, 0.5);
        }

        .btn-main:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--queen-pink);
            text-decoration: none;
            font-size: 0.85rem;
            opacity: 0.6;
            transition: opacity 0.3s;
        }

        .back-link:hover {
            opacity: 1;
        }

        .footer-text {
            margin-top: 25px;
            color: rgba(243, 200, 221, 0.6);
            font-size: 0.85rem;
            text-align: center;
        }

        .footer-text a {
            color: var(--middle-purple);
            text-decoration: none;
            font-weight: 600;
        }

        .hidden {
            display: none;
            opacity: 0;
            transform: translateY(10px);
        }

        @media (max-width: 480px) {
            .role-grid {
                grid-template-columns: 1fr;
            }
            .signup-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <a href="/" class="logo">TIXORA</a>
        
        <!-- Step 1: Role Selection -->
        <div id="step-selection" class="step-content">
            <p class="subtitle">Join Tixora - Pick your path</p>
            
            <div class="role-grid">
                <div class="role-card" onclick="selectRole('Buyer', this)">
                    <div class="role-icon">🎸</div>
                    <div class="role-info">
                        <h3>Concert Enthusiast</h3>
                        <p>Buy tickets, follow artists, and join the party.</p>
                    </div>
                </div>

                <div class="role-card" onclick="selectRole('Organizer', this)">
                    <div class="role-icon">🏗️</div>
                    <div class="role-info">
                        <h3>Event Organizer</h3>
                        <p>Create concerts, manage tickets, and grow your fans.</p>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-main" id="btn-next" onclick="goToForm()" disabled>Continue Registration</button>
            
            <p class="footer-text">
                Already have an account? <a href="{{ route('login') }}">Log In</a>
            </p>
        </div>

        <!-- Step 2: Form -->
        <div id="step-form" class="step-content hidden">
            <a href="javascript:void(0)" class="back-link" onclick="goToSelection()">← Change account type</a>
            <p class="subtitle" id="role-subtitle">Set up your account</p>

            <form method="POST" action="{{ route('signup.post') }}">
                @csrf
                <input type="hidden" name="role" id="role-input">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Jhon Doe" value="{{ old('nama_lengkap') }}" required>
                    @error('nama_lengkap')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="uname@email.com" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="no_telp" class="form-control" placeholder="08xxxxxxxx" value="{{ old('no_telp') }}" required>
                    @error('no_telp')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn-main">Create My Account</button>
            </form>
        </div>
    </div>

    <script>
        let selectedRole = '';

        function selectRole(role, element) {
            selectedRole = role;
            document.getElementById('role-input').value = role;
            
            // Highlight selected card
            document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active'));
            element.classList.add('active');
            
            // Enable button
            document.getElementById('btn-next').disabled = false;
        }

        function goToForm() {
            if (!selectedRole) return;
            
            document.getElementById('step-selection').classList.add('hidden');
            setTimeout(() => {
                document.getElementById('step-selection').style.display = 'none';
                document.getElementById('step-form').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('step-form').classList.remove('hidden');
                    document.getElementById('step-form').style.opacity = '1';
                    document.getElementById('step-form').style.transform = 'translateY(0)';
                }, 50);
            }, 400);

            // Update subtitle based on role
            const subtitle = selectedRole === 'Organizer' ? 'Apply as an Organizer' : 'Join as a Concert Enthusiast';
            document.getElementById('role-subtitle').innerText = subtitle;
        }

        function goToSelection() {
            document.getElementById('step-form').classList.add('hidden');
            setTimeout(() => {
                document.getElementById('step-form').style.display = 'none';
                document.getElementById('step-selection').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('step-selection').classList.remove('hidden');
                }, 50);
            }, 400);
        }

        // Auto-show form if there are validation errors
        @if($errors->any() || old('role'))
            selectedRole = "{{ old('role') }}";
            if (selectedRole) {
                document.getElementById('step-selection').style.display = 'none';
                document.getElementById('step-form').style.display = 'block';
                document.getElementById('step-form').classList.remove('hidden');
                document.getElementById('step-form').style.opacity = '1';
                document.getElementById('step-form').style.transform = 'translateY(0)';
                document.getElementById('role-input').value = selectedRole;
                const subtitle = selectedRole === 'Organizer' ? 'Apply as an Organizer' : 'Join as a Concert Enthusiast';
                document.getElementById('role-subtitle').innerText = subtitle;
            }
        @endif
    </script>

</body>
</html>
