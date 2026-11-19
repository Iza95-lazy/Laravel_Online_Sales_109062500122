<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - ruangbelanja</title>
</head>
<body>
    <p>Pilihan sederhana. Belanja lebih nyaman.</p>
    <nav>
        <span>ruangbelanja. Jelajahi Produk Dashboard Admin Halo, Administrator</span>
        <a href="{{ route('shop.index') }}">Jelajahi Produk</a> 
        <a href="{{ route('admin.categories.index') }}">Kelola Kategori</a>  
        <a href="{{ route('admin.products.index') }}">Kelola Produk</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    </nav>
    <p>ruangbelanja. Proyek praktikum Laravel • Admin & Pelanggan Belanja dengan informasi yang jelas.</p>
</body>
</html>