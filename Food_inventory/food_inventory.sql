-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2015 at 05:49 AM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `food_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(8) NOT NULL AUTO_INCREMENT,
  `category_description` varchar(250) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_description`) VALUES
(1, 'Entree'),
(2, 'Drink'),
(3, 'Appetizer');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE IF NOT EXISTS `food` (
  `food_id` int(8) NOT NULL AUTO_INCREMENT,
  `food_name` varchar(125) NOT NULL,
  `food_description` varchar(250) DEFAULT NULL,
  `regular_price` float NOT NULL,
  `sale_price` float DEFAULT NULL,
  `sale_start_date` date DEFAULT NULL,
  `sale_end_date` date DEFAULT NULL,
  `category_id` int(8) NOT NULL,
  `pic` varchar(250) NOT NULL,
  `cyclone_card_item` tinyint(1) NOT NULL,
  `cyclone_card_price` float DEFAULT NULL,
  PRIMARY KEY (`food_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`food_id`, `food_name`, `food_description`, `regular_price`, `sale_price`, `sale_start_date`, `sale_end_date`, `category_id`, `pic`, `cyclone_card_item`, `cyclone_card_price`) VALUES
(8, 'Sandwich', 'A tasty, delicious, and beautiful sandwich.', 3.99, NULL, NULL, NULL, 1, '8.jpg', 1, 1.5),
(9, 'Drink', 'An ice cold Pepsi.', 1.99, 1.99, '2015-12-01', '2016-01-31', 2, '9.jpg', 0, NULL),
(10, 'Apple', 'A crisp red apple.', 0.99, 3, '2015-01-31', '2015-03-03', 3, '10.jpg', 0, NULL),
(11, 'Coke', 'Ice cold Coke.', 2.99, 0, '2015-07-25', '2015-08-31', 2, '11.jpg', 1, 2.25);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
