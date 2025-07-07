CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `setting_group` varchar(50) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `setting_group_key` (`setting_group`,`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data awal
INSERT INTO `settings` (`setting_group`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
('company', 'name', 'Nama Perusahaan Anda', NOW(), NOW()),
('company', 'address', 'Alamat Perusahaan Anda', NOW(), NOW()),
('company', 'phone', 'Nomor Telepon Perusahaan', NOW(), NOW()),
('company', 'email', 'email@perusahaan.com', NOW(), NOW()); 