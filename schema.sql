-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2022 at 12:49 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kwoodrado_db`
--
CREATE DATABASE IF NOT EXISTS `kwoodrado_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `kwoodrado_db`;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `phone_number` char(11) NOT NULL,
  `email_address` varchar(345) NOT NULL,
  `password` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `placed_order`
--

DROP TABLE IF EXISTS `placed_order`;
CREATE TABLE `placed_order` (
  `id` int(11) NOT NULL,
  `customer_email_address` varchar(345) NOT NULL,
  `placed_in` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_address` text NOT NULL,
  `billing_address` text NOT NULL,
  `status` char(10) NOT NULL,
  `additional_notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `placed_order_item`
--

DROP TABLE IF EXISTS `placed_order_item`;
CREATE TABLE `placed_order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `final_unit_price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `is_taxable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_name` varchar(64) DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `description` text DEFAULT NULL,
  `is_taxable` tinyint(1) NOT NULL,
  `cost_per_item` double NOT NULL,
  `unit_price` double NOT NULL,
  `compare_to_price` double DEFAULT NULL,
  `stock_keeping_unit` varchar(128) NOT NULL,
  `units_in_stock` int(11) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
CREATE TABLE `product_category` (
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `thumbnail` mediumblob DEFAULT NULL,
  `parent_category_name` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

DROP TABLE IF EXISTS `product_image`;
CREATE TABLE `product_image` (
  `product_id` int(11) NOT NULL,
  `local_filesystem_location` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`email_address`);

--
-- Indexes for table `placed_order`
--
ALTER TABLE `placed_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PLACED_ORDER_CUSTOMER_EMAIL_ADDRESS` (`customer_email_address`);

--
-- Indexes for table `placed_order_item`
--
ALTER TABLE `placed_order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PLACED_ORDER_ITEM_ORDER_ID` (`order_id`),
  ADD KEY `FK_PLACED_ORDER_ITEM_PRODUCT_ID` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PRODUCT_CATEGORY_NAME` (`category_name`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`name`),
  ADD KEY `FK_PRODUCT_CATEGORY_PARENT_CATEGORY_NAME` (`parent_category_name`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD KEY `FK_PRODUCT_IMAGE_PRODUCT_ID` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `placed_order`
--
ALTER TABLE `placed_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `placed_order_item`
--
ALTER TABLE `placed_order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `placed_order`
--
ALTER TABLE `placed_order`
  ADD CONSTRAINT `FK_PLACED_ORDER_CUSTOMER_EMAIL_ADDRESS` FOREIGN KEY (`customer_email_address`) REFERENCES `customer` (`email_address`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `placed_order_item`
--
ALTER TABLE `placed_order_item`
  ADD CONSTRAINT `FK_PLACED_ORDER_ITEM_ORDER_ID` FOREIGN KEY (`order_id`) REFERENCES `placed_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PLACED_ORDER_ITEM_PRODUCT_ID` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_PRODUCT_CATEGORY_NAME` FOREIGN KEY (`category_name`) REFERENCES `product_category` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `FK_PRODUCT_CATEGORY_PARENT_CATEGORY_NAME` FOREIGN KEY (`parent_category_name`) REFERENCES `product_category` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `FK_PRODUCT_IMAGE_PRODUCT_ID` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
