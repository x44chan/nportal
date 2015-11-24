-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2015 at 07:55 AM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `account_id` int(11) NOT NULL,
  `uname` varchar(255) DEFAULT NULL,
  `pword` varchar(255) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`account_id`, `uname`, `pword`, `fname`, `lname`, `level`) VALUES
(1, 'admin', 'admin', 'Admin', 'Nistrator', 'Admin'),
(2, 'chan', 'chano', 'Christian', 'Aquino', 'EMP'),
(3, 'hr', 'hr', 'H', 'R', 'HR'),
(4, 'acc', 'acc', 'Accoun', 'Ting', 'ACC'),
(5, 'emp', 'emp', 'Emp', 'Ployee', 'EMP'),
(6, 'chano', 'chanochano', 'chano', 'chano', 'Employee');

-- --------------------------------------------------------

--
-- Table structure for table `nleave`
--

CREATE TABLE IF NOT EXISTS `nleave` (
  `leave_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `datefile` varchar(255) DEFAULT NULL,
  `nameofemployee` varchar(255) DEFAULT NULL,
  `datehired` varchar(255) DEFAULT NULL,
  `deprt` varchar(255) DEFAULT NULL,
  `posttile` varchar(255) DEFAULT NULL,
  `dateofleavfr` varchar(255) DEFAULT NULL,
  `dateofleavto` varchar(255) DEFAULT NULL,
  `numdays` varchar(255) DEFAULT NULL,
  `typeoflea` varchar(255) DEFAULT NULL,
  `othersl` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `twodaysred` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT 'UA',
  `datehr` varchar(255) DEFAULT NULL,
  `dateacc` varchar(255) DEFAULT NULL,
  `dareason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nleave`
--

INSERT INTO `nleave` (`leave_id`, `account_id`, `datefile`, `nameofemployee`, `datehired`, `deprt`, `posttile`, `dateofleavfr`, `dateofleavto`, `numdays`, `typeoflea`, `othersl`, `reason`, `twodaysred`, `state`, `datehr`, `dateacc`, `dareason`) VALUES
(1, 2, '2015-09-09', 'Christian Aquino', '2015-09-11', 'IT', 'Prog.', '2015-09-11', '2015-09-15', '3', 'Others', 'paternity', 'paternity', '2015-09-11', 'AAdmin', 'September 14, 2015 01:07 PM', 'September 14, 2015 01:40 PM', ''),
(2, 2, '2015-09-10', 'Christian Aquino', '2015-09-11', 'IT', 'Prog.', '2015-09-11', '2015-09-15', '3', 'Others', 'paternity app', 'paternity app', '2015-09-12', 'AAdmin', 'September 14, 2015 01:07 PM', 'September 14, 2015 01:40 PM', ''),
(3, 2, '2015-09-11', 'Christian Aquino', '2015-09-11', 'IT', 'Prog.', '2015-09-11', '2015-09-15', '3', 'Others', 'paternity dapp', 'paternity dapp', '2015-09-13', 'DAHR', 'September 14, 2015 01:07 PM', NULL, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `officialbusiness`
--

CREATE TABLE IF NOT EXISTS `officialbusiness` (
  `officialbusiness_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `obdate` date DEFAULT NULL,
  `obename` varchar(255) DEFAULT NULL,
  `obpost` varchar(255) DEFAULT NULL,
  `obdept` varchar(255) DEFAULT NULL,
  `obdatereq` varchar(255) DEFAULT NULL,
  `obreason` varchar(255) DEFAULT NULL,
  `obtimein` varchar(255) DEFAULT NULL,
  `obtimeout` varchar(255) DEFAULT NULL,
  `officialworksched` varchar(255) DEFAULT NULL,
  `state` varchar(25) NOT NULL,
  `datehr` varchar(255) DEFAULT NULL,
  `dateacc` varchar(255) DEFAULT NULL,
  `twodaysred` varchar(255) DEFAULT NULL,
  `dareason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `officialbusiness`
--

INSERT INTO `officialbusiness` (`officialbusiness_id`, `account_id`, `obdate`, `obename`, `obpost`, `obdept`, `obdatereq`, `obreason`, `obtimein`, `obtimeout`, `officialworksched`, `state`, `datehr`, `dateacc`, `twodaysred`, `dareason`) VALUES
(1, 2, '2015-09-09', 'Christian Aquino', 'Programmer', 'IT Department', '2015-09-11', 'installing emp portal', '8:00 AM', '6:00 PM', '8:00 AM 6:00 PM', 'AAdmin', '2015-09-14 01:07 PM', '2015-09-14 01:40 PM', '2015-09-11', ''),
(2, 2, '2015-09-10', 'Christian Aquino', 'Programmer', 'IT Department', '2015-09-11', 'installing emp portal app', '8:00 AM', '6:00 PM', '8:00 AM 6:00 PM', 'AAdmin', '2015-09-14 01:07 PM', '2015-09-14 01:40 PM', '2015-09-12', ''),
(3, 2, '2015-09-12', 'Christian Aquino', 'Programmer', 'IT Department', '2015-09-11', 'installing emp portal dapp', '8:00 AM', '6:00 PM', '8:00 AM 6:00 PM', 'AAdmin', '2015-09-14 01:07 PM', '2015-09-14 01:40 PM', '2015-09-14', ''),
(4, 2, '2015-09-13', 'Christian Aquino', 'Programmer', 'IT Department', '2015-09-14', 'installation of system', '8:00 AM', '6:00 PM', '8:00 AM 6:00 PM', 'DAHR', '2015-09-14 01:07 PM', '2015-09-14 10:53 AM', '2015-09-15', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE IF NOT EXISTS `overtime` (
  `overtime_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `datefile` varchar(255) DEFAULT NULL,
  `2daysred` date NOT NULL,
  `dateofot` varchar(255) DEFAULT NULL,
  `nameofemp` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `startofot` varchar(255) DEFAULT NULL,
  `endofot` varchar(255) DEFAULT NULL,
  `approvedothrs` varchar(255) DEFAULT NULL,
  `officialworksched` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `datehr` varchar(255) DEFAULT NULL,
  `dateacc` varchar(255) DEFAULT NULL,
  `dareason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `overtime`
--

INSERT INTO `overtime` (`overtime_id`, `account_id`, `datefile`, `2daysred`, `dateofot`, `nameofemp`, `reason`, `startofot`, `endofot`, `approvedothrs`, `officialworksched`, `state`, `datehr`, `dateacc`, `dareason`) VALUES
(1, 2, '2015-09-11', '2015-09-13', '2015-09-11', 'Christian Aquino', 'test for approve admin', '6:00 PM', '8:30 PM', '2:30', '8:00 AM - 6:00 PM', 'AAdmin', '2015-09-14 01:06 PM', '2015-09-14 01:40 PM', ''),
(2, 2, '2015-09-15', '2015-09-16', '2015-09-15', 'Christian Aquino', 'test for dapprove admin', '6:00 PM', '8:30 PM', '2:30', '8:00 AM - 6:00 PM', 'DAHR', '2015-09-14 01:07 PM', '2015-09-14 11:34 AM', 'test'),
(3, 2, '2015-09-09', '2015-09-11', '2015-09-11', 'Christian Aquino', 'test for 2days red', '6:00 PM', '8:30 PM', '2:30', '8:00 AM - 6:00 PM', 'AAdmin', '2015-09-14 01:06 PM', '2015-09-14 01:39 PM', ''),
(4, 5, '2015-09-12', '2015-09-14', '2015-09-12', 'Emp Ployee', 'g', '6:00 PM', '8:00 PM', '2:0', '8:00 AM - 6:00 PM', 'AAdmin', '2015-09-14 01:06 PM', '2015-09-14 01:40 PM', ''),
(5, 5, '2015-09-13', '2015-09-15', '2015-09-13', 'Emp Ployee', 'gg', '6:00 PM', '10:00 PM', '4:0', '8:00 AM - 6:00 PM', 'AAdmin', '2015-09-14 01:06 PM', '2015-09-14 01:40 PM', ''),
(6, 2, '2015-09-14', '2015-09-16', '2015-09-14', 'Christian Aquino', 'adasd', '6:00 PM', '10:00 PM', '4:0', '8:00 AM - 6:00 PM', 'AAdmin', '2015-09-14 01:06 PM', '2015-09-14 01:40 PM', '');

-- --------------------------------------------------------

--
-- Table structure for table `undertime`
--

CREATE TABLE IF NOT EXISTS `undertime` (
  `undertime_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `datefile` date DEFAULT NULL,
  `twodaysred` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `dateofundrtime` date DEFAULT NULL,
  `undertimefr` varchar(255) DEFAULT NULL,
  `undertimeto` varchar(255) DEFAULT NULL,
  `numofhrs` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT 'UA',
  `datehr` varchar(255) DEFAULT NULL,
  `dateacc` varchar(255) DEFAULT NULL,
  `dareason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `undertime`
--

INSERT INTO `undertime` (`undertime_id`, `account_id`, `datefile`, `twodaysred`, `name`, `position`, `department`, `dateofundrtime`, `undertimefr`, `undertimeto`, `numofhrs`, `reason`, `state`, `datehr`, `dateacc`, `dareason`) VALUES
(1, 2, '2015-09-10', '2015-09-12', 'Christian Aquino', 'Prog', 'IT', '2015-09-11', '3:00 PM', '6:00 PM', '3/00', 'test for app', 'AAdmin', '2015-09-14 01:07 PM', '2015-09-14 01:40 PM', ''),
(2, 2, '2015-09-11', '2015-09-13', 'Christian Aquino', 'Prog', 'IT', '2015-09-11', '3:00 PM', '6:00 PM', '3/00', 'test for dapp', 'AAdmin', '2015-09-14 01:07 PM', '2015-09-14 01:40 PM', ''),
(3, 2, '2015-09-09', '2015-09-11', 'Christian Aquino', 'Prog', 'IT', '2015-09-11', '3:00 PM', '6:00 PM', '3/00', 'test for 2 days', 'AAdmin', '2015-09-14 01:07 PM', '2015-09-14 01:40 PM', ''),
(4, 2, '2015-09-12', '2015-09-14', 'Christian Aquino', 'g', 'g', '2015-09-11', '3:00 PM', '6:00 PM', '3/00', 'g', 'DAHR', '2015-09-14 01:07 PM', '2015-09-11 11:37 AM', 'test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `nleave`
--
ALTER TABLE `nleave`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `officialbusiness`
--
ALTER TABLE `officialbusiness`
  ADD PRIMARY KEY (`officialbusiness_id`);

--
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`overtime_id`);

--
-- Indexes for table `undertime`
--
ALTER TABLE `undertime`
  ADD PRIMARY KEY (`undertime_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `nleave`
--
ALTER TABLE `nleave`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `officialbusiness`
--
ALTER TABLE `officialbusiness`
  MODIFY `officialbusiness_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
  MODIFY `overtime_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `undertime`
--
ALTER TABLE `undertime`
  MODIFY `undertime_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
