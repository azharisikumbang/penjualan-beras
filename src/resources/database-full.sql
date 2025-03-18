-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: bumdes_kanterleans
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `akun`
--

DROP TABLE IF EXISTS `akun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `akun` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(32) COLLATE utf8mb4_general_ci DEFAULT 'pelanggan',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `akun`
--

LOCK TABLES `akun` WRITE;
/*!40000 ALTER TABLE `akun` DISABLE KEYS */;
INSERT INTO `akun` VALUES (1,'admin','$2y$10$8inQJqCmBEcqpB/9fzcUtOCKhAnqvLLlZtRODyRn9bNmm995DE5ma','ADMIN','2023-07-24 19:49:50'),(12,'pimpinan','$2y$10$r2UzzNpe99M0PVuLDQbQMOl1.AWvmceoC.M1c2sCjyX2nkC2BpPl.','PIMPINAN','2023-07-24 19:49:50'),(15,'arief','$2y$10$KkLnxzsyzItTs4bzjlHFA.3IvrOJVwyytn9Y.QQrh/ucJ0tyNm.Ma','pelanggan','2023-09-20 20:30:36'),(16,'zulham','$2y$10$Umq3qVDA4rVRfm7kGiJ1O.SRQK/xLZnX3nfdImGLZpSns0edwjLZe','pelanggan','2023-09-20 23:09:40'),(17,'azhari','$2y$10$xW7xFxtm7In9CA.XdieMkO6RR/EgssjGn8ddHtnfmItsFlnY/vzuy','pelanggan','2024-02-14 22:31:34');
/*!40000 ALTER TABLE `akun` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beras`
--

DROP TABLE IF EXISTS `beras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beras`
--

LOCK TABLES `beras` WRITE;
/*!40000 ALTER TABLE `beras` DISABLE KEYS */;
INSERT INTO `beras` VALUES (16,'Beras Sokan','2023-08-05 23:10:29','2023-08-05 23:10:29'),(17,'Beras Anak Daro','2023-08-05 23:10:36','2023-08-05 23:10:36'),(18,'Beras Mundan','2023-08-05 23:10:42','2023-08-05 23:10:42'),(19,'Beras Rendah Kuning','2023-08-05 23:10:52','2023-08-05 23:10:52');
/*!40000 ALTER TABLE `beras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_pesanan`
--

DROP TABLE IF EXISTS `detail_pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_pesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis_beras` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga_satuan` decimal(10,2) DEFAULT '0.00',
  `jumlah_beli` int DEFAULT '1',
  `total` decimal(10,2) DEFAULT '0.00',
  `pesanan_id` int NOT NULL,
  `takaran_beras` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ref_beras_id` int DEFAULT NULL,
  `ref_takaran_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_id` (`pesanan_id`),
  CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_pesanan`
--

LOCK TABLES `detail_pesanan` WRITE;
/*!40000 ALTER TABLE `detail_pesanan` DISABLE KEYS */;
INSERT INTO `detail_pesanan` VALUES (30,'Beras Anak Daro',16000.00,100,1600000.00,23,'1 KG',17,1),(31,'Beras Anak Daro',470000.00,50,23500000.00,23,'30 Kg',17,3),(32,'Beras Mundan',470000.00,10,4700000.00,24,'30 Kg',18,3),(33,'Beras Sokan',470000.00,20,9400000.00,25,'30 Kg',16,3),(34,'Beras Rendah Kuning',455000.00,10,4550000.00,26,'30 Kg',19,3),(35,'Beras Mundan',155000.00,10,1550000.00,26,'10 Kg',18,2),(36,'Beras Anak Daro',16000.00,15,240000.00,27,'1 KG',17,1),(37,'Beras Mundan',155000.00,75,11625000.00,27,'10 Kg',18,2),(38,'Beras Anak Daro',16000.00,100,1600000.00,28,'1 KG',17,1),(39,'Beras Sokan',470000.00,10,4700000.00,28,'30 Kg',16,3),(40,'Beras Rendah Kuning',455000.00,10,4550000.00,28,'30 Kg',19,3);
/*!40000 ALTER TABLE `detail_pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `karyawan`
--

DROP TABLE IF EXISTS `karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `karyawan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kontak` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `akun_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `akun_id` (`akun_id`),
  CONSTRAINT `karyawan_ibfk_1` FOREIGN KEY (`akun_id`) REFERENCES `akun` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan`
--

LOCK TABLES `karyawan` WRITE;
/*!40000 ALTER TABLE `karyawan` DISABLE KEYS */;
INSERT INTO `karyawan` VALUES (1,'Alex 2','0827362523841','karyawan',NULL);
/*!40000 ALTER TABLE `karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pelanggan`
--

DROP TABLE IF EXISTS `pelanggan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pelanggan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kontak` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `akun_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `akun_id` (`akun_id`),
  CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`akun_id`) REFERENCES `akun` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelanggan`
--

LOCK TABLES `pelanggan` WRITE;
/*!40000 ALTER TABLE `pelanggan` DISABLE KEYS */;
INSERT INTO `pelanggan` VALUES (8,'Arief Habibie','6282158409012','Jl. Perintis Kemerdekaan no. 45 Padang',15),(9,'Zulham','623874738372','Jl. Mawar no. 56 Padang',16),(10,'azhari','6282275676330','Jl Perintis Kemerdekaan no 20 Padang',17);
/*!40000 ALTER TABLE `pelanggan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomor_pesanan` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `nomor_iterasi_pesanan` int NOT NULL,
  `nama_pesanan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_pengiriman` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_pemesanan` datetime DEFAULT NULL,
  `total_tagihan` decimal(10,2) DEFAULT '0.00',
  `pemesan_id` int NOT NULL,
  `kontak_pesanan` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sub_total` decimal(10,2) DEFAULT '0.00',
  `diskon` decimal(10,2) DEFAULT '0.00',
  `kode_promo` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_pesanan` (`nomor_pesanan`),
  KEY `pemesan_id` (`pemesan_id`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`pemesan_id`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
INSERT INTO `pesanan` VALUES (23,'KANTERLEANS/2023/09/000001',1,'Arief Habibie','Jl. Perintis Kemerdekaan no. 45 Padang','2023-09-20 20:34:04',25100000.00,8,'62812345678900',25100000.00,0.00,NULL),(24,'KANTERLEANS/2023/09/000002',2,'Arief Habibie','Jl. Perintis Kemerdekaan no. 45 Padang','2023-09-20 20:45:19',4465000.00,8,'62812345678900',4700000.00,235000.00,'QL2Q1P'),(25,'KANTERLEANS/2023/09/000003',3,'Zulham','Jl. Mawar no. 56 Padang','2023-09-20 23:13:47',9400000.00,9,'623874738372',9400000.00,0.00,NULL),(26,'KANTERLEANS/2023/09/000004',4,'Zulham','Jl. Mawar no. 56 Padang','2023-09-20 23:14:54',6100000.00,9,'623874738372',6100000.00,0.00,NULL),(27,'KANTERLEANS/2023/09/000005',5,'Arief Habibie','Jl. Perintis Kemerdekaan no. 45 Padang','2023-09-21 23:08:04',11865000.00,8,'62812345678900',11865000.00,0.00,NULL),(28,'KANTERLEANS/2024/01/000001',1,'Arief','Jl. Perintis Kemerdekaan no. 45 Padang','2024-01-17 21:54:38',10307500.00,8,'62812345678900',10850000.00,542500.00,'0S8CQI');
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo`
--

DROP TABLE IF EXISTS `promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis_promo` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_kupon` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_kadaluarsa` date DEFAULT NULL,
  `minimum_pembelian` decimal(10,2) DEFAULT '0.00',
  `potongan_harga` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo`
--

LOCK TABLES `promo` WRITE;
/*!40000 ALTER TABLE `promo` DISABLE KEYS */;
INSERT INTO `promo` VALUES (1,'COUPON_CODE','MPNG6I','2023-08-12',50000.00,5.00),(2,'COUPON_CODE','EI4MSC','2023-08-04',0.00,5.00),(3,'COUPON_CODE','5F9AUQ','2023-08-19',50000.00,30000.00),(4,'COUPON_CODE','57XMXN','2023-08-17',100000.00,10.00),(5,'COUPON_CODE','QL2Q1P','2023-09-20',50000.00,5.00),(6,'COUPON_CODE','0S8CQI','2024-01-31',100000.00,5.00),(7,'COUPON_CODE','QJKX9J','2024-02-17',0.00,30.00),(8,'COUPON_CODE','N17BLO','2024-02-24',0.00,30000.00);
/*!40000 ALTER TABLE `promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stok`
--

DROP TABLE IF EXISTS `stok`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stok` (
  `beras_id` int NOT NULL,
  `varian_takaran_id` int NOT NULL,
  `jumlah_stok` int unsigned DEFAULT '0',
  `harga` decimal(10,2) DEFAULT '0.00',
  KEY `beras_id` (`beras_id`),
  KEY `varian_takaran_id` (`varian_takaran_id`),
  CONSTRAINT `stok_ibfk_1` FOREIGN KEY (`beras_id`) REFERENCES `beras` (`id`),
  CONSTRAINT `stok_ibfk_2` FOREIGN KEY (`varian_takaran_id`) REFERENCES `varian_takaran` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stok`
--

LOCK TABLES `stok` WRITE;
/*!40000 ALTER TABLE `stok` DISABLE KEYS */;
INSERT INTO `stok` VALUES (16,1,1000,16000.00),(16,2,1000,155000.00),(16,3,980,470000.00),(17,1,885,16000.00),(17,2,1000,155000.00),(17,3,950,470000.00),(18,1,1000,16000.00),(18,2,915,155000.00),(18,3,1000,470000.00),(19,1,1000,15500.00),(19,2,1000,150000.00),(19,3,990,455000.00);
/*!40000 ALTER TABLE `stok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tanggal_pembayaran` datetime DEFAULT NULL,
  `nama_pembayaran` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_pembayaran` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nominal_dibayarkan` decimal(10,2) DEFAULT '0.00',
  `status_pembayaran` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `konfirmasi_pembayaran` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `file_bukti_pembayaran` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pesanan_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_id` (`pesanan_id`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (20,'2023-08-20 11:02:32','Arief','Bank BRI',25100000.00,'LUNAS','DITERIMA','a3e37639e016ebd58ebada1934f1de11.jpg',23),(21,NULL,NULL,NULL,0.00,'BELUM_BAYAR','BELUM_BAYAR',NULL,24),(22,'2023-09-20 23:14:08','Zulham','Bank BRI',9400000.00,'LUNAS','DITERIMA','593108d4daa4c3a3db444a092b274827.jpg',25),(23,'2023-09-20 23:15:10','Zulham','Bank BRI',6100000.00,'LUNAS','DITERIMA','622836a9bb0b7c998c07a7366eb21420.jpg',26),(24,'2023-09-21 23:08:23','Arief','Bank Mandiri',11865000.00,'LUNAS','DITERIMA','ac5e6d6c2498356175d53cb2ba63d481.jpg',27),(25,'2024-01-17 21:56:29','Arief','Bank BRI',10307500.00,'LUNAS','MENUNGGU_KONFIRMASI','07366d12a33e4bc8b61ba211d22c289e.jpg',28);
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `varian_takaran`
--

DROP TABLE IF EXISTS `varian_takaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `varian_takaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `variant` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `varian_takaran`
--

LOCK TABLES `varian_takaran` WRITE;
/*!40000 ALTER TABLE `varian_takaran` DISABLE KEYS */;
INSERT INTO `varian_takaran` VALUES (1,'1 KG'),(2,'10 Kg'),(3,'30 Kg');
/*!40000 ALTER TABLE `varian_takaran` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-28 10:49:56
