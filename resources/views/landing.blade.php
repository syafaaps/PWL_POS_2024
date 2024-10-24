<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TwinklePOS - Point of Sales</title>
    <style>
        /* Mengatur dasar tampilan halaman */
        body {
            font-family: 'Poppins', sans-serif; /* Menggunakan font Poppins */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-image: url('{{ asset('photos/bg2.jpeg') }}'); /* Background gambar */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #f0f0f0; /* Warna cadangan jika gambar tidak muncul */
        }

        /* Header untuk logo dan navigasi */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #823460; /* Warna TEAL */
            color: white;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Logo dan styling heading */
        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 50px;
            margin-right: 15px;
        }

        .logo h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: white;
        }

        /* Styling untuk navigasi */
        nav {
            display: flex;
            gap: 15px;
        }

        nav a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #F76C6C; /* Warna Soft Orange */
            color: white;
            border-radius: 50px;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
        }

        nav a:hover {
            background-color: #FF9463; /* Warna saat di-hover */
            transform: scale(1.05); /* Efek zoom saat hover */
        }

        /* Styling untuk konten utama */
        main {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9); /* Latar belakang transparan */
            padding: 20px;
        }

        .content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .content h2 {
            font-size: 36px;
            font-weight: 700;
            color: #823460; /* Warna heading */
            margin-bottom: 20px;
        }

        .content p {
            font-size: 18px;
            color: #333333; /* Warna teks */
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Tombol Call to Action */
        .cta-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #F76C6C;
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: 50px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .cta-button:hover {
            background-color: #FF9463;
            transform: scale(1.05);
        }

        /* Styling untuk footer */
        footer {
            padding: 20px;
            text-align: center;
            background-color: #823460;
            color: white;
            box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .logo h1 {
                font-size: 20px;
            }

            .content h2 {
                font-size: 28px;
            }

            .content p {
                font-size: 16px;
            }

            nav a {
                padding: 8px 16px;
                font-size: 14px;
            }

            .cta-button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="{{ asset('photos/logo.jpg') }}" alt="TwinklePOS Logo">
            <h1>TwinklePOS</h1>
        </div>
        <nav>
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <div class="content">
            <h2>Selamat Datang di TwinklePOS, Solusi Terbaik untuk Bisnis Anda</h2>
            <p>TwinklePOS adalah sistem Point of Sales modern yang dirancang untuk membantu Anda mengelola penjualan, inventaris, dan laporan transaksi secara efisien. Apakah Anda mengelola toko kecil atau bisnis besar, kami memberikan solusi yang dapat diandalkan dan mudah digunakan.</p>
            <a href="{{ route('register') }}" class="cta-button">Daftar Sekarang</a>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>Hubungi kami: twinklepos@example.com | 087777656177</p>
    </footer>
</body>
</html>
