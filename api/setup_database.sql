-- Complete Database Setup for Managing Director App
-- Run this in phpMyAdmin on your Hostinger hosting

-- 1. Create the database (if it doesn't exist)
-- Note: You may need to create this manually in Hostinger control panel
-- Database name: u344026722_md

-- 2. Create the user table
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Clear existing data (optional - uncomment if you want to start fresh)
-- DELETE FROM `tbl_user`;

-- 4. Insert test users
INSERT INTO `tbl_user` (`username`, `password`) VALUES 
('admin', 'admin123'),
('director', 'director123'),
('manager', 'manager123')
ON DUPLICATE KEY UPDATE 
`password` = VALUES(`password`);

-- 5. Verify the data
SELECT * FROM `tbl_user`;

-- 6. Test query to verify login works
SELECT * FROM `tbl_user` WHERE `username` = 'admin' AND `password` = 'admin123'; 