/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE IF NOT EXISTS `seasons` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `leaderboard_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='seasons of awesomenauts';

--
-- Dumping data for table `seasons`
--

INSERT INTO `seasons` (`id`, `start_date`, `end_date`, `leaderboard_id`) VALUES
(8, '2014-02-01', '2014-03-31', 274884),
(9, '2014-04-01', '2014-05-31', 298119),
(10, '2014-06-01', '2014-07-31', 331874),
(11, '2014-08-01', '2014-09-30', 397491),
(12, '2014-10-01', '2014-11-30', 483346),
(13, '2014-12-01', '2014-01-31', 483347),
(14, '2015-02-01', '2015-03-31', 483348),
(15, '2015-04-01', '2015-05-31', 483349),
(16, '2015-06-01', '2015-07-31', 483350),
(17, '2015-08-01', '2015-09-30', 483351),
(18, '2015-10-01', '2015-11-30', 483352),
(19, '2015-12-01', '2016-01-31', 483353),
(20, '2016-02-01', '2016-03-31', 483354),
(21, '2016-04-01', '2016-05-31', 483355),
(22, '2016-06-01', '2016-07-31', 483356),
(23, '2016-08-01', '2016-09-30', 483357),
(24, '2016-10-01', '2016-11-30', 483358),
(25, '2016-12-01', '2017-01-31', 483359),
(26, '2017-02-01', '2017-03-31', 483360),
(27, '2017-04-01', '2017-05-31', 483361),
(28, '2017-06-01', '2017-07-31', 483362),
(29, '2017-08-01', '2017-09-30', 483363),
(30, '2017-10-01', '2017-11-30', 483364),
(31, '2017-12-01', '2018-01-31', 483365),
(32, '2018-02-01', '2018-03-31', 483366),
(33, '2018-04-01', '2018-05-31', 483367),
(34, '2018-06-01', '2018-07-31', 483368),
(35, '2018-08-01', '2018-09-30', 483369),
(36, '2018-10-01', '2018-11-30', 483370),
(37, '2018-12-01', '2019-01-31', 483371),
(38, '2019-02-01', '2019-03-31', 483372),
(39, '2019-04-01', '2019-05-31', 483373),
(40, '2019-06-01', '2019-07-31', 483374),
(41, '2019-08-01', '2019-09-30', 483375),
(42, '2019-10-01', '2019-11-30', 483376),
(43, '2019-12-01', '2020-01-31', 483377),
(44, '2020-02-01', '2020-03-31', 483378),
(45, '2020-04-01', '2020-05-31', 483379),
(46, '2020-06-01', '2020-07-31', 483380),
(47, '2020-08-01', '2020-09-30', 483381),
(48, '2020-10-01', '2020-11-30', 483382),
(49, '2020-12-01', '2021-01-31', 483383),
(50, '2021-02-01', '2021-03-31', 483384),
(51, '2021-04-01', '2021-05-31', 483385),
(52, '2021-06-01', '2021-07-31', 483386),
(53, '2021-08-01', '2021-09-30', 483387),
(54, '2021-10-01', '2021-11-30', 483388),
(55, '2021-12-01', '2022-01-31', 483389),
(56, '2022-02-01', '2022-03-31', 483390),
(57, '2022-04-01', '2022-05-31', 483391),
(58, '2022-06-01', '2022-07-31', 483392),
(59, '2022-08-01', '2022-09-30', 483393);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `seasons`
--
ALTER TABLE `seasons`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
