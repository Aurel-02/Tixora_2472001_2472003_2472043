<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tixora - Face Scan Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
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
        }

        .container {
            background: rgba(58, 52, 91, 0.6);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 2rem;
            color: #fff;
            margin-bottom: 15px;
            font-weight: 700;
        }

        p {
            color: var(--queen-pink);
            opacity: 0.8;
            margin-bottom: 30px;
        }

        .counter {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--middle-purple);
            margin-bottom: 20px;
            padding: 10px 20px;
            background: rgba(209, 131, 169, 0.1);
            border-radius: 50px;
            display: inline-block;
        }

        .camera-container {
            width: 100%;
            max-width: 400px;
            height: 400px;
            margin: 0 auto 30px;
            position: relative;
            background: #000;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid var(--middle-purple);
            box-shadow: 0 0 20px rgba(209, 131, 169, 0.3);
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1); /* Mirror effect */
        }

        canvas {
            display: none;
        }

        .scan-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: rgba(132, 216, 165, 0.8);
            box-shadow: 0 0 10px #84d8a5, 0 0 20px #84d8a5;
            animation: scan 3s infinite linear;
            z-index: 2;
        }

        @keyframes scan {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        .actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 20px;
        }

        .btn-capture {
            background: var(--middle-purple);
            color: var(--jacarta);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(209, 131, 169, 0.3);
        }

        .btn-capture:hover {
            background: #fff;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.4);
        }

        .btn-upload {
            background: rgba(243, 200, 221, 0.1);
            color: var(--queen-pink);
            border: 1px solid var(--middle-purple);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .btn-upload:hover {
            background: var(--middle-purple);
            color: var(--jacarta);
        }

        .btn-upload i.ph-image {
            font-size: 1.8rem;
        }

        .btn-upload i.ph-plus {
            position: absolute;
            font-size: 0.9rem;
            bottom: 5px;
            right: 5px;
            background: var(--jacarta);
            border-radius: 50%;
            padding: 2px;
        }

        .btn-upload:hover i.ph-plus {
            background: #fff;
            color: var(--jacarta);
        }
        
        #fileInput {
            display: none;
        }

    </style>
</head>
<body>
    <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; flex-direction:column; align-items:center; justify-content:center;">
        <div style="font-size: 2rem; margin-bottom: 20px; color: var(--queen-pink); font-weight: 700;">Memuat Model AI Wajah...</div>
        <div style="font-size: 1rem; color: #aaa;">Mohon tunggu sebentar</div>
    </div>

    <div class="container">
        <h1>Face Verification</h1>
        <p>Demi keamanan, harap verifikasi identitas Anda untuk setiap tiket yang dibeli.</p>

        <div class="counter" id="counterDisplay">
            Memindai Wajah 1 dari {{ $total }}
        </div>

        <div class="camera-container">
            <video id="video" autoplay playsinline></video>
            <div class="scan-line"></div>
        </div>
        <canvas id="canvas"></canvas>

        <div class="actions">
            <!-- Upload Photo Action -->
            <label class="btn-upload" title="Unggah Foto">
                <i class="ph ph-image"></i>
                <i class="ph ph-plus"></i>
                <input type="file" id="fileInput" accept="image/*" onchange="handleFileUpload(event)">
            </label>

            <button class="btn-capture" id="captureBtn" onclick="takeSnapshot()">
                <i class="ph ph-camera"></i> Ambil Wajah
            </button>
        </div>
    </div>

    <form id="uploadForm" action="{{ route('face-scan.upload') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="total_scanned" value="{{ $total }}">
    </form>

    <script>
        const totalRequired = {{ $total }};
        let currentScanCount = 0;
        let savedFaceDescriptors = [];

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const counterDisplay = document.getElementById('counterDisplay');

        async function loadModels() {
            document.getElementById('loadingOverlay').style.display = 'flex';
            try {
                const MODEL_URL = 'https://vladmandic.github.io/face-api/model';
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            } catch (err) {
                console.error(err);
            }
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        async function setupCamera() {
            await loadModels();
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "user" }, 
                    audio: false 
                });
                video.srcObject = stream;
            } catch (err) {
                console.error("Kesalahan mengakses kamera: ", err);
                Swal.fire({
                    icon: 'warning',
                    title: 'Kamera Tidak Tersedia',
                    text: 'Silakan gunakan tombol impor/unggah foto.',
                    background: 'var(--jacarta)',
                    color: 'var(--queen-pink)',
                    confirmButtonColor: 'var(--middle-purple)'
                });
            }
        }

        window.onload = setupCamera;

        function updateCounter() {
            if (currentScanCount < totalRequired) {
                counterDisplay.innerText = `Memindai Wajah ${currentScanCount + 1} dari ${totalRequired}`;
            }
        }

        async function checkFaceDuplicate(imgElement) {
            Swal.fire({
                title: 'Menganalisis Wajah...',
                text: 'Mohon tunggu, memindai dan memproses wajah...',
                allowOutsideClick: false,
                background: 'var(--jacarta)',
                color: 'var(--queen-pink)',
                didOpen: () => { Swal.showLoading(); }
            });

            await new Promise(resolve => setTimeout(resolve, 150));

            try {
                const detection = await faceapi.detectSingleFace(imgElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
                
                if (!detection) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Wajah Tidak Terdeteksi',
                        text: 'Pastikan wajah Anda terlihat dengan jelas.',
                        background: 'var(--jacarta)',
                        color: 'var(--queen-pink)',
                        confirmButtonColor: 'var(--middle-purple)'
                    });
                    return null;
                }

                const currentDescriptor = detection.descriptor;

                if (savedFaceDescriptors.length > 0) {
                    for (let i = 0; i < savedFaceDescriptors.length; i++) {
                        const distance = faceapi.euclideanDistance(savedFaceDescriptors[i], currentDescriptor);
                        if (distance < 0.5) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Wajah Sudah Dimasukkan',
                                text: 'Wajah ini telah digunakan untuk tiket sebelumnya. Harap masukkan wajah yang berbeda.',
                                background: 'var(--jacarta)',
                                color: 'var(--queen-pink)',
                                confirmButtonColor: 'var(--middle-purple)'
                            });
                            return null;
                        }
                    }
                }

                return currentDescriptor;
            } catch (err) {
                console.error(err);
                Swal.fire({icon: 'error', title: 'Kesalahan Validasi', text: 'Gagal menganalisis wajah.'});
                return null;
            }
        }

        function takeSnapshot() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageDataUrl = canvas.toDataURL('image/png');
            const img = new Image();
            img.src = imageDataUrl;
            img.onload = async () => {
                const descriptor = await checkFaceDuplicate(img);
                if (descriptor) {
                    promptIdentityAndUpload(imageDataUrl, null, descriptor);
                }
            };
        }

        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = async () => {
                        const descriptor = await checkFaceDuplicate(img);
                        if (descriptor) {
                            promptIdentityAndUpload(null, file, descriptor);
                        }
                    };
                };
                reader.readAsDataURL(file);
            }
            event.target.value = '';
        }

        async function promptIdentityAndUpload(imageDataUrl, fileObj, descriptor) {
            const { value: formValues } = await Swal.fire({
                title: 'Identitas Tiket',
                html:
                    '<p style="margin-bottom: 15px; font-size: 0.9rem;">Silakan masukkan data untuk tiket ini. QR Code akan dikirim ke email ini.</p>' +
                    '<input id="swal-input-name" class="swal2-input" placeholder="Nama Pemilik Tiket" style="width: 80%;">' +
                    '<input id="swal-input-email" type="email" class="swal2-input" placeholder="Email Penerima QR" style="width: 80%;">',
                focusConfirm: false,
                background: 'var(--jacarta)',
                color: 'var(--queen-pink)',
                confirmButtonColor: 'var(--middle-purple)',
                confirmButtonText: 'Simpan & Lanjutkan',
                allowOutsideClick: false,
                preConfirm: () => {
                    const name = document.getElementById('swal-input-name').value;
                    const email = document.getElementById('swal-input-email').value;
                    if (!name || !email) {
                        Swal.showValidationMessage('Nama dan Email harus diisi!');
                        return false;
                    }
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if(!emailRegex.test(email)) {
                        Swal.showValidationMessage('Format email tidak valid!');
                        return false;
                    }
                    return { nama: name, email: email };
                }
            });

            if (formValues) {
                Swal.fire({
                    title: 'Menyimpan Data...',
                    text: 'Sedang menyimpan wajah dan mengirim tiket...',
                    allowOutsideClick: false,
                    background: 'var(--jacarta)',
                    color: 'var(--queen-pink)',
                    didOpen: () => { Swal.showLoading(); }
                });

                let fetchPromise;
                if (imageDataUrl) {
                    fetchPromise = fetch("{{ route('face-scan.upload') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ 
                            image: imageDataUrl, 
                            descriptor: JSON.stringify(Array.from(descriptor)),
                            nama: formValues.nama,
                            email: formValues.email
                        })
                    });
                } else if (fileObj) {
                    const formData = new FormData();
                    formData.append('image_file', fileObj);
                    formData.append('descriptor', JSON.stringify(Array.from(descriptor)));
                    formData.append('nama', formValues.nama);
                    formData.append('email', formValues.email);
                    
                    fetchPromise = fetch("{{ route('face-scan.upload') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });
                }

                fetchPromise.then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            savedFaceDescriptors.push(descriptor);
                            processScanSuccess();
                        } else {
                            Swal.fire({
                                icon: 'error', 
                                title: 'Ditolak', 
                                text: data.message,
                                background: 'var(--jacarta)',
                                color: 'var(--queen-pink)',
                                confirmButtonColor: 'var(--middle-purple)'
                            });
                        }
                    }).catch(err => {
                        console.error(err);
                        Swal.fire({icon: 'error', title: 'Upload Gagal', text: 'Gagal mengunggah foto wajah.'});
                    });
            }
        }

        function processScanSuccess() {
            currentScanCount++;

            Swal.fire({
                icon: 'success',
                title: 'Wajah Terverifikasi!',
                text: `Berhasil menyimpan data tiket ${currentScanCount}.`,
                background: 'var(--jacarta)',
                color: 'var(--queen-pink)',
                confirmButtonColor: 'var(--middle-purple)',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                if (currentScanCount >= totalRequired) {
                    finishScanning();
                } else {
                    updateCounter();
                }
            });
        }

        function finishScanning() {
            const stream = video.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }

            Swal.fire({
                icon: 'success',
                title: 'Selesai!',
                text: 'Semua tiket telah diverifikasi dengan wajah. Kami akan mengarahkan Anda ke beranda tiket.',
                background: 'var(--jacarta)',
                color: 'var(--queen-pink)',
                confirmButtonColor: '#84d8a5',
                showConfirmButton: true,
                confirmButtonText: 'Lanjutkan'
            }).then(() => {
                window.location.href = "{{ route('my-tickets') }}";
            });
        }
    </script>
</body>
</html>
