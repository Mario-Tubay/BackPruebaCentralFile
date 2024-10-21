-- MySQL dump 10.13  Distrib 8.0.34, for macos13 (x86_64)
--
-- Host: 127.0.0.1    Database: app_prueba
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.21-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre1` varchar(255) DEFAULT NULL,
  `nombre2` varchar(255) DEFAULT NULL,
  `apellido1` varchar(255) DEFAULT NULL,
  `apellido2` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `celular` varchar(13) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(2) DEFAULT 1,
  `foto` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Mario','alberto','Tubay','Suarez','mario@mail.com','123456','123456','$2y$12$Etat81oY6nFh87jjbdQFC.94qmYCoylgsBGl5SqHz9S4KykI6Grx6','2024-10-20 06:25:31','2024-10-21 10:27:11',1,'storage/1729488431 - photo.jpg'),(3,'Mario','Alberto','Tubay','Suarez','mario1@mail.com',NULL,NULL,'$2y$12$Etat81oY6nFh87jjbdQFC.94qmYCoylgsBGl5SqHz9S4KykI6Grx6','2024-10-20 11:26:37','2024-10-20 11:26:37',1,NULL),(4,'Mario','Alberto','Tubay','Suarez','mario2@mail.com','11111','11111','$2y$12$nbI2I9oR3DzW.dnlNN22o.ZWk99o6SughPIIxjdQ17QkHnNYVNRC6','2024-10-20 11:47:17','2024-10-20 11:47:17',1,NULL),(5,'1111','1111','111','111','mario11@mail.com','1111','11111','$2y$12$SHmGymd1ASdebi/rXjHTbOwwKOOMxxAekDwzWvkD4umqh1fEd8Mdi','2024-10-20 11:48:53','2024-10-20 11:48:53',1,NULL),(6,'Alberto','Mario','Suarez','Tubay','Alberto@mail.com','1111','11111','$2y$12$GbVJI4ZbVgUsN.TBaxErLuJ9gGiDzdzuNuVHetct3zdm17E9LFi6O','2024-10-21 06:06:56','2024-10-21 06:06:56',1,'storage/1729490815 - photo.jpg');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-21  1:20:09
