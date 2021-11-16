CREATE TABLE `photos` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `file` varchar(250) NOT NULL,
 `datetime` varchar(250) NOT NULL,
 `latitude` varchar(250) NOT NULL,
 `longitude` varchar(250) NOT NULL,
 `altitude` varchar(250) DEFAULT NULL,
 `description` varchar(250) DEFAULT NULL,
 `username` varchar(250) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=latin1;