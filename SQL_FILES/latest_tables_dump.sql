-- MariaDB dump 10.19  Distrib 10.4.19-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: livestockweb
-- ------------------------------------------------------
-- Server version	10.4.19-MariaDB

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'2021-06-17 16:00:00',NULL,'Grasses'),(2,'2021-06-17 16:00:00',NULL,'Legume'),(3,'2021-06-17 16:00:00',NULL,'Agro-industrial by-products'),(4,'2021-06-17 16:00:00',NULL,'Vitamin and Pre-mineral premixes'),(5,'2021-06-17 16:00:00',NULL,'Non-conventional feed');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feeding_reco_log_ingredient`
--

DROP TABLE IF EXISTS `feeding_reco_log_ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feeding_reco_log_ingredient` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `feeding_reco_log_id` int(10) unsigned NOT NULL,
  `ingredient_id` int(10) unsigned DEFAULT NULL,
  `qty` decimal(6,2) NOT NULL,
  `total_price_at_date` decimal(7,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `feeding_reco_log_ingredient_ingredient_id_foreign` (`ingredient_id`),
  KEY `feeding_reco_log_ingredient_feeding_reco_log_id_foreign` (`feeding_reco_log_id`),
  CONSTRAINT `feeding_reco_log_ingredient_feeding_reco_log_id_foreign` FOREIGN KEY (`feeding_reco_log_id`) REFERENCES `feeding_reco_logs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `feeding_reco_log_ingredient_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feeding_reco_log_ingredient`
--

LOCK TABLES `feeding_reco_log_ingredient` WRITE;
/*!40000 ALTER TABLE `feeding_reco_log_ingredient` DISABLE KEYS */;
/*!40000 ALTER TABLE `feeding_reco_log_ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feeding_reco_logs`
--

DROP TABLE IF EXISTS `feeding_reco_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feeding_reco_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `visit_date` date NOT NULL,
  `staff_id` int(10) unsigned NOT NULL,
  `body_weight` int(11) NOT NULL,
  `stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month_pregnancy` int(11) DEFAULT NULL,
  `ave_daily_gain` int(11) NOT NULL,
  `milk_yield` decimal(5,2) NOT NULL,
  `fat_content` decimal(5,2) NOT NULL,
  `milk_price` decimal(6,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feeding_reco_logs`
--

LOCK TABLES `feeding_reco_logs` WRITE;
/*!40000 ALTER TABLE `feeding_reco_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `feeding_reco_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hc_symptoms`
--

DROP TABLE IF EXISTS `hc_symptoms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hc_symptoms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `symptom_id` int(10) unsigned NOT NULL,
  `health_condition_id` int(10) unsigned NOT NULL,
  `differential` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `hc_symptoms_symptom_id_foreign` (`symptom_id`),
  KEY `hc_symptoms_health_condition_id_foreign` (`health_condition_id`),
  CONSTRAINT `hc_symptoms_health_condition_id_foreign` FOREIGN KEY (`health_condition_id`) REFERENCES `health_conditions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hc_symptoms_symptom_id_foreign` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hc_symptoms`
--

LOCK TABLES `hc_symptoms` WRITE;
/*!40000 ALTER TABLE `hc_symptoms` DISABLE KEYS */;
INSERT INTO `hc_symptoms` VALUES (1,NULL,NULL,2,1,0),(2,NULL,NULL,3,1,1),(3,NULL,NULL,4,1,0),(4,NULL,NULL,5,1,0),(5,NULL,NULL,6,2,0),(6,NULL,NULL,7,2,1),(7,NULL,NULL,8,2,0),(8,NULL,NULL,9,2,0),(9,NULL,NULL,10,3,0),(10,NULL,NULL,11,3,1),(11,NULL,NULL,12,3,0),(12,NULL,NULL,13,3,0),(13,NULL,NULL,10,3,0),(14,NULL,NULL,11,3,1),(15,NULL,NULL,12,3,0),(16,NULL,NULL,13,3,0);
/*!40000 ALTER TABLE `hc_symptoms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `health_conditions`
--

DROP TABLE IF EXISTS `health_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `health_conditions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `local_term` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organ_system_id` int(10) unsigned DEFAULT NULL,
  `common_in_region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `how_to_diganose` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `treatment` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `advice_to_farmer` varchar(480) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preventive_measure` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quick_action` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `health_conditions_name_unique` (`name`),
  KEY `health_conditions_organ_system_id_foreign` (`organ_system_id`),
  CONSTRAINT `health_conditions_organ_system_id_foreign` FOREIGN KEY (`organ_system_id`) REFERENCES `organ_systems` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `health_conditions`
--

LOCK TABLES `health_conditions` WRITE;
/*!40000 ALTER TABLE `health_conditions` DISABLE KEYS */;
INSERT INTO `health_conditions` VALUES (1,'Brucellosis','B local',1,NULL,'Bovine brucellosis is a highly transferable bacterial disease. It is mainly caused by Brucella abortus, causing late term-abortion and infertility in cattle. The disease is also a serious zoonosis (disease shared by animals and human), causing on-and-off fever in humans. Main natural hosts are cattle, horses and humans. The bacteria is excreted in uterine discharges and in milk. Diagnosis depends on the isolation of Brucella sp. from abortion material, udder secretions or from tissues removed at post-mortem. Brucella abortus, B. melitensis and B. suis are highly pathogenic for humans.','History of abortion between 2nd and 3rd trimester (5th and 9th month) of pregnancy, orchitis/epididymitis in bulls, infertility, hygromas on leg joints, retained placenta, arthritis','No practical treatment available, cull and slaughter infected animal','Isolate suspect animal, disinfect surroundings, do not touch vaginal secretions, discard contaminated milk','Isolate new animals before introducing to the herd, regular screening test for Brucellosis',1,NULL,NULL),(2,'Metritis','Pamamaga ng matris',1,NULL,'Metritis, an inflammation of the uterus, is caused by bacterial infection, and usually is seen following calving. It occurs most commonly after calvings complicated by dystocia, retained fetal membranes, twins or stillbirths.','Foul-fetid vulvar discharge, red-brown fetid uterine discharge, history, elimination of common diseases, rectal palpation reveals enlargement and thickened doughy feel of uterine horns','Administration of prstaglandin F2-α, Intrauterine antibiotic infusion and vitamins administration','Clean drinking water must be readily available, ensure adequate mineral (calcium) and vitamin supplementation; provide dry and clean maternity environment; hygienic calving measures, avoid overconditioned cows at calving','Ensure adequate mineral (calcium) and vitamin supplementation; provide dry and clean maternity environment; hygienic calving measures, avoid overconditioned cows at calving',1,NULL,NULL),(3,'Bloat','Kabag',2,NULL,'Bloat is an overdistention of the rumen with the gases of fermentation in the form of a persistent foam mixed with the ruminal contents, called primary or frothy bloat, or in the form of free gas separated from the ingesta, called secondary or free-gas bloat.','Commonly occurs in grazing animals, rapid distension of left flank, animal not ruminating, pasty diarrhea. Distress','Remove animal from pasture area immediately, drenching/use stomach tube with anti-foaming agent such as vegetable oil, use trocar and cannula, emergency rumenotomy','Remove animal from pasture area immediately','Provide good quality roughage and concentrates, minimize grazing in legume-rich pasture areas, avoid overfeeding finely ground grains',1,NULL,NULL);
/*!40000 ALTER TABLE `health_conditions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredient_srp_year`
--

DROP TABLE IF EXISTS `ingredient_srp_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredient_srp_year` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ingredient_id` int(10) unsigned NOT NULL,
  `srp_year_id` int(10) unsigned NOT NULL,
  `price` decimal(6,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `ingredient_srp_year_ingredient_id_foreign` (`ingredient_id`),
  KEY `ingredient_srp_year_srp_year_id_foreign` (`srp_year_id`),
  CONSTRAINT `ingredient_srp_year_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ingredient_srp_year_srp_year_id_foreign` FOREIGN KEY (`srp_year_id`) REFERENCES `srp_years` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient_srp_year`
--

LOCK TABLES `ingredient_srp_year` WRITE;
/*!40000 ALTER TABLE `ingredient_srp_year` DISABLE KEYS */;
INSERT INTO `ingredient_srp_year` VALUES (1,NULL,NULL,1,1,15.00),(2,NULL,NULL,1,2,20.00),(3,NULL,NULL,2,1,25.00),(4,NULL,NULL,2,2,30.00),(5,NULL,NULL,3,1,35.00),(6,NULL,NULL,3,2,40.00),(7,NULL,NULL,4,1,45.00),(8,NULL,NULL,4,2,50.00);
/*!40000 ALTER TABLE `ingredient_srp_year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dm` decimal(5,2) NOT NULL,
  `me` decimal(5,2) NOT NULL,
  `ndf` decimal(5,2) NOT NULL,
  `tdn` decimal(5,2) NOT NULL,
  `ca` decimal(5,2) NOT NULL,
  `p` decimal(5,2) NOT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ingredients_category_id_foreign` (`category_id`),
  CONSTRAINT `ingredients_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (1,NULL,NULL,'Grass Type A',1.00,3.00,5.00,7.00,9.00,11.00,1),(2,NULL,NULL,'Grass Type B',2.00,4.00,6.00,8.00,10.00,12.00,1),(3,NULL,NULL,'Legume A',10.00,11.00,12.00,13.00,14.00,15.00,2),(4,NULL,NULL,'Legume B',16.00,17.00,18.00,19.00,20.00,21.00,2);
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interventions`
--

DROP TABLE IF EXISTS `interventions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interventions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `health_condition_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `need_license` tinyint(1) NOT NULL DEFAULT 0,
  `pregnant_applicable` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `interventions_health_condition_id_foreign` (`health_condition_id`),
  CONSTRAINT `interventions_health_condition_id_foreign` FOREIGN KEY (`health_condition_id`) REFERENCES `health_conditions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interventions`
--

LOCK TABLES `interventions` WRITE;
/*!40000 ALTER TABLE `interventions` DISABLE KEYS */;
INSERT INTO `interventions` VALUES (7,NULL,NULL,1,'Isolate suspect animal from the herd',0,1),(8,NULL,NULL,1,'Conduct screening test (Rose Bengal Test) for suspect animal/breeding animals',1,1),(9,NULL,NULL,1,'Isolate suspect animal from herd',1,1),(10,NULL,NULL,1,'Cull and slaughter infected animals',1,1),(11,NULL,NULL,2,'Provide supplementation of vitamins and minerals',0,1),(12,NULL,NULL,2,'Uterine flushing',0,0),(13,NULL,NULL,2,'Administer appropriate antibiotics (ceftiofur, oxytetracycline) at least 3 days and NSAID',1,0),(14,NULL,NULL,2,'Administration of calcium borogluconate in case of hypocalcemia',1,1),(15,NULL,NULL,2,'Uterine flushing with diluted povidone iodine',1,0),(16,NULL,NULL,2,'Intravenous fluid therapy',1,0),(17,NULL,NULL,3,'drenching/use stomach tube with anti-foaming agent such as vegetable oil, use trocar and cannula',0,1),(18,NULL,NULL,3,'drenching/use stomach tube with anti-foaming agent such as vegetable oil, use trocar and cannula',1,1);
/*!40000 ALTER TABLE `interventions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_health_conditions`
--

DROP TABLE IF EXISTS `media_health_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_health_conditions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `health_condition_id` int(10) unsigned NOT NULL,
  `path_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_health_conditions_health_condition_id_foreign` (`health_condition_id`),
  CONSTRAINT `media_health_conditions_health_condition_id_foreign` FOREIGN KEY (`health_condition_id`) REFERENCES `health_conditions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_health_conditions`
--

LOCK TABLES `media_health_conditions` WRITE;
/*!40000 ALTER TABLE `media_health_conditions` DISABLE KEYS */;
INSERT INTO `media_health_conditions` VALUES (1,1,'Brucellosis_Photo_AA','img',NULL,NULL),(2,1,'Brucellosis_Photo_BB','img',NULL,NULL),(3,3,'BloatVid','vid',NULL,NULL);
/*!40000 ALTER TABLE `media_health_conditions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_symptoms`
--

DROP TABLE IF EXISTS `media_symptoms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_symptoms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `symptom_id` int(10) unsigned NOT NULL,
  `path_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_symptoms_symptom_id_foreign` (`symptom_id`),
  CONSTRAINT `media_symptoms_symptom_id_foreign` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_symptoms`
--

LOCK TABLES `media_symptoms` WRITE;
/*!40000 ALTER TABLE `media_symptoms` DISABLE KEYS */;
INSERT INTO `media_symptoms` VALUES (1,1,'abortionphoto.png','img',NULL,NULL),(2,10,'frothyimg.jpg','img',NULL,NULL),(3,10,'frothyvid.mp4','mp4',NULL,NULL);
/*!40000 ALTER TABLE `media_symptoms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (14,'2014_10_12_000000_create_users_table',1),(15,'2014_10_12_100000_create_password_resets_table',1),(16,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(17,'2019_08_19_000000_create_failed_jobs_table',1),(18,'2019_12_14_000001_create_personal_access_tokens_table',1),(19,'2021_06_06_183603_create_sessions_table',1),(20,'2021_06_09_111956_create_organ_systems_table',1),(21,'2021_06_09_113412_create_symptoms_table',1),(22,'2021_06_09_115222_create_organ_system_symptoms_table',1),(23,'2021_06_09_134158_create_health_conditions_table',1),(24,'2021_06_09_143140_create_hc_symptoms_table',1),(25,'2021_06_09_144546_create_interventions_table',1),(30,'2021_06_10_165909_create_media_health_conditions_table',5),(31,'2021_06_10_163631_create_media_symptoms_table',6),(32,'2021_06_15_152339_create_categories_table',7),(34,'2021_06_15_164609_create_srp_years_table',9),(38,'2021_06_15_152518_create_ingredients_table',13),(39,'2021_06_15_165647_create_feeding_reco_logs_table',14),(40,'2021_06_15_170759_create_feeding_reco_log_ingredient_table',15),(41,'2021_06_15_165236_create_ingredient_srp_year_table',16);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organ_system_symptom`
--

DROP TABLE IF EXISTS `organ_system_symptom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organ_system_symptom` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_system_id` int(10) unsigned NOT NULL,
  `symptom_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `organ_system_symptom_organ_system_id_foreign` (`organ_system_id`),
  KEY `organ_system_symptom_symptom_id_foreign` (`symptom_id`),
  CONSTRAINT `organ_system_symptom_organ_system_id_foreign` FOREIGN KEY (`organ_system_id`) REFERENCES `organ_systems` (`id`) ON DELETE CASCADE,
  CONSTRAINT `organ_system_symptom_symptom_id_foreign` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organ_system_symptom`
--

LOCK TABLES `organ_system_symptom` WRITE;
/*!40000 ALTER TABLE `organ_system_symptom` DISABLE KEYS */;
INSERT INTO `organ_system_symptom` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,7),(5,2,11),(6,10,9),(7,14,4),(8,14,5),(9,14,6),(10,14,8),(11,14,9),(12,14,10),(13,14,12),(14,14,13);
/*!40000 ALTER TABLE `organ_system_symptom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organ_systems`
--

DROP TABLE IF EXISTS `organ_systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organ_systems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `local_term` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organ_systems_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organ_systems`
--

LOCK TABLES `organ_systems` WRITE;
/*!40000 ALTER TABLE `organ_systems` DISABLE KEYS */;
INSERT INTO `organ_systems` VALUES (1,'Reproductive','Reproduktibo',NULL,NULL),(2,'Gastrointestinal','Tiyan at Bituka',NULL,NULL),(3,'Respiratory','Respiratoryo',NULL,NULL),(4,'Cardiovascular/Lymphatic','Sirkulatoryo',NULL,NULL),(5,'Nervous','Nerbiyos',NULL,NULL),(6,'Musculoskeletal','Kalamnan at Buto',NULL,NULL),(7,'Excretory','Pang-alis ng mga Dumi',NULL,NULL),(8,'Integumentary','Integumentaryo',NULL,NULL),(9,'Eye','Mata',NULL,NULL),(10,'Mammary','Mamarya',NULL,NULL),(11,'Metabolic','Metaboliko / Metabolismo',NULL,NULL),(12,'Nutritional ','Pangnutrisyon',NULL,NULL),(13,'Poisons/Toxin','Lason/toksin',NULL,NULL),(14,'General','Pangkalahatan',NULL,NULL);
/*!40000 ALTER TABLE `organ_systems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `srp_years`
--

DROP TABLE IF EXISTS `srp_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `srp_years` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `srp_years`
--

LOCK TABLES `srp_years` WRITE;
/*!40000 ALTER TABLE `srp_years` DISABLE KEYS */;
INSERT INTO `srp_years` VALUES (1,'2019',NULL,NULL),(2,'2020',NULL,NULL);
/*!40000 ALTER TABLE `srp_years` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `symptoms`
--

DROP TABLE IF EXISTS `symptoms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `symptoms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `local_term` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_symptom` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symptoms_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `symptoms`
--

LOCK TABLES `symptoms` WRITE;
/*!40000 ALTER TABLE `symptoms` DISABLE KEYS */;
INSERT INTO `symptoms` VALUES (1,'Abortion','Aborsyon',0,NULL,NULL),(2,'Abortion (2nd - 3rd trimester)','Aborsyon (ikalawa hanggang ikatlong yugto ng tatlong buwan)',1,NULL,NULL),(3,'Enlargement/swelling of testicles / scrotal sac','Paglaki/pamamaga ng bayag',0,NULL,NULL),(4,'Milk drop','Pagbaba ng dami ng gatas',0,NULL,NULL),(5,'Hygroma','Paglobo ng harapang tuhod',0,NULL,NULL),(6,'Fever','Lagnat',0,NULL,NULL),(7,'Vulvar discharge (foul-fetid)','Discharge mula sa ari',0,NULL,NULL),(8,'Loss of appetite','Kawalan ng ganang kumain',0,NULL,NULL),(9,'Decrease production of milk','Pagbaba ng dami ng gatas',0,NULL,NULL),(10,'Frothy mouth','Bumubulang bibig',0,NULL,NULL),(11,'Rapid distension of left flank with variable colic','Mabilis na paglaki ng kaliwang bahagi ng tiyan na may paiba-ibang kabag',0,NULL,NULL),(12,'Recumbency','Nakahiga',0,NULL,NULL),(13,'Frothy salivation','Mabulang paglalaway',0,NULL,NULL);
/*!40000 ALTER TABLE `symptoms` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-23 18:22:58
