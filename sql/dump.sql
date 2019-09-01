CREATE TABLE `currency_rate` (
  `date` date NOT NULL,
  `code` char(3) NOT NULL,
  `amount` float NOT NULL,
  `rate` float NOT NULL,
  PRIMARY KEY (`date`,`code`)
) ENGINE=InnoDB;