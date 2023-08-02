CREATE TABLE akun (
    id int primary key auto_increment,
    username varchar(255) unique not null,
    password varchar(255) not null,
    role varchar(32) default 'pelanggan',
    created_at DATETIME default CURRENT_TIMESTAMP
);

CREATE TABLE beras (
    id int primary key auto_increment,
    jenis varchar(128) not null,
    created_at DATETIME default CURRENT_TIMESTAMP,
    updated_at DATETIME default CURRENT_TIMESTAMP
);

CREATE TABLE varian_takaran (
    id int primary key auto_increment,
    variant varchar(64) not null
);

CREATE TABLE stok(
  beras_id int not null,
  varian_takaran_id int not null,
  jumlah_stok int unsigned default 0,
  harga decimal(10, 2) default 0,

  FOREIGN KEY (beras_id) REFERENCES beras(id),
  FOREIGN KEY (varian_takaran_id) REFERENCES varian_takaran(id)
);

CREATE TABLE karyawan (
    id int primary key auto_increment,
    nama varchar(255) not null,
    kontak varchar(32),
    jabatan varchar(32),
    akun_id int,

    FOREIGN KEY (akun_id) REFERENCES akun(id)
);

CREATE TABLE pelanggan (
  id int primary key auto_increment,
  nama varchar(255) not null,
  kontak varchar(32),
  alamat varchar(255),
  akun_id int,

  FOREIGN KEY (akun_id) REFERENCES akun(id)
);

CREATE TABLE pesanan(
    id int primary key auto_increment,
    nomor_pesanan varchar(128) not null unique,
    nomor_iterasi_pesanan int not null,
    nama_pesanan varchar(255) not null,
    alamat_pengiriman varchar(255) not null,
    tanggal_pemesanan DATETIME,
    total_tagihan decimal(10, 2) default 0,
    pemesan_id int not null,

    FOREIGN KEY (pemesan_id) REFERENCES pelanggan(id)
);

CREATE TABLE detail_pesanan(
  id int primary key auto_increment,
  jenis_beras varchar(255) not null,
  takaran_beras varchar(255) not null,
  harga_satuan decimal(10, 2) default 0,
  jumlah_beli int default 1,
  total decimal(10, 2) default 0,
  pesanan_id int not null,

  FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE
);

CREATE TABLE transaksi(
    id int primary key auto_increment,
    tanggal_pembayaran DATETIME not null,
    nama_pembayaran varchar(255),
    bank_pembayaran varchar(255),
    nominal_dibayarkan decimal(10, 2) default 0,
    status_pembayaran varchar(16) not null ,
    konfirmasi_pembayaran varchar(16) not null,
    file_bukti_pembayaran varchar(255),
    pesanan_id int not null,

    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE
);

/* insert admin user */
INSERT INTO akun (username, password, role) VALUES ('admin', '$2y$10$F09VT7vnzeFqoTuQAj3CoOhJg4wq96bJy1Ah1ltQplyHAus8vJGJO', 'ADMIN');

























