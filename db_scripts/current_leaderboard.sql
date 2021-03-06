/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `current_leaderboard`
--

CREATE TABLE IF NOT EXISTS `current_leaderboard` (
  `rank` int(11) NOT NULL,
  `char_id` int(11) NOT NULL DEFAULT '-1',
  `wins` int(11) NOT NULL DEFAULT '0',
  `losses` int(11) NOT NULL DEFAULT '0',
  `season_wins` int(11) NOT NULL DEFAULT '0',
  `season_losses` int(11) NOT NULL DEFAULT '0',
  `season_kills` int(11) NOT NULL DEFAULT '0',
  `season_deaths` int(11) DEFAULT '0',
  `prestige` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `current_leaderboard`
--
ALTER TABLE `current_leaderboard`
 ADD PRIMARY KEY (`rank`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
