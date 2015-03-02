/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `key_value_store`
--

CREATE TABLE IF NOT EXISTS `key_value_store` (
  `id` varchar(20) NOT NULL,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='for storing some log information';

--
-- Dumping data for table `key_value_store`
--

INSERT INTO `key_value_store` (`id`, `data`) VALUES
('current_updated', '2015-03-01 12:11:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `key_value_store`
--
ALTER TABLE `key_value_store`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
