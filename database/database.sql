-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table toko_online.detail_pesanan
CREATE TABLE IF NOT EXISTS `detail_pesanan` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_pesanan` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id_detail`) USING BTREE,
  KEY `fk_detail_pesanan` (`id_pesanan`) USING BTREE,
  KEY `fk_detail_produk` (`id_produk`) USING BTREE,
  CONSTRAINT `fk_detail_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table toko_online.detail_pesanan: ~10 rows (approximately)
INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_produk`, `jumlah`, `harga`) VALUES
	(1, 1, 2, 1, 13000),
	(2, 1, 3, 1, 17000),
	(3, 2, 2, 2, 13000),
	(4, 2, 3, 1, 17000),
	(5, 3, 2, 1, 13000),
	(6, 3, 3, 1, 17000),
	(7, 4, 2, 1, 13000),
	(8, 4, 3, 1, 17000),
	(9, 5, 2, 1, 13000),
	(10, 5, 3, 1, 17000);

-- Dumping structure for table toko_online.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama` varchar(20) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_new_member` tinyint DEFAULT '1',
  `telepon` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table toko_online.pelanggan: ~5 rows (approximately)
INSERT INTO `pelanggan` (`id_pelanggan`, `username`, `nama`, `email`, `password`, `is_new_member`, `telepon`, `alamat`) VALUES
	(1, '', 'reno', 'renobayu@gmail.com', '', 0, '085608744845', 'ya'),
	(2, 'oner', 'mas renn', 'renobayu@gmail.com', '$2y$10$GLWo2LylJ8Syk6e8gJ1zgebcJr044.5tsATKkJWRugYHSumEmVzrC', 1, '085608744845', 'sini'),
	(3, '', 'reno', 'bayu@gmail.com', '', 0, '085608744845', 'sana aja'),
	(4, '', 'mas renn', 'aku@gmail.com', '', 0, '085608744845', 'ya'),
	(5, 'onergantenk', 'oner', 'kamu@gmail.com', '$2y$10$LroSbTOg4RYwROgTnXXig.aUzoNIa8nJr0RIkXyncr2tu6KxFWs56', 1, '085608744845', 'sana');

-- Dumping structure for table toko_online.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `id_pesanan` int DEFAULT NULL,
  `metode` enum('cash','transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tanggal_bayar` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`) USING BTREE,
  KEY `fk_pembayaran_pesanan` (`id_pesanan`) USING BTREE,
  CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table toko_online.pembayaran: ~5 rows (approximately)
INSERT INTO `pembayaran` (`id_pembayaran`, `id_pesanan`, `metode`, `tanggal_bayar`) VALUES
	(1, 1, 'cash', '2025-10-08 07:40:31'),
	(2, 2, 'cash', '2025-10-08 09:37:56'),
	(3, 3, 'cash', '2025-10-08 09:41:07'),
	(4, 4, 'transfer', '2025-10-08 09:42:40'),
	(5, 5, 'cash', '2025-10-08 09:44:49');

-- Dumping structure for table toko_online.pesanan
CREATE TABLE IF NOT EXISTS `pesanan` (
  `id_pesanan` int NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int DEFAULT NULL,
  `tanggal` datetime DEFAULT (now()),
  `total` decimal(10,0) DEFAULT NULL,
  `diskon` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pesanan`) USING BTREE,
  KEY `id_pelanggan` (`id_pelanggan`),
  CONSTRAINT `fk_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table toko_online.pesanan: ~5 rows (approximately)
INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `tanggal`, `total`, `diskon`) VALUES
	(1, 1, '2025-10-08 07:40:31', 30000, 0),
	(2, 2, '2025-10-08 09:37:56', 38700, 4300),
	(3, 3, '2025-10-08 09:41:07', 30000, 0),
	(4, 4, '2025-10-08 09:42:40', 30000, 0),
	(5, 5, '2025-10-08 09:44:49', 27000, 3000);

-- Dumping structure for table toko_online.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` int NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(100) NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `gambar` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_produk`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table toko_online.produk: ~12 rows (approximately)
INSERT INTO `produk` (`id_produk`, `nama_produk`, `harga`, `gambar`) VALUES
	(1, 'ICED COFFEE MOCHA', 12000, 'products-coffee-1'),
	(2, 'COFFEE WITH CREAM', 13000, 'products-coffee-2'),
	(3, 'CAPPUCCINO COFFEE', 17000, 'products-coffee-3'),
	(4, 'COFFEE WITH MILK', 12000, 'products-coffee-4'),
	(5, 'CLASSIC ICED COFFEE', 8000, 'products-coffee-5'),
	(6, 'ICED COFFEE FRAPPE', 15000, 'products-coffee-6'),
	(7, 'VANILLA LATTE', 16000, 'products-coffee-7'),
	(8, 'ICED MATCHA', 14000, 'products-coffee-8'),
	(9, 'CLASSIC COFFEE', 10000, 'products-coffee-9'),
	(10, 'MOCHA COFFEE', 13000, 'products-coffee-10'),
	(11, 'CARAMEL MACCHIATO', 14000, 'products-coffee-11'),
	(12, 'CHAI LATTE', 15000, 'products-coffee-12');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
