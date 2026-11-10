<?php
return [
    'user' => [
        'title' => 'Data User',
        'table' => 'user',
        'pk' => 'id_user',
        'fields' => [
            'nama' => ['label' => 'Nama lengkap', 'type' => 'text', 'required' => true],
            'email' => ['label' => 'Email', 'type' => 'email', 'required' => true],
            'no_hp' => ['label' => 'Nomor HP', 'type' => 'text', 'required' => true],
            'alamat' => ['label' => 'Alamat', 'type' => 'textarea', 'required' => true]
        ],
        'columns' => ['id_user', 'nama', 'email', 'no_hp', 'alamat']
    ],
    'film' => [
        'title' => 'Data Film',
        'table' => 'film',
        'pk' => 'id_film',
        'fields' => [
            'sampul' => ['label' => 'Sampul film', 'type' => 'file', 'required' => false],
            'judul' => ['label' => 'Judul film', 'type' => 'text', 'required' => true],
            'genre' => ['label' => 'Genre', 'type' => 'text', 'required' => true],
            'sutradara' => ['label' => 'Sutradara', 'type' => 'text', 'required' => true],
            'sinopsis' => ['label' => 'Sinopsis', 'type' => 'textarea', 'required' => true]
        ],
        'columns' => ['id_film', 'sampul', 'judul', 'genre', 'sutradara', 'sinopsis']
    ],
    'jadwal_tayang' => [
        'title' => 'Jadwal Tayang',
        'table' => 'jadwal_tayang',
        'pk' => 'id_jadwal',
        'fields' => [
            'id_film' => ['label' => 'Film', 'type' => 'select', 'table' => 'film', 'key' => 'id_film', 'display' => 'judul', 'required' => true],
            'studio' => ['label' => 'Studio', 'type' => 'text', 'required' => true],
            'tanggal_tayang' => ['label' => 'Tanggal tayang', 'type' => 'date', 'required' => true],
            'jam_tayang' => ['label' => 'Jam tayang', 'type' => 'time', 'required' => true],
            'harga_tiket' => ['label' => 'Harga tiket (Rp)', 'type' => 'number', 'required' => true]
        ],
        'columns' => ['id_jadwal', 'id_film', 'studio', 'tanggal_tayang', 'jam_tayang', 'harga_tiket']
    ],
    'pemesanan' => [
        'title' => 'Data Pemesanan',
        'table' => 'pemesanan',
        'pk' => 'id_pemesanan',
        'fields' => [
            'id_user' => ['label' => 'Pengguna', 'type' => 'select', 'table' => 'user', 'key' => 'id_user', 'display' => 'nama', 'required' => true],
            'id_film' => ['label' => 'Film', 'type' => 'select', 'table' => 'film', 'key' => 'id_film', 'display' => 'judul', 'required' => true],
            'id_jadwal' => ['label' => 'Jadwal tayang', 'type' => 'select', 'table' => 'jadwal_tayang', 'key' => 'id_jadwal', 'display' => 'id_jadwal', 'required' => true],
            'tanggal_pemesanan' => ['label' => 'Tanggal pemesanan', 'type' => 'datetime-local', 'required' => true],
            'jumlah_tiket' => ['label' => 'Jumlah tiket', 'type' => 'number', 'required' => true],
            'total_harga' => ['label' => 'Total harga (Rp)', 'type' => 'number', 'required' => true]
        ],
        'columns' => ['id_pemesanan', 'id_user', 'id_film', 'id_jadwal', 'tanggal_pemesanan', 'jumlah_tiket', 'total_harga']
    ],
    'akun' => [
        'title' => 'Data Akun User',
        'table' => 'akun',
        'pk' => 'id_akun',
        'fields' => [
            'id_user' => ['label' => 'Pemilik akun', 'type' => 'select', 'table' => 'user', 'key' => 'id_user', 'display' => 'nama', 'required' => true],
            'username' => ['label' => 'Username', 'type' => 'text', 'required' => true],
            'password' => ['label' => 'Password', 'type' => 'password', 'required' => false]
        ],
        'columns' => ['id_akun', 'id_user', 'username', 'tanggal_dibuat']
    ],
    'admin' => [
        'title' => 'Data Administrator',
        'table' => 'admin',
        'pk' => 'id_admin',
        'fields' => [
            'nama_admin' => ['label' => 'Nama administrator', 'type' => 'text', 'required' => true],
            'username' => ['label' => 'Username', 'type' => 'text', 'required' => true],
            'password' => ['label' => 'Password', 'type' => 'password', 'required' => false]
        ],
        'columns' => ['id_admin', 'nama_admin', 'username', 'tanggal_dibuat']
    ]
];
