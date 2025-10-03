-- MySQL dump 10.13  Distrib 8.0.30, for Linux (x86_64)
--
-- Host: localhost    Database: traffic
-- ------------------------------------------------------
-- Server version	8.0.30-0ubuntu0.20.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_menu`
--

DROP TABLE IF EXISTS `admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `css` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,'Настройки','admin-menu','icon-menu-settings'),(2,0,'Администрация','users','icon-menu-users'),(3,0,'Страницы','content','icon-menu-tasks'),(4,1,'Админ меню','admin-menu','icon-tables'),(5,2,'Пользователи','users','icon-tables'),(6,2,'Группы пользователей','user-groups','icon-tables'),(7,3,'Меню','menu','icon-tables'),(8,3,'Контент','content','icon-tables'),(9,3,'Статичный контент','static-content','icon-tables'),(10,1,'Пагинация','pagination','icon-tables'),(11,1,'Права доступа','permissions','icon-tables'),(12,3,'Тип меню','menu-categories','icon-tables'),(13,0,'Модули','modules-list','icon-menu-tasks'),(14,0,'Партнеры','backoffice/backend-partners','icon-menu-tasks'),(15,14,'Структура','backoffice/backend-partners','icon-menu-tasks'),(16,13,'Структура - Test','test/backend-test','icon-tables'),(17,3,'Отдельные страницы','pages/backend-pages','icon-tables'),(18,0,'Рассылка','mailing/backend-mailing','icon-menu-tasks'),(19,0,'SEO','seo/backend-seo','icon-menu-tasks'),(21,3,'Новости','news/backend-news','icon-tables'),(22,14,'ТОП лидеров','backoffice/backend-partners/top-leaders','icon-tables'),(23,3,'F.A.Q','faq/backend-faq','icon-tables'),(24,14,'Вывод денег','structure/backend-withdrawal','icon-tables'),(25,14,'Тикеты','tickets/backend-tickets','icon-menu-tasks'),(27,3,'Баннеры','advertisement/backend-banners','icon-menu-tasks'),(28,19,'Счетчики','seo/backend-seo/counters','icon-menu-tasks'),(29,0,'Бухгалтерия','accounting/backend-accounting','icon-menu-tasks'),(30,29,'Оплаченные партнеры','accounting/backend-accounting/paid-partners','icon-menu-tasks'),(31,29,'Выплаты','accounting/backend-accounting/earned-partners','icon-menu-tasks'),(33,3,'Слайдер','slider','icon-menu-tasks'),(34,3,'Лендинги','landings/backend-landings','icon-menu-tasks'),(35,14,'Отзывы','feedback/backend-feedback','icon-menu-tasks'),(36,14,'Жетоны','backoffice/backend-gold-token','icon-menu-tasks'),(37,14,'Жетоны - настройки','structure/backend-gold-token-settings','icon-menu-tasks'),(38,29,'Ошибки платежей','structure/backend-payments-faul','icon-menu-tasks'),(39,29,'Платежи','structure/backend-payments-invoices','icon-menu-tasks'),(42,14,'Одинаковые кошельки','backoffice/backend-partners/compare-wallets','icon-menu-tasks'),(43,1,'Платежи - настройки','payments-settings','icon-menu-tasks'),(44,29,'Выплаты за матрицы','backoffice/backend-partners/matrix-payments-list','icon-tables'),(45,29,'Выплаты за личные','backoffice/backend-partners/invite-payoff-list','icon-tables'),(46,29,'Выплаты админу за матрицы','backoffice/backend-partners/admin-payoff-list','icon-tables'),(47,29,'Список payeer операций','payeer-payments-list','icon-tables'),(48,29,'Авто платежи','auto-pay-off-logs-list','icon-tables'),(49,29,'Баллы','balls','icon-tables');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_pay_off`
--

DROP TABLE IF EXISTS `admin_pay_off`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_pay_off` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  CONSTRAINT `admin_pay_off_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_pay_off`
--

LOCK TABLES `admin_pay_off` WRITE;
/*!40000 ALTER TABLE `admin_pay_off` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_pay_off` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adverts_views`
--

DROP TABLE IF EXISTS `adverts_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adverts_views` (
  `id` int NOT NULL AUTO_INCREMENT,
  `advert_id` int NOT NULL,
  `partner_id` int NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `type` tinyint NOT NULL,
  `balls` int NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advert_id` (`advert_id`),
  KEY `partner_id` (`partner_id`),
  CONSTRAINT `adverts_views_text_advert_id` FOREIGN KEY (`advert_id`) REFERENCES `text_advert` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `adverts_views_text_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adverts_views`
--

LOCK TABLES `adverts_views` WRITE;
/*!40000 ALTER TABLE `adverts_views` DISABLE KEYS */;
/*!40000 ALTER TABLE `adverts_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auto_pay_off_logs`
--

DROP TABLE IF EXISTS `auto_pay_off_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auto_pay_off_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `type` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_off` tinyint NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `partner_id` (`partner_id`),
  CONSTRAINT `auto_pay_off_logs_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auto_pay_off_logs`
--

LOCK TABLES `auto_pay_off_logs` WRITE;
/*!40000 ALTER TABLE `auto_pay_off_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `auto_pay_off_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `balls`
--

DROP TABLE IF EXISTS `balls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `balls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `referral_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `type` tinyint NOT NULL,
  `type_balls` tinyint NOT NULL,
  `balls` decimal(10,0) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `balls_partner_id` (`partner_id`),
  KEY `balls_referral_id` (`referral_id`),
  CONSTRAINT `balls_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `balls_referral_id` FOREIGN KEY (`referral_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `balls`
--

LOCK TABLES `balls` WRITE;
/*!40000 ALTER TABLE `balls` DISABLE KEYS */;
/*!40000 ALTER TABLE `balls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `binar_matrices`
--

DROP TABLE IF EXISTS `binar_matrices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `binar_matrices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `parent_sponsor_id` int NOT NULL,
  `partner_id` int NOT NULL,
  `demo` tinyint NOT NULL,
  `clone` tinyint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `binar_matrices_partner_id` (`partner_id`),
  CONSTRAINT `binar_matrices_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `binar_matrices`
--

LOCK TABLES `binar_matrices` WRITE;
/*!40000 ALTER TABLE `binar_matrices` DISABLE KEYS */;
/*!40000 ALTER TABLE `binar_matrices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `content` (
  `id` int NOT NULL AUTO_INCREMENT,
  `controller_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `style` varchar(100) NOT NULL DEFAULT '',
  `meta_title` varchar(100) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `controller_id` (`controller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (5,7,'О проекте','<div class=\"container\">\r\n	\r\n	<div class=\"row\">\r\n		\r\n		<div class=\"col-md-12\">\r\n			\r\n			\r\n			<div class=\"brief text-left\">\r\n				\r\n				\r\n				<h2>О проекте</h2>\r\n				<div class=\"colored-line pull-left\">\r\n				</div>\r\n				\r\n				\r\n				<p>\r\n					«Тraffic – profit» - стремительный поток денег в ваш кошелёк.\r\n				</p>\r\n				\r\n				\r\n				<ul class=\"feature-list-2\"><li>\r\n					\r\n					<div class=\"icon-container pull-left\">\r\n						<i class=\"fal fa-ad\"></i>\r\n						<span class=\"icon_cog\"></span>\r\n					</div>\r\n					\r\n					<div class=\"details pull-left\">\r\n						<h6>Заработок</h6>\r\n						<p>\r\n							Стабильный доход от $331 по «револьверной» системе. Зарабатываем, помогая друг другу. Все средства уходят в партнёрскую структуру, минуя админа (в его функции входит только поддержание работы сайта за свой счёт). \r\n						</p>\r\n						<p>\r\n							Система выплат и распределения полностью автоматизирована.  Выплачиваются вознаграждения за каждого лично приглашённого партнёра. Работает система «переливов». Автоматические выплаты производятся на Ваш Рayeer – кошелёк.\r\n						</p>\r\n					</div>\r\n					</li>\r\n					\r\n					\r\n					<li>\r\n					\r\n					<div class=\"icon-container pull-left\">\r\n						<span class=\"icon_cog\"></span>\r\n					</div>\r\n					\r\n					<div class=\"details pull-left\">\r\n						<h6>Реклама</h6>\r\n						<p>\r\n							В проекте реализована бальная система, то есть, помимо того, что вы зарабатываете в программе, вам начисляются баллы, соответственно заработанным деньгам, которые могут в дальнейшем использоваться для рекламы ваших проектов и бизнесов на рекламных ресурсах компании «Тraffic – profit».\r\n						</p>\r\n					</div>\r\n					</li>\r\n				</ul></div>\r\n		</div>\r\n		\r\n	</div> \r\n	\r\n</div>','section2 bgcolor-2','О проекте','О проекте','О проекте',1,1555240459,1558514696),(6,7,'Рекламные услуги','<div class=\"container\">\r\n	\r\n	<!-- SECTION HEADING -->\r\n	\r\n	<h2>Рекламные услуги</h2>\r\n	\r\n	<div class=\"colored-line\">\r\n	</div>\r\n	\r\n	<div class=\"sub-heading\">\r\n		Компания «Traffic profit», являясь рекламным агентством, предоставляет своим партнёрам следующие виды рекламных услуг:\r\n	</div>\r\n	\r\n	<div class=\"features\">\r\n		\r\n		<!-- FEATURES ROW 1 -->\r\n		<div class=\"row\">\r\n			\r\n			<!-- SINGLE FEATURE BOX -->\r\n			<div class=\"col-md-4\">\r\n				<div class=\"feature\">\r\n					<div class=\"icon\">\r\n						<span class=\"icon_compass_alt\"></span>\r\n					</div>\r\n					<h4>Доска объявлений</h4>\r\n					<p>\r\n						Это тип рекламной площадки, который наиболее распространён в сети. На нашей доске пользователи могут давать самые различные объявления рекламного характера, от поиска работы до предложения собственных услуг и товаров по всему СНГ и зарубежью. Удобный и простой интерфейс нашей доски объявлений позволяет успешно рекламировать партнёрам проекта свои услуги, товары и проекты. С помощью нашей доски объявлений вы можете быстро найти свою целевую аудиторию, найти себе партнёров, успешно реализовать свои товары и услуги.\r\n					</p>\r\n				</div>\r\n			</div>\r\n			\r\n			<!-- SINGLE FEATURE BOX -->\r\n			<div class=\"col-md-4\">\r\n				<div class=\"feature\">\r\n					<div class=\"icon\">\r\n						<span class=\"icon_map_alt\"></span>\r\n					</div>\r\n					<h4>Баннеры</h4>\r\n					<p>\r\n						Баннеры, как и доски объявлений, также довольно популярны в интернете. Немаловажным достоинством размещения вашей баннерной рекламы на сайте проекта является её демократичная стоимость. Наши специалисты знают, как сделать так, чтобы ваш рекламный баннер увидели как можно больше целевых посетителей.\r\n					</p>\r\n				</div>\r\n			</div>\r\n			\r\n			<!-- SINGLE FEATURE BOX -->\r\n			<div class=\"col-md-4\">\r\n				<div class=\"feature\">\r\n					<div class=\"icon\">\r\n						<span class=\"icon_gift_alt\"></span>\r\n					</div>\r\n					<h4>Буксы</h4>\r\n					<p>\r\n						Размещение рекламы на буксах – наиболее простой и быстрый способ раскрутки своего сайта, определённого проекта или группы из социальных сетей. Здесь можно эффективно и недорого рекламировать свои партнерские ссылки для набора рефералов в различные проекты.\r\n						С учётом того, что на буксах уже есть своя целевая аудитория пользователей, которые направляют свои действия на заработок в интернете, то сами буксы являются отличным инструментом для раскрутки личных блогов, сайтов и других проектов, посвященных заработку в интернете, а также для набора рефералов! Именно эти задачи и решает букс проекта.\r\n					</p>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n	\r\n</div> <!-- /END CONTAINER -->','section3','Рекламные услуги','Рекламные услуги','Рекламные услуги',0,1555240579,1558427983),(7,7,'Заработок','<div class=\"container\">\r\n	<div class=\"row\">\r\n		\r\n		<div class=\"col-md-12\">\r\n			\r\n			<!-- DETAILS WITH LIST -->\r\n			<div class=\"brief text-left\">\r\n				\r\n				<!-- HEADING -->\r\n				<h2>Заработок</h2>\r\n				<div class=\"colored-line pull-left\">\r\n				</div>\r\n				\r\n				<!-- TEXT -->\r\n				<p>\r\n					Участвуя в партнёрских программах компании, все зарегистрированные партнёры имеют возможность создать себе дополнительные источники дохода. Компания предлагает два вида дохода.\r\n				</p>\r\n				\r\n				<!-- FEATURE LIST -->\r\n				<ul class=\"feature-list-2\">\r\n					\r\n					<!-- FEATURE -->\r\n					<li>\r\n					<!-- ICON -->\r\n					<div class=\"icon-container pull-left\">\r\n						<span class=\"icon_cog\"></span>\r\n					</div>\r\n					<!-- DETAILS -->\r\n					<div class=\"details pull-left\">\r\n						<h6>Активный</h6>\r\n						<p>\r\n							Буксы, клики, просмотр баннеров за оплату. Работа даже для новичков не представляет особой сложности. По каждому из видов заработка, который выберет партнёр, существует подробное разъяснение и пошаговый алгоритм действий. Например, на буксах получают деньги за просмотр объявлений, так же, как и за баннерную рекламу. В дальнейшем виды оплачиваемой работы будут добавляться.\r\n						</p>\r\n					</div>\r\n					</li>\r\n					\r\n					<!-- FEATURE -->\r\n					<li>\r\n					<!-- ICON -->\r\n					<div class=\"icon-container pull-left\">\r\n						<span class=\"icon_cart_alt\"></span>\r\n					</div>\r\n					<!-- DETAILS -->\r\n					<div class=\"details pull-left\">\r\n						<h6>Пассивный</h6>\r\n						<p>\r\n							За участие в реферальной системе проекта, то есть приглашая людей в программу и получая вознаграждение согласно маркетинговому плану. Выплаты производятся автоматически один раз в сутки на Payeer кошелек. Более подробно ознакомиться возможностями создания активного и пассивного дохода, с маркетингом, планом вознаграждений можно в личном кабинете пользователя после регистрации на сайте проекта.\r\n							Следует заметить, что реферальная программа не обещает сверхдоходов, ограничиваясь средними ежемесячными выплатами от 300 до 3000$.\r\n						</p>\r\n					</div>\r\n					</li>\r\n				</ul>\r\n			</div>\r\n		</div> <!-- /END DETAILS WITH LIST -->\r\n		\r\n	</div> <!-- END ROW -->\r\n	\r\n</div> <!-- END CONTAINER -->','section4 bgcolor-2','Заработок','Заработок','Заработок',0,1555240631,1558427985);
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_admin_pay_off`
--

DROP TABLE IF EXISTS `demo_admin_pay_off`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_admin_pay_off` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  CONSTRAINT `demo_admin_pay_off_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_admin_pay_off`
--

LOCK TABLES `demo_admin_pay_off` WRITE;
/*!40000 ALTER TABLE `demo_admin_pay_off` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_admin_pay_off` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_balls`
--

DROP TABLE IF EXISTS `demo_balls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_balls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `referral_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `type` tinyint NOT NULL,
  `type_balls` tinyint NOT NULL,
  `balls` decimal(10,0) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `balls_partner_id` (`partner_id`),
  KEY `balls_referral_id` (`referral_id`),
  CONSTRAINT `demo_balls_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `demo_balls_referral_id` FOREIGN KEY (`referral_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_balls`
--

LOCK TABLES `demo_balls` WRITE;
/*!40000 ALTER TABLE `demo_balls` DISABLE KEYS */;
INSERT INTO `demo_balls` VALUES (1,2,2,1,1,2,2,1,900,1660925693);
/*!40000 ALTER TABLE `demo_balls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_invite_pay_off`
--

DROP TABLE IF EXISTS `demo_invite_pay_off`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_invite_pay_off` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `benefit_partner_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_off` tinyint NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `payer_partner_id` (`benefit_partner_id`),
  CONSTRAINT `demo_invite_pay_off_benefit_partner_id` FOREIGN KEY (`benefit_partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `demo_invite_pay_off_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_invite_pay_off`
--

LOCK TABLES `demo_invite_pay_off` WRITE;
/*!40000 ALTER TABLE `demo_invite_pay_off` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_invite_pay_off` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_levels_payment`
--

DROP TABLE IF EXISTS `demo_levels_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_levels_payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL,
  `partner_id` int NOT NULL,
  `refferal_id` int NOT NULL,
  `level` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `partner_id` (`partner_id`),
  KEY `refferal_id` (`refferal_id`),
  CONSTRAINT `demo_levels_payment_matrix_id` FOREIGN KEY (`matrix_id`) REFERENCES `demo_matrix_1_1` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `demo_levels_payment_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `demo_levels_payment_refferal_id` FOREIGN KEY (`refferal_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_levels_payment`
--

LOCK TABLES `demo_levels_payment` WRITE;
/*!40000 ALTER TABLE `demo_levels_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_levels_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_matrices_settings`
--

DROP TABLE IF EXISTS `demo_matrices_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_matrices_settings` (
  `number` tinyint NOT NULL,
  `type` tinyint NOT NULL,
  `wide` tinyint NOT NULL,
  `levels` tinyint NOT NULL,
  `clone` tinyint NOT NULL,
  `clone_cycle` tinyint NOT NULL,
  `pay` decimal(10,2) NOT NULL,
  `admin_pay_off` decimal(10,2) NOT NULL,
  `pay_off` decimal(10,2) NOT NULL,
  `clone_pay` decimal(10,2) NOT NULL,
  `clone_admin_pay_off` decimal(10,2) NOT NULL,
  `clone_pay_off` decimal(10,2) NOT NULL,
  `invite_pay_off` tinyint NOT NULL,
  `invite_sponsor_activate` tinyint NOT NULL,
  `close_matrix_balls` int NOT NULL,
  `referral_matrix_balls` int NOT NULL,
  `account_type` tinyint NOT NULL,
  `close_matrix` tinyint NOT NULL,
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_matrices_settings`
--

LOCK TABLES `demo_matrices_settings` WRITE;
/*!40000 ALTER TABLE `demo_matrices_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_matrices_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_matrix_1_1`
--

DROP TABLE IF EXISTS `demo_matrix_1_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_matrix_1_1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL DEFAULT '0',
  `clone_matrix_id` int NOT NULL,
  `partner_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `left_key` int NOT NULL DEFAULT '0',
  `right_key` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '0',
  `level_1` tinyint NOT NULL DEFAULT '0',
  `level_2` tinyint NOT NULL DEFAULT '0',
  `clone` tinyint NOT NULL DEFAULT '0',
  `change` tinyint NOT NULL,
  `open_date` int NOT NULL DEFAULT '0',
  `close_date` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `demo_matrix_1_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_matrix_1_1`
--

LOCK TABLES `demo_matrix_1_1` WRITE;
/*!40000 ALTER TABLE `demo_matrix_1_1` DISABLE KEYS */;
INSERT INTO `demo_matrix_1_1` VALUES (1,0,0,1,0,1,4,1,1,0,0,0,1462217039,0),(2,1,0,2,1,2,3,2,0,0,0,0,1660925693,0);
/*!40000 ALTER TABLE `demo_matrix_1_1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_matrix_1_2`
--

DROP TABLE IF EXISTS `demo_matrix_1_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_matrix_1_2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL DEFAULT '0',
  `clone_matrix_id` int NOT NULL DEFAULT '0',
  `partner_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `left_key` int NOT NULL DEFAULT '0',
  `right_key` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '0',
  `level_1` tinyint NOT NULL DEFAULT '0',
  `level_2` tinyint NOT NULL DEFAULT '0',
  `clone` tinyint NOT NULL DEFAULT '0',
  `change` tinyint NOT NULL DEFAULT '0',
  `open_date` int NOT NULL DEFAULT '0',
  `close_date` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_matrix_1_2`
--

LOCK TABLES `demo_matrix_1_2` WRITE;
/*!40000 ALTER TABLE `demo_matrix_1_2` DISABLE KEYS */;
INSERT INTO `demo_matrix_1_2` VALUES (1,0,0,1,0,1,2,1,0,0,0,0,1462217039,0);
/*!40000 ALTER TABLE `demo_matrix_1_2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_matrix_1_3`
--

DROP TABLE IF EXISTS `demo_matrix_1_3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_matrix_1_3` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL DEFAULT '0',
  `clone_matrix_id` int NOT NULL DEFAULT '0',
  `partner_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `left_key` int NOT NULL DEFAULT '0',
  `right_key` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '0',
  `level_1` tinyint NOT NULL DEFAULT '0',
  `level_2` tinyint NOT NULL DEFAULT '0',
  `clone` tinyint NOT NULL DEFAULT '0',
  `change` tinyint NOT NULL DEFAULT '0',
  `open_date` int NOT NULL DEFAULT '0',
  `close_date` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_matrix_1_3`
--

LOCK TABLES `demo_matrix_1_3` WRITE;
/*!40000 ALTER TABLE `demo_matrix_1_3` DISABLE KEYS */;
INSERT INTO `demo_matrix_1_3` VALUES (1,0,0,1,0,1,2,1,0,0,0,0,1462217039,0);
/*!40000 ALTER TABLE `demo_matrix_1_3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_matrix_1_4`
--

DROP TABLE IF EXISTS `demo_matrix_1_4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_matrix_1_4` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL DEFAULT '0',
  `clone_matrix_id` int NOT NULL DEFAULT '0',
  `partner_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `left_key` int NOT NULL DEFAULT '0',
  `right_key` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '0',
  `level_1` tinyint NOT NULL DEFAULT '0',
  `level_2` tinyint NOT NULL DEFAULT '0',
  `clone` tinyint NOT NULL DEFAULT '0',
  `change` tinyint NOT NULL DEFAULT '0',
  `open_date` int NOT NULL DEFAULT '0',
  `close_date` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_matrix_1_4`
--

LOCK TABLES `demo_matrix_1_4` WRITE;
/*!40000 ALTER TABLE `demo_matrix_1_4` DISABLE KEYS */;
INSERT INTO `demo_matrix_1_4` VALUES (1,0,0,1,0,1,2,1,0,0,0,0,1462217039,0);
/*!40000 ALTER TABLE `demo_matrix_1_4` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_matrix_payments`
--

DROP TABLE IF EXISTS `demo_matrix_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_matrix_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `structure_number` tinyint NOT NULL,
  `partner_id` int NOT NULL,
  `payer_partner_id` int NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `type` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_off` tinyint NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `matrix_payments_partner_id` (`partner_id`),
  KEY `payer_partner_id` (`payer_partner_id`),
  CONSTRAINT `demo_matrix_payments_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `demo_matrix_payments_payer_partner_id` FOREIGN KEY (`payer_partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_matrix_payments`
--

LOCK TABLES `demo_matrix_payments` WRITE;
/*!40000 ALTER TABLE `demo_matrix_payments` DISABLE KEYS */;
INSERT INTO `demo_matrix_payments` VALUES (1,1,2,2,1,2,1,9.00,0,1660925693);
/*!40000 ALTER TABLE `demo_matrix_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_payments`
--

DROP TABLE IF EXISTS `demo_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `refferal_id` int NOT NULL,
  `level` tinyint NOT NULL,
  `payment_type` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `payments_refferal_id` (`refferal_id`),
  CONSTRAINT `demo_payments_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `demo_payments_refferal_id` FOREIGN KEY (`refferal_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_payments`
--

LOCK TABLES `demo_payments` WRITE;
/*!40000 ALTER TABLE `demo_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` tinyint NOT NULL,
  `question` varchar(200) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `feedback` text NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `feedback_partner_id` (`partner_id`),
  CONSTRAINT `feedback_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gold_token`
--

DROP TABLE IF EXISTS `gold_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gold_token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `matrix_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `matrix_id` (`matrix_id`),
  CONSTRAINT `gold_token_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gold_token`
--

LOCK TABLES `gold_token` WRITE;
/*!40000 ALTER TABLE `gold_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `gold_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gold_token_settings`
--

DROP TABLE IF EXISTS `gold_token_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gold_token_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gold_token_settings`
--

LOCK TABLES `gold_token_settings` WRITE;
/*!40000 ALTER TABLE `gold_token_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `gold_token_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invite_pay_off`
--

DROP TABLE IF EXISTS `invite_pay_off`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invite_pay_off` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `benefit_partner_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_off` tinyint NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `payer_partner_id` (`benefit_partner_id`),
  CONSTRAINT `invite_pay_off_benefit_partner_id` FOREIGN KEY (`benefit_partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invite_pay_off_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invite_pay_off`
--

LOCK TABLES `invite_pay_off` WRITE;
/*!40000 ALTER TABLE `invite_pay_off` DISABLE KEYS */;
/*!40000 ALTER TABLE `invite_pay_off` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `landings`
--

DROP TABLE IF EXISTS `landings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `landings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `styles` text NOT NULL,
  `js` text NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `landings`
--

LOCK TABLES `landings` WRITE;
/*!40000 ALTER TABLE `landings` DISABLE KEYS */;
/*!40000 ALTER TABLE `landings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels_payment`
--

DROP TABLE IF EXISTS `levels_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `levels_payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL,
  `partner_id` int NOT NULL,
  `refferal_id` int NOT NULL,
  `level` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `partner_id` (`partner_id`),
  KEY `refferal_id` (`refferal_id`),
  CONSTRAINT `levels_payment_matrix_id` FOREIGN KEY (`matrix_id`) REFERENCES `matrix_1_1` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `levels_payment_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `levels_payment_refferal_id` FOREIGN KEY (`refferal_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels_payment`
--

LOCK TABLES `levels_payment` WRITE;
/*!40000 ALTER TABLE `levels_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `levels_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels_pecentage`
--

DROP TABLE IF EXISTS `levels_pecentage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `levels_pecentage` (
  `level` tinyint NOT NULL,
  `value` tinyint NOT NULL,
  UNIQUE KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels_pecentage`
--

LOCK TABLES `levels_pecentage` WRITE;
/*!40000 ALTER TABLE `levels_pecentage` DISABLE KEYS */;
/*!40000 ALTER TABLE `levels_pecentage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matrices_settings_1`
--

DROP TABLE IF EXISTS `matrices_settings_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matrices_settings_1` (
  `number` tinyint NOT NULL,
  `type` tinyint NOT NULL,
  `wide` tinyint NOT NULL,
  `levels` tinyint NOT NULL,
  `clone` tinyint NOT NULL,
  `clone_cycle` tinyint NOT NULL,
  `pay` decimal(10,2) NOT NULL,
  `admin_pay_off` decimal(10,2) NOT NULL,
  `pay_off` decimal(10,2) NOT NULL,
  `auto_pay_off` tinyint NOT NULL DEFAULT '0',
  `clone_pay` decimal(10,2) NOT NULL,
  `clone_admin_pay_off` decimal(10,2) NOT NULL,
  `clone_pay_off` decimal(10,2) NOT NULL,
  `invite_pay_off` decimal(10,2) NOT NULL,
  `invite_sponsor_activate` tinyint NOT NULL,
  `open_matrix_balls` int NOT NULL,
  `close_matrix_balls` int NOT NULL,
  `referral_matrix_balls` int NOT NULL,
  `account_type` tinyint NOT NULL,
  `close_matrix` tinyint NOT NULL,
  `trans_struct` tinyint NOT NULL,
  `trans_matrix` tinyint NOT NULL,
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matrices_settings_1`
--

LOCK TABLES `matrices_settings_1` WRITE;
/*!40000 ALTER TABLE `matrices_settings_1` DISABLE KEYS */;
INSERT INTO `matrices_settings_1` VALUES (1,2,2,2,0,0,9.00,0.00,0.00,0,0.00,0.00,0.00,0.00,0,900,450,0,1,1,0,0),(2,2,2,2,0,0,27.00,0.00,10.00,0,0.00,0.00,0.00,1.00,0,2700,1350,0,1,1,0,0),(3,2,2,2,0,0,68.00,0.00,25.00,0,0.00,0.00,0.00,2.00,0,6800,3400,0,1,1,0,0),(4,2,2,1,0,0,173.00,0.00,331.00,0,0.00,0.00,0.00,3.00,0,17300,8650,0,1,1,0,0);
/*!40000 ALTER TABLE `matrices_settings_1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matrix_1_1`
--

DROP TABLE IF EXISTS `matrix_1_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matrix_1_1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL DEFAULT '0',
  `clone_matrix_id` int NOT NULL DEFAULT '0',
  `partner_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `left_key` int NOT NULL DEFAULT '0',
  `right_key` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '0',
  `level_1` tinyint NOT NULL DEFAULT '0',
  `level_2` tinyint NOT NULL DEFAULT '0',
  `clone` tinyint NOT NULL DEFAULT '0',
  `change` tinyint NOT NULL DEFAULT '0',
  `open_date` int NOT NULL DEFAULT '0',
  `close_date` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matrix_1_1`
--

LOCK TABLES `matrix_1_1` WRITE;
/*!40000 ALTER TABLE `matrix_1_1` DISABLE KEYS */;
INSERT INTO `matrix_1_1` VALUES (1,0,0,1,0,1,2,1,0,0,0,0,1462217039,0);
/*!40000 ALTER TABLE `matrix_1_1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matrix_1_2`
--

DROP TABLE IF EXISTS `matrix_1_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matrix_1_2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL DEFAULT '0',
  `clone_matrix_id` int NOT NULL DEFAULT '0',
  `partner_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `left_key` int NOT NULL DEFAULT '0',
  `right_key` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '0',
  `level_1` tinyint NOT NULL DEFAULT '0',
  `level_2` tinyint NOT NULL DEFAULT '0',
  `clone` tinyint NOT NULL DEFAULT '0',
  `change` tinyint NOT NULL DEFAULT '0',
  `open_date` int NOT NULL DEFAULT '0',
  `close_date` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `matrix_1_2_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matrix_1_2`
--

LOCK TABLES `matrix_1_2` WRITE;
/*!40000 ALTER TABLE `matrix_1_2` DISABLE KEYS */;
INSERT INTO `matrix_1_2` VALUES (1,0,0,1,0,1,2,1,0,0,0,0,1462217039,0);
/*!40000 ALTER TABLE `matrix_1_2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matrix_1_3`
--

DROP TABLE IF EXISTS `matrix_1_3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matrix_1_3` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL DEFAULT '0',
  `clone_matrix_id` int NOT NULL DEFAULT '0',
  `partner_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `left_key` int NOT NULL DEFAULT '0',
  `right_key` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '0',
  `level_1` tinyint NOT NULL DEFAULT '0',
  `level_2` tinyint NOT NULL DEFAULT '0',
  `clone` tinyint NOT NULL DEFAULT '0',
  `change` tinyint NOT NULL DEFAULT '0',
  `open_date` int NOT NULL DEFAULT '0',
  `close_date` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `matrix_1_3_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matrix_1_3`
--

LOCK TABLES `matrix_1_3` WRITE;
/*!40000 ALTER TABLE `matrix_1_3` DISABLE KEYS */;
INSERT INTO `matrix_1_3` VALUES (1,0,0,1,0,1,2,1,0,0,0,0,1462217039,0);
/*!40000 ALTER TABLE `matrix_1_3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matrix_1_4`
--

DROP TABLE IF EXISTS `matrix_1_4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matrix_1_4` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL DEFAULT '0',
  `clone_matrix_id` int NOT NULL DEFAULT '0',
  `partner_id` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `left_key` int NOT NULL DEFAULT '0',
  `right_key` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '0',
  `level_1` tinyint NOT NULL DEFAULT '0',
  `level_2` tinyint NOT NULL DEFAULT '0',
  `clone` tinyint NOT NULL DEFAULT '0',
  `change` tinyint NOT NULL DEFAULT '0',
  `open_date` int NOT NULL DEFAULT '0',
  `close_date` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `matrix_1_4_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matrix_1_4`
--

LOCK TABLES `matrix_1_4` WRITE;
/*!40000 ALTER TABLE `matrix_1_4` DISABLE KEYS */;
INSERT INTO `matrix_1_4` VALUES (1,0,0,1,0,1,2,1,0,0,0,0,1462217039,0);
/*!40000 ALTER TABLE `matrix_1_4` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matrix_payments`
--

DROP TABLE IF EXISTS `matrix_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matrix_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `structure_number` tinyint NOT NULL,
  `partner_id` int NOT NULL,
  `payer_partner_id` int NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `type` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_off` tinyint NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `matrix_payments_partner_id` (`partner_id`),
  KEY `payer_partner_id` (`payer_partner_id`),
  CONSTRAINT `matrix_payments_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `matrix_payments_payer_partner_id` FOREIGN KEY (`payer_partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matrix_payments`
--

LOCK TABLES `matrix_payments` WRITE;
/*!40000 ALTER TABLE `matrix_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `matrix_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `parent_id` int NOT NULL,
  `controller_id` tinyint NOT NULL,
  `content_id` int DEFAULT NULL,
  `backoffice` tinyint NOT NULL,
  `partner_status` tinyint NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `iso` varchar(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `content_id` (`content_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,2,0,7,5,0,2,'О проекте','about',1,''),(2,2,0,7,6,0,2,'Рекламные услуги','services',0,''),(3,2,0,7,7,0,2,'Заработок','profit',0,'');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_categories`
--

DROP TABLE IF EXISTS `menu_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_categories`
--

LOCK TABLES `menu_categories` WRITE;
/*!40000 ALTER TABLE `menu_categories` DISABLE KEYS */;
INSERT INTO `menu_categories` VALUES (1,'Левое меню - личный кабинет'),(2,'Верхнее меню - фронт');
/*!40000 ALTER TABLE `menu_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `reply` int NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1431065291),('m130524_201442_init',1431065295);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `short_text` text NOT NULL,
  `text` text NOT NULL,
  `meta_title` varchar(100) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `news_user_id` (`author_id`),
  CONSTRAINT `news_user_id` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagination`
--

DROP TABLE IF EXISTS `pagination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagination` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `value` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_id` (`menu_id`),
  CONSTRAINT `pagination_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `admin_menu` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagination`
--

LOCK TABLES `pagination` WRITE;
/*!40000 ALTER TABLE `pagination` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sponsor_id` int NOT NULL,
  `left_key` int NOT NULL,
  `right_key` int NOT NULL,
  `level` int NOT NULL,
  `login` varchar(100) NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(20) NOT NULL,
  `mailing` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL,
  `payeer_wallet` varchar(40) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `matrix_1` tinyint NOT NULL,
  `demo_matrix_1` tinyint NOT NULL,
  `total_amount_1` decimal(10,2) NOT NULL,
  `demo_total_amount_1` decimal(10,2) NOT NULL,
  `total_balls_1` decimal(10,2) NOT NULL,
  `demo_total_balls_1` decimal(10,2) NOT NULL,
  `gold_token` decimal(10,2) NOT NULL DEFAULT '0.00',
  `group_id` int NOT NULL,
  `iso` varchar(2) DEFAULT NULL,
  `geo` blob,
  `ip` varchar(16) NOT NULL,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,0,1,2,1,'admin','admin','admin','','$2y$13$AncwFQCyPpr6ZOUNXLiZtuwdWHW1aQAGbCyrNaf3dIEqwq.nbE7le',NULL,'admin@mail.com','',1,1,'',0.00,4,4,0.00,0.00,0.00,0.00,0.00,0,'US',NULL,'',1451743995,1538069193);
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payeer_payments`
--

DROP TABLE IF EXISTS `payeer_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payeer_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `places` tinyint NOT NULL,
  `order_id` int NOT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `operation_id` int NOT NULL,
  `operation_date` int NOT NULL,
  `operation_pay_date` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `matrix_id` (`matrix_id`),
  CONSTRAINT `payeer_payments_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payeer_payments`
--

LOCK TABLES `payeer_payments` WRITE;
/*!40000 ALTER TABLE `payeer_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payeer_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_logs`
--

DROP TABLE IF EXISTS `payment_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `places` tinyint NOT NULL,
  `account_type` tinyint NOT NULL,
  `sci_sign` varchar(50) NOT NULL,
  `order_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transact_id` varchar(50) NOT NULL,
  `action_type` tinyint NOT NULL,
  `type` tinyint NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  CONSTRAINT `payment_logs_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_logs`
--

LOCK TABLES `payment_logs` WRITE;
/*!40000 ALTER TABLE `payment_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `refferal_id` int NOT NULL,
  `level` tinyint NOT NULL,
  `payment_type` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `payments_refferal_id` (`refferal_id`),
  CONSTRAINT `payments_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payments_refferal_id` FOREIGN KEY (`refferal_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments_faul`
--

DROP TABLE IF EXISTS `payments_faul`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments_faul` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matrix_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `partner_id` int NOT NULL,
  `paid_matrix_partner_id` int NOT NULL,
  `paid_matrix_id` int NOT NULL,
  `payment_type` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `note` varchar(255) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `paid` tinyint NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_faul_partner_id` (`partner_id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `paid_matrix_id` (`paid_matrix_id`),
  KEY `payments_faul_paid_matrix_partner_id` (`paid_matrix_partner_id`),
  CONSTRAINT `payments_faul_paid_matrix_partner_id` FOREIGN KEY (`paid_matrix_partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payments_faul_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments_faul`
--

LOCK TABLES `payments_faul` WRITE;
/*!40000 ALTER TABLE `payments_faul` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments_faul` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments_invoices`
--

DROP TABLE IF EXISTS `payments_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments_invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `structure_number` tinyint NOT NULL,
  `matrix_number` tinyint NOT NULL,
  `matrix_id` int NOT NULL,
  `paid_matrix_partner_id` int NOT NULL,
  `paid_matrix_id` int NOT NULL,
  `payment_type` tinyint NOT NULL,
  `account_type` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `order_id` int NOT NULL,
  `transact_id` varchar(100) NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matrix_id` (`matrix_id`),
  KEY `payments_invoices_partner_id` (`partner_id`),
  KEY `paid_matrix_id` (`paid_matrix_id`),
  KEY `payments_invoices_paid_matrix_partner_id` (`paid_matrix_partner_id`),
  CONSTRAINT `payments_invoices_paid_matrix_partner_id` FOREIGN KEY (`paid_matrix_partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payments_invoices_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments_invoices`
--

LOCK TABLES `payments_invoices` WRITE;
/*!40000 ALTER TABLE `payments_invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `controller_id` tinyint NOT NULL,
  `permissions` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `permissions_group_id` FOREIGN KEY (`group_id`) REFERENCES `user_groups` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,1,1,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}'),(2,1,2,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}'),(3,1,3,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}'),(4,1,4,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}'),(5,1,5,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}'),(6,1,6,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}'),(7,1,7,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}'),(8,1,8,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}'),(9,2,7,_binary 'a:4:{s:9:\"view_perm\";s:1:\"1\";s:11:\"create_perm\";s:1:\"1\";s:11:\"update_perm\";s:1:\"1\";s:11:\"delete_perm\";s:1:\"1\";}');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `register_stats`
--

DROP TABLE IF EXISTS `register_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `register_stats` (
  `register_date` date NOT NULL,
  `count` int NOT NULL,
  UNIQUE KEY `register_date` (`register_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `register_stats`
--

LOCK TABLES `register_stats` WRITE;
/*!40000 ALTER TABLE `register_stats` DISABLE KEYS */;
INSERT INTO `register_stats` VALUES ('2022-08-19',1);
/*!40000 ALTER TABLE `register_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restore_password`
--

DROP TABLE IF EXISTS `restore_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restore_password` (
  `user_id` int NOT NULL,
  `hash` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restore_password`
--

LOCK TABLES `restore_password` WRITE;
/*!40000 ALTER TABLE `restore_password` DISABLE KEYS */;
/*!40000 ALTER TABLE `restore_password` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo`
--

DROP TABLE IF EXISTS `seo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `meta_title` text NOT NULL,
  `meta_desc` text NOT NULL,
  `meta_keywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo`
--

LOCK TABLES `seo` WRITE;
/*!40000 ALTER TABLE `seo` DISABLE KEYS */;
/*!40000 ALTER TABLE `seo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('is_activation_allowed','0'),('is_feedbacks_allowed','0'),('is_payment_allowed','0'),('is_tickets_allowed','1'),('liveinternet','&lt;!--LiveInternet counter--&gt;&lt;script type=&quot;text/javascript&quot;&gt;\r\nnew Image().src = &quot;//counter.yadro.ru/hit?r&quot;+\r\nescape(document.referrer)+((typeof(screen)==&quot;undefined&quot;)?&quot;&quot;:\r\n&quot;;s&quot;+screen.width+&quot;*&quot;+screen.height+&quot;*&quot;+(screen.colorDepth?\r\nscreen.colorDepth:screen.pixelDepth))+&quot;;u&quot;+escape(document.URL)+\r\n&quot;;&quot;+Math.random();&lt;/script&gt;&lt;!--/LiveInternet--&gt;'),('yandex','&lt;!-- Yandex.Metrika counter --&gt;\r\n&lt;script type=&quot;text/javascript&quot;&gt;\r\n    (function (d, w, c) {\r\n        (w[c] = w[c] || []).push(function() {\r\n            try {\r\n                w.yaCounter44082569 = new Ya.Metrika({\r\n                    id:44082569,\r\n                    clickmap:true,\r\n                    trackLinks:true,\r\n                    accurateTrackBounce:true\r\n                });\r\n            } catch(e) { }\r\n        });\r\n\r\n        var n = d.getElementsByTagName(&quot;script&quot;)[0],\r\n            s = d.createElement(&quot;script&quot;),\r\n            f = function () { n.parentNode.insertBefore(s, n); };\r\n        s.type = &quot;text/javascript&quot;;\r\n        s.async = true;\r\n        s.src = &quot;https://mc.yandex.ru/metrika/watch.js&quot;;\r\n\r\n        if (w.opera == &quot;[object Opera]&quot;) {\r\n            d.addEventListener(&quot;DOMContentLoaded&quot;, f, false);\r\n        } else { f(); }\r\n    })(document, window, &quot;yandex_metrika_callbacks&quot;);\r\n&lt;/script&gt;\r\n&lt;noscript&gt;&lt;div&gt;&lt;img src=&quot;https://mc.yandex.ru/watch/44082569&quot; style=&quot;position:absolute; left:-9999px;&quot; alt=&quot;&quot; /&gt;&lt;/div&gt;&lt;/noscript&gt;\r\n&lt;!-- /Yandex.Metrika counter --&gt;');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slider`
--

DROP TABLE IF EXISTS `slider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slider` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider`
--

LOCK TABLES `slider` WRITE;
/*!40000 ALTER TABLE `slider` DISABLE KEYS */;
/*!40000 ALTER TABLE `slider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sponsor_advert`
--

DROP TABLE IF EXISTS `sponsor_advert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sponsor_advert` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sponsor_advert_partner_id` (`partner_id`),
  CONSTRAINT `sponsor_advert_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sponsor_advert`
--

LOCK TABLES `sponsor_advert` WRITE;
/*!40000 ALTER TABLE `sponsor_advert` DISABLE KEYS */;
/*!40000 ALTER TABLE `sponsor_advert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `static_content`
--

DROP TABLE IF EXISTS `static_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `static_content` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `static_content`
--

LOCK TABLES `static_content` WRITE;
/*!40000 ALTER TABLE `static_content` DISABLE KEYS */;
INSERT INTO `static_content` VALUES (1,'back_office','back_office',1,1487870185,1547939709),(2,'profile','profile',1,1487870239,1546877668),(3,'withdrawal','withdrawal',1,1487870303,1487870303),(4,'withdrawal_request','withdrawal_request',1,1487870337,1487870337),(5,'top_leaders','top_leaders',1,1487870401,1556864551),(6,'activation','activation',1,1487870512,1556864372),(7,'pricing_tiles','pricing_tiles',1,1487870942,1488567958),(8,'real_structure','real_structure',1,1487871065,1487871065),(9,'demo_structure','demo_structure',1,1487871104,1487871104),(10,'referals-links','referals-links',1,1487871160,1556901164),(11,'marketing-plan-left','marketing-plan-left',1,1487871266,1558946764),(12,'marketing-plan-right','marketing-plan-right',1,1487871277,1548440614),(13,'contacts_content','contacts_content',1,1487871319,1487871319),(14,'index','index',1,1487871409,1555838745),(15,'success_signup','success_signup',1,1487871466,1512492496),(16,'success_confirm','success_confirm',1,1487871477,1516289801),(17,'purchase_content','purchase_content',1,1488566937,1488567530),(19,'images','images',1,1491633217,1492092466),(20,'top_leader_info','top_leader_info',1,1491891051,1548912872),(21,'faq','faq',1,1492065730,1492067842),(22,'news_attention','news_attention',1,1494005975,1556864206),(23,'matrices_content','matrices_content',1,1497198936,1556864492),(24,'tile_content','tile_content',1,1498150513,1558946698),(25,'feedbacks','feedbacks',1,1511878491,1549842435),(26,'feedback','feedback',1,1511887789,1549842382),(27,'partner_info','partner_info',1,1518287295,1520250132),(28,'video','video',1,1518422931,1518423750),(29,'tickets','tickets',1,1519063645,1556864665),(30,'counter_content','counter_content',1,1541952156,1548437110),(31,'solution','solution',1,1555838901,1556801409),(32,'prelaunch','prelaunch',1,1556875583,1558430564);
/*!40000 ALTER TABLE `static_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `text_advert`
--

DROP TABLE IF EXISTS `text_advert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `text_advert` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `balls` int NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `text_advert_partner_id` (`partner_id`),
  CONSTRAINT `text_advert_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `text_advert`
--

LOCK TABLES `text_advert` WRITE;
/*!40000 ALTER TABLE `text_advert` DISABLE KEYS */;
/*!40000 ALTER TABLE `text_advert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `subject` varchar(100) NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partne_id` (`id`),
  KEY `tickets_partner_id` (`partner_id`),
  CONSTRAINT `tickets_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets_messages`
--

DROP TABLE IF EXISTS `tickets_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `ticket_id` int NOT NULL,
  `type` tinyint NOT NULL,
  `text` text NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_messages_ticket_id` (`ticket_id`),
  KEY `user_id` (`id`),
  KEY `tickets_messages_user_id` (`user_id`),
  CONSTRAINT `tickets_messages_ticket_id` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tickets_messages_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets_messages`
--

LOCK TABLES `tickets_messages` WRITE;
/*!40000 ALTER TABLE `tickets_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tickets_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `top_referals`
--

DROP TABLE IF EXISTS `top_referals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `top_referals` (
  `partner_id` int NOT NULL,
  `count` int NOT NULL,
  UNIQUE KEY `partner_id` (`partner_id`),
  CONSTRAINT `top_referals_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `top_referals`
--

LOCK TABLES `top_referals` WRITE;
/*!40000 ALTER TABLE `top_referals` DISABLE KEYS */;
INSERT INTO `top_referals` VALUES (1,1);
/*!40000 ALTER TABLE `top_referals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transfer_balls`
--

DROP TABLE IF EXISTS `transfer_balls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transfer_balls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `balls` int NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transfer_balls_sender_id` (`sender_id`),
  KEY `transfer_balls_receiver_id` (`receiver_id`),
  CONSTRAINT `transfer_balls_receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transfer_balls_sender_id` FOREIGN KEY (`sender_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transfer_balls`
--

LOCK TABLES `transfer_balls` WRITE;
/*!40000 ALTER TABLE `transfer_balls` DISABLE KEYS */;
/*!40000 ALTER TABLE `transfer_balls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `group_id` int NOT NULL,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'root','','$2y$13$AncwFQCyPpr6ZOUNXLiZtuwdWHW1aQAGbCyrNaf3dIEqwq.nbE7le',NULL,'root@mail.com',1,1,1451743769,1550212137),(2,'admin','','$2y$13$DuKwoGq6/U0BprZt7gOP8eaMaJ0CFsjdA5hLi2XUSf04laOW7kWlq',NULL,'admin@mail.com',1,1,1461332045,1461825033),(3,'editor','','$2y$13$MVhFL3w8U6wmrJZFhFZ3HeF5gDE8nh5jYlDiNpyGPcp92g5OkmL1a',NULL,'editor@mail.com',1,2,1461571136,1461571136);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` VALUES (1,'Администраторы'),(2,'Редакторы'),(3,'Партнеры');
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdrawal`
--

DROP TABLE IF EXISTS `withdrawal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdrawal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `type` tinyint NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  CONSTRAINT `withdrawal_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdrawal`
--

LOCK TABLES `withdrawal` WRITE;
/*!40000 ALTER TABLE `withdrawal` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdrawal` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-19 22:16:20
