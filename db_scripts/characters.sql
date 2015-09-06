--
-- Table structure for table `characters`
--

CREATE TABLE IF NOT EXISTS `characters` (
  `id` int(11) NOT NULL COMMENT 'unique character id used in other tables',
  `name` text NOT NULL COMMENT 'character name',
  `short_name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `characters`
--

INSERT INTO `characters` (`id`, `name`, `short_name`) VALUES
(1, 'Froggy G', 'Froggy'),
(2, 'Sheriff Lonestar', 'Lonestar'),
(3, 'Voltar the Omniscient', 'Voltar'),
(4, 'Yuri', 'Yuri'),
(5, 'Leon Chameleon', 'Leon'),
(6, 'Clunk', 'Clunk'),
(7, 'Derpl Zork', 'Derpl'),
(8, 'Coco Nebulon', 'Coco'),
(9, 'Skølldir', 'Skølldir'),
(10, 'Ksenia', 'Ksenia'),
(11, 'Raelynn', 'Raelynn'),
(12, 'Gnaw', 'Gnaw'),
(13, 'Rocco', 'Rocco'),
(14, 'Ayla', 'Ayla'),
(16, 'Vinnie & Spike', 'Vinnie'),
(18, 'Genji the Pollen Prophet', 'Genji'),
(19, 'Penny Fox', 'Penny'),
(20, 'Admiral Swiggins', 'Swiggins'),
(21, 'Ted McPain', 'Ted'),
(22, 'Sentry X-58', 'Sentry'),
(23, 'Skree', 'Skree'),
(24, 'Scoop of Justice', 'Scoop'),
(25, 'Nibbs', 'Nibbs');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
