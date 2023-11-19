-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 20, 2023 at 01:51 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstoreddemodb`
--

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`role_id`, `role_name`, `role_code`, `is_initial`) VALUES
(1, 'Manage Books', 'MNG_BOOKS', 1),
(2, 'Manage Users', 'MNG_USERS', 0),
(3, 'Manage Groups', 'MNG_GROUPS', 0),
(4, 'Manage Cronjobs', 'MNG_CRONJOBS', 0);

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `email`, `password`, `salt`, `full_name`, `user_group`, `store_id`, `parent_id`, `is_deleted`, `take_tour`) VALUES
(1, 'ahmedshan16@gmail.com', 'a6d2869cef6f668882f9059dbbaaced214bc6a5dacb3b6a0ce4ea2b20d4e8ea4', '653f728552ee91c383cd30b7c298ab50293adfecb7b18', 'Ahmed Shan', 2, NULL, NULL, 0, 0),
(2, 'mohamed.athik@avasride.com', 'f5985075c11d97808b9c1cb837e7edfea937d20ef2191040b69956198c82f847', '6559aa2f4a0711c383cd30b7c298ab50293adfecb7b18', 'Mohamed Athik', 2, NULL, NULL, 0, 0);

--
-- Dumping data for table `tbl_user_groups`
--

INSERT INTO `tbl_user_groups` (`ug_id`, `group_name`, `group_roles`) VALUES
(1, 'Basic', 'MNG_BOOKS'),
(2, 'Administrator', 'MNG_USERS:MNG_ROLES:MNG_GROUPS:MNG_BOOKS:MNG_CLIENTS');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
