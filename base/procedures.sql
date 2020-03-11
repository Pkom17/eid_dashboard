DELIMITER $$
DROP PROCEDURE IF EXISTS `proc_get_eid_outcomes` $$
CREATE PROCEDURE `proc_get_eid_outcomes` (IN which_pcr INT(1), IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  " SELECT
	infant_age_month as age_month,
    sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
	 sum(pcr_result='Positif') as positif,
	 sum(pcr_result='Négatif') as negatif
     FROM `eid_test`
     WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by infant_age_month");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_testing_trends` $$
CREATE PROCEDURE `proc_get_eid_testing_trends` (IN which_pcr INT(1), IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  " SELECT
	YEAR(released_date) as year, 
	 MONTH(released_date) as month,
     sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
	 sum(pcr_result='Positif') as positif,
	 sum(pcr_result='Négatif') as negatif
     FROM `eid_test`
     WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by year, month order by year asc,month asc");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_trends_month` $$
CREATE PROCEDURE `proc_get_eid_trends_month` (IN region_id INT(1),IN district_id INT(1),IN site_id INT(1),IN age_month_min INT(1),IN age_month_max INT(1),IN which_pcr INT(1), IN from_p INT(8), IN to_p INT(8))  BEGIN
  SET @QUERY =  " SELECT
	YEAR(released_date) as year, 
	 MONTH(released_date) as month, 
     sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
	 sum(pcr_result='Positif') as positif,
	 sum(pcr_result='Négatif') as negatif
     FROM `eid_test`
     WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
	IF(region_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `region_id` = ",region_id," ");
	END IF;
	IF(district_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `district_id` = ",district_id," ");
	END IF;
	IF(site_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `site_id` = ",site_id," ");
	END IF;
    IF(age_month_min != -1 AND age_month_max != -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `infant_age_month` between '",age_month_min ,"' and '",age_month_max ,"' ");
    END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by year, month order by year asc,month asc");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_trends_quarter` $$
CREATE PROCEDURE `proc_get_eid_trends_quarter` (IN region_id INT(1),IN district_id INT(1),IN site_id INT(1), IN age_month_min INT(1),IN age_month_max INT(1),IN which_pcr INT(1), IN from_p INT(8), IN to_p INT(8))  BEGIN
  SET @QUERY =  " SELECT
	YEAR(released_date) as year, 
	 QUARTER(released_date) as quarter,
     sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
	 sum(pcr_result='Positif') as positif,
	 sum(pcr_result='Négatif') as negatif
     FROM `eid_test`
     WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
	IF(region_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `region_id` = ",region_id," ");
	END IF;
	IF(district_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `district_id` = ",district_id," ");
	END IF;
	IF(site_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `site_id` = ",site_id," ");
	END IF;
    IF(age_month_min != -1 AND age_month_max != -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `infant_age_month` between '",age_month_min ,"' and '",age_month_max ,"' ");
    END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by year, quarter order by year asc,quarter asc");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_trends_year` $$
CREATE PROCEDURE `proc_get_eid_trends_year` (IN region_id INT(1),IN district_id INT(1),IN site_id INT(1), IN age_month_min INT(1),IN age_month_max INT(1),IN which_pcr INT(1), IN from_p INT(8), IN to_p INT(8))  BEGIN
  SET @QUERY =  " SELECT
	YEAR(released_date) as year, 
    sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
	 sum(pcr_result='Positif') as positif,
	 sum(pcr_result='Négatif') as negatif
     FROM `eid_test`
     WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
	IF(region_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `region_id` = ",region_id," ");
	END IF;
	IF(district_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `district_id` = ",district_id," ");
	END IF;
	IF(site_id != 0) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `site_id` = ",site_id," ");
	END IF;
    IF(age_month_min != -1 AND age_month_max != -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `infant_age_month` between '",age_month_min ,"' and '",age_month_max ,"' ");
    END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by year order by year asc");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_outcomes_type_clinic` $$
CREATE PROCEDURE `proc_get_eid_outcomes_type_clinic` (IN which_pcr INT(1), IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  "SELECT
                ed.entry_name clinic,
                sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
				sum(pcr_result='Positif') as positif,
				sum(pcr_result='Négatif') as negatif,
				infant_age_month as age_month
				FROM eid_test et join eid_patient ep on ep.id= et.patient_id left join eid_dictionary ed on ed.id = ep.type_of_clinic
				WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by age_month,clinic");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_outcomes_mother_regimen` $$
CREATE PROCEDURE `proc_get_eid_outcomes_mother_regimen` (IN which_pcr INT(1), IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  "SELECT
                ed.entry_name mother_regimen,
                sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
				sum(pcr_result='Positif') as positif,
				sum(pcr_result='Négatif') as negatif,
				infant_age_month as age_month
				FROM eid_test et join eid_patient ep on ep.id= et.patient_id left join eid_dictionary ed on ed.id = ep.mother_regimen
				WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by age_month,mother_regimen");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$


DROP PROCEDURE IF EXISTS `proc_get_eid_outcomes_infant_arv` $$
CREATE PROCEDURE `proc_get_eid_outcomes_infant_arv` (IN which_pcr INT(1), IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  "SELECT
                ed.entry_name infant_arv,
                sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
				sum(pcr_result='Positif') as positif,
				sum(pcr_result='Négatif') as negatif,
				infant_age_month as age_month
				FROM eid_test et join eid_patient ep on ep.id= et.patient_id left join eid_dictionary ed on ed.id = ep.infant_arv
				WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by age_month,infant_arv");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_outcomes_hiv_status` $$
CREATE PROCEDURE `proc_get_eid_outcomes_hiv_status` (IN which_pcr INT(1), IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  "SELECT
                ed.entry_name hiv_status,
                sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
				sum(pcr_result='Positif') as positif,
				sum(pcr_result='Négatif') as negatif,
				infant_age_month as age_month
				FROM eid_test et join eid_patient ep on ep.id= et.patient_id left join eid_dictionary ed on ed.id = ep.mother_hiv_status
				WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by age_month,hiv_status");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_outcomes_regions` $$
CREATE PROCEDURE `proc_get_eid_outcomes_regions` (IN which_pcr INT(1), IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  "SELECT
                r.name region,
                sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
				sum(pcr_result='Positif') as positif,
				sum(pcr_result='Négatif') as negatif
				FROM eid_test et left join region r on et.region_id = r.id
				WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by region");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_summary_all_tests` $$
CREATE PROCEDURE `proc_get_eid_summary_all_tests` (IN `from_p` INT(8), IN `to_p` INT(8), OUT total INT(8), OUT pcr1 INT(8), OUT pcr2 INT(8))  
BEGIN
	select count(*) into total from eid_test where yearmonth between from_p and to_p;
	select count(*) into pcr1 from eid_test where yearmonth between from_p and to_p and which_pcr = 1;
	select count(*) into pcr2 from eid_test where yearmonth between from_p and to_p and which_pcr = 2;
END$$


DROP PROCEDURE IF EXISTS `proc_get_eid_outcomes_plateforme` $$
CREATE PROCEDURE `proc_get_eid_outcomes_plateforme` (IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  "select
				 p.name plateforme,	
				 count(*) total,
				 sum(pcr_result='Positif') as positif,
				sum(pcr_result='Négatif') as negatif,
				sum((pcr_result!='Négatif' AND pcr_result!='Positif') OR pcr_result is null) as invalide,
				sum(which_pcr=1) as pcr_1,
				sum(which_pcr=1 AND pcr_result='Positif') as pcr_1_positif,
				sum(which_pcr=2) as pcr_2,
				sum(which_pcr=2 AND pcr_result='Positif') as pcr_2_positif,
				sum(which_pcr is null) as pcr_non_precise,
				sum(which_pcr is null AND (pcr_result='Positif')) as pcr_non_precise_positif
				from eid_test et join plateforme p on et.plateforme_id = p.id 
                where 1";
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by plateforme ");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$

DROP PROCEDURE IF EXISTS `proc_get_eid_labs_age` $$
CREATE PROCEDURE `proc_get_eid_labs_age` (IN which_pcr INT(1), IN `from_p` INT(8), IN `to_p` INT(8))  BEGIN
  SET @QUERY =  " SELECT
	p.name plateforme,	
	infant_age_month as age_month,
	count(*) total,
	sum(pcr_result='Positif') as positif
    FROM `eid_test` et join `plateforme` p on et.plateforme_id = p.id
    WHERE 1 ";
	IF(which_pcr != 0 && which_pcr != -1 ) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` = '",which_pcr,"' ");
	END IF;
	IF(which_pcr = -1) THEN 
		SET @QUERY = CONCAT(@QUERY, " AND `which_pcr` is null");
	END IF;
    SET @QUERY = CONCAT(@QUERY, " AND `yearmonth` between '",from_p ,"' and '",to_p ,"' ");
    SET @QUERY = CONCAT(@QUERY, " group by plateforme, infant_age_month");
     PREPARE stmt FROM @QUERY;
     EXECUTE stmt;
END$$



DELIMITER ;