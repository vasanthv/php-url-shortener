
--
-- Table structure for table `shorturls`
--

CREATE TABLE IF NOT EXISTS `shorturls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shorturlid` varchar(32) NOT NULL,
  `longurl` text NOT NULL,
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
