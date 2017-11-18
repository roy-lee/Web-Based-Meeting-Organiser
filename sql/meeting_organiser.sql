-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2017 at 02:52 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

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

-- --------------------------------------------------------

--
-- Table structure for table `counterproposal`
--

CREATE TABLE `counterproposal` (
  `meeting_meetingid` int(11) NOT NULL,
  `user_userid` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `meetingid` int(11) NOT NULL,
  `meetingtitle` varchar(255) DEFAULT NULL,
  `isallday` tinyint(4) DEFAULT NULL,
  `datestart` datetime DEFAULT NULL,
  `dateend` datetime DEFAULT NULL,
  `venue` varchar(45) DEFAULT NULL,
  `user_userid` int(11) NOT NULL,
  `repeattype` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meetingdates`
--

CREATE TABLE `meetingdates` (
  `meetingdateid` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `meeting_meetingid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `meeting_meetingid` int(11) NOT NULL,
  `user_userid` int(11) NOT NULL,
  `participanttype` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `accountState` varchar(10) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `counterproposal`
--
ALTER TABLE `counterproposal`
  ADD PRIMARY KEY (`meeting_meetingid`,`user_userid`),
  ADD KEY `fk_counterproposal_users1_idx` (`user_userid`);

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`meetingid`,`user_userid`),
  ADD KEY `fk_meeting_user1_idx` (`user_userid`);

--
-- Indexes for table `meetingdates`
--
ALTER TABLE `meetingdates`
  ADD PRIMARY KEY (`meetingdateid`),
  ADD KEY `fk_meetingdates_meeting_idx` (`meeting_meetingid`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`meeting_meetingid`,`user_userid`),
  ADD KEY `fk_participants_user1_idx` (`user_userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meetingid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meetingdates`
--
ALTER TABLE `meetingdates`
  MODIFY `meetingdateid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `counterproposal`
--
ALTER TABLE `counterproposal`
  ADD CONSTRAINT `fk_counterproposal_meeting1` FOREIGN KEY (`meeting_meetingid`) REFERENCES `meeting` (`meetingid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_counterproposal_users1` FOREIGN KEY (`user_userid`) REFERENCES `users` (`userid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `fk_meeting_user1` FOREIGN KEY (`user_userid`) REFERENCES `users` (`userid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `meetingdates`
--
ALTER TABLE `meetingdates`
  ADD CONSTRAINT `fk_meetingdates_meeting` FOREIGN KEY (`meeting_meetingid`) REFERENCES `meeting` (`meetingid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `fk_participants_meeting1` FOREIGN KEY (`meeting_meetingid`) REFERENCES `meeting` (`meetingid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_participants_user1` FOREIGN KEY (`user_userid`) REFERENCES `users` (`userid`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
