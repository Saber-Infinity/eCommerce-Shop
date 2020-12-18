-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2020 at 01:57 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` smallint(6) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Parent` int(11) NOT NULL,
  `Visibility` tinyint(1) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Ordering`, `Parent`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(9, 'Hand Made', 'Hand Made Items', 1, 0, 1, 1, 1),
(10, 'Computers', 'Computer\'s Items', 2, 0, 0, 0, 0),
(11, 'Cell Phones', 'Cell Phones Items', 3, 0, 0, 0, 0),
(12, 'Clothing', 'Clothing And Fasions', 4, 0, 0, 0, 0),
(13, 'Tools', 'Home Tools', 5, 0, 0, 0, 0),
(14, 'Nokia', 'Nokia Phones', 6, 11, 0, 0, 0),
(15, 'Blackberry', 'Blackberry Phones', 7, 11, 0, 0, 0),
(16, 'Bottles', 'A New Category Bottles', 8, 13, 0, 0, 0),
(17, 'Cakes', 'A Delicious Cakes', 9, 9, 0, 0, 0),
(18, 'Games', 'A Category Of Games', 10, 10, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `commDate` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commID`, `Comment`, `Status`, `commDate`, `item_id`, `user_id`) VALUES
(1, 'A Good Monitor, It Has A Nice Resolution And Viewport', 1, '2020-12-03', 29, 17),
(2, 'Literally It Is A Magic Mouse, Because It Has A Several Features For Programmers And Gamers', 0, '2020-12-03', 30, 10),
(3, 'It Seems A Different Type Of Mouses', 0, '2020-12-02', 30, 17),
(4, 'Wow! A Good New Smart Phone', 1, '2020-12-08', 38, 13),
(5, 'Fantastic, I Will Buy It', 0, '2020-12-18', 42, 17);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `catID` smallint(6) NOT NULL,
  `memberID` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Approve`, `catID`, `memberID`, `tags`) VALUES
(29, 'Monitor', 'Monitor + HDMI Cable', '100', '2020-12-17', 'China', '44342041_monitor.jpg', '1', 1, 10, 3, 'Keyboard, Mouse'),
(30, 'Magic Mouse', ' Gaming Mouse Wired, RGB Chroma Backlit Gaming Mouse, 8 Programmable Buttons', '20', '2020-12-17', 'USA', '237792968_mouse.jpg', '1', 1, 10, 3, 'Monitor, Keyboard, Mouse'),
(31, 'Gaming Keyboard ', 'Wired LED RGB Backlit with Multimedia Keys Wrist Rest, 25 Keys Anti-Ghosting, 8 Efficient Multimedia Keys', '33', '2020-12-17', 'China', '785736084_keyboard.jpg', '1', 1, 10, 17, 'Keyboard, Mouse'),
(32, 'Repair Hand Tools', 'DOWELL 90 Piece Tool Set Home Repair Hand Tool Kit with Wrench Sets Plastic Tool Box Storage Case', '31', '2020-12-17', 'USA', '893981934_repair_tools.jpg', '1', 1, 13, 11, 'Repair Tools'),
(33, 'Pocket Tool Bag', 'CLC Custom LeatherCraft 1539 Multi-Compartment 50 ', '73', '2020-12-17', 'China', '852020264_bag.jpg', '1', 1, 13, 10, 'Bags, Tools'),
(34, 'Apple iPhone 11', '[64GB, Black] + Carrier Subscription [Cricket Wireless]', '600', '2020-12-17', 'USA', '396606445_iphone_11.JPG', '1', 1, 11, 2, 'Mobile'),
(35, 'iPhone 12 Pro ', '(128GB, Pacific Blue) [Locked] + Carrier Subscription', '999', '2020-12-17', 'China', '684722901_iphone_12.jpg', '1', 1, 11, 2, 'iPhone, Oppo'),
(36, 'Samsung Galaxy A71', 'SM-A715F/DS 4G LTE 128GB + 6GB Ram Octa Core LTE USA w/Four Cameras (64+12+5+5mp) Android (Prism Crush Silver)', '353', '2020-12-17', 'USA', '83190918_galaxya71.jpg', '1', 1, 11, 14, ''),
(37, 'Xiaomi Redmi Note 8 Pro', '128GB, 6GB RAM 6.53\" LTE GSM 64MP Smartphone - Global Model (Mineral Grey)', '234.50', '2020-12-17', 'China', '600799561_xiaomi.jpg', '1', 1, 11, 3, 'Xiaomi, Samsung, Apple'),
(38, 'OPPO Reno2 Z', 'Dual-SIM CPH1951 128GB (GSM Only, No CDMA) Factory Unlocked 4G/LTE Smartphone - International Version (Luminous Black)', '510', '2020-12-17', 'USA', '554534912_oppo_reno2.jpg', '1', 1, 11, 10, 'Oppo, Samsung'),
(39, 'Antique Look Muted Oushak', 'Egyptian Area Rug Wool Brown Geometric Handmade Oriental Carpet 10x14 (10\' 1\'\' x 14\' 1\'\')', '3,730.00 ', '2020-12-17', 'Egypt', '14068603_antique_carpet.jpg', '1', 1, 9, 11, 'Antique, Carpet'),
(40, 'Hand Woven', 'nuLOOM Rigo Hand Woven Jute Area Rug, 5\' x 8\', Natural', '91', '2020-12-17', 'China', '832092285_hand_woven.jpg', '1', 1, 9, 11, 'Hand Woven'),
(41, 'Hand Tufted wool', 'nuLOOM Ofelia Hand Tufted Wool Area Rug, 5\' x 8\', Multi', '134', '2020-12-17', 'Egypt', '293548584_hand_wool.jpg', '1', 0, 9, 11, ''),
(42, 'Sweatshirt', 'Men\'s Sweatshirt', '28.32', '2020-12-17', 'Egypt', '951660157_sweatshirt.JPG', '1', 1, 12, 17, 'T-shirt, Sweatshirt'),
(43, 'T-Shirt', 'T-Shirt Mickey Mouse', '19.99', '2020-12-17', 'China', '857696534_T-shirt.JPG', '1', 1, 12, 13, 'T-Shirt'),
(44, 'Pants', 'Men\'s Pants', '25.99', '2020-12-17', 'USA', '350036621_pants.JPG', '1', 0, 12, 17, 'T-Shirt, Pants');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To identify user',
  `Username` varchar(255) NOT NULL COMMENT 'Username to login',
  `Password` varchar(255) NOT NULL COMMENT 'Password to login',
  `Email` varchar(255) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'Identify user group',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'Pending (User) Approval',
  `Date` date NOT NULL,
  `Avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `Fullname`, `GroupID`, `RegStatus`, `Date`, `Avatar`) VALUES
(2, 'Ahmad', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ahmad@yahoo.com', 'Ahmad Saleh', 1, 1, '2020-11-09', ''),
(3, 'Saber', '601f1889667efaebb33b8c12572835da3f027f78', 'saber@hotmail.com', 'Saber Abdel-Rafea', 1, 1, '0000-00-00', ''),
(10, 'Maak404', '6ddddf650e33112a58d5eab7fefae90bb3ddbac4', 'mak@gmail.com', 'Mahmoud Kamal', 0, 1, '2020-11-13', ''),
(11, 'John', '7ffaa127d109786202ce876700959785b2a1dfba', 'john@hotmail.com', 'John William', 0, 0, '2020-11-12', ''),
(12, 'Ali01', 'b3f505c9200d795a9525d9d82ab212d90f4e829d', 'ali@yahoo.com', 'Ali Eid', 0, 1, '2020-11-15', ''),
(13, 'Sara', '23cca9b9e43e78e53159b4d8cd9338f71c94dcc1', 'sara@gmail.com', 'Sara Khaled', 0, 1, '2020-11-22', ''),
(14, 'Osama2', '27811ffb166cbfa82b2d43c4bf8dfda01a68cb71', 'osama@info.com', 'Osama Metwally', 0, 1, '2020-11-22', ''),
(16, 'Alioo', 'c4a2d99bc28d236098a095277b7eb0718d6be068', 'salk@lk.as', 'Diang', 0, 1, '2020-12-09', ''),
(17, 'Fahd', 'e7001334d9d19559a8bb0dd6015f16e31d15566c', 'fahd@yahoo.com', 'Fahd Elmowalid', 0, 1, '2020-12-09', ''),
(18, 'Fathy', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', 'fathy@gmail.com', '', 0, 0, '2020-12-18', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commID`),
  ADD KEY `Writes` (`user_id`),
  ADD KEY `Added_Comment` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `buy` (`memberID`),
  ADD KEY `has` (`catID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To identify user', AUTO_INCREMENT=19;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `Added_Comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Writes` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `buy` FOREIGN KEY (`memberID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `has` FOREIGN KEY (`catID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
