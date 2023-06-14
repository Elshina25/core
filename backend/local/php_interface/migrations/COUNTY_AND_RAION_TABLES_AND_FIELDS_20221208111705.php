<?php

namespace Sprint\Migration;


class COUNTY_AND_RAION_TABLES_AND_FIELDS_20221208111705 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        global $DB;
		$sql1 = "
			CREATE TABLE IF NOT EXISTS `ita_county` (
			  `id` INT(11) NOT NULL AUTO_INCREMENT,
			  `name_ru` VARCHAR(45) NOT NULL,
			  `ml_realty_directions_id` INT(11),
			  `code` VARCHAR(45) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
			  INDEX `fk_ita_county_ml_realty_directions1_idx` (`ml_realty_directions_id` ASC),
			  CONSTRAINT `fk_ita_county_ml_realty_directions1`
				FOREIGN KEY (`ml_realty_directions_id`)
				REFERENCES `ml_realty_directions` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8;
			";

		$sql2 = "
			CREATE TABLE IF NOT EXISTS `ita_raion` (
			  `id` INT(11) NOT NULL AUTO_INCREMENT,
			  `name_ru` VARCHAR(45) NOT NULL,
			  `ita_county_id` INT(11) NOT NULL,
			  `ml_realty_directions_id` INT(11),
			  `code` VARCHAR(45) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
			  INDEX `fk_ita_raion_ita_county_idx` (`ita_county_id` ASC),
			  INDEX `fk_ita_raion_ml_realty_directions1_idx` (`ml_realty_directions_id` ASC),
			  CONSTRAINT `fk_ita_raion_ita_county`
				FOREIGN KEY (`ita_county_id`)
				REFERENCES `ita_county` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION,
			  CONSTRAINT `fk_ita_raion_ml_realty_directions1`
				FOREIGN KEY (`ml_realty_directions_id`)
				REFERENCES `ml_realty_directions` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8;
		";

		$sql3 = "
			CREATE TABLE IF NOT EXISTS `ita_raion_ml_realty_location_zones` (
			  `ita_raion_id` INT(11) NOT NULL,
			  `ml_realty_location_zones_id` INT(11) NOT NULL,
			  INDEX `fk_ita_raion_ml_realty_location_zones_ita_raion1_idx` (`ita_raion_id` ASC),
			  INDEX `fk_ita_raion_ml_realty_location_zones_ml_realty_location_zo_idx` (`ml_realty_location_zones_id` ASC),
			  CONSTRAINT `fk_ita_raion_ml_realty_location_zones_ita_raion1`
				FOREIGN KEY (`ita_raion_id`)
				REFERENCES `ita_raion` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION,
			  CONSTRAINT `fk_ita_raion_ml_realty_location_zones_ml_realty_location_zones1`
				FOREIGN KEY (`ml_realty_location_zones_id`)
				REFERENCES `ml_realty_location_zones` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8;
			";

		$sql4 =	"
			CREATE TABLE IF NOT EXISTS `ita_county_ml_realty_location_zones` (
			  `ml_realty_location_zones_id` INT(11) NOT NULL,
			  `ita_county_id` INT(11) NOT NULL,
			  INDEX `fk_ita_county_ml_realty_location_zones_ml_realty_location_z_idx` (`ml_realty_location_zones_id` ASC),
			  INDEX `fk_ita_county_ml_realty_location_zones_ita_county1_idx` (`ita_county_id` ASC),
			  CONSTRAINT `fk_ita_county_ml_realty_location_zones_ml_realty_location_zon1`
				FOREIGN KEY (`ml_realty_location_zones_id`)
				REFERENCES `ml_realty_location_zones` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION,
			  CONSTRAINT `fk_ita_county_ml_realty_location_zones_ita_county1`
				FOREIGN KEY (`ita_county_id`)
				REFERENCES `ita_county` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = utf8;
		";
		$sql5 ="
			ALTER TABLE ml_realty_objects
			ADD ita_county_id INT(11);
		";
		$sql6 = "
			ALTER TABLE ml_realty_objects
			ADD ita_raion_id INT(11);
		";
		$sql7 = "
			ALTER TABLE ml_realty_objects
			ADD CONSTRAINT ml_realty_objects_county_fk 
			FOREIGN KEY(ita_county_id) REFERENCES ita_county(id);
		";
		$sql8 = "
			ALTER TABLE ml_realty_objects
			ADD CONSTRAINT ml_realty_objects_raion_fk 
			FOREIGN KEY(ita_raion_id) REFERENCES ita_raion(id);
		";
		$sql9 = "
			ALTER TABLE ita_county
			ADD ml_realty_cities_id INT(11);
		";
		$sql10 =	"
			ALTER TABLE ita_county
			ADD CONSTRAINT ml_realty_cities_county_fk 
			FOREIGN KEY(ml_realty_cities_id) REFERENCES ml_realty_cities(id);
		";
		$sql11 = "
			ALTER TABLE ita_raion
			ADD ml_realty_cities_id INT(11);
		";
		$sql12 = "
			ALTER TABLE ita_raion
			ADD CONSTRAINT ml_realty_cities_raion_fk
			FOREIGN KEY(ml_realty_cities_id) REFERENCES ml_realty_cities(id);
		";
		$sql13 = "	
			ALTER TABLE ml_realty_metro
			ADD ita_raion_id INT(11);
		";
		$sql14 = "
			ALTER TABLE ml_realty_metro
			ADD CONSTRAINT ita_raion_metro_fk
			FOREIGN KEY(ita_raion_id) REFERENCES ita_raion(id);
		";
		$sql15 = "
			INSERT INTO `ita_county`(id, name_ru, ml_realty_directions_id, code, ml_realty_cities_id) VALUES (1,'ЦАО',NULL,'tsao', 6),(2,'САО',1,'sao', 6),(3,'СВАО',6,'svao', 6),(4,'ВАО',8,'vao', 6),(5,'ЮВАО',2,'uvao', 6),(6,'ЮАО',4,'uao', 6),(7,'ЮЗАО',5,'uzao', 6),(8,'ЗАО',7,'zao', 6),(9,'СЗАО',3,'szao', 6);
		";
		$sql16 = "
			INSERT INTO `ita_raion`(id, name_ru, ita_county_id, ml_realty_directions_id, code, ml_realty_cities_id) VALUES (1,'Арбат',1,NULL,'arbat', 6),(2,'Басманный',1,NULL,'basmanniy', 6),(3,'Замоскворечье',1,NULL,'zamoskvorechie', 6),(4,'Красносельский',1,NULL,'krasnoselskiy', 6),(5,'Мещанский',1,NULL,'meshanskiy', 6),(6,'Пресненский',1,NULL,'presnenskiy', 6),(7,'Таганский',1,NULL,'taganskiy', 6),(8,'Тверской',1,NULL,'tverskoy', 6),(9,'Хамовники',1,NULL,'hamovniki', 6),(10,'Якиманка',1,NULL,'yakimanka', 6),(11,'Аэропорт',2,NULL,'aeroport', 6),(12,'Беговой',2,NULL,'begovoy', 6),(13,'Бескудниковский',2,NULL,'beskudnikovskiy', 6),(14,'Войковский',2,NULL,'voykovskiy', 6),(15,'Головинский',2,NULL,'golovinskiy', 6),(16,'Дегунино Восточное',2,NULL,'degudino_vostochnoe', 6),(17,'Дегунино Западное',2,NULL,'degudino_zapadnoe', 6),(18,'Дмитровский',2,NULL,'dmitrovskiy', 6),(19,'Коптево',2,NULL,'koptevo', 6),(20,'Левобережный',2,NULL,'levoberejniy', 6),(21,'Молжаниновский',2,NULL,'moljaninovskiy', 6),(22,'Савёловский',2,NULL,'savelovskiy', 6),(23,'Сокол',2,NULL,'sokol', 6),(24,'Тимирязевский',2,NULL,'timiryazevskiy', 6),(25,'Ховрино',2,NULL,'hovrino', 6),(26,'Хорошёвский',2,NULL,'horoshevskiy', 6);
		";
		$DB->Query($sql1);
		$DB->Query($sql2);
		$DB->Query($sql3);
		$DB->Query($sql4);
		$DB->Query($sql5);
		$DB->Query($sql6);
		$DB->Query($sql7);
		$DB->Query($sql8);
		$DB->Query($sql9);
		$DB->Query($sql10);
		$DB->Query($sql11);
		$DB->Query($sql12);
		$DB->Query($sql13);
		$DB->Query($sql14);
		$DB->Query($sql15);
		$DB->Query($sql16);
    }

    public function down()
    {
		global $DB;
		$sql1 = "
			ALTER TABLE ml_realty_objects
			DROP FOREIGN KEY ml_realty_objects_county_fk;
		";
		$sql2 = "
			ALTER TABLE ml_realty_objects
			DROP FOREIGN KEY ml_realty_objects_raion_fk;
		";
		$sql3 = "
			ALTER TABLE ml_realty_objects
			DROP COLUMN ita_county_id;
		";
		$sql4 = "
			ALTER TABLE ml_realty_objects
			DROP COLUMN ita_raion_id;
		";
		$sql5 = "
			ALTER TABLE ml_realty_metro
			DROP FOREIGN KEY ita_raion_metro_fk;
		";
		$sql6 ="
			ALTER TABLE ml_realty_metro
			DROP COLUMN ita_raion_id;
		";
		$sql7 = "
			DROP TABLE ita_raion_ml_realty_location_zones;
		";
		$sql8 = "
			DROP TABLE ita_county_ml_realty_location_zones;
		";
		$sql9 = "
			DROP TABLE ita_raion;
		";
		$sql10 = "
			DROP TABLE ita_county;
		";
		$DB->Query($sql1);
		$DB->Query($sql2);
		$DB->Query($sql3);
		$DB->Query($sql4);
		$DB->Query($sql5);
		$DB->Query($sql6);
		$DB->Query($sql7);
		$DB->Query($sql8);
		$DB->Query($sql9);
		$DB->Query($sql10);
    }
}
