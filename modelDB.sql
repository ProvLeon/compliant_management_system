SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `modelDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--
DROP DATABASE IF EXISTS FinalProject ;

CREATE DATABASE FinalProject;

USE FinalProject;

CREATE TABLE `admin` (
  `adName` VARCHAR(255) NOT NULL,
  `pass` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `contact` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adName`, `pass`, `email`, `contact`) VALUES
('admin', 'admin', 'admin@ktu.edu.gh', '2222222222');

-- --------------------------------------------------------

--
-- Table structure for table `caretaker`
--

CREATE TABLE `caretaker` (
  `tid` int(9) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ctype` varchar(25) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `address` varchar(250) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='caretaker';

--
-- Dumping data for table `caretaker`
--

INSERT INTO `caretaker` (`tid`, `name`, `ctype`, `contact`, `address`, `email`, `password`) VALUES
(5, '', 'type', '', '', '', ''),
(1, 'caretaker1', 'Hostel', '0567760258', 'KTU', 'caretaker1@ktu.edu.gh', 'caretaker'),
(2, 'caretaker2', 'Academics', '0567760258', 'KTU', 'caretaker2@ktu.edu.gh', 'caretaker'),
(3, 'caretaker3', 'Harrassment', '0247456321', 'KTU', 'caretaker3@ktu.edu.gh', 'caretaker'),
(4, 'caretaker4', 'Other', '0567752525', 'KTU', 'caretaker4@ktu.edu.gh', 'caretaker');

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `cid` int(6) NOT NULL,
  `description` varchar(400) NOT NULL,
  `sid` varchar(15) NOT NULL,
  `type` varchar(25) NOT NULL,
  `SEmail` varchar(255) NOT NULL,
  `status` varchar(15) NOT NULL,
  `Cby` varchar(25) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`cid`, `description`, `sid`, `type`, `SEmail`, `status`, `Cby`, `date`) VALUES
(123505, 'The hostel booking website is too slow, can you please work on the speed', 'B20220605', 'Hostel', 'B20220605@ktu.edu.gh', 'pending', 'ray cudjoe', '2022-02-09 08:45:16'),
(123506, 'The project deadline is 28 april please extends the date...', 'B20220603', 'Other', 'B20220603@ktu.edu.gh', 'approved', 'kelvin moah', '2022-03-09 17:20:29'),
(123502, 'Testing the system...', 'B20220601', 'Other', 'B20220601@ktu.edu.gh', 'approved', 'nakam', '2022-04-04 19:20:29'),
(123503, 'Hello, please programming 1 is not found on the examination timetable', 'B20220602', 'academics', 'B20220602@ktu.edu.gh', 'approved', 'gideon', '2022-05-19 19:20:29');
-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `fid` int(25) NOT NULL,
  `sid` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`fid`, `sid`, `name`, `email`, `description`) VALUES
(5093, 'B20220605', 'Gilbert', 'B20220605@ktu.edu.gh', 'system working properly');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS student;
CREATE TABLE `student` (
  `rollno` varchar(10) NOT NULL,
  `name` varchar(66) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hostel` varchar(30) NOT NULL,
  `course` varchar(30) NOT NULL,
  `password` varchar(25) DEFAULT NULL,
  `active` char(1) NOT NULL DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`rollno`, `name`, `contact`, `email`, `hostel`, `course`, `password`, `active`) VALUES
('B202220601', 'User One', '0556516391', 'B202220601@ktu.edu.gh', 'private hostel', 'diploma in IT', 'password', 'y'),
('B202220602', 'User two', '0556516391', 'B202220602@ktu.edu.gh', 'private hostel', 'diploma in IT', 'password', 'y'),
('B202220603', 'User three', '0556516391', 'B202220603@ktu.edu.gh', 'private hostel', 'diploma in IT', 'password', 'y'),
('B202220604', 'Nana Asamoah Kwaw', '0556516391', 'B202220604@ktu.edu.gh', 'private hostel', 'diploma in IT', 'password', 'y'),
('B202220605', 'Gilbert Afranie', '0551838624', 'B202220605@ktu.edu.gh', 'mba', 'new hostel', 'password', 'y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `caretaker`
--
ALTER TABLE `caretaker`
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `tid` (`tid`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `Cby` (`Cby`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`rollno`),
  ADD UNIQUE KEY `email` (`email`);


UPDATE `student` SET `active` = 'y';

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `caretaker`
--
ALTER TABLE `caretaker`
  MODIFY `tid` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `cid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123507;
--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `fid` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5094;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
