-- MySQL dump 10.13  Distrib 5.6.11, for osx10.7 (i386)
--
-- Host: localhost    Database: FRAUD
-- ------------------------------------------------------
-- Server version	5.6.11-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `FRAUD`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `FRAUD` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `FRAUD`;

--
-- Table structure for table `OWN_ACCESS_FRAUD`
--

DROP TABLE IF EXISTS `OWN_ACCESS_FRAUD`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OWN_ACCESS_FRAUD` (
  `DATE` date NOT NULL DEFAULT '0000-00-00',
  `PROFILE_ID` int(11) DEFAULT NULL,
  `TECK_ID` int(11) DEFAULT NULL,
  `TECK_TITLE` text,
  `TECK_TOTAL_VIEWS` int(11) DEFAULT NULL,
  `TECKS_OWN_VIEWS` int(11) DEFAULT NULL,
  `TECKS_DISCOUNTED_VIEWS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OWN_ACCESS_FRAUD`
--

LOCK TABLES `OWN_ACCESS_FRAUD` WRITE;
/*!40000 ALTER TABLE `OWN_ACCESS_FRAUD` DISABLE KEYS */;
INSERT INTO `OWN_ACCESS_FRAUD` VALUES ('2013-11-07',16983,167952,'Spook Hill Mystery',1589,12,1577),('2013-11-07',16983,168371,'You Canâ€™t Please Everyone',1071,12,1059),('2013-11-07',16983,168380,'Old Dirt Road : Fishing Tale',1407,12,1395),('2013-11-07',23645,165506,'Saint Brigid',450,15,435),('2013-11-07',23645,146681,'Abandoned',988,12,976),('2013-11-07',23645,164102,'Kinsale Street',543,12,531),('2013-11-07',12081,162631,'Halloween - TÃ” FORA!!!',180,12,168),('2013-11-07',22903,168016,'Cantora',47,33,14),('2013-11-07',22903,168390,'Dentista',28,24,4),('2013-11-07',22903,167979,'Cabelos ao vento',24,24,0),('2013-11-07',22903,168547,'colete',124,18,106),('2013-11-07',22903,168552,'Afro',125,15,110),('2013-11-07',22903,167957,'Baile',29,15,14),('2013-11-07',22903,167811,'DoenÃ§a de Down',27,12,15),('2013-11-07',22903,167664,'Pixaim',26,12,14),('2013-11-07',24433,168494,'June Lake, Eastern Sierra Nevada Mountains',236,12,224),('2013-11-07',17289,168474,'What Is Storify.com? ',112,18,94),('2013-11-07',18747,168464,'Carro de F1 miniatura criado com peÃ§as de HD',66038,12,66026),('2013-11-07',18747,168463,'Volkswagen Up!',64856,12,64844),('2013-11-07',18747,168460,'Fiat Viaggio',69333,12,69321),('2013-11-07',24749,168265,'Losing My Mind',11,15,-4),('2013-11-07',24749,168486,'Judging a Fish by Its Cover',27,15,12),('2013-11-07',24749,168324,'Ending Boredom',27,12,15),('2013-11-07',23333,168437,'Need money :(',68,18,50),('2013-11-07',24473,167469,'Os 6 lugares mais misteriosos do mundo',4534,12,4522),('2013-11-07',24045,168217,'When Celebrities Try to Save Face',113,15,98),('2013-11-07',24284,168159,'Get paid to write recipes at My Recipe Magic: Oct 2013 payment prove',69,12,57),('2013-11-07',16296,154616,'Mac OS X Mavericks disponÃ­vel de graÃ§a para download',10713,12,10701),('2013-11-07',6499,168216,'Medo Hereditario',29,12,17),('2013-11-07',25297,168061,'THIS is Beautiful?!',101,12,89),('2013-11-08',24722,168153,'Sinceridade',64,11,53),('2013-11-08',22903,168644,'Principe',272,16,256),('2013-11-08',22903,169156,'Desfile',19,12,7),('2013-11-08',22903,168552,'Afro',125,12,113),('2013-11-08',17289,168474,'What Is Storify.com? ',112,16,96),('2013-11-08',24284,168747,'Recent audit may have increased our Teckler earnings',134,20,114),('2013-11-08',24284,168757,'Chinese Braised Pork with Preserved Plum Recipe',45,17,28),('2013-11-08',24284,168074,'Creative Chinese Cuisine: Jammy Shiitake Mushroom Recipe',22,12,10),('2013-11-08',25401,168700,'5$ De desconto na HealthDesigns',104430,18,104412),('2013-11-08',54,169142,'Pies de Gato',42,20,22),('2013-11-08',24045,168217,'When Celebrities Try to Save Face',113,11,102),('2013-11-08',10363,134569,'O chamado.',270,20,250),('2013-11-08',25090,168708,'MÃºsica de verdade',51,13,38),('2013-11-08',24300,168476,'Quick, Easy Holiday Appetizer Idea #2',51,11,40),('2013-11-08',11699,169018,'Mr. Bean Andando de bicicleta peladÃ£o ... ',16,12,4),('2013-11-08',25405,168739,'Cat Loves to Swim In Tub',29090,11,29079),('2013-11-09',22243,168755,'Journey towards meeting God',112,20,92),('2013-11-09',22243,170819,'Loving you - a small poem ',94,18,76),('2013-11-09',54,169142,'Pies de Gato',42,32,10),('2013-11-09',25068,169358,'Parvo Virus Survivor (dog)',105,14,91),('2013-11-09',25428,169327,'What are the Health Benefits of Zinc? Where can Zinc be Found?',89,14,75),('2013-11-09',25454,171448,'The Light in the Window',101,14,87),('2013-11-10',24722,171821,'Belfort Vs. Henderson',74,14,60),('2013-11-10',24722,170847,'Sonhos e Conquistas',103,12,91),('2013-11-10',24722,168907,'Ponte Preta x VÃ©lez Sarsfield',102,12,90),('2013-11-10',25454,171811,'War on Thanksgiving Day',114,20,94),('2013-11-11',23645,151877,'Wet Leaf',841,12,829),('2013-11-11',23645,151883,'Rain Drops on a Spider Net',985,12,973),('2013-11-11',23645,151900,'Rainy Day',868,12,856),('2013-11-11',23645,151912,'Roofs',833,14,819),('2013-11-11',23645,151925,'Rust',821,16,805),('2013-11-11',23645,151947,'Universe Background',842,16,826),('2013-11-11',23645,151988,'No Parking',844,16,828),('2013-11-11',23645,152004,'The Hanging Pig',923,16,907),('2013-11-11',23645,152016,'Wild Horse',880,16,864),('2013-11-11',23645,152020,'Ghosts',912,16,896),('2013-11-11',23645,152024,'Pink Blossom',869,16,853),('2013-11-11',23645,152714,'Castle',871,16,855),('2013-11-11',23645,152719,'Blooming Stone',834,16,818),('2013-11-11',23645,152722,'Shapes',869,16,853),('2013-11-11',23645,152723,'Sugastars',1034,14,1020),('2013-11-11',23645,152724,'Sugastar',866,16,850),('2013-11-11',23645,164102,'Kinsale Street',543,16,527),('2013-11-11',23645,165498,'Spike Island',500,16,484),('2013-11-11',23645,165502,'Monastery Window',499,16,483),('2013-11-11',23645,165506,'Saint Brigid',450,16,434),('2013-11-11',24433,172021,'Cat Meets Tarantula',88,14,74),('2013-11-11',24433,172024,'Pigeon Sitting in a Plate of Birdseed',109,12,97),('2013-11-11',22903,172425,'Camisa estampada',40,18,22),('2013-11-11',22903,172514,'Vestido com cinto',32,14,18),('2013-11-11',22903,172536,'natal',17,12,5),('2013-11-11',25454,172221,'Thank a Veteran',72,18,54),('2013-11-11',25454,171811,'War on Thanksgiving Day',114,12,102),('2013-11-11',25454,172542,'Twister in McComb',97,12,85),('2013-11-11',23742,171745,'Is Typhoon Yolanda a Result of The USA Manmade Weather Modification Technique?',2858,14,2844),('2013-11-11',23690,172226,'                         \"O OUTRO LADO DO GOL: TRISTEZA\"',31,12,19),('2013-11-11',21018,172155,'ACESSO AO PROCESSO DE JULGAMENTO HOJE TELEXFREE 11/11/2013 COMPARTILHEM!!',1299,16,1283),('2013-11-12',23742,171745,'Is Typhoon Yolanda a Result of The USA Manmade Weather Modification Technique?',2858,13,2845),('2013-11-18',25409,177234,'Curso de InglÃªs gratis online - Aula 19',211,84,127),('2013-11-18',25409,177217,'Curso de InglÃªs gratis online - Aula 08',199,87,112),('2013-11-18',25409,177233,'Curso de InglÃªs gratis online - Aula 18',194,88,106),('2013-11-18',25409,177216,'Curso de InglÃªs gratis online - Aula 07',202,90,112),('2013-11-18',25409,177224,'Curso de InglÃªs gratis online - Aula 12',208,90,118),('2013-11-18',25409,177227,'Curso de InglÃªs gratis online - Aula 14',201,90,111),('2013-11-18',25409,177211,'Curso de InglÃªs gratis online - Aula 04',204,91,113),('2013-11-18',25409,177219,'Curso de InglÃªs gratis online - Aula 10',215,92,123),('2013-11-18',25409,177221,'Curso de InglÃªs gratis online - Aula 11',208,92,116),('2013-11-18',25409,177230,'Curso de InglÃªs gratis online - Aula 16',207,92,115),('2013-11-18',25409,177214,'Curso de InglÃªs gratis online - Aula 05',204,93,111),('2013-11-18',25409,177215,'Curso de InglÃªs gratis online - Aula 06',218,94,124),('2013-11-18',25409,177232,'Curso de InglÃªs gratis online - Aula 17',214,94,120),('2013-11-18',25409,177235,'Curso de InglÃªs gratis online - Aula 20',244,95,149),('2013-11-18',25409,177225,'Curso de InglÃªs gratis online - Aula 13',210,96,114),('2013-11-18',25409,177206,'Curso de InglÃªs gratis online - Aula 02',205,97,108),('2013-11-18',25409,177208,'Curso de InglÃªs gratis online - Aula 03',206,97,109),('2013-11-18',25409,177228,'Curso de InglÃªs gratis online - Aula 15',209,98,111),('2013-11-18',25409,168914,'Curso de InglÃªs gratis online - Aula 01',220,99,121),('2013-11-18',25409,177218,'Curso de InglÃªs gratis online - Aula 09',213,103,110);
/*!40000 ALTER TABLE `OWN_ACCESS_FRAUD` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-11-19 15:51:49
