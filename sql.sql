/*run before start app I use OpenServer/MySql*/
CREATE DATABASE `rzmsoft` /*!40100 DEFAULT CHARACTER SET utf8 */;


CREATE TABLE `rzmsoft`.`isUsed` (
  `idUsed` tinyint(1) NOT NULL AUTO_INCREMENT,
  `usedStatus` varchar(45) NOT NULL,
  PRIMARY KEY (`idUsed`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;



INSERT INTO `rzmsoft`.`isUsed`
    (`idUsed`,
	`usedStatus`)
VALUES
    (1, "is not used"),
    (2, "is used");
    
    
CREATE TABLE `rzmsoft`.`status` (
  `idstatus` int(11) NOT NULL AUTO_INCREMENT,
  `statusName` varchar(45) NOT NULL,
  UNIQUE KEY `idstatus_UNIQUE` (`idstatus`),
  UNIQUE KEY `statusName_UNIQUE` (`statusName`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


INSERT INTO `rzmsoft`.`status`
(`idstatus`,
`statusName`)
VALUES
(1, "We have not called this contact yet"),
(2, "Contact closed"),
(3, "Busy"),
(4, "No answer"),
(5, "Asked to call back"),
(6, "Refusal to talk");




CREATE TABLE `rzmsoft`.`contacts` (
  `idContact` int(11) NOT NULL AUTO_INCREMENT,
  `contactName` varchar(45) NOT NULL,
  `idStatus` int(11) DEFAULT NULL,
  `idUsed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idContact`),
  UNIQUE KEY `idContact_UNIQUE` (`idContact`),
  KEY `idStatus_idx` (`idStatus`),
  KEY `idUsed_idx` (`idUsed`),
  CONSTRAINT `idStatus` FOREIGN KEY (`idStatus`) REFERENCES `status` (`idstatus`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idUsed` FOREIGN KEY (`idUsed`) REFERENCES `isUsed` (`idUsed`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


INSERT INTO `rzmsoft`.`contacts`
(`idContact`, `contactName`, `idStatus`, `idUsed`)
VALUES
(1, 'Yogesh singh', 1, 1),
(2,  'Sonarika', 2, 1),
(3,  'Vishal Sahu', 3, 1),
(4, 'Mayank', 4, 1),
(5, 'Vijay', 5, 1),
(6,  'Jiten singh', 6, 1);







