
DROP TABLE IF EXISTS `apikey`;
CREATE TABLE `apikey` (
	`id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`val` varchar(255) NOT NULL,
	`uses` bigint(20) NOT NULL DEFAULT 0,
	`usage_limit` bigint(20) DEFAULT NULL,
	`expiration` date DEFAULT NULL,
	`active` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` timestamp NULL DEFAULT current_timestamp(),
	`updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
	`id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`name` varchar(255) DEFAULT NULL,
	`filename` varchar(255) DEFAULT NULL,
	`extension` varchar(255) DEFAULT NULL,
	`type` varchar(255) DEFAULT NULL,
	`size` bigint(11) DEFAULT NULL,
	`location` varchar(255) DEFAULT NULL,
	`shared_key` varchar(255) DEFAULT NULL,
	`downloads` int(11) DEFAULT 0,
	`apikey_id` varchar(255) DEFAULT NULL,
	`created_at` timestamp NULL DEFAULT current_timestamp(),
	`updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `folders`;
CREATE TABLE IF NOT EXISTS `folders` (
	`id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`name` varchar(255) DEFAULT NULL,
	`location` varchar(255) DEFAULT NULL,
	`shared_key` varchar(255) DEFAULT NULL,
	`apikey_id` varchar(255) DEFAULT NULL,
	`created_at` timestamp NULL DEFAULT current_timestamp(),
	`updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `sharekeys`;
CREATE TABLE IF NOT EXISTS `sharekeys` (
	`id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`value` varchar(255) NOT NULL,
	`active` tinyint(1) NOT NULL DEFAULT 1,
	`recursive` tinyint(1) NOT NULL DEFAULT 1, 
	`created_at` timestamp NULL DEFAULT current_timestamp(),
	`updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



