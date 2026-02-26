-- ============================================================
-- DATABASE: tokobungajakartautara
-- Project : TOKOBUNGAJAKARTAUTARA.COM
-- Created : 2026
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `tokobungajakartautara`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE `tokobungajakartautara`;

-- ============================================================
-- TABEL: admin
-- ============================================================
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Password: admin123
INSERT INTO `admin` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$ydcwjQIlRn4xCwiO.07X6uPP7GPpeEHwMPFPyvmm7lucQ.ULlHrrO', 'admin@tokobungajakartautara.com');

-- ============================================================
-- TABEL: settings
-- ============================================================
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` (`key`, `value`) VALUES
('site_name',          'Toko Bunga Jakarta Utara'),
('site_tagline',       'Florist Terpercaya & Pengiriman Cepat 24 Jam'),
('whatsapp_number',    '6281322991131'),
('whatsapp_url',       'https://wa.me/6281322991131'),
('phone_display',      '0813-2299-1131'),
('address',            'Jl. Toko Bunga No. 1, Jakarta Utara, DKI Jakarta 14000'),
('email',              'order@tokobungajakartautara.com'),
('jam_buka',           'Senin - Minggu, 07.00 - 21.00 WIB'),
('maps_embed',         'https://maps.google.com/maps?q=Jakarta+Utara&output=embed'),
('meta_title_home',    'Toko Bunga Jakarta Utara Terpercaya | Kirim Cepat 24 Jam'),
('meta_desc_home',     'Toko bunga Jakarta Utara melayani karangan bunga papan, wedding, duka cita & hand bouquet. Kirim cepat 24 jam. Harga mulai 300rb. Order sekarang!'),
('meta_keywords_home', 'toko bunga jakarta utara, florist jakarta utara, karangan bunga papan, bunga duka cita, bunga wedding, hand bouquet jakarta utara'),
('logo',               ''),
('favicon',            ''),
('hero_title',         'Toko Bunga Jakarta Utara Terpercaya & Murah'),
('hero_subtitle',      'Melayani karangan bunga papan, hand bouquet, bunga pernikahan, dan duka cita. Pengiriman cepat ke seluruh Jakarta Utara.'),
('hero_image',         ''),
('about_text',         'Kami adalah florist profesional yang telah melayani pelanggan di Jakarta Utara sejak tahun 2010. Dengan pengalaman lebih dari 10 tahun, kami berkomitmen menghadirkan rangkaian bunga segar berkualitas tinggi untuk setiap momen spesial Anda.'),
('footer_text',        'Toko Bunga Jakarta Utara — Florist terpercaya dengan pengiriman cepat 24 jam ke seluruh wilayah Jakarta Utara.');

-- ============================================================
-- TABEL: categories
-- ============================================================
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` (`name`, `slug`, `meta_title`, `meta_description`, `content`, `status`) VALUES
('Karangan Bunga Papan',   'karangan-bunga-papan-jakarta-utara',   'Karangan Bunga Papan Jakarta Utara | Harga Mulai 350rb',        'Pesan karangan bunga papan Jakarta Utara untuk ucapan selamat, duka cita & grand opening. Kirim cepat 2-4 jam. Hubungi kami sekarang!',       '<p>Karangan bunga papan adalah pilihan utama untuk berbagai momen penting di Jakarta Utara. Kami menyediakan berbagai desain karangan bunga papan yang elegan dan berkualitas tinggi untuk setiap kesempatan.</p><p>Layanan kami tersedia untuk pengiriman ke seluruh wilayah Jakarta Utara termasuk Penjaringan, Pademangan, Tanjung Priok, Koja, Cilincing, dan Kelapa Gading. Dengan harga mulai dari Rp 350.000, kami memastikan setiap karangan bunga papan dibuat dengan bunga segar pilihan.</p>', 'active'),
('Bunga Duka Cita',        'bunga-duka-cita-jakarta-utara',        'Bunga Duka Cita Jakarta Utara | Pengiriman Cepat & Terpercaya', 'Toko bunga duka cita Jakarta Utara terpercaya. Tersedia karangan bunga papan belasungkawa & standing flower. Order via WA, kirim 2-4 jam.',  '<p>Kami hadir untuk menemani momen duka cita Anda dengan rangkaian bunga yang penuh penghormatan di Jakarta Utara. Setiap rangkaian dibuat dengan penuh perasaan menggunakan bunga putih segar yang melambangkan kesucian dan kedamaian.</p>',                                                                                                                                                                                                   'active'),
('Bunga Wedding',          'bunga-wedding-jakarta-utara',          'Bunga Wedding Jakarta Utara | Dekorasi Pernikahan Elegan',      'Jasa dekorasi bunga pernikahan Jakarta Utara. Hand bouquet pengantin, backdrop, meja, dan pelaminan. Konsultasi gratis, harga terjangkau.',  '<p>Percayakan momen pernikahan Anda kepada florist berpengalaman di Jakarta Utara. Kami menyediakan layanan dekorasi bunga pernikahan lengkap mulai dari hand bouquet pengantin, dekorasi pelaminan, meja tamu, hingga backdrop foto.</p>',                                                                                                                                                                                                      'active'),
('Hand Bouquet',           'hand-bouquet-jakarta-utara',           'Hand Bouquet Jakarta Utara | Buket Bunga Cantik & Segar',       'Pesan hand bouquet Jakarta Utara untuk wisuda, ulang tahun, anniversary & valentine. Desain custom, bunga segar, kirim same day.',          '<p>Hand bouquet atau buket bunga tangan adalah hadiah sempurna untuk orang-orang tersayang Anda di Jakarta Utara. Tersedia dalam berbagai ukuran dan pilihan bunga segar untuk wisuda, ulang tahun, valentine, dan momen spesial lainnya.</p>',                                                                                                                                                                                                  'active'),
('Standing Flower',        'standing-flower-jakarta-utara',        'Standing Flower Jakarta Utara | Grand Opening & Ucapan Selamat','Standing flower Jakarta Utara untuk grand opening, ulang tahun perusahaan & peresmian gedung. Desain mewah, pengiriman tepat waktu.',         '<p>Standing flower atau bunga standing adalah dekorasi elegan yang cocok untuk berbagai acara formal di Jakarta Utara. Tersedia dalam berbagai ukuran dan desain untuk grand opening, perayaan ulang tahun perusahaan, dan peresmian gedung.</p>',                                                                                                                                                                                               'active'),
('Bunga Papan Ucapan',     'bunga-papan-ucapan-jakarta-utara',     'Bunga Papan Ucapan Jakarta Utara | Selamat & Sukses',           'Bunga papan ucapan selamat Jakarta Utara. Tersedia berbagai desain untuk ulang tahun, promosi jabatan & pencapaian bisnis.',                '<p>Ungkapkan kebanggaan dan kebahagiaan Anda dengan bunga papan ucapan selamat yang indah di Jakarta Utara. Tersedia berbagai desain colorful dan elegan untuk setiap momen perayaan.</p>',                                                                                                                                                                                                                                                       'active'),
('Parcel Bunga',           'parcel-bunga-jakarta-utara',           'Parcel Bunga Jakarta Utara | Hadiah Spesial & Berkesan',        'Pesan parcel bunga Jakarta Utara untuk hari raya, ulang tahun & apresiasi bisnis. Kombinasi bunga segar dan hamper eksklusif.',              '<p>Parcel bunga adalah pilihan hadiah yang mewah dan berkesan untuk berbagai kesempatan di Jakarta Utara. Kombinasi bunga segar dan produk premium dalam kemasan eksklusif yang siap dikirim ke seluruh wilayah Jakarta Utara.</p>',                                                                                                                                                                                                               'active'),
('Bunga Meja',             'bunga-meja-jakarta-utara',             'Bunga Meja Jakarta Utara | Dekorasi Kantor & Acara',            'Sewa & jual bunga meja Jakarta Utara untuk dekorasi kantor, seminar, dan acara formal. Rangkaian segar diganti berkala.',                   '<p>Bunga meja menghadirkan keindahan dan kesegaran alami ke dalam ruangan Anda di Jakarta Utara. Tersedia layanan sewa bunga meja mingguan untuk kantor, hotel, dan berbagai acara formal.</p>',                                                                                                                                                                                                                                                  'active');

-- ============================================================
-- TABEL: products
-- ============================================================
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `fk_category` (`category_id`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`name`, `category_id`, `description`, `price`, `image`, `status`) VALUES
('Karangan Bunga Papan Selamat Premium',    1, 'Karangan bunga papan eksklusif dengan mawar merah dan krisan putih. Cocok untuk ucapan selamat pembukaan usaha, promosi jabatan, dan anniversary perusahaan di Jakarta Utara.', 450000, '', 'active'),
('Karangan Bunga Papan Duka Cita Standard', 1, 'Rangkaian bunga papan duka cita yang elegan dengan bunga putih dan hijau. Pengiriman cepat ke rumah duka di seluruh Jakarta Utara.',                                           350000, '', 'active'),
('Bunga Duka Cita Standing Premium',        2, 'Standing flower duka cita dengan lily putih dan mawar krem. Desain penuh penghormatan untuk menyampaikan belasungkawa di Jakarta Utara.',                                     500000, '', 'active'),
('Bunga Duka Cita Papan Double',            2, 'Karangan bunga papan duka cita double dengan rangkaian bunga putih berlimpah. Tampilan megah dan penuh penghormatan.',                                                         650000, '', 'active'),
('Hand Bouquet Wisuda Elegan',              4, 'Buket bunga wisuda cantik dengan mawar merah muda, baby breath, dan daun hijau segar. Pilihan terbaik untuk hari wisuda berkesan di Jakarta Utara.',                          300000, '', 'active'),
('Hand Bouquet Valentine Premium',          4, 'Buket bunga valentine romantis dengan 12 tangkai mawar merah segar. Dilengkapi wrapping eksklusif dan kartu ucapan.',                                                          400000, '', 'active'),
('Bunga Wedding Bouquet Pengantin',         3, 'Hand bouquet pengantin dengan mawar putih, peoni, dan baby breath. Tampilan elegan untuk hari pernikahan yang sempurna di Jakarta Utara.',                                    550000, '', 'active'),
('Dekorasi Meja Wedding Round',             3, 'Rangkaian bunga meja pernikahan berbentuk dome dengan mawar dan hydrangea. Cocok untuk meja tamu VIP dan meja prasmanan.',                                                    350000, '', 'active'),
('Standing Flower Grand Opening',           5, 'Standing flower mewah untuk grand opening dengan rangkaian bunga berwarna-warni. Tinggi 150cm, tampilan megah dan profesional untuk bisnis di Jakarta Utara.',               600000, '', 'active'),
('Bunga Papan Ucapan Selamat Ulang Tahun',  6, 'Papan bunga ucapan selamat ulang tahun dengan desain ceria dan colorful. Tersedia tulisan custom sesuai permintaan, pengiriman ke seluruh Jakarta Utara.',                   400000, '', 'active'),
('Parcel Bunga & Coklat Premium',           7, 'Kombinasi bunga segar dan coklat premium dalam kemasan eksklusif. Hadiah sempurna untuk hari jadi, ulang tahun, dan apresiasi bisnis di Jakarta Utara.',                     500000, '', 'active'),
('Bunga Meja Kantor Weekly',                8, 'Layanan bunga meja kantor mingguan dengan bunga segar diganti setiap minggu. Tersedia berbagai ukuran untuk kantor di Jakarta Utara.',                                        250000, '', 'active');

-- ============================================================
-- TABEL: locations (6 kecamatan Jakarta Utara)
-- ============================================================
CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `locations` (`name`, `slug`, `address`, `phone`, `meta_title`, `meta_description`, `content`, `status`) VALUES
('Penjaringan',   'toko-bunga-penjaringan',   'Kecamatan Penjaringan, Jakarta Utara',   '6281322991131', 'Toko Bunga Penjaringan Jakarta Utara | Kirim Cepat 24 Jam',    'Toko bunga Penjaringan Jakarta Utara. Karangan bunga papan, hand bouquet, bunga duka cita & wedding. Kirim cepat ke Penjaringan. Order WA!',   '<p>Kami melayani pengiriman bunga ke seluruh wilayah Kecamatan Penjaringan, Jakarta Utara termasuk Pluit, Penjaringan, Pejagalan, Kapuk Muara, dan sekitarnya. Dengan armada pengiriman yang siap 24 jam, pesanan Anda akan tiba tepat waktu dalam kondisi segar.</p>', 'active'),
('Pademangan',    'toko-bunga-pademangan',    'Kecamatan Pademangan, Jakarta Utara',    '6281322991131', 'Toko Bunga Pademangan Jakarta Utara | Florist Terpercaya',     'Toko bunga Pademangan Jakarta Utara. Tersedia karangan bunga papan, hand bouquet & standing flower. Pengiriman same day. Pesan via WA!',       '<p>Layanan pengiriman bunga ke Kecamatan Pademangan dan sekitarnya. Kami melayani wilayah Pademangan Barat, Pademangan Timur, dan Ancol dengan pengiriman cepat dan bunga segar berkualitas tinggi.</p>',                                                           'active'),
('Tanjung Priok', 'toko-bunga-tanjung-priok', 'Kecamatan Tanjung Priok, Jakarta Utara', '6281322991131', 'Toko Bunga Tanjung Priok Jakarta Utara | Harga Mulai 300rb',   'Florist Tanjung Priok Jakarta Utara terpercaya. Karangan bunga papan, duka cita, wedding & hand bouquet. Kirim cepat, harga terjangkau.',     '<p>Melayani kebutuhan bunga di Kecamatan Tanjung Priok, Jakarta Utara. Area layanan mencakup Tanjung Priok, Sunter Agung, Sunter Jaya, Papanggo, Kebon Bawang, dan seluruh kelurahan sekitarnya.</p>',                                                             'active'),
('Koja',          'toko-bunga-koja',          'Kecamatan Koja, Jakarta Utara',          '6281322991131', 'Toko Bunga Koja Jakarta Utara | Order 24 Jam Siap Kirim',      'Toko bunga Koja Jakarta Utara. Bunga segar berkualitas untuk duka cita, pernikahan & ucapan selamat. Pengiriman 2-4 jam ke Koja.',             '<p>Layanan bunga lengkap untuk warga Kecamatan Koja, Jakarta Utara. Kami melayani pengiriman ke Koja, Tugu Selatan, Tugu Utara, Lagoa, Rawa Badak Selatan, dan Rawa Badak Utara dengan cepat dan profesional.</p>',                                               'active'),
('Cilincing',     'toko-bunga-cilincing',     'Kecamatan Cilincing, Jakarta Utara',     '6281322991131', 'Toko Bunga Cilincing Jakarta Utara | Florist Terdekat',        'Florist Cilincing Jakarta Utara. Karangan bunga papan, standing flower & hand bouquet tersedia. Harga mulai 300rb, kirim same day.',          '<p>Kami hadir untuk memenuhi kebutuhan bunga Anda di Kecamatan Cilincing, Jakarta Utara. Melayani pengiriman ke Cilincing, Semper Barat, Semper Timur, Rorotan, Marunda, Kalibaru, dan Sukapura.</p>',                                                             'active'),
('Kelapa Gading', 'toko-bunga-kelapa-gading', 'Kecamatan Kelapa Gading, Jakarta Utara', '6281322991131', 'Toko Bunga Kelapa Gading Jakarta Utara | Florist Premium',    'Toko bunga Kelapa Gading Jakarta Utara. Bunga duka cita, wedding, papan ucapan & hand bouquet segar. Kirim 2-4 jam ke Kelapa Gading.',      '<p>Layanan pengiriman bunga premium ke seluruh Kecamatan Kelapa Gading, Jakarta Utara. Melayani Kelapa Gading Barat, Kelapa Gading Timur, dan Pegangsaan Dua dengan standar kualitas tertinggi.</p>',                                                             'active');

-- ============================================================
-- TABEL: testimonials
-- ============================================================
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` tinyint(1) DEFAULT 5,
  `location` varchar(100) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `testimonials` (`name`, `content`, `rating`, `location`, `urutan`, `status`) VALUES
('Budi Santoso',   'Bunga papan untuk grand opening toko kami di Kelapa Gading sangat bagus! Desainnya elegan, bunganya segar, dan pengiriman tepat waktu. Sangat merekomendasikan!',                     5, 'Kelapa Gading, Jakarta Utara', 1, 'active'),
('Sari Rahayu',    'Pesan hand bouquet untuk wisuda di Tanjung Priok, hasilnya luar biasa cantik! Pelayanan ramah, harga terjangkau, dan bunga benar-benar segar. Terima kasih!',                        5, 'Tanjung Priok, Jakarta Utara', 2, 'active'),
('Ahmad Fauzi',    'Kami menggunakan jasa dekorasi bunga untuk pernikahan putri kami di Penjaringan. Hasilnya melebihi ekspektasi! Sangat profesional dan detail dalam bekerja.',                         5, 'Penjaringan, Jakarta Utara',   3, 'active'),
('Dewi Lestari',   'Bunga duka cita yang dipesan tiba tepat waktu ke rumah duka di Koja. Desain sangat pantas dan terhormat. Terima kasih atas pelayanan yang cepat di saat kami butuhkan.',              5, 'Koja, Jakarta Utara',          4, 'active'),
('Rina Kusuma',    'Parcel bunga untuk ulang tahun atasan saya di Pademangan sangat berkelas. Kemasannya rapi dan bunga pilihannya premium. Bosnya sangat senang dan terkesan!',                          5, 'Pademangan, Jakarta Utara',    5, 'active'),
('Hendra Wijaya',  'Standing flower untuk acara seminar perusahaan kami di Cilincing tampak megah. Pengiriman on time meskipun kami pesan mendadak. Pasti akan order lagi!',                              5, 'Cilincing, Jakarta Utara',     6, 'active');

-- ============================================================
-- TABEL: faqs
-- ============================================================
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `urutan` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_urutan` (`urutan`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `faqs` (`question`, `answer`, `urutan`, `status`) VALUES
('Apakah bisa kirim hari yang sama?',                       'Ya! Kami melayani pengiriman same day untuk pesanan yang masuk sebelum pukul 14.00 WIB. Untuk pesanan mendadak, hubungi kami via WhatsApp dan kami akan berusaha mengakomodasi kebutuhan Anda.',                                                                        1,  'active'),
('Apakah melayani seluruh Jakarta Utara?',                  'Ya, kami melayani pengiriman ke seluruh 6 kecamatan di Jakarta Utara: Penjaringan, Pademangan, Tanjung Priok, Koja, Cilincing, dan Kelapa Gading beserta semua kelurahan di dalamnya.',                                                                                     2,  'active'),
('Bisa request desain custom?',                             'Tentu saja! Kami menerima permintaan desain custom sesuai keinginan Anda. Silakan kirimkan referensi foto, warna yang diinginkan, dan budget Anda melalui WhatsApp kami.',                                                                                                   3,  'active'),
('Berapa harga karangan bunga papan?',                      'Harga karangan bunga papan kami mulai dari Rp 350.000 hingga Rp 1.500.000 tergantung ukuran dan desain. Hubungi kami untuk penawaran terbaik sesuai budget Anda.',                                                                                                           4,  'active'),
('Bagaimana cara memesan?',                                 'Pemesanan dilakukan melalui WhatsApp kami di 0813-2299-1131. Cukup kirimkan nama pemesan, jenis bunga, alamat pengiriman, tanggal & waktu pengiriman, serta pesan yang ingin dituliskan.',                                                                                   5,  'active'),
('Apakah bunga dijamin segar?',                             'Ya! Kami hanya menggunakan bunga segar berkualitas tinggi yang diambil langsung dari pasar bunga setiap hari. Kesegaran bunga adalah prioritas utama kami.',                                                                                                                  6,  'active'),
('Apakah bisa mengirim ke luar Jakarta Utara?',             'Ya, kami juga melayani pengiriman ke seluruh wilayah Jakarta dan Jabodetabek. Untuk area luar kota, pengiriman menggunakan jasa ekspedisi terpercaya.',                                                                                                                      7,  'active'),
('Jam berapa saja bisa memesan?',                           'Kami menerima pesanan 24 jam sehari, 7 hari seminggu melalui WhatsApp. Untuk pengiriman, kami beroperasi setiap hari pukul 07.00 - 21.00 WIB.',                                                                                                                              8,  'active'),
('Apakah tersedia untuk acara besar seperti wedding?',      'Ya! Kami memiliki layanan khusus untuk acara pernikahan, grand opening, seminar, dan acara korporat lainnya. Hubungi kami untuk konsultasi dan penawaran paket spesial.',                                                                                                     9,  'active'),
('Apakah ada garansi jika bunga tidak sesuai?',             'Kepuasan pelanggan adalah prioritas kami. Jika produk yang diterima tidak sesuai atau rusak saat pengiriman, kami akan melakukan penggantian atau kompensasi yang adil.',                                                                                                     10, 'active');

-- ============================================================
-- TABEL: orders
-- ============================================================
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `whatsapp_url` text DEFAULT NULL,
  `status` enum('pending','confirmed','processing','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- VIEW: v_statistics
-- ============================================================
CREATE OR REPLACE VIEW `v_statistics` AS
SELECT
  (SELECT COUNT(*) FROM `products`      WHERE `status` = 'active')  AS `total_products`,
  (SELECT COUNT(*) FROM `locations`     WHERE `status` = 'active')  AS `total_locations`,
  (SELECT COUNT(*) FROM `categories`    WHERE `status` = 'active')  AS `total_categories`,
  (SELECT COUNT(*) FROM `faqs`          WHERE `status` = 'active')  AS `total_faqs`,
  (SELECT COUNT(*) FROM `testimonials`  WHERE `status` = 'active')  AS `total_testimonials`,
  (SELECT COUNT(*) FROM `orders`        WHERE `status` = 'pending') AS `pending_orders`,
  (SELECT COUNT(*) FROM `orders`        WHERE `status` = 'delivered') AS `delivered_orders`;

COMMIT;
