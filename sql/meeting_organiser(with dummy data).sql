-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 22, 2017 at 05:44 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meeting_organiser`
--
-- -----------------------------------------------------
-- Schema meeting_organiser
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema meeting_organiser
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `meeting_organiser` DEFAULT CHARACTER SET utf8 ;
USE `meeting_organiser` ;


-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `meetingID` int(11) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `startTime` time(6) DEFAULT NULL,
  `endTime` time(6) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `eventStatus` varchar(10) DEFAULT NULL,
  `venue_venueID` int(11) NOT NULL,
  `user_userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`meetingID`, `startDate`, `endDate`, `startTime`, `endTime`, `title`, `description`, `eventStatus`, `venue_venueID`, `user_userID`) VALUES
(1, '2017-11-22', '2017-11-22', '08:00:00.000000', '16:00:00.000000', 'Hello Pando', 'The objective of the course is to familiarise participants in the various options available for bone augmentation as well as given the opportunity to perform more complex grafting procedures such as bone augmentation from the chin/ramus area and sinus lift surgery.\r\n                            <br><br> After the course, participants should be able to understand and perform basic techniques with bone grafting. This includes socket preservation and guided bone regeneration, in addition, they will be familiar with harvesting autogenous bone from the jaws.</p>', '1', 1, 1),
(2, '2017-11-24', '2017-11-24', '08:00:00.000000', '16:00:00.000000', 'Hello Moto', 'HELLO MOTO!!!', '0', 2, 2),
(3, '2017-11-25', '2017-11-25', '08:00:00.000000', '16:00:00.000000', 'Hello SIT', 'Welcome to SIT', '1', 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `meeting_participants`
--

CREATE TABLE `meeting_participants` (
  `meeting_meetingID` int(11) NOT NULL,
  `meeting_venue_venueID` int(11) NOT NULL,
  `meeting_user_userID` int(11) NOT NULL,
  `user_userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meeting_participants`
--

INSERT INTO `meeting_participants` (`meeting_meetingID`, `meeting_venue_venueID`, `meeting_user_userID`, `user_userID`) VALUES
(1, 1, 1, 1),
(1, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fullName` varchar(50) DEFAULT NULL,
  `verified` varchar(5) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `email`, `username`, `password`, `fullName`, `verified`) VALUES
(1, '1700500@sit.singaporetech.edu.sg', 'roylee', '$2y$10$766Hv4vxxre8XW9RLQ.GEu4hVefL598TxTZ21Y7YDSUUGb0L2HiHO', 'Roy Lee', 'yes'),
(2, '1700501@sit.singaporetech.edu.sg', 'roylee1', '$2y$10$766Hv4vxxre8XW9RLQ.GEu4hVefL598TxTZ21Y7YDSUUGb0L2HiHO', 'Mohamud Ali', 'yes'),
(3, '1700502@sit.singaporetech.edu.sg', 'roylee2', '$2y$10$766Hv4vxxre8XW9RLQ.GEu4hVefL598TxTZ21Y7YDSUUGb0L2HiHO', 'Fatimah', 'yes'),
(4, '1700503@sit.singaporetech.edu.sg', 'roylee3', '$2y$10$766Hv4vxxre8XW9RLQ.GEu4hVefL598TxTZ21Y7YDSUUGb0L2HiHO', 'Ah Mao', 'yes'),
(5, '1700504@sit.singaporetech.edu.sg', 'roylee4', '$2y$10$766Hv4vxxre8XW9RLQ.GEu4hVefL598TxTZ21Y7YDSUUGb0L2HiHO', 'Ah Gou', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venueID` int(11) NOT NULL,
  `venue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venueID`, `venue`) VALUES
(1, 'SIT@SP'),
(2, 'SIT@NP');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`meetingID`,`venue_venueID`,`user_userID`),
  ADD KEY `fk_meeting_venue_idx` (`venue_venueID`),
  ADD KEY `fk_meeting_user1_idx` (`user_userID`);

--
-- Indexes for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD PRIMARY KEY (`meeting_meetingID`,`meeting_venue_venueID`,`meeting_user_userID`,`user_userID`),
  ADD KEY `fk_meeting_participants_meeting1_idx` (`meeting_meetingID`,`meeting_venue_venueID`,`meeting_user_userID`),
  ADD KEY `fk_meeting_participants_user1_idx` (`user_userID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venueID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meetingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `venueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `fk_meeting_user1` FOREIGN KEY (`user_userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_meeting_venue` FOREIGN KEY (`venue_venueID`) REFERENCES `venue` (`venueID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD CONSTRAINT `fk_meeting_participants_meeting1` FOREIGN KEY (`meeting_meetingID`,`meeting_venue_venueID`,`meeting_user_userID`) REFERENCES `meeting` (`meetingID`, `venue_venueID`, `user_userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_meeting_participants_user1` FOREIGN KEY (`user_userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
