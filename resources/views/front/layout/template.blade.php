<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--<meta name="description" content="" /> -->
    <meta name="author" content="StudyGroup" />
    @stack('meta-seo')
    <title>@yield('title')</title>
    <!-- Favicon-->
    <link rel="icon" type="image/png" href="{{ asset('front/assets/study.png') }}">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('front/css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('front/css/custom.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @stack('css')
</head>

<body>
    <!-- Responsive navbar-->
    @include('front.layout.navbar')

    <!-- Page header with logo and tagline-->
    <header class="custom-header py-5 mb-4">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder mb-3" id="animated-headline"></h1>
                <p class="lead mb-4 fs-5" id="animated-slogan"></p>
            </div>

            <div class="search-container">
                <form action="{{ route('search') }}" method="POST">
                    @csrf
                    <div class="input-group search-bar">
                        <input class="form-control" style="color: #063e23" type="text" name="keyword"
                            placeholder="Cari Mata Pelajaran..." />
                        <button class="btn btn-search" type="submit">Cari</button>
                    </div>
                </form>
            </div>
            @include('front.layout.side-widget')
        </div>
    </header>


    @yield('content')

    <!-- Footer-->
    <footer class="py-5" style="background-color: rgba(12, 75, 45, 0.851);">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Study Group 2025</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('front/js/scripts.js') }}"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... (AOS.init() code Anda tetap di sini) ...

            const headlineElement = document.getElementById('animated-headline');
            const sloganElement = document.getElementById('animated-slogan');

            if (!headlineElement || !sloganElement) {
                console.error("Elemen 'animated-headline' atau 'animated-slogan' tidak ditemukan.");
                if (headlineElement) headlineElement.textContent = "Selamat Datang di Study Group!";
                if (sloganElement) sloganElement.textContent =
                    "Tingkatkan pemahaman materi, pecahkan masalah bersama, dan dorong kesuksesan akademikmu dalam komunitas belajar kolaboratif kami.";
                return;
            }

            const headlineText = "Selamat Datang di Platform Study Group!";
            const sloganText =
                "Tingkatkan pemahaman materi, pecahkan masalah bersama, dan dorong kesuksesan akademikmu dalam komunitas belajar kolaboratif kami.";

            const typingSpeed = 50; // Kecepatan mengetik (ms per huruf)
            const delayBeforeRestart = 1500; // Jeda sebelum headline mengulang (ms)
            const delayBeforeSlogan = 1000; // Jeda sebelum slogan muncul (ms)

            // Fungsi untuk mengetik teks satu per satu (tanpa menghapus)
            function typeTextOneByOne(element, text, charIndex = 0, callback) {
                if (charIndex < text.length) {
                    const charSpan = document.createElement('span');
                    // === MODIFIKASI BARIS INI ===
                    if (text[charIndex] === ' ') {
                        charSpan.innerHTML = '&nbsp;'; // Ganti spasi dengan non-breaking space
                    } else {
                        charSpan.textContent = text[charIndex]; // Isi span dengan karakter biasa
                    }
                    // === AKHIR MODIFIKASI ===

                    element.appendChild(charSpan);

                    charSpan.style.animationDelay = (charIndex * (typingSpeed / 1000)) + 's';
                    charSpan.classList.add('slide-in-char');
                    charSpan.style.opacity = '1';

                    setTimeout(() => {
                        typeTextOneByOne(element, text, charIndex + 1, callback);
                    }, typingSpeed);
                } else {
                    if (callback) {
                        callback();
                    }
                }
            }

            // Fungsi untuk mengelola siklus animasi headline yang berulang
            function animateHeadlineCycle() {
                headlineElement.innerHTML = ''; // Kosongkan headline untuk animasi ulang
                headlineElement.style.opacity = '1'; // Pastikan container terlihat
                typeTextOneByOne(headlineElement, headlineText, 0, () => {
                    // Setelah headline selesai diketik, tunggu sebentar, lalu sembunyikan semua span untuk reset
                    setTimeout(() => {
                        // Sembunyikan dan hapus span untuk memulai ulang
                        Array.from(headlineElement.children).forEach(span => {
                            span.style.opacity = '0';
                            span.classList.remove(
                                'slide-in-char'); // Hapus kelas animasi untuk reset
                        });
                        // Setelah semua span tersembunyi, panggil lagi animasi
                        setTimeout(animateHeadlineCycle, 100); // Jeda singkat sebelum animasi ulang
                    }, delayBeforeRestart);
                });
            }


            // Inisialisasi: Mulai animasi headline dan slogan
            // Mulai headline
            animateHeadlineCycle(); // Ini akan looping terus

            // Setelah jeda, mulai slogan (hanya sekali)
            setTimeout(() => {
                    typeTextOneByOne(sloganElement, sloganText); // Slogan diketik sekali dan tetap tampil
                }, headlineText.length * typingSpeed +
                delayBeforeSlogan); // Jeda setelah headline diketik penuh + delay
        });
    </script>
    @stack('js')
</body>

</html>
