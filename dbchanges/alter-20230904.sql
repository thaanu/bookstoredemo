CREATE TABLE `tbl_canned_text` (
  `ct_id` int(11) NOT NULL AUTO_INCREMENT,
  `canned_text` text DEFAULT NULL,
  `canned_text_type` varchar(45) DEFAULT NULL,
  `canned_text_active` tinyint(4) DEFAULT NULL,
  `canned_text_title` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`ct_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;