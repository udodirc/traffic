-- MySQL dump 10.13  Distrib 5.7.21, for Linux (i686)
--
-- Host: localhost    Database: withoutadmin
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping routines for database 'withoutadmin'
--
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP FUNCTION IF EXISTS `split_str`;
CREATE FUNCTION `split_str`(x VARCHAR(255), delim VARCHAR(12), pos INT) RETURNS varchar(255) CHARSET utf8
DETERMINISTIC
RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
       LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
       delim, '') ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `activate_partner`;
CREATE PROCEDURE `activate_partner`(IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_PARTNER_ID` INT(10), IN `VAR_IN_MATRIX` VARCHAR(20) CHARSET utf8, IN `VAR_IN_DATE` VARCHAR(24) CHARSET utf8, IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_STATUS` TINYINT(8), IN `VAR_IN_CLASSIC_STUCTURE` TINYINT(8), OUT `VAR_OUT_RESULT` INT(8), OUT `VAR_OUT_RESULT2` TEXT CHARSET utf8)
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS INT(8);
	DECLARE VAR_PARTNER_LEFT_KEY INT(10);
	DECLARE VAR_PARTNER_RIGHT_KEY INT(10);
	DECLARE VAR_PARTNER_LEVEL INT(10);
	DECLARE VAR_DEPTH_LEVEL TINYINT(8);
	DECLARE VAR_MATRIX_ID INT(10);
	DECLARE VAR_TYPE TINYINT(8);
	DECLARE VAR_PAY DECIMAL(10,2);
	DECLARE VAR_RESULT TINYINT(8);
	DECLARE VAR_RESULT2 TEXT;
	
	SET @VAR_PARTNER_LEFT_KEY = 0;
	SET @VAR_PARTNER_RIGHT_KEY = 0;
	SET @VAR_PARTNER_LEVEL = 0;
	SET @VAR_MATRIX_ID = 0;
	SET @VAR_DEPTH_LEVEL = 0;
	SET @VAR_PAY = 0;
	SET @VAR_AFFECTED_ROWS = 0;
	SET @VAR_RESULT = 0;
	SET @VAR_RESULT2 = '';
	SET VAR_OUT_RESULT = 0;
	SET VAR_OUT_RESULT2 = '';

	SELECT `type`, `pay` INTO @VAR_TYPE, @VAR_PAY
	FROM `matrices_settings_1`
	WHERE `number` = 1;
	SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'01',';;;;',@VAR_TYPE,';;;;',@VAR_PAY,';;;;');
			
	IF @VAR_TYPE > 0 
	THEN
			
		IF @VAR_TYPE > 1 
		THEN
				
			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',VAR_IN_SPONSOR_ID,';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_STATUS,';;;;');	
			CALL `binar_matrix`(VAR_IN_SPONSOR_ID, VAR_IN_PARTNER_ID, VAR_IN_MATRIX, VAR_IN_DATE, VAR_IN_DEMO, VAR_IN_STATUS, 0, 0, 0, @VAR_RESULT, @VAR_RESULT2);
				
		ELSE
		
			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_DEMO,';;;;',VAR_IN_STATUS,';;;;');
			CALL `linear_matrix`(VAR_IN_PARTNER_ID, VAR_IN_MATRIX, VAR_IN_DEMO, VAR_IN_DATE, VAR_IN_STATUS, @VAR_RESULT, @VAR_RESULT2);
			
		END IF;
			
	END IF;
	
	IF (@VAR_RESULT > 0)
	THEN
		
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'03',';;;;',@VAR_RESULT,';;;;');
		
		IF (VAR_IN_CLASSIC_STUCTURE > 0)
		THEN
					
			CALL `get_levels_depth`(@VAR_DEPTH_LEVEL); 
			
			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'04',';;;;',@VAR_DEPTH_LEVEL,';;;;');
					
			IF (@VAR_DEPTH_LEVEL > 0)
			THEN
				
				SELECT `level`, `left_key`, `right_key` INTO @VAR_PARTNER_LEVEL, @VAR_PARTNER_LEFT_KEY, @VAR_PARTNER_RIGHT_KEY
				FROM `partners` 
				WHERE `id` = VAR_IN_PARTNER_ID;
				
				IF (@VAR_PARTNER_LEFT_KEY > 0 && @VAR_PARTNER_RIGHT_KEY > 0 && @VAR_PARTNER_LEVEL > 0)
				THEN
					
					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'05',';;;;',@VAR_PARTNER_LEFT_KEY,';;;;',@VAR_PARTNER_RIGHT_KEY,';;;;',@VAR_PARTNER_LEVEL,';;;;');
					
					CALL `get_matrix_id`(VAR_IN_MATRIX, VAR_IN_DEMO, @VAR_MATRIX_ID); 
						
					IF (@VAR_MATRIX_ID > 0)
					THEN	
						
						SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',@VAR_MATRIX_ID,';;;;');
						
						IF (@VAR_DEPTH_LEVEL > @VAR_PARTNER_LEVEL)
						THEN
							
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',VAR_IN_PARTNER_ID,';;;;',@VAR_DEPTH_LEVEL,';;;;',@VAR_PARTNER_LEFT_KEY,';;;;',@VAR_PARTNER_RIGHT_KEY,';;;;',@VAR_PARTNER_LEVEL,';;;;',@VAR_MATRIX_ID,';;;;',@VAR_PAY,';;;;',';;;;',VAR_IN_DEMO,';;;;');		
							CALL `levels_payments_depth_more_referal_level`(VAR_IN_PARTNER_ID, @VAR_DEPTH_LEVEL, @VAR_PARTNER_LEFT_KEY, @VAR_PARTNER_RIGHT_KEY, @VAR_PARTNER_LEVEL, @VAR_MATRIX_ID, @VAR_PAY, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
							
						ELSE
								
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'07',';;;;',VAR_IN_PARTNER_ID,';;;;',@VAR_DEPTH_LEVEL,';;;;',@VAR_PARTNER_LEFT_KEY,';;;;',@VAR_PARTNER_RIGHT_KEY,';;;;',@VAR_PARTNER_LEVEL,';;;;',@VAR_MATRIX_ID,';;;;',@VAR_PAY,';;;;',';;;;',VAR_IN_DEMO,';;;;');		
							CALL `levels_payments_depth_lesser_referal_level`(VAR_IN_PARTNER_ID, @VAR_DEPTH_LEVEL, @VAR_PARTNER_LEFT_KEY, @VAR_PARTNER_RIGHT_KEY, @VAR_PARTNER_LEVEL, @VAR_MATRIX_ID, @VAR_PAY, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);

						END IF;
						
					END IF;
							
					IF @VAR_AFFECTED_ROWS > 0 
					THEN

						SET @VAR_RESULT = 1;
						SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'08',';;;;',@VAR_RESULT,';;;;');
						
					END IF;
						
				END IF;
				
			END IF;
			
		END IF;
		
	END IF;
	
	SET VAR_OUT_RESULT = @VAR_RESULT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `add_partner_in_structure`;
CREATE PROCEDURE `add_partner_in_structure`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_SPONSOR_ID` INT(10), IN `VAR_IN_LOGIN` VARCHAR(30) CHARSET utf8, IN `VAR_IN_FIRST_NAME` VARCHAR(100) CHARSET utf8, IN `VAR_IN_LAST_NAME` VARCHAR(100) CHARSET utf8, IN `VAR_IN_EMAIL` VARCHAR(50) CHARSET utf8, IN `VAR_IN_PHONE` VARCHAR(20) CHARSET utf8, IN `VAR_IN_PASSWORD` VARCHAR(255) CHARSET utf8, IN `VAR_IN_DATE` INT(11), IN `VAR_IN_AUTH_KEY` VARCHAR(32) CHARSET utf8, IN `VAR_IN_STATUS` TINYINT(8), IN `VAR_IN_CLASSIC_STUCTURE` TINYINT(8), OUT `VAR_OUT_RESULT` VARCHAR(255) CHARSET utf8)
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS INT(8);
	DECLARE VAR_PARTNER_LEFT_KEY INT(10);
	DECLARE VAR_PARTNER_RIGHT_KEY INT(10);
	DECLARE VAR_PARTNER_LEVEL INT(10);
	DECLARE VAR_DEPTH_LEVEL TINYINT(8);
	DECLARE VAR_MATRIX_ID INT(10);
	DECLARE VAR_SPONSOR_ID INT(10);
	DECLARE VAR_PARTNER_ID INT(10);
	DECLARE VAR_SPONSOR_RIGHT_KEY INT(10);
	DECLARE VAR_SPONSOR_LEVEL INT(10);
	DECLARE VAR_DATE INT(11);
	DECLARE VAR_TYPE TINYINT(8);
	DECLARE VAR_PAY DECIMAL(10,2);
	DECLARE VAR_RESULT TINYINT(8);
	DECLARE VAR_RESULT2 TEXT;
	
	SET VAR_OUT_RESULT = 0;
	SET VAR_RESULT = 0;
	SET VAR_RESULT2 = '';
	SET VAR_AFFECTED_ROWS = 0;
	SET VAR_SPONSOR_ID = 0;
	SET VAR_PARTNER_ID = 0;
	SET VAR_SPONSOR_RIGHT_KEY = 0;
	SET VAR_SPONSOR_LEVEL = 0;
	SET VAR_DATE = 0;
	SET VAR_TYPE = 0;
	SET VAR_PAY = 0;
	SET VAR_PARTNER_LEFT_KEY = 0;
	SET VAR_PARTNER_RIGHT_KEY = 0;
	SET VAR_PARTNER_LEVEL = 0;
	SET VAR_MATRIX_ID = 0;
	SET VAR_DEPTH_LEVEL = 0;
	SET VAR_PAY = 0;
	
	START TRANSACTION;
	
		IF VAR_IN_SPONSOR_ID > 0
		THEN
	
			SET VAR_SPONSOR_ID = VAR_IN_SPONSOR_ID;
			
			SELECT `right_key`, `level` INTO VAR_SPONSOR_RIGHT_KEY, VAR_SPONSOR_LEVEL
			FROM `partners`
			WHERE `id` = VAR_IN_SPONSOR_ID;
	
		ELSE
		
			SELECT `id`, `right_key`, `level` INTO VAR_SPONSOR_ID, VAR_SPONSOR_RIGHT_KEY, VAR_SPONSOR_LEVEL
			FROM `partners`
			WHERE `left_key` = 1;
	
		END IF;
		
		IF (VAR_SPONSOR_RIGHT_KEY > 0 && VAR_SPONSOR_LEVEL > 0 && VAR_SPONSOR_ID > 0)
		THEN
		
			UPDATE `partners` SET `right_key` = `right_key` + 2, `left_key` = IF(`left_key` > VAR_SPONSOR_RIGHT_KEY, `left_key` + 2, `left_key`) 
			WHERE `right_key` >= VAR_SPONSOR_RIGHT_KEY; 
			
			SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
			
			IF VAR_AFFECTED_ROWS > 0 
			THEN
			
				SET VAR_AFFECTED_ROWS = 0;
				
				IF VAR_IN_DATE > 0 
				THEN
				
					INSERT INTO `partners` SET `sponsor_id` = VAR_SPONSOR_ID, `left_key` = VAR_SPONSOR_RIGHT_KEY, `right_key` = VAR_SPONSOR_RIGHT_KEY + 1, `level` = VAR_SPONSOR_LEVEL + 1, `login` = VAR_IN_LOGIN, `first_name` = VAR_IN_FIRST_NAME, `last_name` = VAR_IN_LAST_NAME,  `auth_key` = VAR_IN_AUTH_KEY, `password_hash` = VAR_IN_PASSWORD, `email` = VAR_IN_EMAIL, `phone` = VAR_IN_PHONE, `status` = VAR_IN_STATUS, `created_at` = VAR_IN_DATE;
				
				ELSE
				
					INSERT INTO `partners` SET `sponsor_id` = VAR_SPONSOR_ID, `left_key` = VAR_SPONSOR_RIGHT_KEY, `right_key` = VAR_SPONSOR_RIGHT_KEY + 1, `level` = VAR_SPONSOR_LEVEL + 1, `login` = VAR_IN_LOGIN, `first_name` = VAR_IN_FIRST_NAME, `last_name` = VAR_IN_LAST_NAME,  `auth_key` = VAR_IN_AUTH_KEY, `password_hash` = VAR_IN_PASSWORD, `email` = VAR_IN_EMAIL, `phone` = VAR_IN_PHONE, `status` = VAR_IN_STATUS, `created_at` = UNIX_TIMESTAMP();
					
				END IF;
				
				SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
				
				IF VAR_AFFECTED_ROWS > 0 
				THEN
				
					SET VAR_AFFECTED_ROWS = 0;
					SET VAR_PARTNER_ID = LAST_INSERT_ID();
					
					INSERT INTO `top_referals` (`partner_id`, `count`) VALUES (VAR_IN_SPONSOR_ID, '1')
					ON DUPLICATE KEY UPDATE `count` = `count` + 1;
				
					SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
				
					IF VAR_AFFECTED_ROWS > 0 
					THEN
				
						SET VAR_AFFECTED_ROWS = 0;
				
						INSERT INTO `register_stats` (`register_date`, `count`) VALUES (CURDATE(), '1')
						ON DUPLICATE KEY UPDATE `count` = `count` + 1;
				
						SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
						
						IF VAR_AFFECTED_ROWS > 0 
						THEN
				
							SET VAR_RESULT = VAR_PARTNER_ID;
		
						END IF;
						
					END IF;
				
				END IF;
		
			END IF;
		
		END IF;	
	
		IF VAR_RESULT > 0 
		THEN
				
			SET VAR_RESULT = 0;	
				
			CALL `binar_matrix`(VAR_IN_SPONSOR_ID, VAR_PARTNER_ID, VAR_IN_STRUCTURE_NUMBER, 1, VAR_IN_DATE, 1, VAR_IN_STATUS, 0, 0, 0, 0, 0, VAR_RESULT, VAR_RESULT2);	
				
			IF (VAR_RESULT > 0)
			THEN
			
				IF (VAR_IN_CLASSIC_STUCTURE > 0)
				THEN
							
					CALL `get_levels_depth`(VAR_DEPTH_LEVEL); 
						
					IF (VAR_DEPTH_LEVEL > 0)
					THEN
						
						SELECT `level`, `left_key`, `right_key` INTO VAR_PARTNER_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY
						FROM `partners` 
						WHERE `id` = VAR_PARTNER_ID;
						
						IF (VAR_PARTNER_LEFT_KEY > 0 && VAR_PARTNER_RIGHT_KEY > 0 && VAR_PARTNER_LEVEL > 0)
						THEN
							
							CALL `get_matrix_id`(VAR_IN_STRUCTURE_NUMBER, 1, 1, VAR_MATRIX_ID); 
								
							IF (VAR_MATRIX_ID > 0)
							THEN	
								
								IF (VAR_DEPTH_LEVEL > VAR_PARTNER_LEVEL)
								THEN
									
									CALL `levels_payments_depth_more_referal_level`(VAR_PARTNER_ID, VAR_DEPTH_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY, VAR_PARTNER_LEVEL, VAR_MATRIX_ID, VAR_PAY, 1, VAR_AFFECTED_ROWS);
									
								ELSE
										
									CALL `levels_payments_depth_lesser_referal_level`(VAR_PARTNER_ID, VAR_DEPTH_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY, VAR_PARTNER_LEVEL, VAR_MATRIX_ID, VAR_PAY, 1, VAR_AFFECTED_ROWS);

								END IF;
								
							END IF;
									
							IF VAR_AFFECTED_ROWS > 0 
							THEN

								SET VAR_RESULT = 1;
								
							END IF;
								
						END IF;
						
					END IF;
					
				END IF;
				
			END IF;
		
		END IF;
		
		IF VAR_RESULT > 0 
		THEN
		
			SET VAR_OUT_RESULT = VAR_PARTNER_ID;
			COMMIT;
		
		ELSE
			
			ROLLBACK;

		END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `binar_matrix`;
CREATE PROCEDURE `binar_matrix`(IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_PARTNER_ID` INT(10), IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX` TINYINT(8), IN `VAR_IN_DATE` VARCHAR(24) CHARSET utf8, IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_STATUS` TINYINT(8), IN `VAR_IN_CLONE` TINYINT(8), IN `VAR_IN_RESERVE` TINYINT(8), IN `VAR_IN_CLONE_SPONSOR_MATRIX_ID` INT(10), IN `VAR_IN_ROOT_MATRIX` TINYINT(8), IN `VAR_IN_CHANGE` TINYINT(8), OUT `VAR_OUT_RESULT` INT(8), OUT `VAR_OUT_RESULT2` TEXT CHARSET utf8)
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS INT(8);
	DECLARE VAR_ROLLBACK TINYINT(4);
	DECLARE VAR_STATUS TINYINT(8);
	DECLARE VAR_LAST_INSERT_ID INT(10);
	DECLARE VAR_PARTNER_LEVEL INT(10);
	DECLARE VAR_SQL VARCHAR(255);
	DECLARE VAR_MATRIX_LEVEL1 TINYINT(8);
	DECLARE VAR_MATRIX_LEVEL2 TINYINT(8);
	DECLARE VAR_ID INT(10);
	DECLARE VAR_ID2 INT(10);
	DECLARE VAR_SPONSOR_ID INT(11);
	DECLARE VAR_SPONSOR_ID2 INT(11);
	DECLARE VAR_INVITE_SPONSOR_ID INT(11);
	DECLARE VAR_SPONSOR_MATRIX_ID INT(11);
	DECLARE VAR_CHILD_MATRIX_ID INT(11);
	DECLARE VAR_PARENT_SPONSOR_ID INT(10);
	DECLARE VAR_SPONSOR_LEFT_KEY INT(10);
	DECLARE VAR_SPONSOR_RIGHT_KEY INT(10);
	DECLARE VAR_SPONSOR_LEFT_KEY2 INT(10);
	DECLARE VAR_SPONSOR_RIGHT_KEY2 INT(10);
	DECLARE VAR_CHECK_RIGHT_KEY INT(10);
	DECLARE VAR_SPONSOR_LEVEL INT(10);
	DECLARE VAR_SPONSOR_PARTNERS_COUNT TINYINT(8);
	DECLARE VAR_CHECK_PARTNERS_COUNT INT(10);
	DECLARE VAR_CHECK_MATRIX TINYINT(8);
	DECLARE VAR_MATRIX_ID INT(10);
	DECLARE VAR_LEVEL INT(10);
	DECLARE VAR_MATRIX_TYPE TINYINT(8);
	DECLARE VAR_MATRIX_LEVELS TINYINT(8);
	DECLARE VAR_MATRIX_CLONE TINYINT(8);
	DECLARE VAR_CLONE_NUMBER TINYINT(8);
	DECLARE VAR_MATRIX_NUMBER TINYINT(8);
	DECLARE VAR_MATRIX_PAY DECIMAL(10,2);
	DECLARE VAR_FIRST_MATRIX_PAY DECIMAL(10,2); 
	DECLARE VAR_MATRIX_CLONE_CYCLE TINYINT(8);
	DECLARE VAR_ADMIN_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_MATRIX_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_AUTO_PAY_OFF TINYINT(8);
	DECLARE VAR_MATRIX_POWER INT(10);
	DECLARE VAR_CLONE_ADMIN_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_CLONE_MATRIX_PAY DECIMAL(10,2);
	DECLARE VAR_CLONE_MATRIX_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_OPEN_MATRIX_BALLS INT(10);
	DECLARE VAR_CLOSE_MATRIX_BALLS INT(10);
	DECLARE VAR_CLONE_SPONSOR_MATRIX_ID INT(10);
	DECLARE VAR_INVITE_SPONSOR_ACTIVE TINYINT(8);
	DECLARE VAR_INVITE_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_REFERRAL_MATRIX_BALLS INT(10);
	DECLARE VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID INT(10);
	DECLARE VAR_ACCOUNT_TYPE TINYINT(8);
	DECLARE VAR_CLOSE_MATRIX TINYINT(8);
	DECLARE VAR_TRANS_STRUCTURE TINYINT(8);
	DECLARE VAR_TRANS_MATRIX TINYINT(8);
	DECLARE VAR_MAX_MATRIX_NUMBER INT(8);
	DECLARE VAR_CLOSE_MATRIX_PARTNER_ID INT(11);
	DECLARE VAR_I INT(8);
	DECLARE VAR_RESULT TINYINT(8);
	DECLARE VAR_RESULT2 TEXT;
	DECLARE VAR_QUERY TEXT;

	SET @VAR_AFFECTED_ROWS = 0;
	SET @VAR_STATUS = 0;
	SET @VAR_ROLLBACK = 1;
	SET @VAR_LAST_INSERT_ID = 0;
	SET @VAR_PARTNER_LEVEL = 0;
	SET @VAR_MATRIX_LEVEL1 = 2;
	SET @VAR_MATRIX_LEVEL2 = 4;
	SET @VAR_ID = 0;
	SET @VAR_ID2 = 0;
	SET @VAR_INVITE_SPONSOR_ID = 0;
	SET @VAR_SPONSOR_ID = 0;
	SET @VAR_CHILD_MATRIX_ID = 0;
	SET @VAR_SPONSOR_ID2 = 0;
	SET @VAR_PARENT_SPONSOR_ID = 0;
	SET @VAR_SPONSOR_LEFT_KEY = 0;
	SET @VAR_SPONSOR_RIGHT_KEY = 0;
	SET @VAR_SPONSOR_LEFT_KEY2 = 0;
	SET @VAR_SPONSOR_RIGHT_KEY2 = 0;
	SET @VAR_CHECK_RIGHT_KEY = 0;
	SET @VAR_SPONSOR_LEVEL = 0;
	SET @VAR_SPONSOR_PARTNERS_COUNT = 0;
	SET @VAR_CHECK_PARTNERS_COUNT = 0;
	SET @VAR_CHECK_MATRIX = 0;
	SET @VAR_MATRIX_ID = 0;
	SET @VAR_LEVEL = 0;
	SET @VAR_SPONSOR_MATRIX_ID = 0;
	SET @VAR_MATRIX_POWER = 0;
	SET @VAR_MATRIX_TYPE = 0;	
	SET @VAR_MATRIX_LEVELS = 0;
	SET @VAR_FIRST_MATRIX_PAY = 0;
	SET @VAR_CLONE_NUMBER = 0;
	SET @VAR_MATRIX_NUMBER = 0;
	SET @VAR_MATRIX_CLONE = 0;
	SET @VAR_MATRIX_CLONE_CYCLE = 0;
	SET @VAR_MATRIX_PAY = 0;
	SET @VAR_ADMIN_PAY_OFF = 0; 
	SET @VAR_AUTO_PAY_OFF = 0;
	SET @VAR_MATRIX_PAY_OFF = 0;
	SET @VAR_CLONE_MATRIX_PAY = 0;
	SET @VAR_CLONE_ADMIN_PAY_OFF = 0;
	SET @VAR_CLONE_MATRIX_PAY_OFF = 0;
	SET @VAR_OPEN_MATRIX_BALLS = 0;
	SET @VAR_CLOSE_MATRIX_BALLS = 0;
	SET @VAR_REFERRAL_MATRIX_BALLS = 0;
	SET @VAR_INVITE_SPONSOR_ACTIVE = 0;
	SET @VAR_INVITE_PAY_OFF = 0;
	SET @VAR_ACCOUNT_TYPE= 0;
	SET @VAR_CLOSE_MATRIX = 0;
	SET VAR_TRANS_STRUCTURE = 0;
	SET VAR_TRANS_MATRIX = 0;
	SET @VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID = 0;
	SET @VAR_MAX_MATRIX_NUMBER = 0;
	SET @VAR_CLOSE_MATRIX_PARTNER_ID = 0;
	SET @VAR_CLONE_SPONSOR_MATRIX_ID = 0;
	SET @VAR_I = 1;
	SET VAR_OUT_RESULT = 0;
	SET VAR_OUT_RESULT2 = '';
	SET VAR_RESULT = 0;
	SET VAR_RESULT2 = '';
	SET VAR_OUT_RESULT2 = '';
	
	SET GLOBAL max_allowed_packet = 20000000;
	SET GLOBAL max_sp_recursion_depth = 255;
	SET session max_sp_recursion_depth = 255;
	
	IF (VAR_IN_SPONSOR_ID <= 0)
	THEN
	
		SET VAR_IN_SPONSOR_ID = 1;
	
	END IF;
	
	IF VAR_IN_CLONE > 0
	THEN
	
		SELECT `matrix_id` INTO @VAR_CLONE_SPONSOR_MATRIX_ID 
		FROM `binar_matrices`
		WHERE `structure_number` = VAR_IN_STRUCTURE_NUMBER AND `matrix_number` = (VAR_IN_MATRIX + 1) AND `partner_id` = VAR_IN_PARTNER_ID AND `demo` = VAR_IN_DEMO AND `clone` = VAR_IN_CLONE
		ORDER BY `id` DESC
		LIMIT 0,1;
	
	END IF;
	
	IF (VAR_IN_RESERVE > 1)
	THEN
	
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'00',';;;;',VAR_IN_SPONSOR_ID,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_DEMO,';;;;',VAR_IN_CLONE,';;;;',@VAR_CLONE_SPONSOR_MATRIX_ID,';;;;');
		CALL `get_gold_token_sponsor_data`(VAR_IN_SPONSOR_ID, VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_DEMO, @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT); 
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'025',';;;;',@VAR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_SPONSOR_LEFT_KEY,';;;;',@VAR_SPONSOR_RIGHT_KEY,';;;;',@VAR_SPONSOR_LEVEL,';;;;',@VAR_SPONSOR_PARTNERS_COUNT,';;;;');
	
	ELSE
		
		CALL `get_matrix_settings`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_MATRIX_TYPE, @VAR_MATRIX_LEVELS, @VAR_MATRIX_CLONE, @VAR_MATRIX_CLONE_CYCLE, @VAR_MATRIX_PAY, @VAR_ADMIN_PAY_OFF, @VAR_MATRIX_PAY_OFF, @VAR_AUTO_PAY_OFF, @VAR_CLONE_MATRIX_PAY, @VAR_CLONE_ADMIN_PAY_OFF, @VAR_INVITE_PAY_OFF, @VAR_INVITE_SPONSOR_ACTIVE, @VAR_CLONE_MATRIX_PAY_OFF, @VAR_OPEN_MATRIX_BALLS, @VAR_CLOSE_MATRIX_BALLS, @VAR_REFERRAL_MATRIX_BALLS, @VAR_ACCOUNT_TYPE, @VAR_CLOSE_MATRIX, @VAR_TRANS_STRUCTURE, @VAR_TRANS_MATRIX); 
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'067',';;;;',VAR_IN_STRUCTURE_NUMBER,';;;;',VAR_IN_MATRIX,';;;;',@VAR_MATRIX_TYPE,';;;;',@VAR_MATRIX_LEVELS,';;;;',@VAR_MATRIX_CLONE,';;;;',@VAR_MATRIX_CLONE_CYCLE,';;;;',@VAR_MATRIX_PAY,';;;;',@VAR_ADMIN_PAY_OFF,';;;;',@VAR_MATRIX_PAY_OFF,';;;;',@VAR_INVITE_PAY_OFF,';;;;',@VAR_INVITE_SPONSOR_ACTIVE,';;;;',@VAR_OPEN_MATRIX_BALLS,';;;;',@VAR_CLOSE_MATRIX_BALLS,';;;;',@VAR_REFERRAL_MATRIX_BALLS,';;;;',@VAR_ACCOUNT_TYPE,';;;;',@VAR_CLOSE_MATRIX,';;;;',@VAR_TRANS_STRUCTURE,';;;;',@VAR_TRANS_MATRIX,';;;;');
		
		IF (@VAR_MATRIX_LEVELS > 0)
		THEN
	
			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'00',';;;;',VAR_IN_SPONSOR_ID,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_DEMO,';;;;',VAR_IN_CLONE,';;;;',@VAR_CLONE_SPONSOR_MATRIX_ID,';;;;',VAR_IN_ROOT_MATRIX,';;;;');
			CALL `get_sponsor_data`(VAR_IN_SPONSOR_ID, VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_DEMO, VAR_IN_CLONE, VAR_IN_ROOT_MATRIX, @VAR_MATRIX_LEVELS, @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT); 
			/*SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'01',';;;;',@VAR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_PARENT_SPONSOR_ID,';;;;',@VAR_SPONSOR_LEFT_KEY,';;;;',@VAR_SPONSOR_RIGHT_KEY,';;;;',@VAR_SPONSOR_LEVEL,';;;;',@VAR_SPONSOR_PARTNERS_COUNT,';;;;');*/
			
			IF (@VAR_SPONSOR_LEFT_KEY <= 0 || @VAR_SPONSOR_RIGHT_KEY <= 0)
			THEN
		
				SET @VAR_SPONSOR_RIGHT_KEY2 = 0;
				SET @VAR_SPONSOR_LEFT_KEY2 = 0;
				SET @VAR_ID = 0;
				SET @VAR_SPONSOR_ID = 0;	
				SET @VAR_SPONSOR_ID2 = VAR_IN_SPONSOR_ID;	
				SET @VAR_SPONSOR_LEVEL = 0;
				SET @VAR_SPONSOR_PARTNERS_COUNT = 0;		
				SET @VAR_SPONSOR_LEFT_KEY = 0;
				SET @VAR_SPONSOR_RIGHT_KEY = 0;
			
				IF (VAR_IN_CLONE > 0) && (VAR_IN_SPONSOR_ID = VAR_IN_PARTNER_ID) && (VAR_IN_PARTNER_ID != 1)
				THEN
				
					SELECT `sponsor_id` INTO @VAR_SPONSOR_ID2 
					FROM `partners`
					WHERE `id` = VAR_IN_PARTNER_ID;
				
				END IF;
				
				SELECT `left_key`, `right_key` INTO @VAR_SPONSOR_LEFT_KEY2, @VAR_SPONSOR_RIGHT_KEY2
				FROM `partners`
				WHERE `id` = @VAR_SPONSOR_ID2;
				
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',@VAR_SPONSOR_ID2,';;;;',VAR_IN_MATRIX,';;;;',@VAR_SPONSOR_LEFT_KEY2,';;;;',@VAR_SPONSOR_RIGHT_KEY2,';;;;',VAR_IN_CLONE,';;;;');
				
				IF (@VAR_SPONSOR_LEFT_KEY2 > 0 || @VAR_SPONSOR_RIGHT_KEY2 > 0)
				THEN
				
					CALL `get_parent_sponsor_data`(@VAR_SPONSOR_ID2, VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_SPONSOR_LEFT_KEY2, @VAR_SPONSOR_RIGHT_KEY2, VAR_IN_DEMO, VAR_IN_CLONE, @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT);
					
				END IF;
				
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'03',';;;;',@VAR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_SPONSOR_LEFT_KEY,';;;;',@VAR_SPONSOR_RIGHT_KEY,';;;;',@VAR_SPONSOR_LEVEL,';;;;',@VAR_SPONSOR_PARTNERS_COUNT,';;;;');
				
			END IF;
		
		END IF;
		
	END IF;	
		
	IF (@VAR_ID > 0 && @VAR_SPONSOR_ID > 0 && @VAR_SPONSOR_LEFT_KEY > 0 && @VAR_SPONSOR_RIGHT_KEY && @VAR_SPONSOR_LEVEL > 0)
	THEN	
		
		IF (@VAR_SPONSOR_PARTNERS_COUNT >= @VAR_MATRIX_LEVEL1)
		THEN
			
			SET @VAR_ID = 0;
			SET @VAR_SPONSOR_ID = 0;		
			SET @VAR_SPONSOR_LEVEL = 0;
			SET @VAR_SPONSOR_PARTNERS_COUNT = 0;		
			SET @VAR_SPONSOR_RIGHT_KEY2 = @VAR_SPONSOR_RIGHT_KEY;
			SET @VAR_SPONSOR_LEFT_KEY2 = @VAR_SPONSOR_LEFT_KEY;
			SET @VAR_SPONSOR_LEFT_KEY = 0;
			SET @VAR_SPONSOR_RIGHT_KEY = 0;
			
			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'04',';;;;',VAR_IN_MATRIX,';;;;',@VAR_MATRIX_LEVEL1,';;;;',@VAR_SPONSOR_LEFT_KEY2,';;;;',@VAR_SPONSOR_RIGHT_KEY2,';;;;',VAR_IN_DEMO,';;;;');
			
			CALL `get_child_sponsor_data`(VAR_IN_MATRIX, VAR_IN_STRUCTURE_NUMBER, @VAR_MATRIX_LEVEL1, @VAR_SPONSOR_LEFT_KEY2, @VAR_SPONSOR_RIGHT_KEY2, VAR_IN_DEMO, VAR_IN_CLONE, @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT); 
			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'05',';;;;',@VAR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_SPONSOR_LEFT_KEY,';;;;',@VAR_SPONSOR_RIGHT_KEY,';;;;',@VAR_SPONSOR_LEVEL,';;;;',@VAR_SPONSOR_PARTNERS_COUNT,';;;;');
			
		END IF;
		
		IF (@VAR_SPONSOR_LEFT_KEY > 0 && @VAR_SPONSOR_RIGHT_KEY > 0 && @VAR_ID > 0)
		THEN
			
			CALL `update_matrix_keys_in_structure`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_SPONSOR_RIGHT_KEY, VAR_IN_DEMO, @VAR_AFFECTED_ROWS); 
			
			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'05',';;;;',@VAR_AFFECTED_ROWS,';;;;');
				
			IF @VAR_AFFECTED_ROWS > 0 
			THEN
				
				SET @VAR_AFFECTED_ROWS = 0;
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',VAR_IN_MATRIX,';;;;',@VAR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',VAR_IN_PARTNER_ID,';;;;',@VAR_SPONSOR_RIGHT_KEY,';;;;',@VAR_SPONSOR_LEVEL,';;;;',VAR_IN_DEMO,';;;;',VAR_IN_CLONE,';;;;',VAR_IN_CHANGE,';;;;',VAR_IN_CLONE_SPONSOR_MATRIX_ID,';;;;');
			
				CALL `set_partner_data_in_matrix`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_ID, @VAR_SPONSOR_ID, VAR_IN_PARTNER_ID, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, VAR_IN_DEMO, VAR_IN_CLONE, @VAR_CLONE_SPONSOR_MATRIX_ID, VAR_IN_CHANGE, @VAR_AFFECTED_ROWS);
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',@VAR_AFFECTED_ROWS,';;;;');
								
				IF @VAR_AFFECTED_ROWS > 0 
				THEN
				
					CALL `get_last_id`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_DEMO, @VAR_LAST_INSERT_ID, @VAR_PARTNER_LEVEL); 
				
					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',@VAR_LAST_INSERT_ID,';;;;',@VAR_PARTNER_LEVEL,';;;;');
				
					IF @VAR_LAST_INSERT_ID > 0 
					THEN
						
						SET @VAR_AFFECTED_ROWS = 0;
						
						CALL `get_check_matrix`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_PARTNER_ID, VAR_IN_DEMO, @VAR_CHECK_MATRIX); 
						
						IF (@VAR_CHECK_MATRIX < VAR_IN_MATRIX)
						THEN
						
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'07',';;;;',@VAR_CHECK_MATRIX,';;;;');
							SET @VAR_AFFECTED_ROWS = 0;
						
							CALL `update_partner_matrix_number`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_DEMO, VAR_IN_MATRIX, VAR_IN_PARTNER_ID, @VAR_AFFECTED_ROWS);
							
							IF @VAR_AFFECTED_ROWS <= 0 
							THEN
						
								SET @VAR_ROLLBACK = 0;
			
							END IF;
					
						END IF;
						
						IF (@VAR_ROLLBACK > 0)
						THEN	
							
							IF (VAR_IN_MATRIX = 1)
							THEN
							
								SET @VAR_AFFECTED_ROWS = 0;
										
								CALL `set_matrix_payments`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_LAST_INSERT_ID, VAR_IN_PARTNER_ID, VAR_IN_PARTNER_ID, 1, @VAR_MATRIX_PAY, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'10',';;;;',@VAR_AFFECTED_ROWS,';;;;');
								
								IF @VAR_AFFECTED_ROWS <= 0 
								THEN
							
									SET @VAR_ROLLBACK = 0;
				
								END IF;
							
							END IF;
							
							IF (@VAR_ROLLBACK > 0)
							THEN
							
								IF (@VAR_MATRIX_PAY > 0)
								THEN
								
									IF (@VAR_OPEN_MATRIX_BALLS > 0)
									THEN
																						
										SET @VAR_AFFECTED_ROWS = 0;
																							
										CALL `set_matrix_balls`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_LAST_INSERT_ID, VAR_IN_PARTNER_ID, VAR_IN_PARTNER_ID, 2, 1, @VAR_OPEN_MATRIX_BALLS, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
										SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'40',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																							
										IF @VAR_AFFECTED_ROWS > 0 
										THEN
																								
											SET @VAR_AFFECTED_ROWS = 0;
																								
											CALL `update_total_balls`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_PARTNER_ID, @VAR_OPEN_MATRIX_BALLS, VAR_IN_DEMO, '+', @VAR_AFFECTED_ROWS); 
											SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'41',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																									
											IF @VAR_AFFECTED_ROWS <= 0 
											THEN
																										
												SET @VAR_ROLLBACK = 0;
																					
											END IF;
																									
										ELSE
														
											SET @VAR_ROLLBACK = 0;		
																						
										END IF;
																						
									END IF;
									
									IF (@VAR_ROLLBACK > 0)
									THEN
									
										IF (@VAR_INVITE_PAY_OFF > 0)
										THEN
										
											IF (VAR_IN_SPONSOR_ID = VAR_IN_PARTNER_ID)
											THEN
											
												IF (VAR_IN_PARTNER_ID = 1)
												THEN
											
													SET @VAR_INVITE_SPONSOR_ID = 1;
												
												ELSE
												
													SELECT `sponsor_id` INTO @VAR_INVITE_SPONSOR_ID
													FROM `partners`
													WHERE `id` = VAR_IN_PARTNER_ID;
												
												END IF;
											
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'098',';;;;',@VAR_INVITE_SPONSOR_ID,';;;;');
											
											ELSE
											
												SET @VAR_INVITE_SPONSOR_ID = VAR_IN_SPONSOR_ID;
												
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'099',';;;;',@VAR_INVITE_SPONSOR_ID,';;;;');
										
											END IF;
											
											SET @VAR_AFFECTED_ROWS = 0;
											SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'09',';;;;',VAR_IN_STRUCTURE_NUMBER,';;;;',VAR_IN_MATRIX,';;;;',@VAR_INVITE_SPONSOR_ID,';;;;',@VAR_INVITE_SPONSOR_ACTIVE,';;;;',VAR_IN_PARTNER_ID,';;;;',@VAR_INVITE_PAY_OFF,';;;;',VAR_IN_DEMO,';;;;');
											
											CALL `set_invite_pay_off`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_LAST_INSERT_ID, @VAR_INVITE_SPONSOR_ID, @VAR_INVITE_SPONSOR_ACTIVE, VAR_IN_PARTNER_ID, @VAR_INVITE_PAY_OFF, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
											
											SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'10',';;;;',@VAR_AFFECTED_ROWS,';;;;');
												
											IF @VAR_AFFECTED_ROWS > 0 
											THEN
												
												SET @VAR_AFFECTED_ROWS = 0;
												
												CALL `update_total_amount`(VAR_IN_STRUCTURE_NUMBER, @VAR_INVITE_SPONSOR_ID, @VAR_INVITE_PAY_OFF, VAR_IN_DEMO, '+', @VAR_AFFECTED_ROWS); 
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'555',';;;;',@VAR_AFFECTED_ROWS,';;;;');
													
												IF @VAR_AFFECTED_ROWS <= 0 
												THEN
																							
													SET @VAR_ROLLBACK = 0;
													
												ELSE
												
													IF (VAR_IN_DEMO = 0 && @VAR_AUTO_PAY_OFF > 0)
													THEN
													
														SET @VAR_AFFECTED_ROWS = 0;
																									
														INSERT INTO `auto_pay_off_logs` SET `structure_number` = VAR_IN_STRUCTURE_NUMBER, `matrix_number` = VAR_IN_MATRIX,  `matrix_id` = @VAR_LAST_INSERT_ID, `partner_id` = @VAR_INVITE_SPONSOR_ID, `type` = 2, `amount` = @VAR_INVITE_PAY_OFF, `created_at` = UNIX_TIMESTAMP();
																									
														SELECT ROW_COUNT() INTO @VAR_AFFECTED_ROWS;
														
														IF @VAR_AFFECTED_ROWS <= 0 
														THEN
																									
															SET @VAR_ROLLBACK = 0;
															
														END IF;
													
													ELSE
													
														SET @VAR_ROLLBACK = 1;
														
													END IF;
																		
												END IF;
												
											ELSE
												
												SET @VAR_ROLLBACK = 0;
																	
											END IF;
												
										END IF;
									
									END IF;
									
									IF (@VAR_ROLLBACK > 0)
									THEN
										
										/*IF (VAR_IN_MATRIX = 1 && VAR_IN_RESERVE > 0 && @VAR_ACCOUNT_TYPE = 2)
										THEN
										
											SET @VAR_AFFECTED_ROWS = 0;
										
											CALL `set_matrix_payments`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_ID, VAR_IN_PARTNER_ID, VAR_IN_PARTNER_ID, 1, @VAR_MATRIX_PAY, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
											SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'10',';;;;',@VAR_AFFECTED_ROWS,';;;;');
												
										END IF;*/
										
										IF (@VAR_ROLLBACK > 0)
										THEN
										
											SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'11',';;;;',@VAR_ID,';;;;',@VAR_PARTNER_LEVEL,';;;;');
												
												SET @VAR_I = 0;
												SET @VAR_CHILD_MATRIX_ID = @VAR_LAST_INSERT_ID;
														
												sponsor_levels: LOOP
														
												SET @VAR_I = @VAR_I + 1;
															 
												SET @VAR_SPONSOR_ID = 0;
												SET @VAR_MATRIX_ID = 0;
												SET @VAR_CHECK_PARTNERS_COUNT = 0;
															
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'12',';;;;',VAR_IN_STRUCTURE_NUMBER,';;;;',VAR_IN_MATRIX,';;;;',@VAR_ID,';;;;',@VAR_CHILD_MATRIX_ID,';;;;',VAR_IN_PARTNER_ID,';;;;');
												CALL `get_sponsor_partners_count`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_DEMO, @VAR_CHILD_MATRIX_ID, 2, @VAR_SPONSOR_ID, @VAR_LEVEL, @VAR_MATRIX_ID, @VAR_CHECK_PARTNERS_COUNT);
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,CONCAT('13_', @VAR_I),';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_LEVEL,';;;;',@VAR_MATRIX_ID,';;;;',@VAR_CHECK_PARTNERS_COUNT,';;;;');
															
												IF (@VAR_MATRIX_ID > 0)
												THEN
																
													SET @VAR_AFFECTED_ROWS = 1;
																		
													CALL `update_sponsor_level_in_matrix`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_MATRIX_ID, @VAR_I, VAR_IN_DEMO, @VAR_AFFECTED_ROWS); 
													SELECT @p1 INTO `VAR_AFFECTED_ROWS`;
															
													SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'14_', @VAR_I,';;;;',@VAR_AFFECTED_ROWS,';;;;');
																
													IF @VAR_AFFECTED_ROWS > 0 
													THEN
															
														SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'15_', @VAR_I,';;;;',@VAR_AFFECTED_ROWS,';;;;');
																			
														IF (@VAR_ACCOUNT_TYPE = 2)
														THEN
																				
															IF (@VAR_MATRIX_PAY_OFF > 0)
															THEN
																														
																SET @VAR_AFFECTED_ROWS = 0;
																														
																IF (VAR_IN_CLONE > 0)
																THEN
																												
																	SET @VAR_MATRIX_PAY_OFF = @VAR_CLONE_MATRIX_PAY_OFF;
																								
																END IF;
																	
																IF (@VAR_MATRIX_PAY_OFF > 0)
																THEN	
																					
																	CALL `set_matrix_payments`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_MATRIX_ID, @VAR_SPONSOR_ID, VAR_IN_PARTNER_ID, 2, @VAR_MATRIX_PAY_OFF, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
																	SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'16_', @VAR_I,';;;;',VAR_IN_STRUCTURE_NUMBER,';;;;',VAR_IN_MATRIX,';;;;',@VAR_MATRIX_ID,';;;;',@VAR_SPONSOR_ID,';;;;',VAR_IN_PARTNER_ID,';;;;',@VAR_MATRIX_PAY_OFF,';;;;',@VAR_AFFECTED_ROWS,';;;;');
																															
																	IF @VAR_AFFECTED_ROWS > 0 
																	THEN
																															
																		SET @VAR_AFFECTED_ROWS = 0;
																															
																		CALL `update_total_amount`(VAR_IN_STRUCTURE_NUMBER, @VAR_SPONSOR_ID, @VAR_MATRIX_PAY_OFF, VAR_IN_DEMO, '+', @VAR_AFFECTED_ROWS); 
																		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'17_', @VAR_I,';;;;',@VAR_AFFECTED_ROWS,';;;;');
																																
																		IF @VAR_AFFECTED_ROWS <= 0 
																		THEN
																																
																			SET @VAR_ROLLBACK = 0;
																			LEAVE sponsor_levels;
																											
																		END IF;
																							
																	ELSE
														
																		SET @VAR_ROLLBACK = 0;
																		LEAVE sponsor_levels;
																									
																	END IF;
																
																END IF;
																												
															END IF;
																				
														END IF;
												
													ELSE
														
														SET @VAR_ROLLBACK = 0;
														LEAVE sponsor_levels;
																
													END IF;
															
												ELSE
															
													SET @VAR_ROLLBACK = 1;
													LEAVE sponsor_levels;
																	
												END IF;
															
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'235',';;;;',@VAR_MATRIX_ID,';;;;','235;;;;');
												SET @VAR_CHILD_MATRIX_ID = @VAR_MATRIX_ID;
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'234',';;;;',@VAR_SPONSOR_LEVEL,';;;;',@VAR_MATRIX_LEVELS,';;;;',@VAR_I,';;;;',@VAR_CHILD_MATRIX_ID,';;;;','234;;;;');
															
												IF @VAR_SPONSOR_LEVEL <= @VAR_MATRIX_LEVELS 
												THEN
																
													IF @VAR_I = @VAR_SPONSOR_LEVEL
													THEN
																 
														LEAVE sponsor_levels;
																	 
													END IF;
															
												ELSE
															 
												IF @VAR_I = @VAR_MATRIX_LEVELS 
												THEN
																 
													LEAVE sponsor_levels;
																	 
												END IF;
															
											END IF;	
																
												END LOOP sponsor_levels;
													
											
													
										END IF;
													
										IF (@VAR_ROLLBACK > 0)
										THEN
											
											IF (@VAR_ACCOUNT_TYPE = 2)
											THEN
																				
												CALL `get_admin_pay_off`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_CLONE, @VAR_ADMIN_PAY_OFF); 
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'18',';;;;',@VAR_ADMIN_PAY_OFF,';;;;');
													
												IF (@VAR_ADMIN_PAY_OFF > 0)
												THEN
																									
													SET @VAR_AFFECTED_ROWS = 0;
													SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'199',';;;;',@VAR_ADMIN_PAY_OFF,';;;;');
																										
													CALL `set_admin_pay_off`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_PARTNER_ID, @VAR_MATRIX_ID, @VAR_ADMIN_PAY_OFF, VAR_IN_DEMO, @VAR_AFFECTED_ROWS); 
																										
													SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'200',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																											
													IF @VAR_AFFECTED_ROWS <= 0 
													THEN
																											
														SET @VAR_ROLLBACK = 0;
																						
													END IF;
																									
												END IF;
												
											END IF;
																						
										END IF;
													
										IF (@VAR_ROLLBACK > 0)
										THEN
													
											IF (VAR_IN_RESERVE > 0)
											THEN
														
												IF (@VAR_REFERRAL_MATRIX_BALLS > 0)
												THEN
															
													SELECT `sponsor_id` INTO @VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID
													FROM `partners`
													WHERE `id` = VAR_IN_PARTNER_ID;
																	
													SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'17',';;;;',@VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID,';;;;');
																	
													IF (@VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID > 0)
													THEN
																	
														SET @VAR_AFFECTED_ROWS = 0;
																						
														CALL `set_matrix_balls`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_ID, @VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID, VAR_IN_PARTNER_ID, 1, 0, @VAR_REFERRAL_MATRIX_BALLS, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
														SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'18',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																					
														IF @VAR_AFFECTED_ROWS > 0 
														THEN
																							
															SET @VAR_AFFECTED_ROWS = 0;
																							
															CALL `update_total_balls`(VAR_IN_STRUCTURE_NUMBER, @VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID, @VAR_REFERRAL_MATRIX_BALLS, VAR_IN_DEMO, '+', @VAR_AFFECTED_ROWS); 
															SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'19',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																								
															IF @VAR_AFFECTED_ROWS <= 0 
															THEN
																								
																SET @VAR_ROLLBACK = 0;
																			
															END IF;
																								
														ELSE
													
															SET @VAR_ROLLBACK = 0;
																		
														END IF;
															
													END IF;
													
												END IF;
															
											END IF;
													
										END IF;
													
										IF (@VAR_ROLLBACK > 0)
										THEN
															
											IF (VAR_IN_STATUS > 0)
											THEN
													
												SELECT `status` INTO @VAR_STATUS
												FROM `partners`
												WHERE `id` = VAR_IN_PARTNER_ID;
															
												IF (VAR_IN_STATUS > @VAR_STATUS)
												THEN
																	
													SET @VAR_AFFECTED_ROWS = 0;
																	
													UPDATE `partners` SET `status` = VAR_IN_STATUS
													WHERE `id` = VAR_IN_PARTNER_ID;
																
													SELECT ROW_COUNT() INTO @VAR_AFFECTED_ROWS;
																	
													IF @VAR_AFFECTED_ROWS <= 0 
													THEN
																		
														SET @VAR_ROLLBACK = 0;
																	
													END IF;
																
												END IF;
													
											END IF;
														
											IF (@VAR_ROLLBACK > 0)
											THEN
												
												IF (@VAR_MATRIX_LEVELS > 0 && @VAR_MATRIX_TYPE > 1)
												THEN
																
													SET @VAR_MATRIX_POWER = POW(2,@VAR_MATRIX_LEVELS);
																		
													IF (@VAR_MATRIX_LEVELS > 1)
													THEN
																			
														SET @VAR_SPONSOR_MATRIX_ID = @VAR_MATRIX_ID;
																			
													ELSE
																			
														SET @VAR_SPONSOR_MATRIX_ID = @VAR_ID;
															
													END IF;
															
													IF (@VAR_SPONSOR_MATRIX_ID > 0)
													THEN	
																			
														SET @VAR_CHECK_PARTNERS_COUNT = 0;
														SET @VAR_SPONSOR_ID = 0;
																				
														CALL `get_matrix_data_by_id`(VAR_IN_STRUCTURE_NUMBER, @VAR_SPONSOR_MATRIX_ID, VAR_IN_MATRIX, @VAR_MATRIX_LEVELS, VAR_IN_DEMO, @VAR_SPONSOR_ID, @VAR_CHECK_PARTNERS_COUNT, @VAR_CLONE_NUMBER); 
														SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'20',';;;;',VAR_IN_STRUCTURE_NUMBER,';;;;',@VAR_SPONSOR_MATRIX_ID,';;;;',VAR_IN_MATRIX,';;;;',@VAR_MATRIX_LEVELS,';;;;',VAR_IN_DEMO,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_CHECK_PARTNERS_COUNT,';;;;',@VAR_CLONE_NUMBER,';;;;');
														SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'21',';;;;',@VAR_CHECK_PARTNERS_COUNT,';;;;',@VAR_MATRIX_POWER,';;;;');
																				
														IF (@VAR_CHECK_PARTNERS_COUNT = @VAR_MATRIX_POWER)
														THEN
																
															SET @VAR_AFFECTED_ROWS = 0;
																					
															CALL `close_matrix`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_SPONSOR_MATRIX_ID, VAR_IN_DEMO, @VAR_AFFECTED_ROWS); 
															SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'22',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																				
															IF @VAR_AFFECTED_ROWS > 0 
															THEN
																				
																IF (@VAR_ACCOUNT_TYPE = 1)
																THEN
																	
																	SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'222',';;;;',@VAR_MATRIX_PAY_OFF,';;;;');					
																	
																	IF (@VAR_MATRIX_PAY_OFF > 0)
																	THEN
																						
																		SET @VAR_AFFECTED_ROWS = 0;
																									
																		IF (VAR_IN_CLONE > 0)
																		THEN
																							
																			SET @VAR_MATRIX_PAY_OFF = @VAR_CLONE_MATRIX_PAY_OFF;
																				
																		END IF;
																		
																		IF (@VAR_MATRIX_PAY_OFF > 0)
																		THEN
																				
																			CALL `set_matrix_payments`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_SPONSOR_MATRIX_ID, @VAR_SPONSOR_ID, VAR_IN_PARTNER_ID, 2, @VAR_MATRIX_PAY_OFF, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
																			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'223',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																											
																			IF @VAR_AFFECTED_ROWS > 0 
																			THEN
																											
																				SET @VAR_AFFECTED_ROWS = 0;
																				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'233',';;;;',VAR_IN_STRUCTURE_NUMBER,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_MATRIX_PAY_OFF,';;;;',VAR_IN_DEMO,';;;;');
																											
																				CALL `update_total_amount`(VAR_IN_STRUCTURE_NUMBER, @VAR_SPONSOR_ID, @VAR_MATRIX_PAY_OFF, VAR_IN_DEMO, '+', @VAR_AFFECTED_ROWS); 
																				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'23',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																												
																				IF @VAR_AFFECTED_ROWS <= 0 
																				THEN
																												
																					SET @VAR_ROLLBACK = 0;
																						
																				ELSE
																					
																					IF (VAR_IN_DEMO = 0 && @VAR_AUTO_PAY_OFF > 0)
																					THEN
																						
																						SET @VAR_AFFECTED_ROWS = 0;
																																		
																						INSERT INTO `auto_pay_off_logs` SET `structure_number` = VAR_IN_STRUCTURE_NUMBER, `matrix_number` = VAR_IN_MATRIX,  `matrix_id` = @VAR_SPONSOR_MATRIX_ID, `partner_id` = @VAR_SPONSOR_ID, `type` = 1, `amount` = @VAR_MATRIX_PAY_OFF, `created_at` = UNIX_TIMESTAMP();
																																	
																						SELECT ROW_COUNT() INTO @VAR_AFFECTED_ROWS;
																						
																						IF @VAR_AFFECTED_ROWS <= 0 
																						THEN
																																		
																							SET @VAR_ROLLBACK = 0;
																								
																						END IF;
																						
																					ELSE
																						
																						SET @VAR_ROLLBACK = 1;
																							
																					END IF;
																						
																				END IF;
																											
																			ELSE
																
																				SET @VAR_ROLLBACK = 0;
																						
																			END IF;
																				
																		END IF;
																	
																	END IF;
																							
																	IF (@VAR_ROLLBACK > 0)
																	THEN
																							
																		CALL `get_admin_pay_off`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_CLONE, @VAR_ADMIN_PAY_OFF); 
																		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'24',';;;;',@VAR_ADMIN_PAY_OFF,';;;;');
													
																		IF (@VAR_ADMIN_PAY_OFF > 0)
																		THEN
																								
																			SET @VAR_AFFECTED_ROWS = 0;
																			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'240',';;;;',@VAR_ADMIN_PAY_OFF,';;;;');
																										
																			CALL `set_admin_pay_off`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_SPONSOR_ID, @VAR_SPONSOR_MATRIX_ID, @VAR_ADMIN_PAY_OFF, VAR_IN_DEMO, @VAR_AFFECTED_ROWS); 
																										
																			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'250',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																											
																			IF @VAR_AFFECTED_ROWS <= 0 
																			THEN
																											
																				SET @VAR_ROLLBACK = 0;
																						
																			END IF;
																								
																		END IF;
																							
																	END IF;
																		
																	IF (@VAR_ROLLBACK > 0)
																	THEN
																								
																		IF (VAR_IN_MATRIX = 1)
																		THEN
																							
																			SELECT `sponsor_id` INTO @VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID
																			FROM `partners`
																			WHERE `id` = VAR_IN_PARTNER_ID;
																								
																			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'26',';;;;',@VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID,';;;;');
																								
																			IF (@VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID > 0)
																			THEN
																									
																				CALL `get_referral_balls`(VAR_IN_STRUCTURE_NUMBER, (VAR_IN_MATRIX + 1), @VAR_REFERRAL_MATRIX_BALLS); 
																									
																				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'27',';;;;',@VAR_REFERRAL_MATRIX_BALLS,';;;;');
																									
																				IF (@VAR_REFERRAL_MATRIX_BALLS > 0)
																				THEN
																								
																					SET @VAR_AFFECTED_ROWS = 0;
																														
																					CALL `set_matrix_balls`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_SPONSOR_MATRIX_ID, @VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID, VAR_IN_PARTNER_ID, 1, 0, @VAR_REFERRAL_MATRIX_BALLS, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
																					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'28',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																														
																					IF @VAR_AFFECTED_ROWS > 0 
																					THEN
																															
																						SET @VAR_AFFECTED_ROWS = 0;
																															
																						CALL `update_total_balls`(VAR_IN_STRUCTURE_NUMBER, @VAR_REFERRAL_MATRIX_BALLS_PARTNER_ID, @VAR_REFERRAL_MATRIX_BALLS, VAR_IN_DEMO, '+', @VAR_AFFECTED_ROWS); 
																						SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'29',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																																
																						IF @VAR_AFFECTED_ROWS <= 0 
																						THEN
																																
																							SET @VAR_ROLLBACK = 0;
																											
																						END IF;
																																
																					ELSE
																				
																						SET @VAR_ROLLBACK = 0;
																									
																					END IF;
																								
																				END IF;
																							
																			END IF;
																								
																		END IF;
																							
																	END IF;
																		
																	IF (@VAR_ROLLBACK > 0)
																	THEN
																					
																		IF (@VAR_CLOSE_MATRIX_BALLS > 0)
																		THEN
																						
																			SET @VAR_AFFECTED_ROWS = 0;
																							
																			CALL `set_matrix_balls`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_SPONSOR_MATRIX_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_ID, 2, 2, @VAR_CLOSE_MATRIX_BALLS, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
																			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'30',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																							
																				IF @VAR_AFFECTED_ROWS > 0 
																				THEN
																								
																					SET @VAR_AFFECTED_ROWS = 0;
																								
																					CALL `update_total_balls`(VAR_IN_STRUCTURE_NUMBER, @VAR_SPONSOR_ID, @VAR_CLOSE_MATRIX_BALLS, VAR_IN_DEMO, '+', @VAR_AFFECTED_ROWS); 
																					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'31',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																									
																					IF @VAR_AFFECTED_ROWS <= 0 
																					THEN
																										
																						SET @VAR_ROLLBACK = 0;
																					
																					END IF;
																									
																				ELSE
														
																					SET @VAR_ROLLBACK = 0;
																			
																				END IF;
																						
																			END IF;
																						
																		END IF;
																			
																		IF (@VAR_ROLLBACK > 0)
																		THEN
																						
																			IF (@VAR_SPONSOR_ID = 1)
																			THEN
																								
																				SET @VAR_PARENT_SPONSOR_ID = 1;
																								
																			ELSE
																							
																				SELECT `sponsor_id` INTO @VAR_PARENT_SPONSOR_ID 
																				FROM `partners`
																				WHERE `id` = @VAR_SPONSOR_ID;
																							
																			END IF;
																			
																			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'32',';;;;',@VAR_PARENT_SPONSOR_ID,';;;;',@VAR_SPONSOR_ID,';;;;');
																						
																			CALL `get_max_matrix_number`(VAR_IN_STRUCTURE_NUMBER, @VAR_MAX_MATRIX_NUMBER);
																						
																			SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'33',';;;;',@VAR_MAX_MATRIX_NUMBER,';;;;',';;;;',VAR_IN_MATRIX,';;;;');
																			
																			IF (@VAR_MAX_MATRIX_NUMBER > VAR_IN_MATRIX)
																			THEN
																			
																				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'46',';;;;',@VAR_MAX_MATRIX_NUMBER,';;;;');
																				
																				SET @VAR_MATRIX_TYPE = 0;
																				SET @VAR_MATRIX_LEVELS = 0;
																				SET @VAR_MATRIX_CLONE = 0;
																				SET @VAR_CLONE_MATRIX_PAY = 0;
																				SET @VAR_MATRIX_CLONE_CYCLE = 0;
																				SET @VAR_MATRIX_PAY = 0;
																				SET @VAR_MATRIX_PAY_OFF = 0;
																				SET @VAR_ADMIN_PAY_OFF = 0;
																				SET @VAR_CLONE_ADMIN_PAY_OFF = 0;
																				SET @VAR_CLONE_MATRIX_PAY_OFF = 0;
																				SET @VAR_CLOSE_MATRIX_BALLS = 0;
																				SET @VAR_REFERRAL_MATRIX_BALLS = 0;
																						
																				CALL `get_matrix_settings`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_MATRIX_TYPE, @VAR_MATRIX_LEVELS, @VAR_MATRIX_CLONE, @VAR_MATRIX_CLONE_CYCLE, @VAR_MATRIX_PAY, @VAR_ADMIN_PAY_OFF, @VAR_MATRIX_PAY_OFF, @VAR_AUTO_PAY_OFF, @VAR_CLONE_MATRIX_PAY, @VAR_CLONE_ADMIN_PAY_OFF, @VAR_INVITE_PAY_OFF, @VAR_INVITE_SPONSOR_ACTIVE, @VAR_CLONE_MATRIX_PAY_OFF, @VAR_OPEN_MATRIX_BALLS, @VAR_CLOSE_MATRIX_BALLS, @VAR_REFERRAL_MATRIX_BALLS, @VAR_ACCOUNT_TYPE, @VAR_CLOSE_MATRIX, @VAR_TRANS_STRUCTURE, @VAR_TRANS_MATRIX);
																				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'34',';;;;',@VAR_MATRIX_TYPE,';;;;',@VAR_MATRIX_LEVELS,';;;;',@VAR_MATRIX_CLONE,';;;;',@VAR_MATRIX_CLONE_CYCLE,';;;;',@VAR_MATRIX_PAY,';;;;',@VAR_ADMIN_PAY_OFF,';;;;',@VAR_MATRIX_PAY_OFF,';;;;',@VAR_OPEN_MATRIX_BALLS,';;;;',@VAR_CLOSE_MATRIX_BALLS,';;;;',@VAR_REFERRAL_MATRIX_BALLS,';;;;',@VAR_ACCOUNT_TYPE,';;;;',@VAR_CLOSE_MATRIX,';;;;',@VAR_TRANS_STRUCTURE,';;;;',@VAR_TRANS_MATRIX,';;;;');
																							
																				IF (@VAR_MATRIX_TYPE > 1)
																				THEN
																							
																					SET @VAR_AFFECTED_ROWS = 0;
																								
																					INSERT INTO `binar_matrices` SET `structure_number` = VAR_IN_STRUCTURE_NUMBER, `matrix_number` = (VAR_IN_MATRIX + 1),  `matrix_id` = @VAR_SPONSOR_MATRIX_ID, `parent_sponsor_id` = @VAR_PARENT_SPONSOR_ID, `partner_id` = @VAR_SPONSOR_ID, `demo` = VAR_IN_DEMO, `clone` = @VAR_MATRIX_CLONE;
																								
																					SELECT ROW_COUNT() INTO @VAR_AFFECTED_ROWS;
																								
																					IF @VAR_AFFECTED_ROWS > 0 
																					THEN
																									
																						IF @VAR_CLONE_NUMBER = 0  
																						THEN
																						
																							IF (@VAR_CLOSE_MATRIX > 0)
																							THEN
																											
																								SET @VAR_AFFECTED_ROWS = 0;
																								SET @VAR_MATRIX_NUMBER = (VAR_IN_MATRIX + 1);
																								
																								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'35',';;;;',@VAR_PARENT_SPONSOR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_MATRIX_NUMBER,';;;;',VAR_IN_DEMO,';;;;',@VAR_MATRIX_CLONE,';;;;',@VAR_SPONSOR_MATRIX_ID,';;;;');
																								
																								CALL `binar_matrix`(@VAR_SPONSOR_ID, @VAR_SPONSOR_ID, VAR_IN_STRUCTURE_NUMBER, @VAR_MATRIX_NUMBER, NULL, VAR_IN_DEMO, VAR_IN_STATUS, 0, 0, 0, 0, 0, @VAR_AFFECTED_ROWS, @p1); 
																								SELECT @p1 INTO `VAR_OUT_RESULT2`;
																											
																							ELSE
																									
																								SET VAR_OUT_RESULT = 1;
																											
																							END IF;	
																							
																						END IF;
																						
																						/*IF (@VAR_AFFECTED_ROWS > 0)
																						THEN
																								
																							SET @VAR_MATRIX_NUMBER = (VAR_IN_MATRIX + 1);
																								
																							CALL `get_transition_settings`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_TRANS_STRUCTURE, @VAR_TRANS_MATRIX);
																							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'355',';;;;',VAR_IN_STRUCTURE_NUMBER,';;;;',VAR_IN_MATRIX,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_TRANS_STRUCTURE,';;;;',@VAR_TRANS_MATRIX,';;;;');
																								
																							IF (@VAR_TRANS_STRUCTURE > 0 && @VAR_TRANS_MATRIX > 0)
																							THEN
																								
																								SET @VAR_AFFECTED_ROWS = 0;
																								
																								CALL `binar_matrix`(@VAR_SPONSOR_ID, @VAR_SPONSOR_ID, @VAR_TRANS_STRUCTURE, @VAR_TRANS_MATRIX, NULL, VAR_IN_DEMO, VAR_IN_STATUS, 0, 0, 0, 0, 0, @VAR_AFFECTED_ROWS, @p1); 
																								SELECT @p1 INTO `VAR_OUT_RESULT2`;
																									
																							END IF;
																								
																						END IF;	*/
																						
																						SET @VAR_CLONE_NUMBER = 0;
																						SET @VAR_MATRIX_CLONE_CYCLE = 0;
																									
																						CALL `get_clone_settings`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, @VAR_CLONE_NUMBER, @VAR_MATRIX_CLONE_CYCLE);
																								
																						SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'36',';;;;',@VAR_PARENT_SPONSOR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',(VAR_IN_MATRIX + 1),';;;;',VAR_IN_DEMO,';;;;');
																						SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'37',';;;;',VAR_IN_MATRIX,';;;;',@VAR_CLONE_NUMBER,';;;;',@VAR_MATRIX_CLONE_CYCLE,';;;;');
																									
																						IF @VAR_AFFECTED_ROWS > 0  
																						THEN
																							
																							SET @VAR_ID = 0;
																							SET @VAR_SPONSOR_ID = 0;
																							SET @VAR_MATRIX_CLONE = 0;
																											
																							CALL `get_matrix_clone`(VAR_IN_STRUCTURE_NUMBER, (VAR_IN_MATRIX + 1), @VAR_ID, @VAR_SPONSOR_ID, @VAR_MATRIX_CLONE); 
																							
																							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'38',';;;;',VAR_IN_STRUCTURE_NUMBER,';;;;',VAR_IN_MATRIX,';;;;',@VAR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_MATRIX_CLONE,';;;;');				
																							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'39',';;;;',@VAR_MATRIX_CLONE,';;;;');	
																											
																							IF (@VAR_MATRIX_CLONE > 0)
																							THEN
																											
																								IF (@VAR_MATRIX_CLONE_CYCLE > 0)
																								THEN
																											
																									SET @VAR_MATRIX_NUMBER = VAR_IN_MATRIX;
																											
																								ELSE
																											
																									SET @VAR_MATRIX_NUMBER = (VAR_IN_MATRIX + 1);
																										
																								END IF;	
																									
																								SELECT `matrix_id` INTO @VAR_CLONE_SPONSOR_MATRIX_ID 
																								FROM `binar_matrices`
																								WHERE `structure_number` = VAR_IN_STRUCTURE_NUMBER AND `matrix_number` = @VAR_MATRIX_NUMBER AND `parent_sponsor_id` = @VAR_PARENT_SPONSOR_ID AND `partner_id` = @VAR_SPONSOR_ID AND `demo` = VAR_IN_DEMO AND `clone` = @VAR_MATRIX_CLONE
																								LIMIT 0,1;
																										
																								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'40',';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_ID,';;;;',@VAR_CLONE_NUMBER,';;;;',VAR_IN_DEMO,';;;;',@VAR_MATRIX_CLONE,';;;;',@VAR_CLONE_SPONSOR_MATRIX_ID,';;;;');
																										
																								CALL `binar_matrix`(@VAR_ID, @VAR_ID, VAR_IN_STRUCTURE_NUMBER, @VAR_CLONE_NUMBER, NULL, VAR_IN_DEMO, VAR_IN_STATUS, @VAR_MATRIX_CLONE, 0, @VAR_CLONE_SPONSOR_MATRIX_ID, 0, 0, @p1, @p2); 
																								SELECT @p1, @p2 INTO `VAR_RESULT`, `VAR_RESULT2`;
																								
																								SET VAR_OUT_RESULT = VAR_RESULT;
																								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'41',';;;;',VAR_OUT_RESULT,';;;;');
																							
																							ELSE
																							
																								SET VAR_OUT_RESULT = 1;
																										
																							END IF;
																						
																						END IF;
																						
																					END IF;
																					
																				END IF;
																			
																			ELSE
																			
																				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'41',';;;;',@VAR_MAX_MATRIX_NUMBER,';;;;');
																				
																				IF (@VAR_CLOSE_MATRIX > 0)
																				THEN
																											
																					SET @VAR_AFFECTED_ROWS = 0;
																					SET @VAR_MATRIX_NUMBER = 1;
																								
																					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'42',';;;;',@VAR_PARENT_SPONSOR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_MATRIX_NUMBER,';;;;',VAR_IN_DEMO,';;;;',@VAR_MATRIX_CLONE,';;;;',@VAR_SPONSOR_MATRIX_ID,';;;;');
																												
																					CALL `binar_matrix`(@VAR_SPONSOR_ID, @VAR_SPONSOR_ID, VAR_IN_STRUCTURE_NUMBER, @VAR_MATRIX_NUMBER, NULL, VAR_IN_DEMO, VAR_IN_STATUS, 0, 0, 0, 0, 0, @VAR_AFFECTED_ROWS, @p1); 
																					SELECT @p1 INTO `VAR_OUT_RESULT2`;
																					
																					IF @VAR_AFFECTED_ROWS > 0  
																					THEN
																					
																						SET VAR_OUT_RESULT = 1;
																										
																					END IF;
																											
																				ELSE
																									
																					SET VAR_OUT_RESULT = 1;
																										
																				END IF;
																				
																		END IF;
																			
																	END IF;
																		
																ELSE
																
																	SET VAR_OUT_RESULT = 1;
																	
																END IF;	
																		
															END IF;
																
														ELSE
																
															SET VAR_OUT_RESULT = 1;
																
														END IF;
															
													ELSE
																		
														SET VAR_OUT_RESULT = 1;
																			
													END IF;
														
												END IF;
												
											END IF;
												
										END IF;
											
									END IF;
										
								END IF;
								
							ELSE
							
								SET @VAR_ROLLBACK = 0;
								
							END IF;
					
						END IF;
					
					END IF;
								
				END IF;
				
			END IF;
			
		END IF;
		
	END IF;
	
END ;;
DELIMITER ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `auto_payment`;
CREATE PROCEDURE `auto_payment`(IN `VAR_IN_AUTO_PAY_ID` INT(10), IN `VAR_IN_PARTNER_ID` INT(10), IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_PAYMENT_TYPE` TINYINT(8), IN `VAR_IN_AMOUNT` DECIMAL(10, 2), IN `VAR_IN_TRANSACTION_ID` INT(10), IN `VAR_IN_CURRENCY` VARCHAR(6) CHARSET utf8, IN `VAR_IN_PAYMENT_SYSTEM` VARCHAR(10) CHARSET utf8, IN `VAR_IN_AUTO_PAYMENT` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS TINYINT(4);
	DECLARE VAR_ROLLBACK TINYINT(4);
	DECLARE VAR_ID INT(10);
	
	SET VAR_AFFECTED_ROWS = 0;
	SET VAR_ROLLBACK = 1;
	SET @VAR_ID = 0;
	SET VAR_OUT_RESULT = 0;
	
	START TRANSACTION;
	
		IF VAR_IN_PAYMENT_SYSTEM = 'payeer'
		THEN
	
			INSERT INTO `payeer_payments` SET `partner_id` = VAR_IN_PARTNER_ID, `structure_number` = VAR_IN_STRUCTURE_NUMBER, `matrix_number` = VAR_IN_MATRIX_NUMBER, `matrix_id` = VAR_IN_MATRIX_ID, `places` = 0, `order_id` = 0, `type` = 2, `amount` = VAR_IN_AMOUNT, `currency` = VAR_IN_CURRENCY, `operation_id` = VAR_IN_TRANSACTION_ID, `operation_date` = UNIX_TIMESTAMP(), `operation_pay_date` = UNIX_TIMESTAMP();
			
			SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
			
			IF VAR_AFFECTED_ROWS <= 0 
			THEN
																										
				SET VAR_ROLLBACK = 0;
																					
			END IF;
			
		END IF;
					
		IF VAR_ROLLBACK > 0
		THEN
			
			IF VAR_IN_AUTO_PAYMENT > 0
			THEN
		
				SET VAR_AFFECTED_ROWS = 0;
				
				UPDATE `auto_pay_off_logs` SET `paid_off` = 1
				WHERE `id` = VAR_IN_AUTO_PAY_ID;
				
				SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
				
				IF VAR_AFFECTED_ROWS <= 0 
				THEN
																										
					SET VAR_ROLLBACK = 0;
																					
				END IF;
			
			END IF;
					
			IF VAR_ROLLBACK > 0
			THEN
			
				IF VAR_IN_PAYMENT_TYPE = 1
				THEN
				
					SELECT `id` INTO @VAR_ID
					FROM `matrix_payments`
					WHERE `structure_number` = VAR_IN_STRUCTURE_NUMBER AND `matrix_number` = VAR_IN_MATRIX_NUMBER AND `matrix_id` = VAR_IN_MATRIX_ID;
					
					IF @VAR_ID > 0
					THEN
					
						SET VAR_AFFECTED_ROWS = 0;
				
						UPDATE `matrix_payments` SET `paid_off` = 1
						WHERE `id` = @VAR_ID;
						
						SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
						
						IF VAR_AFFECTED_ROWS > 0
						THEN
						
							SET VAR_OUT_RESULT = 1;
						
						END IF;
					
					END IF;
				
				ELSEIF VAR_IN_PAYMENT_TYPE = 2
				THEN
		
					SELECT `id` INTO @VAR_ID
					FROM `invite_pay_off`
					WHERE `structure_number` = VAR_IN_STRUCTURE_NUMBER AND `matrix_number` = VAR_IN_MATRIX_NUMBER AND `matrix_id` = VAR_IN_MATRIX_ID;
					
					IF @VAR_ID > 0
					THEN
					
						SET VAR_AFFECTED_ROWS = 0;
				
						UPDATE `invite_pay_off` SET `paid_off` = 1
						WHERE `id` = @VAR_ID;
						
						SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
						
						IF VAR_AFFECTED_ROWS > 0
						THEN
						
							SET VAR_OUT_RESULT = 1;
						
						END IF;
					
					END IF;
					
				ELSEIF VAR_IN_PAYMENT_TYPE = 3
				THEN
		
					SELECT `id` INTO @VAR_ID
					FROM `auto_pay_off_logs`
					WHERE `structure_number` = VAR_IN_STRUCTURE_NUMBER AND `matrix_number` = VAR_IN_MATRIX_NUMBER AND `matrix_id` = VAR_IN_MATRIX_ID;
					
					IF @VAR_ID > 0
					THEN
					
						SET VAR_AFFECTED_ROWS = 0;
				
						UPDATE `auto_pay_off_logs` SET `paid_off` = 1
						WHERE `id` = @VAR_ID;
						
						SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
						
						IF VAR_AFFECTED_ROWS > 0
						THEN
						
							SET VAR_OUT_RESULT = 1;
						
						END IF;
					
					END IF;
		
				END IF;
				
			END IF;
		
		END IF;
		
		IF VAR_OUT_RESULT = 0
		THEN
		
			ROLLBACK;
			
		END IF;
	
	COMMIT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `close_matrix`;
CREATE PROCEDURE `close_matrix`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_OUT_RESULT = 0;
SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("UPDATE `",
VAR_DEMO,
"matrix_",
VAR_IN_STRUCTURE_NUMBER,
"_",
VAR_IN_MATRIX_NUMBER, 
"` SET `close_date` = UNIX_TIMESTAMP()
WHERE `id` = '", 
VAR_IN_MATRIX_ID, 
"';");

PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `create_ticket`;
CREATE PROCEDURE `create_ticket`(IN `VAR_IN_PARTNER_ID` INT(10), IN `VAR_IN_USER_ID` INT(10), IN `VAR_IN_SUBJECT` VARCHAR(100) CHARSET utf8, IN `VAR_IN_TYPE` TINYINT(4), IN `VAR_IN_TEXT` TEXT CHARSET utf8, IN `VAR_IN_STATUS` TINYINT(4), OUT `VAR_OUT_RESULT` TINYINT(4))
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS TINYINT(4);
	DECLARE VAR_TICKET_ID INT(10);
	
	SET VAR_OUT_RESULT = 0;
	SET VAR_AFFECTED_ROWS = 0;
	SET VAR_TICKET_ID = 0;
	
	START TRANSACTION;
	
		IF VAR_IN_PARTNER_ID > 0 && VAR_IN_USER_ID > 0 && VAR_IN_SUBJECT <> '' && VAR_IN_TEXT <> ''
		THEN
		
			INSERT INTO `tickets` SET `partner_id` = VAR_IN_PARTNER_ID, `subject` = VAR_IN_SUBJECT, `status` = VAR_IN_STATUS, `created_at` = UNIX_TIMESTAMP();
		
			SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
			SET VAR_TICKET_ID = LAST_INSERT_ID();
					
			IF VAR_AFFECTED_ROWS > 0 && VAR_TICKET_ID > 0
			THEN
			
				SET VAR_AFFECTED_ROWS = 0;
				INSERT INTO `tickets_messages` SET `user_id` = VAR_IN_USER_ID, `ticket_id` = VAR_TICKET_ID, `type` = VAR_IN_TYPE, `text` = VAR_IN_TEXT, `created_at` = UNIX_TIMESTAMP();
			
				SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
				
				IF VAR_AFFECTED_ROWS > 0
				THEN
				
					SET VAR_OUT_RESULT = 1;
				
				ELSE
		
					ROLLBACK;
		
				END IF;
			
			ELSE
		
				ROLLBACK;
		
			END IF;
		
		ELSE
		
			ROLLBACK;
		
		END IF;
	
	COMMIT;
	
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `change_withdarwal_status`;
CREATE PROCEDURE `change_withdarwal_status`(IN `VAR_IN_ID` INT(10), IN `VAR_IN_ITEM_ID` INT(10), IN `VAR_IN_TYPE` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(4))
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS TINYINT(4);
	DECLARE VAR_TICKET_ID INT(10);
	
	SET VAR_OUT_RESULT = 0;
	SET VAR_AFFECTED_ROWS = 0;
	SET VAR_TICKET_ID = 0;
	
	START TRANSACTION;
	
		IF VAR_IN_ID > 0 && VAR_IN_ITEM_ID > 0
		THEN
		
			UPDATE `withdrawal` SET `status` = '1' WHERE `id` = VAR_IN_ID;
		
			SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
					
			IF VAR_AFFECTED_ROWS > 0
			THEN
			
				SET VAR_AFFECTED_ROWS = 0;
				
				IF VAR_IN_TYPE = 1
				THEN
				
					UPDATE `matrix_payments` SET `paid_off` = '1' WHERE `matrix_payments`.`id` = VAR_IN_ITEM_ID;
					SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
				
				ELSEIF VAR_IN_TYPE = 2
				THEN
					
					UPDATE `invite_pay_off` SET `paid_off` = '1' WHERE `invite_pay_off`.`id` = VAR_IN_ITEM_ID;
					SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
					
				END IF;
				
				IF VAR_AFFECTED_ROWS > 0
				THEN
				
					SET VAR_OUT_RESULT = 1;
				
				ELSE
		
					ROLLBACK;
		
				END IF;
			
			ELSE
		
				ROLLBACK;
		
			END IF;
		
		ELSE
		
			ROLLBACK;
		
		END IF;
	
	COMMIT;
	
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `demo_levels_payments`;
CREATE PROCEDURE `demo_levels_payments`(IN `VAR_IN_REFFERAL_ID` INT(11), IN `VAR_IN_LEVEL_DEPTH` TINYINT(8), IN `VAR_IN_REFFERAL_LEFT_KEY` INT(10), IN `VAR_IN_REFFERAL_RIGHT_KEY` INT(10), IN `VAR_IN_REFFERAL_LEVEL` INT(10), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_CREDIT` TINYINT(4), OUT `VAR_OUT_RESULT` TINYINT(4))
    NO SQL
BEGIN

DECLARE VAR_AFFECTED_ROWS INT(10);
DECLARE VAR_SPONSOR_ID INT(11);
DECLARE VAR_LOOP_FINISHED INT(11);
DECLARE VAR_SPONSOR_LEVEL TINYINT(8);
DECLARE VAR_SPONSOR_PERCENT TINYINT(8);

DECLARE partners_cursor CURSOR FOR 
SELECT `id` FROM `partners` WHERE `left_key` <= VAR_IN_REFFERAL_LEFT_KEY AND `right_key` >= VAR_IN_REFFERAL_RIGHT_KEY AND (`level` >= VAR_IN_REFFERAL_LEVEL - VAR_IN_LEVEL_DEPTH AND `level` < VAR_IN_REFFERAL_LEVEL) AND `id` != VAR_IN_REFFERAL_ID;

DECLARE CONTINUE HANDLER 
FOR NOT FOUND SET VAR_LOOP_FINISHED = 1;

SET VAR_AFFECTED_ROWS = 0;
SET VAR_LOOP_FINISHED = 0;
SET VAR_SPONSOR_ID = 0;
SET VAR_SPONSOR_LEVEL = 1;
SET VAR_SPONSOR_PERCENT = 0;
SET VAR_OUT_RESULT = 0;

OPEN partners_cursor;

get_partners: LOOP

FETCH partners_cursor INTO VAR_SPONSOR_ID;

IF VAR_LOOP_FINISHED = 1 THEN

SET VAR_OUT_RESULT = 1; 
LEAVE get_partners;

END IF;

CALL `get_levels_percentage`(VAR_SPONSOR_LEVEL, VAR_IN_CREDIT, @p1); 
SELECT @p1 INTO `VAR_SPONSOR_PERCENT`;

IF (VAR_SPONSOR_PERCENT > 0)
THEN

CALL `set_levels_payments`(VAR_IN_MATRIX_ID, VAR_SPONSOR_ID, VAR_IN_REFFERAL_ID, VAR_SPONSOR_LEVEL, (VAR_SPONSOR_PERCENT * VAR_IN_AMOUNT / 100), VAR_IN_CREDIT, @p1); 
SELECT @p1 INTO `VAR_AFFECTED_ROWS`;

IF VAR_AFFECTED_ROWS <= 0 
THEN

LEAVE get_partners;

END IF;

SET VAR_SPONSOR_LEVEL = VAR_SPONSOR_LEVEL + 1;
SET VAR_SPONSOR_PERCENT = 0;

ELSE

LEAVE get_partners;

END IF;

END LOOP get_partners;

CLOSE partners_cursor;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_admin_pay_off`;
CREATE PROCEDURE `get_admin_pay_off`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_CLONE` TINYINT(8), OUT `VAR_OUT_ADMIN_PAY_OFF` DECIMAL(10,2))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_CLONE TEXT;

SET @VAR_QUERY = '';
SET @VAR_CLONE = '';
SET VAR_OUT_ADMIN_PAY_OFF = 0;

IF (VAR_IN_CLONE > 0)
THEN

SET @VAR_CLONE = 'clone_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `", @VAR_CLONE, "admin_pay_off` INTO @VAR_ADMIN_PAY_OFF
FROM `matrices_settings_", VAR_IN_STRUCTURE_NUMBER, "` 
WHERE `number` = '",
VAR_IN_MATRIX_NUMBER,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_ADMIN_PAY_OFF;
    
    SET VAR_OUT_ADMIN_PAY_OFF = @VAR_ADMIN_PAY_OFF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_check_matrix`;
CREATE PROCEDURE `get_check_matrix`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_CHECK_MATRIX TINYINT(8);
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_DEMO = '';
SET VAR_CHECK_MATRIX = 0;

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `",
VAR_DEMO,
"matrix_", VAR_IN_STRUCTURE_NUMBER, "` INTO @VAR_CHECK_MATRIX
FROM `partners`
WHERE `id` = ", VAR_IN_PARTNER_ID);

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_CHECK_MATRIX;

SET VAR_OUT_RESULT = @VAR_CHECK_MATRIX;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_child_sponsor_data`;
CREATE PROCEDURE `get_child_sponsor_data`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_LEVEL_1_COUNT` INT(10), IN `VAR_IN_SPONSOR_LEFT_KEY` INT(10), IN `VAR_IN_SPONSOR_RIGHT_KEY` INT(10), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_CLONE` TINYINT(8), OUT `VAR_OUT_ID` INT(10), OUT `VAR_OUT_SPONSOR_ID` INT(11), OUT `VAR_OUT_SPONSOR_LEFT_KEY` INT(10), OUT `VAR_OUT_SPONSOR_RIGHT_KEY` INT(10), OUT `VAR_OUT_SPONSOR_LEVEL` INT(10), OUT `VAR_OUT_SPONSOR_PARTNERS_COUNT` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_ID INT(10);
	DECLARE VAR_SPONSOR_ID INT(11);
	DECLARE VAR_SPONSOR_LEFT_KEY INT(10);
	DECLARE VAR_SPONSOR_RIGHT_KEY INT(10);
	DECLARE VAR_SPONSOR_LEVEL INT(10);
	DECLARE VAR_SPONSOR_PARTNERS_COUNT INT(10);
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
	DECLARE VAR_CLONE VARCHAR(16) CHARSET utf8;
		
	SET VAR_ID = 0;	
	SET VAR_SPONSOR_ID = 0;
	SET VAR_SPONSOR_LEFT_KEY = 0;
	SET VAR_SPONSOR_RIGHT_KEY = 0;
	SET VAR_SPONSOR_LEVEL = 0;
	SET VAR_SPONSOR_PARTNERS_COUNT = 0;
	SET VAR_OUT_SPONSOR_ID = 0;
	SET VAR_OUT_SPONSOR_LEFT_KEY = 0;
	SET VAR_OUT_SPONSOR_RIGHT_KEY = 0;
	SET VAR_OUT_SPONSOR_LEVEL = 0;
	SET VAR_OUT_SPONSOR_PARTNERS_COUNT = 0;
	SET VAR_DEMO = '';
	SET @VAR_CLONE = '';
	
	IF VAR_IN_DEMO > 0
	THEN
	
		SET VAR_DEMO = 'demo_';
	
	END IF;
	
	IF VAR_IN_CLONE > 0
	THEN
	
		SET @VAR_CLONE = ' AND `clone` = 1';
		
	ELSE
	
		SET @VAR_CLONE = ' AND `clone` = 0';
	
	END IF;
	
	SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id`, `left_key`, `right_key`, `level`, `level_1` INTO @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT
	FROM `",
	VAR_DEMO,
	"matrix_", VAR_IN_STRUCTURE_NUMBER, "_", VAR_IN_MATRIX_NUMBER, "`
	WHERE `left_key` >= ", VAR_IN_SPONSOR_LEFT_KEY, " AND `right_key` <= ", VAR_IN_SPONSOR_RIGHT_KEY, " AND IFNULL(`level_1`, 0) < ", VAR_IN_LEVEL_1_COUNT, "
	ORDER BY `level` ASC, `left_key` ASC
	LIMIT 0, 1;");
	
	PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_OUT_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;

	IF @VAR_ID > 0
	THEN
		
		SET VAR_OUT_ID = @VAR_ID;
		SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
		SET VAR_OUT_SPONSOR_LEFT_KEY = @VAR_SPONSOR_LEFT_KEY;
		SET VAR_OUT_SPONSOR_RIGHT_KEY = @VAR_SPONSOR_RIGHT_KEY;
		SET VAR_OUT_SPONSOR_LEVEL = @VAR_SPONSOR_LEVEL;
		SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
		
	ELSE
	
		SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id`, `left_key`, `right_key`, `level`, `level_1` INTO @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT
		FROM `",
		VAR_DEMO,
		"matrix_", VAR_IN_STRUCTURE_NUMBER, "_", VAR_IN_MATRIX_NUMBER, "`
		WHERE `left_key` >= ", VAR_IN_SPONSOR_LEFT_KEY, " AND `right_key` <= ", VAR_IN_SPONSOR_RIGHT_KEY, " AND IFNULL(`level_1`, 0) < ", VAR_IN_LEVEL_1_COUNT, "
		ORDER BY `level` ASC, `left_key` ASC
		LIMIT 0, 1;");
		
		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
		
		SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_OUT_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;

		SET VAR_OUT_ID = @VAR_ID;
		SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
		SET VAR_OUT_SPONSOR_LEFT_KEY = @VAR_SPONSOR_LEFT_KEY;
		SET VAR_OUT_SPONSOR_RIGHT_KEY = @VAR_SPONSOR_RIGHT_KEY;
		SET VAR_OUT_SPONSOR_LEVEL = @VAR_SPONSOR_LEVEL;
		SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
	
	END IF;
	
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_clone_number_by_matrix_number`;
CREATE PROCEDURE `get_clone_number_by_matrix_number`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), OUT `VAR_OUT_CLONE` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_CLONE TINYINT(8);

SET VAR_OUT_CLONE = 0;

SET @VAR_QUERY = CONCAT("SELECT `clone` INTO @VAR_CLONE
FROM `matrices_settings` 
WHERE `number` = '",
VAR_IN_MATRIX_NUMBER,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_CLONE;
    
    SET VAR_OUT_CLONE = @VAR_CLONE;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_clone_settings`;
CREATE PROCEDURE `get_clone_settings`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), OUT `VAR_OUT_CLONE` TINYINT(8), OUT `VAR_OUT_CLONE_CYCLE` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_CLONE TINYINT(8);
DECLARE VAR_CLONE_CYCLE TINYINT(8);

SET VAR_CLONE = 0;
SET VAR_CLONE_CYCLE = 0;
SET VAR_OUT_CLONE = 0;
SET VAR_OUT_CLONE_CYCLE = 0;

SET @VAR_QUERY = CONCAT("SELECT `clone`, `clone_cycle` INTO @VAR_CLONE, @VAR_CLONE_CYCLE
FROM `matrices_settings_", VAR_IN_STRUCTURE_NUMBER, "`
WHERE `number` = '",
VAR_IN_MATRIX_NUMBER,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_CLONE, @VAR_CLONE_CYCLE;
    
    SET VAR_OUT_CLONE = @VAR_CLONE;
SET VAR_OUT_CLONE_CYCLE = @VAR_CLONE_CYCLE;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_transition_settings`;
CREATE PROCEDURE `get_transition_settings`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), OUT `VAR_OUT_TRANS_STRUCTURE` TINYINT(8), OUT `VAR_OUT_TRANS_MATRIX` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_TRANS_STRUCTURE TINYINT(8);
DECLARE VAR_TRANS_MATRIX TINYINT(8);

SET VAR_TRANS_STRUCTURE = 0;
SET VAR_TRANS_MATRIX = 0;
SET VAR_OUT_TRANS_STRUCTURE = 0;
SET VAR_OUT_TRANS_MATRIX = 0;

SET @VAR_QUERY = CONCAT("SELECT `trans_struct`, `trans_matrix` INTO @VAR_TRANS_STRUCTURE, @VAR_TRANS_MATRIX
FROM `matrices_settings_", VAR_IN_STRUCTURE_NUMBER, "`
WHERE `number` = '",
VAR_IN_MATRIX_NUMBER,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_TRANS_STRUCTURE, @VAR_TRANS_MATRIX;
    
    SET VAR_OUT_TRANS_STRUCTURE = @VAR_TRANS_STRUCTURE;
	SET VAR_OUT_TRANS_MATRIX = @VAR_TRANS_MATRIX;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_close_matrix_partner_id`;
CREATE PROCEDURE `get_close_matrix_partner_id`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_CREDIT` TINYINT(4), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
DECLARE VAR_CREDIT VARCHAR(7) CHARSET utf8;
DECLARE VAR_RESULT INT(11);

SET VAR_OUT_RESULT = 0;
SET VAR_DEMO = '';
SET VAR_CREDIT = '';
SET VAR_RESULT = 0;

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

IF VAR_IN_CREDIT > 0
THEN

SET VAR_CREDIT = 'credit_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `partner_id` INTO @VAR_RESULT
FROM `",
VAR_CREDIT,
VAR_DEMO,
"matrix_", 
VAR_IN_MATRIX_NUMBER, 
"`
WHERE `id` = '", 
VAR_IN_MATRIX_ID, 
"';");

PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_RESULT;
    
    SET VAR_OUT_RESULT = @VAR_RESULT;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_gold_token_sponsor_data`;
CREATE PROCEDURE `get_gold_token_sponsor_data`(IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_ID` INT(10), OUT `VAR_OUT_SPONSOR_ID` INT(11), OUT `VAR_OUT_SPONSOR_LEFT_KEY` INT(10), OUT `VAR_OUT_SPONSOR_RIGHT_KEY` INT(10), OUT `VAR_OUT_SPONSOR_LEVEL` INT(10), OUT `VAR_OUT_SPONSOR_PARTNERS_COUNT` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_ID INT(10);
	DECLARE VAR_SPONSOR_ID INT(11);
	DECLARE VAR_PARENT_SPONSOR_LEFT_KEY INT(10);
	DECLARE VAR_PARENT_SPONSOR_RIGHT_KEY INT(10);
	DECLARE VAR_SPONSOR_LEFT_KEY INT(10);
	DECLARE VAR_SPONSOR_RIGHT_KEY INT(10);
	DECLARE VAR_SPONSOR_LEVEL INT(10);
	DECLARE VAR_SPONSOR_PARTNERS_COUNT INT(10);
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
		
	SET @VAR_ID = 0;	
	SET VAR_SPONSOR_ID = 0;
	SET VAR_PARENT_SPONSOR_LEFT_KEY = 0;
	SET VAR_PARENT_SPONSOR_RIGHT_KEY = 0;
	SET VAR_SPONSOR_LEFT_KEY = 0;
	SET VAR_SPONSOR_RIGHT_KEY = 0;
	SET VAR_SPONSOR_LEVEL = 0;
	SET VAR_SPONSOR_PARTNERS_COUNT = 0;
	SET VAR_OUT_SPONSOR_ID = 0;
	SET VAR_OUT_SPONSOR_LEFT_KEY = 0;
	SET VAR_OUT_SPONSOR_RIGHT_KEY = 0;
	SET VAR_OUT_SPONSOR_LEVEL = 0;
	SET VAR_OUT_SPONSOR_PARTNERS_COUNT = 0;
	SET @VAR_DEMO = '';
	
	IF VAR_IN_DEMO > 0
	THEN
	
		SET @VAR_DEMO = 'demo_';
	
	END IF;
	
	SET @VAR_QUERY = CONCAT("SELECT `left_key`, `right_key` INTO @VAR_PARENT_SPONSOR_LEFT_KEY, @VAR_PARENT_SPONSOR_RIGHT_KEY
	FROM `",
	@VAR_DEMO,
	"matrix_",
	VAR_IN_STRUCTURE_NUMBER,
	"_",
	VAR_IN_MATRIX_NUMBER,
	"` WHERE `partner_id` = ",
	VAR_IN_SPONSOR_ID,
	" AND `change` = 0 
	ORDER BY `open_date`
	LIMIT 0,1;");
	
	PREPARE stmt FROM @VAR_QUERY;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
		
	SELECT @VAR_PARENT_SPONSOR_LEFT_KEY, @VAR_PARENT_SPONSOR_RIGHT_KEY;
	
	IF (@VAR_PARENT_SPONSOR_LEFT_KEY > 0 && @VAR_PARENT_SPONSOR_RIGHT_KEY > 0)
	THEN
	
		SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id`, `left_key`, `right_key`, `level`, `level_1` INTO  @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT 
		FROM `",
		@VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER,
		"` WHERE `left_key` >= '",
		@VAR_PARENT_SPONSOR_LEFT_KEY,
		"' AND `right_key` <= '",
		@VAR_PARENT_SPONSOR_RIGHT_KEY,
		"' AND `level_1` < 2 AND `change` = 0
		ORDER BY `open_date`
		LIMIT 0,1");
		
		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
		
		SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_OUT_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;

		IF @VAR_ID > 0
		THEN

			SET VAR_OUT_ID = @VAR_ID;
			SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
			SET VAR_OUT_SPONSOR_LEFT_KEY = @VAR_SPONSOR_LEFT_KEY;
			SET VAR_OUT_SPONSOR_RIGHT_KEY = @VAR_SPONSOR_RIGHT_KEY;
			SET VAR_OUT_SPONSOR_LEVEL = @VAR_SPONSOR_LEVEL;
			SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
			
		END IF;

	END IF;
	
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_last_id`;
CREATE PROCEDURE `get_last_id`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8),  IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` INT(10), OUT `VAR_OUT_LEVEL` INT(10))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_LAST_ID INT(10);
DECLARE VAR_LEVEL INT(10);
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_DEMO = '';
SET VAR_LAST_ID = 0;
SET VAR_LEVEL = 0;

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `id`, `level` INTO @VAR_LAST_ID, @VAR_LEVEL
FROM `",
VAR_DEMO,
"matrix_",
VAR_IN_STRUCTURE_NUMBER,
"_", VAR_IN_MATRIX_NUMBER, "`
ORDER BY `id` DESC 
LIMIT 1;");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_LAST_ID, @VAR_LEVEL;

SET VAR_OUT_RESULT = @VAR_LAST_ID;
SET VAR_OUT_LEVEL = @VAR_LEVEL;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_levels_depth`;
CREATE PROCEDURE `get_levels_depth`(OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEPTH_LEVEL TINYINT(8);

SET VAR_OUT_RESULT = 0;
SET VAR_DEPTH_LEVEL = 0;

SET @VAR_QUERY = CONCAT("SELECT MAX(`level`) INTO @VAR_DEPTH_LEVEL
FROM `levels_pecentage`");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_DEPTH_LEVEL;
    
    SET VAR_OUT_RESULT = @VAR_DEPTH_LEVEL;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_levels_percentage`;
CREATE PROCEDURE `get_levels_percentage`(IN `VAR_IN_SPONSOR_LEVEL` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_SPONSOR_PERCENT TINYINT(8);

SET VAR_OUT_RESULT = 0;
SET VAR_SPONSOR_PERCENT = 0;


SET @VAR_QUERY = CONCAT("SELECT `value` INTO @VAR_SPONSOR_PERCENT
FROM `levels_pecentage`
WHERE `level` = '",
VAR_IN_SPONSOR_LEVEL,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_SPONSOR_PERCENT;
    
    SET VAR_OUT_RESULT = @VAR_SPONSOR_PERCENT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_matrix_clone`;
CREATE PROCEDURE `get_matrix_clone`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), OUT VAR_OUT_ID INT(11), OUT VAR_OUT_PARENT_SPONSOR_ID INT(10), OUT `VAR_OUT_CLONE` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_ID INT(11);
DECLARE VAR_PARENT_SPONSOR_ID INT(10);
DECLARE VAR_CLONE TINYINT(8);

SET VAR_ID = 0;
SET VAR_PARENT_SPONSOR_ID = 0;
SET VAR_CLONE = 0;
SET VAR_OUT_ID = 0;
SET VAR_OUT_PARENT_SPONSOR_ID = 0;
SET VAR_OUT_CLONE = 0;

SET @VAR_QUERY = CONCAT("SELECT `partner_id`, `parent_sponsor_id`, `clone` INTO @VAR_MATRIX_ID, @VAR_PARENT_SPONSOR_ID, @VAR_CLONE
FROM `binar_matrices`
WHERE `structure_number` = ", VAR_IN_STRUCTURE_NUMBER ," AND `matrix_number` = '", VAR_IN_MATRIX_NUMBER ,"'
ORDER BY `id` DESC
LIMIT 0,1");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_CLONE, @VAR_MATRIX_ID, @VAR_PARENT_SPONSOR_ID, @VAR_CLONE;
    
    SET VAR_OUT_ID = @VAR_MATRIX_ID;
SET VAR_OUT_PARENT_SPONSOR_ID = @VAR_PARENT_SPONSOR_ID;
SET VAR_OUT_CLONE = @VAR_CLONE;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_matrix_data_by_id`;
CREATE PROCEDURE `get_matrix_data_by_id`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(11), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_LEVEL` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_SPONSOR_ID` INT(11), 
OUT `VAR_OUT_SPONSOR_PARTNERS_COUNT` INT(10), OUT `VAR_OUT_CLONE` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_SPONSOR_ID INT(11);
DECLARE VAR_SPONSOR_PARTNERS_COUNT INT(10);
DECLARE VAR_CLONE TINYINT(8);
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_SPONSOR_ID = 0;
SET VAR_SPONSOR_PARTNERS_COUNT = 0;
SET VAR_CLONE = 0;
SET VAR_DEMO = '';
SET VAR_OUT_SPONSOR_ID = 0;
SET VAR_OUT_SPONSOR_PARTNERS_COUNT = 0;
SET VAR_OUT_CLONE = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `partner_id`, `level_", VAR_IN_LEVEL, "`, `clone` INTO @VAR_SPONSOR_ID, @VAR_SPONSOR_PARTNERS_COUNT, @VAR_CLONE
FROM `",
VAR_DEMO,
"matrix_",
VAR_IN_STRUCTURE_NUMBER,
"_", VAR_IN_MATRIX_NUMBER, "`
WHERE `id` = '", VAR_IN_MATRIX_ID, "'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_SPONSOR_ID, @VAR_SPONSOR_PARTNERS_COUNT, @VAR_CLONE;

SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
SET VAR_OUT_CLONE = @VAR_CLONE;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_matrix_data_by_level`;
CREATE PROCEDURE `get_matrix_data_by_level`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_LEVEL` INT(10), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_ID` INT(10), OUT `VAR_OUT_SPONSOR_ID` INT(11))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_ID INT(10);
DECLARE VAR_SPONSOR_ID INT(11);
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_ID = 0;
SET VAR_SPONSOR_ID = 0;
SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id` INTO @VAR_ID, @VAR_SPONSOR_ID
FROM `",
VAR_DEMO,
"matrix_", VAR_IN_MATRIX_NUMBER, "`
WHERE `level` = '", VAR_IN_LEVEL, "'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_ID, @VAR_SPONSOR_ID;

SET VAR_OUT_ID = @VAR_ID;
SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_matrix_id`;
CREATE PROCEDURE `get_matrix_id`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_ID` DECIMAL(10,2))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
DECLARE VAR_ID INT(11);

SET VAR_DEMO = '';
SET @VAR_ID = 0;

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `id`INTO @VAR_ID 
FROM `",
VAR_DEMO,
"matrix_",
VAR_IN_STRUCTURE_NUMBER,
"_",
VAR_IN_MATRIX_NUMBER,
"` ORDER BY `id` DESC
LIMIT 0, 1;");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_ID;
    
SET VAR_OUT_ID = @VAR_ID;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_matrix_pay`;
CREATE PROCEDURE `get_matrix_pay`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), OUT `VAR_OUT_PAY` DECIMAL(10,2))
    NO SQL
BEGIN

DECLARE VAR_PAY DECIMAL(10,2);

SET VAR_PAY = 0;

SET @VAR_QUERY = CONCAT("SELECT `pay` INTO @VAR_PAY
FROM `matrices_settings` 
WHERE `number` = '",
VAR_IN_MATRIX_NUMBER,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_PAY;
    
SET VAR_OUT_PAY = @VAR_PAY;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_matrix_settings`;
CREATE PROCEDURE `get_matrix_settings`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), OUT `VAR_OUT_TYPE` TINYINT(8), OUT `VAR_OUT_LEVELS` TINYINT(8), OUT `VAR_OUT_CLONE` TINYINT(8), OUT `VAR_OUT_CLONE_CYCLE` TINYINT(8), OUT `VAR_OUT_PAY` DECIMAL(10,2), OUT `VAR_OUT_ADMIN_PAY_OFF` DECIMAL(10,2), OUT `VAR_OUT_PAY_OFF` DECIMAL(10,2), OUT `VAR_OUT_AUTO_PAY_OFF` TINYINT(8), 
OUT `VAR_CLONE_OUT_PAY` DECIMAL(10,2), OUT `VAR_CLONE_OUT_ADMIN_PAY_OFF` DECIMAL(10,2), OUT `VAR_OUT_INVITE_PAY_OFF` DECIMAL(10,2), OUT `VAR_OUT_INVITE_SPONSOR_ACTIVATE` TINYINT(8), OUT `VAR_CLONE_OUT_PAY_OFF` DECIMAL(10,2), OUT `VAR_OUT_OPEN_MATRIX_BALLS` INT(10), OUT `VAR_OUT_CLOSE_MATRIX_BALLS` INT(10), OUT `VAR_OUT_REFERRAL_MATRIX_BALLS` INT(10), OUT `VAR_OUT_ACCOUNT_TYPE` TINYINT(8), OUT `VAR_OUT_CLOSE_MATRIX` TINYINT(8),
OUT `VAR_OUT_TRANS_STRUCTURE` TINYINT(8), OUT `VAR_OUT_TRANS_MATRIX` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_TYPE TINYINT(8);
	DECLARE VAR_LEVELS TINYINT(8);
	DECLARE VAR_CLONE TINYINT(8);
	DECLARE VAR_CLONE_CYCLE TINYINT(8);
	DECLARE VAR_PAY DECIMAL(10,2);
	DECLARE VAR_ADMIN_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_AUTO_PAY_OFF TINYINT(8);
	DECLARE VAR_CLONE_PAY DECIMAL(10,2);
	DECLARE VAR_CLONE_ADMIN_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_CLONE_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_INVITE_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_INVITE_SPONSOR_ACTIVATE TINYINT(8);
	DECLARE VAR_OPEN_MATRIX_BALLS INT(10);
	DECLARE VAR_CLOSE_MATRIX_BALLS INT(10);
	DECLARE VAR_REFERRAL_MATRIX_BALLS INT(10);
	DECLARE VAR_ACCOUNT_TYPE TINYINT(8);
	DECLARE VAR_CLOSE_MATRIX TINYINT(8);
	DECLARE VAR_TRANS_STRUCTURE TINYINT(8);
	DECLARE VAR_TRANS_MATRIX TINYINT(8);

	SET VAR_TYPE = 0;
	SET VAR_LEVELS = 0;
	SET VAR_CLONE = 0;
	SET VAR_CLONE_CYCLE = 0;
	SET VAR_PAY = 0;
	SET VAR_PAY_OFF = 0;
	SET VAR_AUTO_PAY_OFF = 0;
	SET VAR_ADMIN_PAY_OFF = 0;
	SET VAR_CLONE_PAY = 0;
	SET VAR_CLONE_PAY_OFF = 0;
	SET VAR_CLONE_ADMIN_PAY_OFF = 0;
	SET VAR_INVITE_PAY_OFF = 0;
	SET VAR_INVITE_SPONSOR_ACTIVATE = 0;
	SET VAR_OPEN_MATRIX_BALLS = 0;
	SET VAR_CLOSE_MATRIX_BALLS = 0;
	SET VAR_REFERRAL_MATRIX_BALLS = 0;
	SET VAR_ACCOUNT_TYPE = 0;
	SET VAR_CLOSE_MATRIX = 0;
	SET VAR_TRANS_STRUCTURE = 0;
	SET VAR_TRANS_MATRIX = 0;
	SET VAR_OUT_TYPE = 0;
	SET VAR_OUT_CLONE = 0;
	SET VAR_OUT_CLONE_CYCLE = 0;
	SET VAR_OUT_LEVELS = 0;
	SET VAR_OUT_PAY = 0;
	SET VAR_OUT_ADMIN_PAY_OFF = 0;
	SET VAR_OUT_PAY_OFF = 0;
	SET VAR_CLONE_OUT_PAY = 0;
	SET VAR_CLONE_OUT_ADMIN_PAY_OFF = 0;
	SET VAR_OUT_INVITE_PAY_OFF = 0;
	SET VAR_OUT_INVITE_SPONSOR_ACTIVATE = 0;
	SET VAR_CLONE_OUT_PAY_OFF = 0;
	SET VAR_OUT_OPEN_MATRIX_BALLS = 0;
	SET VAR_OUT_CLOSE_MATRIX_BALLS = 0;
	SET VAR_OUT_REFERRAL_MATRIX_BALLS = 0;
	SET VAR_OUT_ACCOUNT_TYPE = 0;
	SET VAR_OUT_CLOSE_MATRIX = 0;
	SET VAR_OUT_TRANS_STRUCTURE = 0;
	SET VAR_OUT_TRANS_MATRIX = 0;

	SET @VAR_QUERY = CONCAT("SELECT `type`, `levels`, `clone`, `clone_cycle`, `pay`, `admin_pay_off`, `pay_off`, `auto_pay_off`, `clone_pay`, `clone_admin_pay_off`, `clone_pay_off`, `invite_pay_off`, `invite_sponsor_activate`, `open_matrix_balls`, `close_matrix_balls`, `referral_matrix_balls`, `account_type`, `close_matrix`, `trans_struct`, `trans_matrix` INTO @VAR_TYPE, @VAR_LEVELS, @VAR_CLONE, @VAR_CLONE_CYCLE, @VAR_PAY, @VAR_ADMIN_PAY_OFF, @VAR_PAY_OFF, @VAR_AUTO_PAY_OFF, @VAR_CLONE_PAY, 
	@VAR_CLONE_ADMIN_PAY_OFF, @VAR_CLONE_PAY_OFF, @VAR_INVITE_PAY_OFF, @VAR_INVITE_SPONSOR_ACTIVATE, @VAR_OPEN_MATRIX_BALLS, @VAR_CLOSE_MATRIX_BALLS, @VAR_REFERRAL_MATRIX_BALLS, @VAR_ACCOUNT_TYPE, @VAR_CLOSE_MATRIX, @VAR_TRANS_STRUCTURE, @VAR_TRANS_MATRIX
	FROM `matrices_settings_", VAR_IN_STRUCTURE_NUMBER, "` 
	WHERE `number` = '",
	VAR_IN_MATRIX_NUMBER,
	"'");

	PREPARE stmt FROM @VAR_QUERY;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
		
	SELECT @VAR_TYPE, @VAR_LEVELS, @VAR_CLONE, @VAR_CLONE_CYCLE, @VAR_PAY, @VAR_ADMIN_PAY_OFF, @VAR_PAY_OFF, @VAR_AUTO_PAY_OFF, @VAR_CLONE_PAY, @VAR_CLONE_ADMIN_PAY_OFF, @VAR_CLONE_PAY_OFF, @VAR_INVITE_PAY_OFF, @VAR_INVITE_SPONSOR_ACTIVATE, @VAR_OPEN_MATRIX_BALLS, @VAR_CLOSE_MATRIX_BALLS, @VAR_REFERRAL_MATRIX_BALLS, @VAR_ACCOUNT_TYPE, @VAR_CLOSE_MATRIX, @VAR_TRANS_STRUCTURE, @VAR_TRANS_MATRIX;
		
	SET VAR_OUT_TYPE = @VAR_TYPE;
	SET VAR_OUT_LEVELS = @VAR_LEVELS;
	SET VAR_OUT_CLONE = @VAR_CLONE;
	SET VAR_OUT_CLONE_CYCLE = @VAR_CLONE_CYCLE;
	SET VAR_OUT_PAY = @VAR_PAY;
	SET VAR_OUT_ADMIN_PAY_OFF = @VAR_ADMIN_PAY_OFF;
	SET VAR_OUT_PAY_OFF = @VAR_PAY_OFF;
	SET VAR_OUT_AUTO_PAY_OFF = @VAR_AUTO_PAY_OFF;
	SET VAR_CLONE_OUT_PAY = @VAR_CLONE_PAY;
	SET VAR_CLONE_OUT_ADMIN_PAY_OFF = @VAR_CLONE_ADMIN_PAY_OFF;
	SET VAR_CLONE_OUT_PAY_OFF = @VAR_CLONE_PAY_OFF;
	SET VAR_OUT_INVITE_PAY_OFF = @VAR_INVITE_PAY_OFF;
	SET VAR_OUT_INVITE_SPONSOR_ACTIVATE = @VAR_INVITE_SPONSOR_ACTIVATE;
	SET VAR_OUT_OPEN_MATRIX_BALLS = @VAR_OPEN_MATRIX_BALLS;
	SET VAR_OUT_CLOSE_MATRIX_BALLS = @VAR_CLOSE_MATRIX_BALLS;
	SET VAR_OUT_REFERRAL_MATRIX_BALLS = @VAR_REFERRAL_MATRIX_BALLS;
	SET VAR_OUT_ACCOUNT_TYPE = @VAR_ACCOUNT_TYPE;
	SET VAR_OUT_CLOSE_MATRIX = @VAR_CLOSE_MATRIX;
	SET VAR_OUT_TRANS_STRUCTURE = @VAR_TRANS_STRUCTURE;
	SET VAR_OUT_TRANS_MATRIX = @VAR_TRANS_MATRIX;

END ;;
DELIMITER ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_invite_pay_off`;
CREATE PROCEDURE `set_invite_pay_off`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(11), IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_INVITE_SPONSOR_ACTIVE` TINYINT(8), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_SPONSOR_MATRIX INT(10);
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

	SET VAR_OUT_RESULT = 0;
	SET @VAR_QUERY = '';
	SET @VAR_SPONSOR_MATRIX = 0;
	SET VAR_DEMO = '';
	
	IF VAR_IN_DEMO > 0
	THEN

		SET VAR_DEMO = 'demo_';

	END IF;

	IF VAR_IN_INVITE_SPONSOR_ACTIVE > 0
	THEN

		SET @VAR_QUERY = CONCAT("SELECT `matrix_1` INTO @VAR_SPONSOR_MATRIX
		FROM `partners` 
		WHERE `id` = '",
		VAR_IN_SPONSOR_ID,
		"'");

		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;

		SELECT @VAR_SPONSOR_MATRIX;

	ELSE

		SET @VAR_SPONSOR_MATRIX = 1;

	END IF;

	IF @VAR_SPONSOR_MATRIX > 0
	THEN

		SET VAR_QUERY = '';
		SET @VAR_QUERY = CONCAT("INSERT INTO `",
		VAR_DEMO,
		"invite_pay_off` SET `partner_id` = '", VAR_IN_PARTNER_ID, "', `benefit_partner_id` = '", VAR_IN_SPONSOR_ID, "',  `structure_number` = '", VAR_IN_STRUCTURE_NUMBER, "', `matrix_number` = '", VAR_IN_MATRIX_NUMBER, "', `matrix_id` = '", VAR_IN_MATRIX_ID, "', `amount` = '", VAR_IN_AMOUNT, "', `created_at` = UNIX_TIMESTAMP()");

		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
		DEALLOCATE PREPARE stmt;
		
	END IF;

END ;;
DELIMITER ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_matrix_settings_pay`;
CREATE PROCEDURE `get_matrix_settings_pay`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_CREDIT` TINYINT(4), OUT `VAR_OUT_PAY` DECIMAL(10,2))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_PAY DECIMAL(10,2);
DECLARE VAR_CREDIT_QUERY VARCHAR(7) CHARSET utf8;

SET VAR_PAY = 0;
SET VAR_CREDIT_QUERY = '';

IF VAR_IN_CREDIT > 0
THEN

SET VAR_CREDIT_QUERY = 'credit_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `pay` INTO @VAR_PAY
FROM `",
VAR_CREDIT_QUERY,
"matrices_settings` 
WHERE `number` = '",
VAR_IN_MATRIX_NUMBER,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_PAY;
    
    SET VAR_OUT_PAY = @VAR_PAY;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_max_level`;
CREATE PROCEDURE `get_max_level`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_MAX_LEVEL` INT(10))
    NO SQL
BEGIN



DECLARE VAR_QUERY TEXT;

DECLARE VAR_LAST_LEVEL INT(10);

DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;



SET VAR_LAST_LEVEL = 0;

SET VAR_DEMO = '';

SET VAR_OUT_MAX_LEVEL = 0;



IF VAR_IN_DEMO > 0

THEN



SET VAR_DEMO = 'demo_';



END IF;



SET @VAR_QUERY = CONCAT("SELECT MAX(`level`) INTO @VAR_LAST_LEVEL

FROM `",

VAR_DEMO,

"matrix_",

VAR_IN_MATRIX_NUMBER,

"`;");



PREPARE stmt FROM @VAR_QUERY;

EXECUTE stmt;

DEALLOCATE PREPARE stmt;



SELECT @VAR_LAST_LEVEL;



SET VAR_OUT_MAX_LEVEL = @VAR_LAST_LEVEL;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_max_matrix_number`;
CREATE PROCEDURE `get_max_matrix_number`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), OUT `VAR_OUT_RESULT` INT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_RESULT INT(8);

SET VAR_RESULT = 0;
SET VAR_OUT_RESULT = 0;

SET @VAR_QUERY = CONCAT("SELECT MAX(`number`) INTO @VAR_RESULT
FROM `matrices_settings_", VAR_IN_STRUCTURE_NUMBER, "`");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_RESULT;
    
    SET VAR_OUT_RESULT = @VAR_RESULT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_parent_sponsor_data`;
CREATE PROCEDURE `get_parent_sponsor_data`(IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_SPONSOR_LEFT_KEY` INT(10), IN `VAR_IN_SPONSOR_RIGHT_KEY` INT(10), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_CLONE` TINYINT(8), OUT `VAR_OUT_ID` INT(10), OUT `VAR_OUT_SPONSOR_ID` INT(11), OUT `VAR_OUT_SPONSOR_LEFT_KEY` INT(10), OUT `VAR_OUT_SPONSOR_RIGHT_KEY` INT(10), OUT `VAR_OUT_SPONSOR_LEVEL` INT(10), OUT `VAR_OUT_SPONSOR_PARTNERS_COUNT` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_ID INT(10);
	DECLARE VAR_SPONSOR_ID INT(11);
	DECLARE VAR_SPONSOR_LEFT_KEY INT(10);
	DECLARE VAR_SPONSOR_RIGHT_KEY INT(10);
	DECLARE VAR_SPONSOR_LEVEL INT(10);
	DECLARE VAR_SPONSOR_PARTNERS_COUNT INT(10);
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
	DECLARE VAR_CLONE VARCHAR(16) CHARSET utf8;
		
	SET VAR_ID = 0;	
	SET VAR_SPONSOR_ID = 0;
	SET VAR_SPONSOR_LEFT_KEY = 0;
	SET VAR_SPONSOR_RIGHT_KEY = 0;
	SET VAR_SPONSOR_LEVEL = 0;
	SET VAR_SPONSOR_PARTNERS_COUNT = 0;
	SET VAR_OUT_SPONSOR_ID = 0;
	SET VAR_OUT_SPONSOR_LEFT_KEY = 0;
	SET VAR_OUT_SPONSOR_RIGHT_KEY = 0;
	SET VAR_OUT_SPONSOR_LEVEL = 0;
	SET VAR_OUT_SPONSOR_PARTNERS_COUNT = 0;
	SET VAR_DEMO = '';
	SET @VAR_CLONE = '';
	
	IF VAR_IN_DEMO > 0
	THEN
	
		SET VAR_DEMO = 'demo_';
	
	END IF;
	
	IF VAR_IN_CLONE > 0
	THEN
	
		SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id`, `left_key`, `right_key`, `level`, `level_1` INTO  @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT 
		FROM `",
		@VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER,
		"` WHERE (`partner_id` = ",
		VAR_IN_SPONSOR_ID,
		" AND `change` = 0) AND `clone` > 0 AND `close_date` = 0
		ORDER BY `level` ASC, `left_key` ASC LIMIT 0, 1;");
		
		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
		
		SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_OUT_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;

		IF @VAR_ID = 0
		THEN
	
			SET @VAR_QUERY = CONCAT("SELECT `",
			VAR_DEMO,
			"matrix_",
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER,"`.`id`, 
			`",
			VAR_DEMO,
			"matrix_",
			VAR_IN_STRUCTURE_NUMBER,
			"_", 
			VAR_IN_MATRIX_NUMBER, "`.`partner_id`, 
			`",
			VAR_DEMO,
			"matrix_", 
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER, "`.`left_key`, 
			`",
			VAR_DEMO,
			"matrix_", 
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER, "`.`right_key`, 
			`",
			VAR_DEMO,
			"matrix_", 
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER, "`.`level`, 
			`",
			VAR_DEMO,
			"matrix_", 
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER, "`.`level_1` 
			INTO  @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT 
			FROM `",
			VAR_DEMO,
			"matrix_", 
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER, "` 
			LEFT JOIN `partners` ON `partners`.`id` = `",
			VAR_DEMO,
			"matrix_", 
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER, "`.`partner_id` 
			WHERE `partners`.`left_key` <= ", VAR_IN_SPONSOR_LEFT_KEY, " AND `partners`.`right_key` >= ", VAR_IN_SPONSOR_RIGHT_KEY, " AND `",
			VAR_DEMO,
			"matrix_",
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER,"`.`change` = 0
			ORDER BY `partners`.`level` DESC, `partners`.`left_key` DESC 
			LIMIT 0, 1;");
			
			PREPARE stmt FROM @VAR_QUERY;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;
		
			SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;
		
		END IF;
		
	ELSE
	
		SET @VAR_QUERY = CONCAT("SELECT `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER,"`.`id`, 
		`",
		VAR_DEMO,
		"matrix_", 
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER, "`.`partner_id`, 
		`",
		VAR_DEMO,
		"matrix_", 
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER, "`.`left_key`, 
		`",
		VAR_DEMO,
		"matrix_", 
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER, "`.`right_key`, 
		`",
		VAR_DEMO,
		"matrix_", 
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER, "`.`level`, 
		`",
		VAR_DEMO,
		"matrix_", 
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER, "`.`level_1` 
		INTO  @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT 
		FROM `",
		VAR_DEMO,
		"matrix_", 
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER, "` 
		LEFT JOIN `partners` ON `partners`.`id` = `",
		VAR_DEMO,
		"matrix_", 
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER, "`.`partner_id` 
		WHERE `partners`.`left_key` <= ", VAR_IN_SPONSOR_LEFT_KEY, " AND `partners`.`right_key` >= ", VAR_IN_SPONSOR_RIGHT_KEY, " AND (`",
		VAR_DEMO,
		"matrix_", 
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER, "`.`partner_id` != ", VAR_IN_SPONSOR_ID, " AND `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER,"`.`change` = 0)
		ORDER BY `partners`.`level` DESC, `partners`.`left_key` DESC 
		LIMIT 0, 1;");
		
		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
    
		SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_OUT_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;

	END IF;
	
	IF @VAR_ID > 0
	THEN

		SET VAR_OUT_ID = @VAR_ID;
		SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
		SET VAR_OUT_SPONSOR_LEFT_KEY = @VAR_SPONSOR_LEFT_KEY;
		SET VAR_OUT_SPONSOR_RIGHT_KEY = @VAR_SPONSOR_RIGHT_KEY;
		SET VAR_OUT_SPONSOR_LEVEL = @VAR_SPONSOR_LEVEL;
		SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
		
	ELSE
	
		SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id`, `left_key`, `right_key`, `level`, `level_1` INTO  @VAR_ID, @VAR_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT 
		FROM `",@VAR_DEMO,"matrix_",
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER,"` 
		WHERE (`partner_id` = 1 AND `change` = 0)
		ORDER BY `level` ASC, `left_key` ASC LIMIT 0, 1;");
		
		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
		
		SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;
		
		SET VAR_OUT_ID = @VAR_ID;
		SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
		SET VAR_OUT_SPONSOR_LEFT_KEY = @VAR_SPONSOR_LEFT_KEY;
		SET VAR_OUT_SPONSOR_RIGHT_KEY = @VAR_SPONSOR_RIGHT_KEY;
		SET VAR_OUT_SPONSOR_LEVEL = @VAR_SPONSOR_LEVEL;
		SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
		
	END IF;
	
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_partner_matrix_number`;
CREATE PROCEDURE `get_partner_matrix_number`(IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_PARTNER_ID` INT(10), OUT `VAR_OUT_CHECK_MATRIX` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_CHECK_MATRIX TINYINT(8);
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_CHECK_MATRIX = 0;
SET VAR_DEMO = '';
SET VAR_OUT_CHECK_MATRIX = 0;

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `",
VAR_DEMO,
"matrix` INTO @VAR_CHECK_MATRIX
FROM `partners`
WHERE `id` = ",
VAR_IN_PARTNER_ID,
";");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_CHECK_MATRIX;

SET VAR_OUT_CHECK_MATRIX = @VAR_CHECK_MATRIX;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_pay`;
CREATE PROCEDURE `get_pay`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), OUT `VAR_OUT_PAY` DECIMAL(10,2), OUT `VAR_OUT_TYPE` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;

SET @VAR_QUERY = '';
SET VAR_OUT_PAY = 0;
SET VAR_OUT_TYPE = 0;

SET @VAR_QUERY = CONCAT("SELECT `type`, `pay` INTO @VAR_TYPE, @VAR_PAY
FROM `matrices_settings_", VAR_IN_STRUCTURE_NUMBER, "` 
WHERE `number` = '",
VAR_IN_MATRIX_NUMBER,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_TYPE, @VAR_PAY;
    
    SET VAR_OUT_PAY = @VAR_PAY;
SET VAR_OUT_TYPE = @VAR_TYPE;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_referral_balls`;
CREATE PROCEDURE `get_referral_balls`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), OUT `VAR_OUT_REFERRAL_MATRIX_BALLS` INT(10))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_REFERRAL_MATRIX_BALLS INT(10);

SET VAR_REFERRAL_MATRIX_BALLS = 0;
SET VAR_OUT_REFERRAL_MATRIX_BALLS = 0;

SET @VAR_QUERY = CONCAT("SELECT `referral_matrix_balls` INTO @VAR_REFERRAL_MATRIX_BALLS
FROM `matrices_settings_", VAR_IN_STRUCTURE_NUMBER, "`  
WHERE `number` = '",
VAR_IN_MATRIX_NUMBER,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_REFERRAL_MATRIX_BALLS;
    
    SET VAR_OUT_REFERRAL_MATRIX_BALLS = @VAR_REFERRAL_MATRIX_BALLS;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_sponsor_data`;
CREATE PROCEDURE `get_sponsor_data`(IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_CLONE` TINYINT(8), IN `VAR_IN_ROOT_MATRIX` TINYINT(8), IN `VAR_IN_LEVELS` INT(10), OUT `VAR_OUT_ID` INT(10), OUT `VAR_OUT_SPONSOR_ID` INT(11), OUT `VAR_OUT_PARENT_SPONSOR_ID` INT(10), OUT `VAR_OUT_SPONSOR_LEFT_KEY` INT(10), OUT `VAR_OUT_SPONSOR_RIGHT_KEY` INT(10), OUT `VAR_OUT_SPONSOR_LEVEL` INT(10), OUT `VAR_OUT_SPONSOR_PARTNERS_COUNT` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_WHERE_CONDITIONS TEXT;
	DECLARE VAR_ID INT(10);
	DECLARE VAR_SPONSOR_ID INT(11);
	DECLARE VAR_PARENT_SPONSOR_ID INT(10);
	DECLARE VAR_SPONSOR_LEFT_KEY INT(10);
	DECLARE VAR_SPONSOR_RIGHT_KEY INT(10);
	DECLARE VAR_SPONSOR_LEVEL INT(10);
	DECLARE VAR_SPONSOR_PARTNERS_COUNT TINYINT(8);
	DECLARE VAR_MATRIX_LEVEL1 TINYINT(8);
	DECLARE VAR_MATRIX_LEVEL2 TINYINT(8);
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
	DECLARE VAR_CLONE VARCHAR(16) CHARSET utf8;
	DECLARE VAR_ROOT_MATRIX VARCHAR(32) CHARSET utf8;
		
	SET @VAR_ID = 0;	
	SET @VAR_SPONSOR_ID = 0;
	SET @VAR_PARENT_SPONSOR_ID = 0;
	SET @VAR_SPONSOR_LEFT_KEY = 0;
	SET @VAR_SPONSOR_RIGHT_KEY = 0;
	SET @VAR_SPONSOR_LEVEL = 0;
	SET @VAR_SPONSOR_PARTNERS_COUNT = 0;
	SET @VAR_MATRIX_LEVEL1 = 2;
	SET @VAR_MATRIX_LEVEL2 = 4;
	SET VAR_OUT_SPONSOR_ID = 0;
	SET VAR_OUT_PARENT_SPONSOR_ID = 0;
	SET VAR_OUT_SPONSOR_LEFT_KEY = 0;
	SET VAR_OUT_SPONSOR_RIGHT_KEY = 0;
	SET VAR_OUT_SPONSOR_LEVEL = 0;
	SET VAR_OUT_SPONSOR_PARTNERS_COUNT = 0;
	SET @VAR_DEMO = '';
	SET @VAR_CLONE = '';
	SET @VAR_ROOT_MATRIX = '';
	SET @VAR_WHERE_CONDITIONS = '';
	
	IF VAR_IN_DEMO > 0
	THEN
	
		SET @VAR_DEMO = 'demo_';
	
	END IF;
	
	IF VAR_IN_CLONE > 0
	THEN
	
		SET @VAR_CLONE = ' AND `clone` = 1';
		
	ELSE
	
		SET @VAR_CLONE = ' AND `clone` = 0';
	
	END IF;
	
	IF VAR_IN_ROOT_MATRIX > 0
	THEN
	
		SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id`, `parent_id`, `left_key`, `right_key`, `level`, `level_1` INTO @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT 
		FROM `",
		@VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER,
		"` WHERE (`partner_id` = ",
		VAR_IN_SPONSOR_ID," AND `change` = 0)
		ORDER BY `level` ASC, `left_key` ASC LIMIT 0, 1;");
		
		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
		
		SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;

		IF @VAR_ID > 0
		THEN
		
			SET VAR_OUT_ID = @VAR_ID;
			SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
			SET VAR_OUT_PARENT_SPONSOR_ID = @VAR_PARENT_SPONSOR_ID;
			SET VAR_OUT_SPONSOR_LEFT_KEY = @VAR_SPONSOR_LEFT_KEY;
			SET VAR_OUT_SPONSOR_RIGHT_KEY = @VAR_SPONSOR_RIGHT_KEY;
			SET VAR_OUT_SPONSOR_LEVEL = @VAR_SPONSOR_LEVEL;
			SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
			
		END IF;
	
	ELSE
	
		SET @VAR_I = 0;
											
		sponsor_levels: LOOP
			
			SET @VAR_I = @VAR_I + 1;	
			SET @VAR_WHERE_CONDITIONS = CONCAT(@VAR_WHERE_CONDITIONS, "`level_",@VAR_I,"` < POW(2, ",@VAR_I,") OR ");
											
			IF @VAR_I = VAR_IN_LEVELS 
			THEN
												 
				LEAVE sponsor_levels;
													 
			END IF;		
												 
		END LOOP sponsor_levels;
	
		SET @VAR_WHERE_CONDITIONS = LEFT(@VAR_WHERE_CONDITIONS, CHAR_LENGTH(@VAR_WHERE_CONDITIONS) - 4);
		SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id`, `parent_id`, `left_key`, `right_key`, `level`, `level_1` INTO @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT 
		FROM `",
		@VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER,
		"_",
		VAR_IN_MATRIX_NUMBER,
		"` WHERE (`partner_id` = ",
		VAR_IN_SPONSOR_ID, 
		" AND `change` = 0) AND (",
		@VAR_WHERE_CONDITIONS,") 
		ORDER BY `level` ASC, `left_key` ASC LIMIT 0, 1;");
		
		PREPARE stmt FROM @VAR_QUERY;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;
		
		SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;

		IF @VAR_ID > 0
		THEN
		
			SET VAR_OUT_ID = @VAR_ID;
			SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
			SET VAR_OUT_PARENT_SPONSOR_ID = @VAR_PARENT_SPONSOR_ID;
			SET VAR_OUT_SPONSOR_LEFT_KEY = @VAR_SPONSOR_LEFT_KEY;
			SET VAR_OUT_SPONSOR_RIGHT_KEY = @VAR_SPONSOR_RIGHT_KEY;
			SET VAR_OUT_SPONSOR_LEVEL = @VAR_SPONSOR_LEVEL;
			SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
			
		ELSE
		
			SET @VAR_QUERY = CONCAT("SELECT `id`, `partner_id`, `parent_id`, `left_key`, `right_key`, `level`, `level_1` INTO @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT 
			FROM `",
			@VAR_DEMO,
			"matrix_",
			VAR_IN_STRUCTURE_NUMBER,
			"_",
			VAR_IN_MATRIX_NUMBER,
			"` WHERE (`partner_id` = ",
			VAR_IN_SPONSOR_ID," AND `change` = 0)
			ORDER BY `level` ASC, `left_key` ASC LIMIT 0, 1;");
		
			PREPARE stmt FROM @VAR_QUERY;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;
			
			SELECT @VAR_ID, @VAR_SPONSOR_ID, @VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_LEFT_KEY, @VAR_SPONSOR_RIGHT_KEY, @VAR_SPONSOR_LEVEL, @VAR_SPONSOR_PARTNERS_COUNT;

			IF @VAR_ID > 0
			THEN
			
				SET VAR_OUT_ID = @VAR_ID;
				SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;
				SET VAR_OUT_PARENT_SPONSOR_ID = @VAR_PARENT_SPONSOR_ID;
				SET VAR_OUT_SPONSOR_LEFT_KEY = @VAR_SPONSOR_LEFT_KEY;
				SET VAR_OUT_SPONSOR_RIGHT_KEY = @VAR_SPONSOR_RIGHT_KEY;
				SET VAR_OUT_SPONSOR_LEVEL = @VAR_SPONSOR_LEVEL;
				SET VAR_OUT_SPONSOR_PARTNERS_COUNT = @VAR_SPONSOR_PARTNERS_COUNT;
				
			END IF;
		
		END IF;
	
	END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_sponsor_id`;
CREATE PROCEDURE `get_sponsor_id`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_ID` INT(11), OUT VAR_OUT_SPONSOR_ID INT(10))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_SPONSOR_ID INT(10);

SET VAR_SPONSOR_ID = 0;
SET VAR_OUT_SPONSOR_ID = 0;

SET @VAR_QUERY = CONCAT("SELECT `matrix_id` INTO @VAR_SPONSOR_ID
FROM `demo_matrix_", VAR_IN_MATRIX_NUMBER ,"`
WHERE `id` = '", VAR_IN_ID ,"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_SPONSOR_ID;
    
    SET VAR_OUT_SPONSOR_ID = @VAR_SPONSOR_ID;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `get_sponsor_partners_count`;
CREATE PROCEDURE `get_sponsor_partners_count`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_ID` INT(11), IN `VAR_IN_LEVEL` TINYINT(8), OUT `VAR_OUT_PARTNER_ID` INT(10), OUT VAR_OUT_PARTNER_LEVEL INT(10), OUT `VAR_OUT_MATRIX_ID` INT(10), OUT `VAR_OUT_VAR_CHECK_PARTNERS_COUNT` INT(10))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_MATRIX_ID INT(10);
	DECLARE VAR_PARTNER_ID INT(10);
	DECLARE VAR_PARTNER_LEVEL INT(10);
	DECLARE VAR_CHECK_PARTNERS_COUNT INT(10);
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

	SET VAR_MATRIX_ID = 0;
	SET VAR_PARTNER_ID = 0;
	SET VAR_PARTNER_LEVEL = 0;
	SET VAR_CHECK_PARTNERS_COUNT = 0;
	SET VAR_DEMO = '';

	IF VAR_IN_DEMO > 0
	THEN

		SET VAR_DEMO = 'demo_';

	END IF;

	IF VAR_IN_ID = 1
	THEN

		SET @VAR_QUERY = CONCAT("SELECT `partner_id`, `level`, `matrix_id`, `level_", VAR_IN_LEVEL, "` INTO @VAR_PARTNER_ID, @VAR_PARTNER_LEVEL, @VAR_MATRIX_ID, @VAR_CHECK_PARTNERS_COUNT 
		FROM `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER, "_",
		VAR_IN_MATRIX_NUMBER,
		"` WHERE `id` = '",
		VAR_IN_ID,
		"'");

	ELSE

		SET @VAR_QUERY = CONCAT("SELECT `sponsor_matrix`.`partner_id`, `sponsor_matrix`.`level`, `partner_matrix`.`matrix_id`, `partner_matrix`.`level_", VAR_IN_LEVEL, "` INTO @VAR_PARTNER_ID, @VAR_PARTNER_LEVEL, @VAR_MATRIX_ID, @VAR_CHECK_PARTNERS_COUNT 
		FROM `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER, "_",
		VAR_IN_MATRIX_NUMBER,
		"` AS `partner_matrix` 
		LEFT JOIN `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_STRUCTURE_NUMBER, "_",
		VAR_IN_MATRIX_NUMBER,
		"` AS `sponsor_matrix` ON `sponsor_matrix`.`id` = `partner_matrix`.`matrix_id` 
		WHERE `partner_matrix`.`id` = '",
		VAR_IN_ID,
		"'");

	END IF;

	PREPARE stmt FROM @VAR_QUERY;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
		
	SELECT @VAR_PARTNER_ID, @VAR_PARTNER_LEVEL, @VAR_MATRIX_ID, @VAR_SPONSOR_PARTNERS_COUNT;
	
	SET VAR_OUT_PARTNER_ID = @VAR_PARTNER_ID;
	SET VAR_OUT_PARTNER_LEVEL = @VAR_PARTNER_LEVEL;
	SET VAR_OUT_MATRIX_ID = @VAR_MATRIX_ID;
	SET VAR_OUT_VAR_CHECK_PARTNERS_COUNT = @VAR_CHECK_PARTNERS_COUNT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `gold_token`;
CREATE PROCEDURE `gold_token`(IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_PARTNER_ID` INT(10), IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX` VARCHAR(20) CHARSET utf8, IN `VAR_IN_DATE` VARCHAR(24) CHARSET utf8, IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_STATUS` TINYINT(8), IN `VAR_IN_RESERVE` TINYINT(8), IN `VAR_IN_CLASSIC_STUCTURE` TINYINT(8), IN `VAR_IN_GOLD_TOKEN_NUMBER` INT(10), IN `VAR_IN_ROOT_MATRIX` TINYINT(8), OUT `VAR_OUT_RESULT` INT(8), OUT `VAR_OUT_RESULT2` TEXT CHARSET utf8)
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS INT(8);
	DECLARE VAR_PARTNER_LEFT_KEY INT(10);
	DECLARE VAR_PARTNER_RIGHT_KEY INT(10);
	DECLARE VAR_PARTNER_LEVEL INT(10);
	DECLARE VAR_DEPTH_LEVEL TINYINT(8);
	DECLARE VAR_MATRIX_ID INT(10);
	DECLARE VAR_TYPE TINYINT(8);
	DECLARE VAR_PAY DECIMAL(10,2);
	DECLARE VAR_AMOUNT DECIMAL(10,2);
	DECLARE VAR_RESULT TINYINT(8);
	DECLARE VAR_RESULT2 TEXT;
	
	SET VAR_PARTNER_LEFT_KEY = 0;
	SET VAR_PARTNER_RIGHT_KEY = 0;
	SET VAR_PARTNER_LEVEL = 0;
	SET VAR_MATRIX_ID = 0;
	SET VAR_DEPTH_LEVEL = 0;
	SET VAR_PAY = 0;
	SET VAR_AMOUNT = 0;
	SET VAR_AFFECTED_ROWS = 0;
	SET VAR_RESULT = 0;
	SET VAR_RESULT2 = '';
	SET VAR_OUT_RESULT = 0;
	SET VAR_OUT_RESULT2 = '';
	SET VAR_OUT_RESULT = 0;
	
	START TRANSACTION;
	
		CALL `get_pay`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_PAY, VAR_TYPE);
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'01',';;;;',VAR_TYPE,';;;;',VAR_PAY,';;;;');
				
		IF VAR_TYPE > 0 
		THEN
				
			IF VAR_TYPE > 1 
			THEN
			
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',VAR_IN_SPONSOR_ID,';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_STATUS,';;;;',VAR_IN_RESERVE,';;;;');	
				CALL `binar_matrix`(VAR_IN_SPONSOR_ID, VAR_IN_PARTNER_ID, VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_DATE, VAR_IN_DEMO, VAR_IN_STATUS, 0, VAR_IN_RESERVE, 0, VAR_IN_ROOT_MATRIX, 0, VAR_RESULT, VAR_RESULT2);
					
			ELSE
			
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_DEMO,';;;;',VAR_IN_STATUS,';;;;');
				CALL `linear_matrix`(VAR_IN_PARTNER_ID, VAR_IN_MATRIX, VAR_IN_DEMO, VAR_IN_DATE, VAR_IN_STATUS, VAR_RESULT, VAR_RESULT2);
				
			END IF;
				
		END IF;
		
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'03',';;;;',VAR_RESULT,';;;;');
		
		IF (VAR_RESULT > 0)
		THEN
		
			IF (VAR_IN_CLASSIC_STUCTURE > 0)
			THEN
						
				CALL `get_levels_depth`(VAR_DEPTH_LEVEL); 
				
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'04',';;;;',VAR_DEPTH_LEVEL,';;;;');
						
				IF (VAR_DEPTH_LEVEL > 0)
				THEN
					
					SELECT `level`, `left_key`, `right_key` INTO VAR_PARTNER_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY
					FROM `partners` 
					WHERE `id` = VAR_IN_PARTNER_ID;
					
					IF (VAR_PARTNER_LEFT_KEY > 0 && VAR_PARTNER_RIGHT_KEY > 0 && VAR_PARTNER_LEVEL > 0)
					THEN
						
						SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'05',';;;;',VAR_PARTNER_LEFT_KEY,';;;;',VAR_PARTNER_RIGHT_KEY,';;;;',VAR_PARTNER_LEVEL,';;;;');
						
						CALL `get_matrix_id`(VAR_IN_MATRIX, VAR_IN_DEMO, VAR_MATRIX_ID); 
							
						IF (VAR_MATRIX_ID > 0)
						THEN	
							
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',VAR_MATRIX_ID,';;;;');
							
							IF (VAR_DEPTH_LEVEL > VAR_PARTNER_LEVEL)
							THEN
								
								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_DEPTH_LEVEL,';;;;',VAR_PARTNER_LEFT_KEY,';;;;',VAR_PARTNER_RIGHT_KEY,';;;;',VAR_PARTNER_LEVEL,';;;;',VAR_MATRIX_ID,';;;;',VAR_PAY,';;;;',';;;;',VAR_IN_DEMO,';;;;');		
								CALL `levels_payments_depth_more_referal_level`(VAR_IN_PARTNER_ID, VAR_DEPTH_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY, VAR_PARTNER_LEVEL, VAR_MATRIX_ID, VAR_PAY, VAR_IN_DEMO, VAR_AFFECTED_ROWS);
								
							ELSE
									
								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'07',';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_DEPTH_LEVEL,';;;;',VAR_PARTNER_LEFT_KEY,';;;;',VAR_PARTNER_RIGHT_KEY,';;;;',VAR_PARTNER_LEVEL,';;;;',VAR_MATRIX_ID,';;;;',VAR_PAY,';;;;',';;;;',VAR_IN_DEMO,';;;;');		
								CALL `levels_payments_depth_lesser_referal_level`(VAR_IN_PARTNER_ID, VAR_DEPTH_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY, VAR_PARTNER_LEVEL, VAR_MATRIX_ID, VAR_PAY, VAR_IN_DEMO, VAR_AFFECTED_ROWS);

							END IF;
							
						END IF;
								
						IF VAR_AFFECTED_ROWS > 0 
						THEN

							SET VAR_RESULT = 1;
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'08',';;;;',VAR_RESULT,';;;;');
							
						END IF;
							
					END IF;
					
				END IF;
				
			END IF;
			
		END IF;
		
		IF VAR_RESULT > 0 
		THEN
			
			SET VAR_RESULT = 0;
			
			CALL `get_matrix_id`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_DEMO, VAR_MATRIX_ID); 
							
			IF (VAR_MATRIX_ID > 0)
			THEN
			
				SELECT `amount` INTO VAR_AMOUNT
				FROM `gold_token_settings` 
				WHERE `id` = VAR_IN_GOLD_TOKEN_NUMBER;
						
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'11',';;;;',VAR_AMOUNT,';;;;');
					
				IF VAR_AMOUNT > 0 
				THEN
				
					SET VAR_AFFECTED_ROWS = 0;		
					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'12',';;;;',VAR_MATRIX_ID,';;;;');
						
					INSERT INTO `gold_token` (`partner_id`, `matrix_id`, `structure_number`, `matrix`, `amount`, `created_at`) VALUES (VAR_IN_SPONSOR_ID, VAR_MATRIX_ID, VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_AMOUNT, UNIX_TIMESTAMP());

					SET VAR_AFFECTED_ROWS = (SELECT ROW_COUNT());

					IF VAR_AFFECTED_ROWS > 0 
					THEN
						
						SET VAR_AFFECTED_ROWS = 0;
							
						UPDATE `partners` SET `gold_token` = `gold_token` + VAR_AMOUNT 
						WHERE `id` = VAR_IN_SPONSOR_ID;
							
						SET VAR_RESULT = (SELECT ROW_COUNT());
						SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'13',';;;;',VAR_RESULT,';;;;');
			
					END IF;
				
				END IF;
			
			END IF;
			
		END IF;
		
		SET VAR_OUT_RESULT = VAR_RESULT;
	
	IF VAR_RESULT > 0 
	THEN
		
		SET VAR_OUT_RESULT = 1;
		COMMIT;
		
	ELSE
			
		ROLLBACK;

	END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `levels_payments`;
CREATE PROCEDURE `levels_payments`(IN `VAR_IN_REFFERAL_ID` INT(11), IN `VAR_IN_LEVEL_DEPTH` TINYINT(8), IN `VAR_IN_REFFERAL_LEFT_KEY` INT(10), IN `VAR_IN_REFFERAL_RIGHT_KEY` INT(10), IN `VAR_IN_REFFERAL_LEVEL` INT(10), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_CREDIT` TINYINT(4), OUT `VAR_OUT_RESULT` TINYINT(4))
    NO SQL
BEGIN

SET VAR_OUT_RESULT = 0;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `levels_payments_depth_lesser_referal_level`;
CREATE PROCEDURE `levels_payments_depth_lesser_referal_level`(IN `VAR_IN_REFFERAL_ID` INT(11), IN `VAR_IN_LEVEL_DEPTH` TINYINT(8), IN `VAR_IN_REFFERAL_LEFT_KEY` INT(10), IN `VAR_IN_REFFERAL_RIGHT_KEY` INT(10), IN `VAR_IN_REFFERAL_LEVEL` INT(10), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(4))
    NO SQL
BEGIN

DECLARE VAR_AFFECTED_ROWS INT(10);
DECLARE VAR_ID INT(11);
DECLARE VAR_SPONSOR_ID INT(11);
DECLARE VAR_SPONSOR_ID2 INT(11);
DECLARE VAR_LOOP_FINISHED INT(11);
DECLARE VAR_SPONSOR_LEVEL TINYINT(8);
DECLARE VAR_SPONSOR_PERCENT TINYINT(8);

DECLARE partners_cursor CURSOR FOR 
SELECT `id`, `sponsor_id` 
FROM `partners` 
WHERE `left_key` <= VAR_IN_REFFERAL_LEFT_KEY AND `right_key` >= VAR_IN_REFFERAL_RIGHT_KEY AND 
(`level` >= VAR_IN_REFFERAL_LEVEL - VAR_IN_LEVEL_DEPTH AND `level` <= VAR_IN_REFFERAL_LEVEL)
AND `id` != VAR_IN_REFFERAL_ID 
ORDER BY `level` DESC;

DECLARE CONTINUE HANDLER 
FOR NOT FOUND SET VAR_LOOP_FINISHED = 1;

SET VAR_AFFECTED_ROWS = 0;
SET VAR_LOOP_FINISHED = 0;
SET VAR_ID = 0;
SET VAR_SPONSOR_ID = 0;
SET VAR_SPONSOR_ID2 = 0;
SET VAR_SPONSOR_LEVEL = 1;
SET VAR_SPONSOR_PERCENT = 0;
SET VAR_OUT_RESULT = 0;

OPEN partners_cursor;

get_partners: LOOP

FETCH partners_cursor INTO VAR_ID, VAR_SPONSOR_ID;

IF VAR_LOOP_FINISHED = 1 THEN

SET VAR_OUT_RESULT = 1; 
LEAVE get_partners;

END IF;

IF (VAR_SPONSOR_ID2 = VAR_ID || VAR_SPONSOR_LEVEL = 1)
THEN

SET VAR_SPONSOR_ID2 = VAR_SPONSOR_ID;

CALL `get_levels_percentage`(VAR_SPONSOR_LEVEL, @p1); 
SELECT @p1 INTO `VAR_SPONSOR_PERCENT`;

IF (VAR_SPONSOR_PERCENT > 0)
THEN

CALL `set_levels_payments`(VAR_IN_MATRIX_ID, VAR_ID, VAR_IN_REFFERAL_ID, VAR_SPONSOR_LEVEL, (VAR_SPONSOR_PERCENT * VAR_IN_AMOUNT / 100), VAR_IN_DEMO, @p1); 
SELECT @p1 INTO `VAR_AFFECTED_ROWS`;

IF VAR_AFFECTED_ROWS <= 0 
THEN

LEAVE get_partners;

END IF;

SET VAR_SPONSOR_PERCENT = 0;

ELSE

LEAVE get_partners;

END IF;

SET VAR_SPONSOR_LEVEL = VAR_SPONSOR_LEVEL + 1;

END IF;

END LOOP get_partners;

CLOSE partners_cursor;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `levels_payments_depth_more_referal_level`;
CREATE PROCEDURE `levels_payments_depth_more_referal_level`(IN `VAR_IN_REFFERAL_ID` INT(11), IN `VAR_IN_LEVEL_DEPTH` TINYINT(8), IN `VAR_IN_REFFERAL_LEFT_KEY` INT(10), IN `VAR_IN_REFFERAL_RIGHT_KEY` INT(10), IN `VAR_IN_REFFERAL_LEVEL` INT(10), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(4))
    NO SQL
BEGIN

DECLARE VAR_AFFECTED_ROWS INT(10);
DECLARE VAR_ID INT(11);
DECLARE VAR_SPONSOR_ID INT(11);
DECLARE VAR_SPONSOR_ID2 INT(11);
DECLARE VAR_LOOP_FINISHED INT(11);
DECLARE VAR_SPONSOR_LEVEL TINYINT(8);
DECLARE VAR_SPONSOR_PERCENT TINYINT(8);

DECLARE partners_cursor CURSOR FOR 
SELECT `id`, `sponsor_id` 
FROM `partners` 
WHERE `left_key` <= VAR_IN_REFFERAL_LEFT_KEY AND `right_key` >= VAR_IN_REFFERAL_RIGHT_KEY AND 
(`level` >= 1 AND `level` < VAR_IN_REFFERAL_LEVEL)
AND `id` != VAR_IN_REFFERAL_ID 
ORDER BY `level` DESC;

DECLARE CONTINUE HANDLER 
FOR NOT FOUND SET VAR_LOOP_FINISHED = 1;

SET VAR_AFFECTED_ROWS = 0;
SET VAR_LOOP_FINISHED = 0;
SET VAR_ID = 0;
SET VAR_SPONSOR_ID = 0;
SET VAR_SPONSOR_ID2 = 0;
SET VAR_SPONSOR_LEVEL = 1;
SET VAR_SPONSOR_PERCENT = 0;
SET VAR_OUT_RESULT = 0;

OPEN partners_cursor;

get_partners: LOOP

FETCH partners_cursor INTO VAR_ID, VAR_SPONSOR_ID;

IF VAR_LOOP_FINISHED = 1 THEN

SET VAR_OUT_RESULT = 1; 
LEAVE get_partners;

END IF;

IF (VAR_SPONSOR_ID2 = VAR_ID || VAR_SPONSOR_LEVEL = 1)
THEN

SET VAR_SPONSOR_ID2 = VAR_SPONSOR_ID;

CALL `get_levels_percentage`(VAR_SPONSOR_LEVEL, @p1); 
SELECT @p1 INTO `VAR_SPONSOR_PERCENT`;

IF (VAR_SPONSOR_PERCENT > 0)
THEN

CALL `set_levels_payments`(VAR_IN_MATRIX_ID, VAR_ID, VAR_IN_REFFERAL_ID, VAR_SPONSOR_LEVEL, (VAR_SPONSOR_PERCENT * VAR_IN_AMOUNT / 100), VAR_IN_DEMO, @p1); 
SELECT @p1 INTO `VAR_AFFECTED_ROWS`;

IF VAR_AFFECTED_ROWS <= 0 
THEN

LEAVE get_partners;

END IF;

SET VAR_SPONSOR_PERCENT = 0;

ELSE

LEAVE get_partners;

END IF;

SET VAR_SPONSOR_LEVEL = VAR_SPONSOR_LEVEL + 1;

END IF;

END LOOP get_partners;

CLOSE partners_cursor;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `linear_matrix`;
CREATE PROCEDURE `linear_matrix`(IN `VAR_IN_PARTNER_ID` INT(10), IN `VAR_IN_MATRIX` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_DATE` VARCHAR(24) CHARSET utf8, IN `VAR_IN_STATUS` TINYINT(8), OUT `VAR_OUT_RESULT` VARCHAR(255) CHARSET utf8, OUT `VAR_OUT_RESULT2` TEXT CHARSET utf8)
    NO SQL
BEGIN

DECLARE VAR_AFFECTED_ROWS INT(8);
DECLARE VAR_ROLLBACK TINYINT(4);
DECLARE VAR_UPDATE_QUERY TINYINT(4);
DECLARE VAR_MATRIX_COUNT TINYINT(8);
DECLARE VAR_ID INT(10);
DECLARE VAR_SPONSOR_ID INT(11);
DECLARE VAR_PARENT_SPONSOR_ID INT(10);
DECLARE VAR_MATRIX_ID INT(10);
DECLARE VAR_SPONSOR_LEVEL INT(10);
DECLARE VAR_LAST_LEVEL INT(10);
DECLARE VAR_CHECK_PARTNERS_COUNT INT(10);
DECLARE VAR_CHECK_MATRIX TINYINT(8);
DECLARE VAR_PARTNER_LEFT_KEY INT(10);
DECLARE VAR_PARTNER_RIGHT_KEY INT(10);
DECLARE VAR_PARTNER_LEVEL INT(10);
DECLARE VAR_MATRIX_TYPE TINYINT(8);
DECLARE VAR_MATRIX_LEVELS TINYINT(8);
DECLARE VAR_DEPTH_LEVEL TINYINT(8);
DECLARE VAR_MATRIX_PAY DECIMAL(10,2);
DECLARE VAR_MATRIX_PAY_OFF DECIMAL(10,2);
DECLARE VAR_MATRIX_CLONE TINYINT(8);
DECLARE VAR_MATRIX_CLONE_CYCLE TINYINT(8);
DECLARE VAR_ADMIN_PAY DECIMAL(10,2);
DECLARE VAR_CLONE_MATRIX_PAY DECIMAL(10,2);
DECLARE VAR_ADMIN_PAY_OFF DECIMAL(10,2);
DECLARE VAR_CLONE_ADMIN_PAY_OFF DECIMAL(10,2);
DECLARE VAR_CLONE_MATRIX_PAY_OFF DECIMAL(10,2);
DECLARE VAR_CLOSE_MATRIX_BALLS INT(10);
DECLARE VAR_CLONE_SPONSOR_MATRIX_ID INT(10);
DECLARE VAR_REFERRAL_MATRIX_BALLS INT(10);
DECLARE VAR_RESULT TEXT;

SET @VAR_AFFECTED_ROWS = 0;
SET @VAR_ROLLBACK = 1;
SET @VAR_UPDATE_QUERY = 1;
SET @VAR_ID = 0;
SET @VAR_SPONSOR_ID = 0;
SET @VAR_PARENT_SPONSOR_ID = 0;
SET @VAR_MATRIX_ID = 0;
SET @VAR_CLONE_SPONSOR_MATRIX_ID = 0;
SET @VAR_LAST_LEVEL = 0;
SET @VAR_SPONSOR_LEVEL = 0;
SET @VAR_CHECK_MATRIX = 0;
SET @VAR_PARTNER_LEFT_KEY = 0;
SET @VAR_PARTNER_RIGHT_KEY = 0;
SET @VAR_PARTNER_LEVEL = 0;
SET @VAR_MATRIX_TYPE = 0;
SET @VAR_MATRIX_LEVELS = 0;
SET @VAR_DEPTH_LEVEL = 0;
SET @VAR_MATRIX_CLONE = 0;
SET @VAR_CLONE_MATRIX_PAY = 0;
SET @VAR_MATRIX_CLONE_CYCLE = 0;
SET @VAR_MATRIX_PAY = 0;
SET @VAR_MATRIX_PAY_OFF = 0;
SET @VAR_ADMIN_PAY_OFF = 0;
SET @VAR_CLONE_ADMIN_PAY_OFF = 0;
SET @VAR_CLONE_MATRIX_PAY_OFF = 0;
SET @VAR_CLOSE_MATRIX_BALLS = 0;
SET @VAR_REFERRAL_MATRIX_BALLS = 0;
SET @VAR_RESULT = '';
SET VAR_OUT_RESULT = 0;
SET VAR_OUT_RESULT2 = '';

START TRANSACTION;

SET GLOBAL max_allowed_packet = 20000000;
SET GLOBAL max_sp_recursion_depth = 255;
SET session max_sp_recursion_depth = 255;

	CALL `get_max_level`(VAR_IN_MATRIX, VAR_IN_DEMO, @VAR_LAST_LEVEL); 
	
	SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'01',';;;;',@VAR_LAST_LEVEL,';;;;');

	IF (@VAR_LAST_LEVEL > 0)
	THEN

		CALL `set_linear_matrix_partner_data`(VAR_IN_MATRIX, VAR_IN_DEMO, VAR_IN_PARTNER_ID, @VAR_LAST_LEVEL, VAR_IN_DATE, @VAR_AFFECTED_ROWS, @VAR_MATRIX_ID); 
		
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',@VAR_MATRIX_ID,';;;;');
		
		IF @VAR_AFFECTED_ROWS > 0 
		THEN

			CALL `get_partner_matrix_number`(VAR_IN_DEMO, VAR_IN_PARTNER_ID, @VAR_CHECK_MATRIX); 
			
			IF (@VAR_CHECK_MATRIX < VAR_IN_MATRIX)
			THEN

				SET @VAR_AFFECTED_ROWS = 0;

				CALL `update_partner_matrix_number`(VAR_IN_DEMO, VAR_IN_MATRIX, VAR_IN_PARTNER_ID, @VAR_AFFECTED_ROWS);
				
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'03',';;;;',@VAR_AFFECTED_ROWS,';;;;',VAR_IN_DEMO,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_PARTNER_ID,';;;;');

				IF @VAR_AFFECTED_ROWS <= 0 
				THEN

					SET @VAR_ROLLBACK = 0;

				END IF;

			END IF;

			IF (@VAR_MATRIX_ID > 0 && @VAR_ROLLBACK > 0)
			THEN

				CALL `get_matrix_settings`(VAR_IN_MATRIX, @VAR_MATRIX_TYPE, @VAR_MATRIX_LEVELS, @VAR_MATRIX_CLONE, @VAR_MATRIX_CLONE_CYCLE, @VAR_MATRIX_PAY, @VAR_ADMIN_PAY_OFF, @VAR_MATRIX_PAY_OFF, @VAR_CLONE_MATRIX_PAY, @VAR_CLONE_ADMIN_PAY_OFF, @VAR_CLONE_MATRIX_PAY_OFF, @VAR_CLOSE_MATRIX_BALLS, @VAR_REFERRAL_MATRIX_BALLS); 
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'04',';;;;',@VAR_MATRIX_TYPE,';;;;',@VAR_MATRIX_LEVELS,';;;;',@VAR_MATRIX_CLONE,';;;;',@VAR_MATRIX_CLONE_CYCLE,';;;;',@VAR_MATRIX_PAY,';;;;',@VAR_ADMIN_PAY_OFF,';;;;',@VAR_MATRIX_PAY_OFF,';;;;',@VAR_CLOSE_MATRIX_BALLS,';;;;',@VAR_REFERRAL_MATRIX_BALLS,';;;;');

				IF (@VAR_MATRIX_PAY > 0)
				THEN

					SET @VAR_AFFECTED_ROWS = 0;

					CALL `set_matrix_payments`(VAR_IN_MATRIX, @VAR_MATRIX_ID, @VAR_SPONSOR_ID, VAR_IN_PARTNER_ID, 1, @VAR_MATRIX_PAY, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'05',';;;;',@VAR_AFFECTED_ROWS,';;;;');

					IF @VAR_AFFECTED_ROWS <= 0 
					THEN

						SET @VAR_ROLLBACK = 0;

					END IF;

				END IF;
				
				IF (@VAR_ROLLBACK > 0)
				THEN
					
					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'09',';;;;',@VAR_ROLLBACK,';;;;');
					
					IF (VAR_IN_STATUS > 0)
					THEN
							
						SELECT `status` INTO @VAR_STATUS
						FROM `partners`
						WHERE `id` = VAR_IN_PARTNER_ID;
									
						IF (VAR_IN_STATUS > @VAR_STATUS)
						THEN
										
							SET @VAR_AFFECTED_ROWS = 0;
										
							UPDATE `partners` SET `status` = VAR_IN_STATUS
							WHERE `id` = VAR_IN_PARTNER_ID;
										
							SELECT ROW_COUNT() INTO @VAR_AFFECTED_ROWS;
										
							IF @VAR_AFFECTED_ROWS <= 0 
							THEN
											
								SET @VAR_ROLLBACK = 0;
										
							END IF;
										
						END IF;
							
					END IF;
					
					IF (@VAR_ROLLBACK > 0)
					THEN
					
						IF (((@VAR_LAST_LEVEL + 1) - @VAR_MATRIX_LEVELS) > 0)
						THEN
						
							SET @VAR_AFFECTED_ROWS = 0;
							SET @VAR_SPONSOR_LEVEL =  ((@VAR_LAST_LEVEL + 1) - @VAR_MATRIX_LEVELS);
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'10',';;;;',@VAR_SPONSOR_LEVEL,';;;;');
							
							CALL `get_matrix_data_by_level`(VAR_IN_MATRIX, @VAR_SPONSOR_LEVEL, VAR_IN_DEMO, @VAR_ID, @VAR_SPONSOR_ID); 
							
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'11',';;;;',@VAR_SPONSOR_LEVEL,';;;;',@VAR_ID,';;;;',@VAR_SPONSOR_ID,';;;;');

							IF (@VAR_ID > 0)
							THEN

								SET @VAR_AFFECTED_ROWS = 0;
								
								CALL `close_matrix`(VAR_IN_MATRIX, @VAR_ID, VAR_IN_DEMO, @VAR_AFFECTED_ROWS); 
								
								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'12',';;;;',@VAR_AFFECTED_ROWS,';;;;');

								IF (@VAR_AFFECTED_ROWS > 0) 
								THEN
									
									IF (@VAR_SPONSOR_ID > 0)
									THEN
									
										SET @VAR_MATRIX_TYPE = 0;
										SET @VAR_MATRIX_LEVELS = 0;
										SET @VAR_MATRIX_CLONE = 0;
										SET @VAR_CLONE_MATRIX_PAY = 0;
										SET @VAR_MATRIX_CLONE_CYCLE = 0;
										SET @VAR_MATRIX_PAY = 0;
										SET @VAR_MATRIX_PAY_OFF = 0;
										SET @VAR_ADMIN_PAY_OFF = 0;
										SET @VAR_CLONE_ADMIN_PAY_OFF = 0;
										SET @VAR_CLONE_MATRIX_PAY_OFF = 0;
										SET @VAR_CLOSE_MATRIX_BALLS = 0;
										SET @VAR_REFERRAL_MATRIX_BALLS = 0;
									
										CALL `get_matrix_settings`((VAR_IN_MATRIX + 1), @VAR_MATRIX_TYPE, @VAR_MATRIX_LEVELS, @VAR_MATRIX_CLONE, @VAR_MATRIX_CLONE_CYCLE, @VAR_MATRIX_PAY, @VAR_ADMIN_PAY_OFF, @VAR_MATRIX_PAY_OFF, @VAR_CLONE_MATRIX_PAY, @VAR_CLONE_ADMIN_PAY_OFF, @VAR_CLONE_MATRIX_PAY_OFF, @VAR_CLOSE_MATRIX_BALLS, @VAR_REFERRAL_MATRIX_BALLS); 
										SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'13',';;;;',@VAR_MATRIX_TYPE,';;;;',@VAR_MATRIX_LEVELS,';;;;',@VAR_MATRIX_CLONE,';;;;',@VAR_MATRIX_CLONE_CYCLE,';;;;',@VAR_MATRIX_PAY,';;;;',@VAR_ADMIN_PAY_OFF,';;;;',@VAR_MATRIX_PAY_OFF,';;;;',@VAR_CLOSE_MATRIX_BALLS,';;;;',@VAR_REFERRAL_MATRIX_BALLS,';;;;');
										
										IF (@VAR_MATRIX_TYPE > 1)
										THEN
										
											IF (@VAR_SPONSOR_ID > 1)
											THEN
											
												SELECT `sponsor_id` INTO @VAR_PARENT_SPONSOR_ID 
												FROM `partners`
												WHERE `id` = @VAR_SPONSOR_ID;
											
											ELSE
											
												SET @VAR_PARENT_SPONSOR_ID = 1;
											
											END IF;
											
											SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'14',';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_PARENT_SPONSOR_ID,';;;;',@VAR_MATRIX_CLONE,';;;;',@VAR_CLONE_SPONSOR_MATRIX_ID,';;;;');
											
											IF (@VAR_PARENT_SPONSOR_ID > 0)
											THEN
											
												SELECT `matrix_id` INTO @VAR_CLONE_SPONSOR_MATRIX_ID 
												FROM `binar_matrices`
												WHERE `matrix_number` = (VAR_IN_MATRIX + 1) AND `parent_sponsor_id` = @VAR_PARENT_SPONSOR_ID AND `partner_id` = @VAR_SPONSOR_ID AND `demo` = VAR_IN_DEMO AND `clone` = @VAR_MATRIX_CLONE
												LIMIT 0,1;
												
												CALL `binar_matrix`(@VAR_PARENT_SPONSOR_ID, @VAR_SPONSOR_ID, (VAR_IN_MATRIX + 1), NULL, VAR_IN_DEMO, 1, @VAR_MATRIX_CLONE, 0, @VAR_CLONE_SPONSOR_MATRIX_ID, @p1, @p2); 
												SELECT @p1 INTO `VAR_OUT_RESULT`;
												
												SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'15',';;;;',VAR_OUT_RESULT,';;;;');
											
											END IF;
											
										ELSE
										
											CALL `linear_matrix`(@VAR_SPONSOR_ID, (VAR_IN_MATRIX + 1), VAR_IN_DEMO, NULL, 1, @p1, @p2); 
											SELECT @p1 INTO `VAR_OUT_RESULT`;
											
											SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'14',';;;;',VAR_OUT_RESULT,';;;;');
										
										END IF;
									
									END IF;
								
								END IF;

							END IF;
					
						ELSE

							SET VAR_OUT_RESULT = 1;

						END IF;
					
					END IF;
					
				END IF;
				
			END IF;

		END IF;
		
	END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `linear_matrix_recursively`;
CREATE PROCEDURE `linear_matrix_recursively`(IN `VAR_IN_PARTNER_ID` INT(10), IN `VAR_IN_MATRIX` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_DATE` VARCHAR(24) CHARSET utf8, IN `VAR_IN_STATUS` TINYINT(8), OUT `VAR_OUT_RESULT` VARCHAR(255) CHARSET utf8, OUT `VAR_OUT_RESULT2` TEXT CHARSET utf8)
    NO SQL
BEGIN

DECLARE VAR_RESULT TINYINT(8);
DECLARE VAR_RESULT2 TEXT;

SET VAR_RESULT = 0;
SET VAR_RESULT2 = '';
SET VAR_OUT_RESULT = 0;
SET VAR_OUT_RESULT2 = '';

START TRANSACTION;

SET GLOBAL max_allowed_packet = 20000000;
SET GLOBAL max_sp_recursion_depth = 255;
SET session max_sp_recursion_depth = 255;

CALL `linear_matrix`(VAR_IN_PARTNER_ID, VAR_IN_MATRIX, VAR_IN_DEMO, VAR_IN_DATE, VAR_IN_STATUS, @p1, @p2); 
SELECT @p1, @p2 INTO `VAR_RESULT`, `VAR_RESULT2`;
SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'14',';;;;',VAR_RESULT,';;;;',VAR_RESULT2,';;;;');

IF VAR_RESULT > 0 
THEN

SET VAR_OUT_RESULT = 1;
SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'15',';;;;');
COMMIT;

ELSE

SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'16',';;;;');
ROLLBACK;

END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `money_withdrawal`;
CREATE PROCEDURE `money_withdrawal`(IN `VAR_IN_WITHDRAWAL_ID` INT(10), IN `VAR_IN_WITHDRAWAL_STATUS` TINYINT(8), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_AMOUNT` DECIMAL(10,2), OUT `VAR_OUT_RESULT` INT(10))
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS TINYINT(4);
	
	SET VAR_OUT_RESULT = 0;
	SET VAR_AFFECTED_ROWS = 0;
	
	START TRANSACTION;
	
		IF (VAR_IN_WITHDRAWAL_ID <= 0 && VAR_IN_PARTNER_ID < 0 && VAR_IN_AMOUNT < 0)
		THEN
		
			ROLLBACK;
			
		ELSE
		
			UPDATE `withdrawal` SET `status` = VAR_IN_WITHDRAWAL_STATUS
			WHERE `id` = VAR_IN_WITHDRAWAL_ID;
			
			SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
			
			IF VAR_AFFECTED_ROWS > 0 
			THEN
			
				SET VAR_AFFECTED_ROWS = 0;
				
				UPDATE `partners` 
				SET `total_amount` = total_amount - VAR_IN_AMOUNT
				WHERE `id` = VAR_IN_PARTNER_ID;
				
				SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
			
				IF VAR_AFFECTED_ROWS > 0 
				THEN
				
					SET VAR_OUT_RESULT = 1;
				
				ELSE
	
					ROLLBACK;
	
				END IF;
			
			ELSE
	
				ROLLBACK;
	
			END IF;
		
		END IF;
	
	COMMIT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `partner_activation`;
CREATE PROCEDURE `partner_activation`(IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_PARTNER_ID` INT(10), IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX` VARCHAR(20) CHARSET utf8, IN `VAR_IN_DATE` VARCHAR(24) CHARSET utf8, IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_STATUS` TINYINT(8), IN `VAR_IN_RESERVE` TINYINT(8), IN `VAR_IN_CLASSIC_STUCTURE` TINYINT(8), IN `VAR_IN_ROOT_MATRIX` TINYINT(8), IN `VAR_IN_CHANGE` TINYINT(8), OUT `VAR_OUT_RESULT` INT(8), OUT `VAR_OUT_RESULT2` TEXT CHARSET utf8)
    NO SQL
BEGIN

	DECLARE VAR_AFFECTED_ROWS INT(8);
	DECLARE VAR_PARTNER_LEFT_KEY INT(10);
	DECLARE VAR_PARTNER_RIGHT_KEY INT(10);
	DECLARE VAR_PARTNER_LEVEL INT(10);
	DECLARE VAR_DEPTH_LEVEL TINYINT(8);
	DECLARE VAR_MATRIX_ID INT(10);
	DECLARE VAR_TYPE TINYINT(8);
	DECLARE VAR_PAY DECIMAL(10,2);
	DECLARE VAR_RESULT TINYINT(8);
	DECLARE VAR_RESULT2 TEXT;
	
	SET VAR_PARTNER_LEFT_KEY = 0;
	SET VAR_PARTNER_RIGHT_KEY = 0;
	SET VAR_PARTNER_LEVEL = 0;
	SET VAR_MATRIX_ID = 0;
	SET VAR_DEPTH_LEVEL = 0;
	SET VAR_PAY = 0;
	SET VAR_AFFECTED_ROWS = 0;
	SET VAR_RESULT = 0;
	SET VAR_RESULT2 = '';
	SET VAR_OUT_RESULT = 0;
	SET VAR_OUT_RESULT2 = '';
	SET VAR_OUT_RESULT = 0;
	
	START TRANSACTION;
	
		CALL `get_pay`(VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_PAY, VAR_TYPE);
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'01',';;;;',VAR_TYPE,';;;;',VAR_PAY,';;;;');
				
		IF VAR_TYPE > 0 
		THEN
				
			IF VAR_TYPE > 1 
			THEN
			
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',VAR_IN_SPONSOR_ID,';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_STATUS,';;;;',VAR_IN_RESERVE,';;;;');	
				CALL `binar_matrix`(VAR_IN_SPONSOR_ID, VAR_IN_PARTNER_ID, VAR_IN_STRUCTURE_NUMBER, VAR_IN_MATRIX, VAR_IN_DATE, VAR_IN_DEMO, VAR_IN_STATUS, 0, VAR_IN_RESERVE, 0, VAR_IN_ROOT_MATRIX, VAR_IN_CHANGE, VAR_RESULT, VAR_RESULT2);
					
			ELSE
			
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_IN_MATRIX,';;;;',VAR_IN_DEMO,';;;;',VAR_IN_STATUS,';;;;');
				CALL `linear_matrix`(VAR_IN_PARTNER_ID, VAR_IN_MATRIX, VAR_IN_DEMO, VAR_IN_DATE, VAR_IN_STATUS, VAR_RESULT, VAR_RESULT2);
				
			END IF;
				
		END IF;
		
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'03',';;;;',VAR_RESULT,';;;;');
		
		IF (VAR_RESULT > 0)
		THEN
		
			IF (VAR_IN_CLASSIC_STUCTURE > 0)
			THEN
						
				CALL `get_levels_depth`(VAR_DEPTH_LEVEL); 
				
				SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'04',';;;;',VAR_DEPTH_LEVEL,';;;;');
						
				IF (VAR_DEPTH_LEVEL > 0)
				THEN
					
					SELECT `level`, `left_key`, `right_key` INTO VAR_PARTNER_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY
					FROM `partners` 
					WHERE `id` = VAR_IN_PARTNER_ID;
					
					IF (VAR_PARTNER_LEFT_KEY > 0 && VAR_PARTNER_RIGHT_KEY > 0 && VAR_PARTNER_LEVEL > 0)
					THEN
						
						SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'05',';;;;',VAR_PARTNER_LEFT_KEY,';;;;',VAR_PARTNER_RIGHT_KEY,';;;;',VAR_PARTNER_LEVEL,';;;;');
						
						CALL `get_matrix_id`(VAR_IN_MATRIX, VAR_IN_DEMO, VAR_MATRIX_ID); 
							
						IF (VAR_MATRIX_ID > 0)
						THEN	
							
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',VAR_MATRIX_ID,';;;;');
							
							IF (VAR_DEPTH_LEVEL > VAR_PARTNER_LEVEL)
							THEN
								
								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_DEPTH_LEVEL,';;;;',VAR_PARTNER_LEFT_KEY,';;;;',VAR_PARTNER_RIGHT_KEY,';;;;',VAR_PARTNER_LEVEL,';;;;',VAR_MATRIX_ID,';;;;',VAR_PAY,';;;;',';;;;',VAR_IN_DEMO,';;;;');		
								CALL `levels_payments_depth_more_referal_level`(VAR_IN_PARTNER_ID, VAR_DEPTH_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY, VAR_PARTNER_LEVEL, VAR_MATRIX_ID, VAR_PAY, VAR_IN_DEMO, VAR_AFFECTED_ROWS);
								
							ELSE
									
								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'07',';;;;',VAR_IN_PARTNER_ID,';;;;',VAR_DEPTH_LEVEL,';;;;',VAR_PARTNER_LEFT_KEY,';;;;',VAR_PARTNER_RIGHT_KEY,';;;;',VAR_PARTNER_LEVEL,';;;;',VAR_MATRIX_ID,';;;;',VAR_PAY,';;;;',';;;;',VAR_IN_DEMO,';;;;');		
								CALL `levels_payments_depth_lesser_referal_level`(VAR_IN_PARTNER_ID, VAR_DEPTH_LEVEL, VAR_PARTNER_LEFT_KEY, VAR_PARTNER_RIGHT_KEY, VAR_PARTNER_LEVEL, VAR_MATRIX_ID, VAR_PAY, VAR_IN_DEMO, VAR_AFFECTED_ROWS);

							END IF;
							
						END IF;
								
						IF VAR_AFFECTED_ROWS > 0 
						THEN

							SET VAR_RESULT = 1;
							SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'08',';;;;',VAR_RESULT,';;;;');
							
						END IF;
							
					END IF;
					
				END IF;
				
			END IF;
			
		END IF;
		
		SET VAR_OUT_RESULT = VAR_RESULT;
	
	IF VAR_RESULT > 0 
	THEN
		
		SET VAR_OUT_RESULT = 1;
		COMMIT;
		
	ELSE
			
		ROLLBACK;

	END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_admin_pay_off`;
CREATE PROCEDURE `set_admin_pay_off`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_OUT_RESULT = 0;
SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("INSERT INTO `",
VAR_DEMO,
"admin_pay_off` SET `partner_id` = '", VAR_IN_PARTNER_ID, "', `structure_number` = '", VAR_IN_STRUCTURE_NUMBER, "', `matrix_number` = '", VAR_IN_MATRIX_NUMBER, "', `matrix_id` = '", VAR_IN_MATRIX_ID, "', `amount` = '", VAR_IN_AMOUNT, "', `created_at` = UNIX_TIMESTAMP()");
PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_binar_matrices_by_ids_list`;
CREATE PROCEDURE `set_binar_matrices_by_ids_list`(IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_REFFERAL_ID` INT(11), IN `VAR_IN_PARTNER_LEVEL` INT(10), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET VAR_OUT_RESULT = 0;
SET @VAR_QUERY = CONCAT("INSERT INTO `",
VAR_DEMO,
"levels_payment` SET `matrix_id` = '", VAR_IN_MATRIX_ID, "', `partner_id` = '", VAR_IN_PARTNER_ID, "', `refferal_id` = '", VAR_IN_REFFERAL_ID, "', `level` = '", VAR_IN_PARTNER_LEVEL, "', `amount` = '", VAR_IN_AMOUNT, "', `created_at` = UNIX_TIMESTAMP();");
PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_levels_payments`;
CREATE PROCEDURE `set_levels_payments`(IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_REFFERAL_ID` INT(11), IN `VAR_IN_PARTNER_LEVEL` INT(10), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET VAR_OUT_RESULT = 0;
SET @VAR_QUERY = CONCAT("INSERT INTO `",
VAR_DEMO,
"levels_payment` SET `matrix_id` = '", VAR_IN_MATRIX_ID, "', `partner_id` = '", VAR_IN_PARTNER_ID, "', `refferal_id` = '", VAR_IN_REFFERAL_ID, "', `level` = '", VAR_IN_PARTNER_LEVEL, "', `amount` = '", VAR_IN_AMOUNT, "', `created_at` = UNIX_TIMESTAMP();");
PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_linear_matrix_partner_data`;
CREATE PROCEDURE `set_linear_matrix_partner_data`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_LAST_LEVEL` INT(10), IN `VAR_IN_DATE` VARCHAR(24) CHARSET utf8, OUT `VAR_OUT_RESULT` TINYINT(8), OUT `VAR_OUT_MATRIX_ID` INT(10))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
	
	SET VAR_OUT_RESULT = 0;
	SET VAR_OUT_MATRIX_ID = 0;
	SET VAR_DEMO = '';
	
	IF VAR_IN_DEMO > 0
	THEN
	
		SET VAR_DEMO = 'demo_';
	
	END IF;
	
	IF VAR_IN_DATE > 0
	THEN
		
		SET @VAR_QUERY = CONCAT("INSERT INTO `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER,
		"` SET `partner_id` = '", VAR_IN_PARTNER_ID, "', `level` = '", VAR_IN_LAST_LEVEL, "' + 1, `open_date` = '", VAR_IN_DATE, "';");
		
	ELSE
		
		SET @VAR_QUERY = CONCAT("INSERT INTO `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER,
		"` SET `partner_id` = '", VAR_IN_PARTNER_ID, "', `level` = '", VAR_IN_LAST_LEVEL, "' + 1, `open_date` = UNIX_TIMESTAMP();");
	
	END IF;
	
	PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    SET VAR_OUT_MATRIX_ID = LAST_INSERT_ID();
    DEALLOCATE PREPARE stmt;
	
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_matrix_balls`;
CREATE PROCEDURE `set_matrix_balls`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_REFERRAL_ID` INT(11), IN `VAR_IN_TYPE` TINYINT(8), IN `VAR_IN_TYPE_BALLS` TINYINT(8), IN `VAR_IN_BALLS` INT(10), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET VAR_OUT_RESULT = 0;
SET @VAR_QUERY = CONCAT("INSERT INTO `",
VAR_DEMO,
"balls` SET `partner_id` = '", VAR_IN_PARTNER_ID, "', `referral_id` = '", VAR_IN_REFERRAL_ID, "', `structure_number` = '", VAR_IN_STRUCTURE_NUMBER, "', `matrix_number` = ", VAR_IN_MATRIX_NUMBER, ", `matrix_id` = '", VAR_IN_MATRIX_ID, "', `type` = '", VAR_IN_TYPE, "', `type_balls` = '", VAR_IN_TYPE_BALLS, "', `balls` = '", VAR_IN_BALLS, "', `created_at` = UNIX_TIMESTAMP();");
PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_matrix_payments`;
CREATE PROCEDURE `set_matrix_payments`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_TYPE` TINYINT(8), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET VAR_OUT_RESULT = 0;
SET @VAR_QUERY = CONCAT("INSERT INTO `",
VAR_DEMO,
"matrix_payments` SET `structure_number` = '", VAR_IN_STRUCTURE_NUMBER, "', `partner_id` = '", VAR_IN_SPONSOR_ID , "', `payer_partner_id` = '", VAR_IN_PARTNER_ID, "', `matrix_number` = ", VAR_IN_MATRIX_NUMBER, ", `matrix_id` = '", VAR_IN_MATRIX_ID, "', `type` = '", VAR_IN_TYPE, "', `amount` = '", VAR_IN_AMOUNT, "', `created_at` = UNIX_TIMESTAMP();");
PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_partner_data_in_matrix`;
CREATE PROCEDURE `set_partner_data_in_matrix`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_PARTNER_ID` INT(11), IN `VAR_IN_SPONSOR_RIGHT_KEY` INT(10), IN `VAR_IN_SPONSOR_LEVEL` INT(10), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_CLONE` TINYINT(8), IN `VAR_IN_CLONE_SPONSOR_MATRIX_ID` INT(10), IN `VAR_IN_CHANGE` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
DECLARE VAR_RESULT TINYINT(8);

SET VAR_OUT_RESULT = 0;
SET @VAR_RESULT = 0;
SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("INSERT INTO `",
VAR_DEMO,
"matrix_", VAR_IN_STRUCTURE_NUMBER, "_", VAR_IN_MATRIX_NUMBER, "` SET `matrix_id` = '", VAR_IN_MATRIX_ID, "', `clone_matrix_id` = '", VAR_IN_CLONE_SPONSOR_MATRIX_ID, "', `partner_id` = '", VAR_IN_PARTNER_ID, "', `parent_id` = '", VAR_IN_SPONSOR_ID, "', `left_key` = '", VAR_IN_SPONSOR_RIGHT_KEY, "', `right_key` = '", VAR_IN_SPONSOR_RIGHT_KEY, "' + 1, `level` = '", VAR_IN_SPONSOR_LEVEL, "' + 1, `clone` = '", VAR_IN_CLONE, "', `change` = '", VAR_IN_CHANGE, "', `open_date` = UNIX_TIMESTAMP();");
PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
SET @VAR_RESULT = (SELECT ROW_COUNT());
DEALLOCATE PREPARE stmt;
    
SET VAR_OUT_RESULT = @VAR_RESULT;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `transfer_balls`;
CREATE PROCEDURE `transfer_balls`(IN `VAR_IN_SENDER_ID` INT(11), IN `VAR_IN_RECEIVER_ID` INT(11), IN `VAR_IN_BALLS` INT(10), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_RESULT TINYINT(8);

SET VAR_QUERY = '';
SET VAR_RESULT = 0;
SET VAR_OUT_RESULT = 0;

SET @VAR_QUERY = CONCAT("INSERT INTO `transfer_balls` SET `sender_id` = '", VAR_IN_SENDER_ID, "', `receiver_id` = ", VAR_IN_RECEIVER_ID, ", `balls` = '", VAR_IN_BALLS, "', `created_at` = UNIX_TIMESTAMP();");
PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    SET VAR_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;
    
    IF VAR_RESULT > 0
THEN

SET VAR_RESULT = 0;

SET @VAR_QUERY = CONCAT("UPDATE `partners` SET `total_balls` = (`total_balls` - '", VAR_IN_BALLS, "') WHERE `id` = '", VAR_IN_SENDER_ID, "'");
PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
SET VAR_RESULT = (SELECT ROW_COUNT());
DEALLOCATE PREPARE stmt;

IF VAR_RESULT > 0
THEN

SET VAR_RESULT = 0;

SET @VAR_QUERY = CONCAT("UPDATE `partners` SET `total_balls` = (`total_balls` + '", VAR_IN_BALLS, "') WHERE `id` = '", VAR_IN_RECEIVER_ID, "'");
PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
SET VAR_RESULT = (SELECT ROW_COUNT());
DEALLOCATE PREPARE stmt;

IF VAR_RESULT > 0
THEN

SET VAR_OUT_RESULT = 1;

END IF;

END IF;

END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `update_matrix_keys_in_structure`;
CREATE PROCEDURE `update_matrix_keys_in_structure`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_SPONSOR_RIGHT_KEY` INT(10), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` INT(10))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_OUT_RESULT = 0;
SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("UPDATE `",
VAR_DEMO,
"matrix_", VAR_IN_STRUCTURE_NUMBER, "_", VAR_IN_MATRIX_NUMBER, "` SET `right_key` = `right_key` + 2, `left_key` = IF(`left_key` > ", VAR_IN_SPONSOR_RIGHT_KEY, ", `left_key` + 2, `left_key`) 
WHERE `right_key` >= ", VAR_IN_SPONSOR_RIGHT_KEY, ";");

PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `update_partner_id_in_matrix`;
CREATE PROCEDURE `update_partner_id_in_matrix`(IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(10), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
	
	SET VAR_OUT_RESULT = 0;
	SET VAR_DEMO = '';
	
	IF VAR_IN_DEMO > 0
	THEN
	
		SET VAR_DEMO = 'demo_';
	
	END IF;
	
	SET @VAR_QUERY = CONCAT("UPDATE `",
	VAR_DEMO,
	"matrix_",
	VAR_IN_MATRIX_NUMBER,
	"` SET `partner_id` = '1' 
	WHERE `id` = ",
	VAR_IN_MATRIX_ID,
	";");
	
	PREPARE stmt FROM @VAR_QUERY;
	EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `update_partner_matrix_number`;
CREATE PROCEDURE `update_partner_matrix_number`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_PARTNER_ID` INT(11), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_OUT_RESULT = 0;
SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("UPDATE `partners` SET `",
VAR_DEMO,
"matrix_",
VAR_IN_STRUCTURE_NUMBER, "` = ",
VAR_IN_MATRIX_NUMBER,
" 
WHERE `id` = ",
VAR_IN_PARTNER_ID,
";");

PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `update_sponsor_levels_in matix`;
CREATE PROCEDURE `update_sponsor_levels_in matix`(IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_REFFERAL_LEFT_KEY` INT(10), IN `VAR_IN_REFFERAL_RIGHT_KEY` INT(10), IN `VAR_IN_REFFERAL_LEVEL` INT(10), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_DELIMITER` MEDIUMTEXT CHARSET utf8, IN `VAR_IN_DELIMITER_SPONSOR_ID` MEDIUMTEXT CHARSET utf8, IN `VAR_IN_CLONE_DELIMITER` MEDIUMTEXT CHARSET utf8, OUT `VAR_OUT_IDS_LIST` TEXT CHARSET utf8, OUT `VAR_OUT_RESULT` TINYINT(4), OUT `VAR_OUT_RESULT2` TEXT CHARSET utf8)
    NO SQL
BEGIN

	DECLARE cursor_end CONDITION FOR SQLSTATE '02000'; 
	DECLARE VAR_AFFECTED_ROWS INT(10);
	DECLARE VAR_ROLLBACK TINYINT(8);
	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_ID INT(11);
	DECLARE VAR_PARTNERS_SPONSOR_ID INT(11);
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
	DECLARE VAR_SPONSOR_ID INT(11);
	DECLARE VAR_PARENT_SPONSOR_ID INT(10);
	DECLARE VAR_SPONSOR_LEVEL TINYINT(8);
	DECLARE VAR_CHECK_PARTNERS_COUNT INT(10);
	DECLARE VAR_MATRIX_POWER INT(10);
	DECLARE VAR_MATRIX_TYPE TINYINT(8);
	DECLARE VAR_MATRIX_LEVELS TINYINT(8);
	DECLARE VAR_MATRIX_CLONE TINYINT(8);
	DECLARE VAR_MATRIX_PAY DECIMAL(10,2);
	DECLARE VAR_ADMIN_PAY_OFF DECIMAL(10,2);
	DECLARE VAR_MATRIX_PAY_OFF DECIMAL(10,2);
	
	DECLARE VAR_LOOP_FINISHED INT DEFAULT 0; 
	DECLARE partners_cursor CURSOR FOR SELECT * FROM QUERY_VIEW; 
	DECLARE CONTINUE HANDLER FOR cursor_end SET VAR_LOOP_FINISHED = 1; 

	SET @VAR_AFFECTED_ROWS = 0;
	SET @VAR_ROLLBACK = 0;
	SET VAR_ID = 0;
	SET VAR_PARTNERS_SPONSOR_ID = 0;
	SET VAR_DEMO = '';
	SET @VAR_SPONSOR_ID = 0;
	SET @VAR_CHECK_PARTNERS_COUNT = 0;
	SET @VAR_PARENT_SPONSOR_ID = 0;
	SET @VAR_MATRIX_POWER = 0;
	SET @VAR_MATRIX_TYPE = 0;	
	SET @VAR_MATRIX_LEVELS = 0;
	SET @VAR_MATRIX_CLONE = 0;
	SET @VAR_MATRIX_PAY = 0;
	SET @VAR_ADMIN_PAY_OFF = 0;
	SET @VAR_MATRIX_PAY_OFF = 0;
	SET VAR_OUT_IDS_LIST = '';
	SET VAR_OUT_RESULT = 0;
	SET VAR_OUT_RESULT2 = '';
	
	CALL `get_matrix_settings`(VAR_IN_MATRIX_NUMBER, @VAR_MATRIX_TYPE, @VAR_MATRIX_LEVELS, @VAR_MATRIX_CLONE, @VAR_MATRIX_PAY, @VAR_ADMIN_PAY_OFF, @VAR_MATRIX_PAY_OFF); 
	SET VAR_SPONSOR_LEVEL = @VAR_MATRIX_LEVELS - 1;
	SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'01',';;;;',VAR_SPONSOR_LEVEL,';;;;',@VAR_MATRIX_TYPE,';;;;',@VAR_MATRIX_LEVELS,';;;;',@VAR_CLONE,';;;;',@VAR_MATRIX_PAY,';;;;',@VAR_ADMIN_PAY_OFF,';;;;',@VAR_MATRIX_PAY_OFF,';;;;');
	
	IF VAR_SPONSOR_LEVEL > 0
	THEN
	
		IF VAR_IN_DEMO > 0
		THEN
		
			SET VAR_DEMO = 'demo_';
		
		END IF;
		
		SET @VAR_QUERY = CONCAT("CREATE VIEW QUERY_VIEW as SELECT `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER, "`.`id`, `partners`.`sponsor_id` 
		FROM `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER, "`
		LEFT JOIN `partners` ON `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER, "`.`partner_id` = `partners`.`id`
		 WHERE `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER, "`.`left_key` <= '",
		VAR_IN_REFFERAL_LEFT_KEY,
		"' AND `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER, "`.`right_key` >= '",
		VAR_IN_REFFERAL_RIGHT_KEY,
		"' AND (`",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER, "`.`level` > ('",
		VAR_IN_REFFERAL_LEVEL,
		"' - '",
		@VAR_MATRIX_LEVELS,
		"') AND `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER, "`.`level` < '",
		VAR_IN_REFFERAL_LEVEL,
		"')
		ORDER BY `",
		VAR_DEMO,
		"matrix_",
		VAR_IN_MATRIX_NUMBER, "`.`level` DESC;"); 
		SELECT @VAR_QUERY;  
		PREPARE stmt from @VAR_QUERY; 
		EXECUTE stmt; 
		DEALLOCATE PREPARE stmt; 

		OPEN partners_cursor; 
		
			FETCH partners_cursor INTO VAR_ID, VAR_PARTNERS_SPONSOR_ID; 
			
			WHILE VAR_LOOP_FINISHED = 0 DO 
					
				SET @VAR_AFFECTED_ROWS = 0;
				SET @VAR_ROLLBACK = 0;
				SET @VAR_CHECK_PARTNERS_COUNT = 0;	
					
				SELECT VAR_ID, VAR_PARTNERS_SPONSOR_ID; 
					
				SET @VAR_QUERY = CONCAT("SELECT `partner_id`, `level_", VAR_SPONSOR_LEVEL, "` INTO @VAR_SPONSOR_ID, @VAR_CHECK_PARTNERS_COUNT 
				FROM `",
				VAR_DEMO,
				"matrix_",
				VAR_IN_MATRIX_NUMBER,
				"` WHERE `id` = '",
				VAR_ID,
				"'");
				
				PREPARE stmt FROM @VAR_QUERY;
				EXECUTE stmt;
				DEALLOCATE PREPARE stmt;
				
				SELECT @VAR_SPONSOR_ID, @VAR_CHECK_PARTNERS_COUNT;	
				
				SET @VAR_MATRIX_POWER = POW(2, VAR_SPONSOR_LEVEL);
					
				IF (@VAR_CHECK_PARTNERS_COUNT < @VAR_MATRIX_POWER)
				THEN
				
					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'02',';;;;',VAR_ID,';;;;',@VAR_SPONSOR_ID,';;;;',@VAR_CHECK_PARTNERS_COUNT,';;;;',@VAR_MATRIX_POWER,';;;;');
				
					CALL `update_sponsor_level_in_matrix`(VAR_IN_MATRIX_NUMBER, VAR_ID, VAR_SPONSOR_LEVEL, VAR_IN_DEMO, @VAR_AFFECTED_ROWS); 
					SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'03',';;;;',@VAR_AFFECTED_ROWS,';;;;');	
					
					IF @VAR_AFFECTED_ROWS > 0 
					THEN
						
						IF (((@VAR_CHECK_PARTNERS_COUNT + 1) = @VAR_MATRIX_POWER) && (@VAR_MATRIX_LEVELS = VAR_SPONSOR_LEVEL))
						THEN	
													
							IF (@VAR_MATRIX_PAY_OFF > 0)
							THEN
																		
								SET @VAR_AFFECTED_ROWS = 0;
													
								CALL `set_matrix_payments`(VAR_IN_MATRIX_NUMBER, VAR_ID, @VAR_SPONSOR_ID, 2, @VAR_MATRIX_PAY_OFF, VAR_IN_DEMO, @VAR_AFFECTED_ROWS);
								SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'04',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																		
								IF @VAR_AFFECTED_ROWS > 0 
								THEN
																		
									SET @VAR_AFFECTED_ROWS = 0;
																		
									CALL `update_total_amount`(@VAR_SPONSOR_ID, @VAR_MATRIX_PAY_OFF, VAR_IN_DEMO, '+', @VAR_AFFECTED_ROWS); 
									SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'05',';;;;',@VAR_AFFECTED_ROWS,';;;;');
													
								END IF;
								
							END IF;
							
							IF @VAR_AFFECTED_ROWS > 0 
							THEN
							
								IF (@VAR_ADMIN_PAY_OFF > 0)
								THEN
																			
									SET @VAR_AFFECTED_ROWS = 0;
									SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'06',';;;;',@VAR_ADMIN_PAY_OFF,';;;;');
																				
									CALL `set_admin_pay_off`(VAR_IN_MATRIX_NUMBER, @VAR_SPONSOR_ID, @VAR_SPONSOR_ID, @VAR_ADMIN_PAY_OFF, VAR_IN_DEMO, @VAR_AFFECTED_ROWS); 
																				
									SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'07',';;;;',@VAR_AFFECTED_ROWS,';;;;');
																			
									IF @VAR_AFFECTED_ROWS <= 0 
									THEN
																					
										SET @VAR_ROLLBACK = 0;
																
									END IF;
																			
								END IF;
								
								IF @VAR_AFFECTED_ROWS > 0 
								THEN
								
									IF (@VAR_SPONSOR_ID = 1)
									THEN
																		
										SET @VAR_PARENT_SPONSOR_ID = 1;
																		
									ELSE
																		
										SET @VAR_PARENT_SPONSOR_ID = VAR_PARTNERS_SPONSOR_ID;
																	
									END IF;
																
									SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'08',';;;;',@VAR_PARENT_SPONSOR_ID,';;;;',@VAR_SPONSOR_ID,';;;;');
								
									CALL `get_max_matrix_number`(@VAR_MAX_MATRIX_NUMBER);
																
									SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'09',';;;;',@VAR_MAX_MATRIX_NUMBER,';;;;');
									
									IF (@VAR_MAX_MATRIX_NUMBER > VAR_IN_MATRIX_NUMBER)
									THEN
																	
										SET @VAR_AFFECTED_ROWS = 0;
													
										INSERT INTO `binar_matrices` SET `matrix_number` = (VAR_IN_MATRIX_NUMBER + 1),  `matrix_id` = VAR_ID, `parent_sponsor_id` = @VAR_PARENT_SPONSOR_ID, `partner_id` = @VAR_SPONSOR_ID, `demo` = VAR_IN_DEMO, `clone` = @VAR_CLONE;
																	
										SELECT ROW_COUNT() INTO @VAR_AFFECTED_ROWS;
										
										SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'10',';;;;',@VAR_AFFECTED_ROWS,';;;;');
										
										IF @VAR_AFFECTED_ROWS > 0 
										THEN
										
											SET @VAR_ROLLBACK = 1;
											SET VAR_OUT_IDS_LIST = CONCAT(VAR_OUT_IDS_LIST,VAR_ID,VAR_IN_DELIMITER_SPONSOR_ID,@VAR_PARENT_SPONSOR_ID,VAR_IN_DELIMITER);
											
										ELSE
										
											SET @VAR_ROLLBACK = 0;
										
										END IF;
											
									END IF;
									
								END IF;
							
							END IF;
							
						ELSE
										
							SET @VAR_ROLLBACK = 1;
							
						END IF;
						
					END IF;
					
					IF @VAR_ROLLBACK <= 0 
					THEN
					
						SET VAR_LOOP_FINISHED = 1;
					
					END IF;
				
				END IF;
				
				SET VAR_SPONSOR_LEVEL = VAR_SPONSOR_LEVEL + 1;
				FETCH partners_cursor INTO VAR_ID, VAR_PARTNERS_SPONSOR_ID; 
					
			END WHILE; 
				
		CLOSE partners_cursor;
		
		DROP VIEW QUERY_VIEW;
		
		SET VAR_OUT_RESULT2 = CONCAT(VAR_OUT_RESULT2,'12',';;;;',VAR_SPONSOR_LEVEL,';;;;');
		
		IF VAR_SPONSOR_LEVEL > (@VAR_MATRIX_LEVELS - 1)
		THEN
		
			IF @VAR_ROLLBACK > 0 
			THEN

				SET VAR_OUT_RESULT = 1;
				
				IF VAR_OUT_IDS_LIST != ''
				THEN
				
					SET VAR_OUT_IDS_LIST = SUBSTRING(VAR_OUT_IDS_LIST, 1, LENGTH(VAR_OUT_IDS_LIST) - LENGTH(VAR_IN_DELIMITER));
					SET VAR_OUT_IDS_LIST = CONCAT(VAR_OUT_IDS_LIST,VAR_IN_CLONE_DELIMITER,@VAR_MATRIX_CLONE);
				
				END IF;
			
			END IF;
			
		ELSE
		
			SET VAR_OUT_RESULT = 1;
		
		END IF;
	
	END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `update_sponsor_level_in_matrix`;
CREATE PROCEDURE `update_sponsor_level_in_matrix`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_NUMBER` TINYINT(8), IN `VAR_IN_MATRIX_ID` INT(10), IN `VAR_IN_LEVEL` TINYINT(8), IN `VAR_IN_DEMO` TINYINT(8), OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
DECLARE VAR_CHECK_PARTNERS_COUNT INT(10);
DECLARE VAR_MATRIX_POWER INT(10);

SET VAR_OUT_RESULT = 0;
SET VAR_DEMO = '';
SET VAR_CHECK_PARTNERS_COUNT = 0;
SET @VAR_MATRIX_POWER = POW(2, VAR_IN_LEVEL);

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("SELECT `level_", VAR_IN_LEVEL, "` INTO @VAR_CHECK_PARTNERS_COUNT 
FROM `",
VAR_DEMO,
"matrix_", VAR_IN_STRUCTURE_NUMBER, "_", VAR_IN_MATRIX_NUMBER, "` WHERE `id` = '",
VAR_IN_MATRIX_ID,
"'");

PREPARE stmt FROM @VAR_QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SELECT @VAR_CHECK_PARTNERS_COUNT;
    
    IF @VAR_CHECK_PARTNERS_COUNT < @VAR_MATRIX_POWER
THEN

SET @VAR_QUERY = CONCAT("UPDATE `",
VAR_DEMO,
"matrix_", VAR_IN_STRUCTURE_NUMBER, "_", VAR_IN_MATRIX_NUMBER, "` SET `level_", VAR_IN_LEVEL, "` = IFNULL(`level_", VAR_IN_LEVEL, "`, 0) + 1
WHERE `id` = '", VAR_IN_MATRIX_ID, "';");

PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
DEALLOCATE PREPARE stmt;

ELSE

SET VAR_OUT_RESULT = 1;
    
    END IF;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `update_total_amount`;
CREATE PROCEDURE `update_total_amount`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_AMOUNT` DECIMAL(10,2), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_SIGN` VARCHAR(1) CHARSET utf8, OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

	DECLARE VAR_QUERY TEXT;
	DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;
	DECLARE VAR_AFFECTED_ROWS TINYINT(8);

	SET VAR_OUT_RESULT = 0;
	SET @VAR_AFFECTED_ROWS = 0;
	SET VAR_DEMO = '';

	IF VAR_IN_DEMO > 0
	THEN

	SET VAR_DEMO = 'demo_';

	END IF;

	SET @VAR_QUERY = CONCAT("UPDATE `partners` SET `", 
	VAR_DEMO,
	"total_amount_", VAR_IN_STRUCTURE_NUMBER, "` = `", 
	VAR_DEMO,
	"total_amount_", VAR_IN_STRUCTURE_NUMBER, "` ", 
	VAR_IN_SIGN,
	" '", VAR_IN_AMOUNT, "'
	WHERE `id` = '", VAR_IN_SPONSOR_ID, "';");

	PREPARE stmt FROM @VAR_QUERY;
	EXECUTE stmt;
    SET @VAR_AFFECTED_ROWS = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;
    
    IF @VAR_AFFECTED_ROWS > 0
	THEN

		SET @VAR_AFFECTED_ROWS = 0;
				
		UPDATE `partners` 
		SET `total_amount` = `total_amount` + VAR_IN_AMOUNT
		WHERE `id` = VAR_IN_SPONSOR_ID;
				
		SELECT ROW_COUNT() INTO @VAR_AFFECTED_ROWS;
			
		IF @VAR_AFFECTED_ROWS > 0 
		THEN
				
			SET VAR_AFFECTED_ROWS = 0;
				
				UPDATE `partners` 
				SET `total_amount` = `total_amount` + VAR_IN_AMOUNT
				WHERE `id` = VAR_IN_SPONSOR_ID;
				
				SELECT ROW_COUNT() INTO VAR_AFFECTED_ROWS;
			
				IF VAR_AFFECTED_ROWS > 0 
				THEN
				
					SET VAR_OUT_RESULT = 1;
				
				END IF;
	
		END IF;

	END IF;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
DROP PROCEDURE IF EXISTS `update_total_balls`;
CREATE PROCEDURE `update_total_balls`(IN `VAR_IN_STRUCTURE_NUMBER` TINYINT(8), IN `VAR_IN_SPONSOR_ID` INT(11), IN `VAR_IN_BALLS` INT(10), IN `VAR_IN_DEMO` TINYINT(8), IN `VAR_IN_SIGN` VARCHAR(1) CHARSET utf8, OUT `VAR_OUT_RESULT` TINYINT(8))
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
DECLARE VAR_DEMO VARCHAR(5) CHARSET utf8;

SET VAR_OUT_RESULT = 0;
SET VAR_DEMO = '';

IF VAR_IN_DEMO > 0
THEN

SET VAR_DEMO = 'demo_';

END IF;

SET @VAR_QUERY = CONCAT("UPDATE `partners` SET `", 
VAR_DEMO,
"total_balls_", VAR_IN_STRUCTURE_NUMBER, "` = `", 
VAR_DEMO,
"total_balls_", VAR_IN_STRUCTURE_NUMBER, "` ", 
VAR_IN_SIGN,
" '", VAR_IN_BALLS, "'
WHERE `id` = '", VAR_IN_SPONSOR_ID, "';");

PREPARE stmt FROM @VAR_QUERY;
EXECUTE stmt;
    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
    DEALLOCATE PREPARE stmt;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-17 19:52:05
DELIMITER ;;
DROP PROCEDURE IF EXISTS `set_text_advert_balls`;
CREATE PROCEDURE `set_text_advert_balls`(IN VAR_IN_USER_ID int, IN VAR_IN_ADVERT_USER_ID int,
                                                             IN VAR_IN_ADVERT_ID int, IN VAR_IN_BALLS tinyint,
                                                             IN VAR_IN_STRUCTURE_NUMBER tinyint,
                                                             OUT VAR_OUT_RESULT tinyint)
    NO SQL
BEGIN

DECLARE VAR_QUERY TEXT;
    DECLARE VAR_ID INT(10);

    SET VAR_ID = 0;
    SET VAR_OUT_RESULT = 0;

    START TRANSACTION;

        SET @VAR_QUERY = CONCAT("SELECT `id` INTO @VAR_ID
        FROM `text_advert_balls`
        WHERE `user_id` = '", VAR_IN_USER_ID ,"' AND `advert_id` = '", VAR_IN_ADVERT_ID, "'");

        PREPARE stmt FROM @VAR_QUERY;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SELECT @VAR_ID;

        IF(@VAR_ID <= 0 || @VAR_ID IS NULL)
        THEN

            SET @VAR_QUERY = CONCAT("INSERT INTO `text_advert_balls` SET `user_id` = '", VAR_IN_USER_ID, "', `advert_id` = '", VAR_IN_ADVERT_ID, "', `balls` = '", VAR_IN_BALLS, "', `created_at` = UNIX_TIMESTAMP()");

            PREPARE stmt FROM @VAR_QUERY;
            EXECUTE stmt;
            SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
            DEALLOCATE PREPARE stmt;

            IF VAR_OUT_RESULT > 0
            THEN
                
                SET VAR_OUT_RESULT = 0;
                SET @VAR_QUERY = CONCAT("UPDATE `text_advert` SET `counter` = `counter` - '", VAR_IN_BALLS, "' WHERE `id` = '", VAR_IN_ADVERT_ID, "'");
                
                PREPARE stmt FROM @VAR_QUERY;
                EXECUTE stmt;
                SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
                DEALLOCATE PREPARE stmt;

                IF VAR_OUT_RESULT > 0
                THEN

                    SET VAR_OUT_RESULT = 0;
                    SET @VAR_QUERY = CONCAT("UPDATE `partners` SET `total_balls_", VAR_IN_STRUCTURE_NUMBER, "` = `total_balls_", VAR_IN_STRUCTURE_NUMBER, "` - '", VAR_IN_BALLS, "' WHERE `id` = '", VAR_IN_ADVERT_USER_ID, "'");
                    
                    PREPARE stmt FROM @VAR_QUERY;
                    EXECUTE stmt;
                    SET VAR_OUT_RESULT = (SELECT ROW_COUNT());
                    DEALLOCATE PREPARE stmt;

                END IF;

            END IF;

        ELSE

            ROLLBACK;    

        END IF;

    IF VAR_OUT_RESULT > 0
    THEN

        COMMIT;

    ELSE

        ROLLBACK;

    END IF;
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-17 19:52:05
