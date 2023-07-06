CREATE SCHEMA `NewDeal` ;

CREATE TABLE `NewDeal`.`Preuser` (
  `token` VARCHAR(255) NOT NULL,
  `mail` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`token`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC) VISIBLE,
  UNIQUE INDEX `mail_UNIQUE` (`mail` ASC) VISIBLE
);

CREATE TABLE `NewDeal`.`Role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `NewDeal`.`City` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `land_trust` INT,
  `size` INT,
  PRIMARY KEY (`id`)
);

CREATE TABLE `NewDeal`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `mail` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `city_trust` INT,
  `state_trust` INT,
  `role` INT,
  `city` INT,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `mail_UNIQUE` (`mail` ASC) VISIBLE,
  FOREIGN KEY (`role`) REFERENCES `NewDeal`.`Role`(`id`),
  FOREIGN KEY (`city_trust`) REFERENCES `NewDeal`.`User`(`id`),
  FOREIGN KEY (`state_trust`) REFERENCES `NewDeal`.`User`(`id`)
);

INSERT INTO `NewDeal`.`User` (`mail`, `password`, `city_trust`, `state_trust`, `role`, `city`)
VALUES
  ('test1@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL, NULL),
  ('test3@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL, NULL),
  ('test4@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL, NULL),
  ('test5@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL, NULL),
  ('test6@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL, NULL);



INSERT INTO `NewDeal`.`Role` (description) VALUES 
  ('citizen'), -- Default value
  ('state_executive'), -- Admin position of NewDeal
  ('city_executive'), -- Admin position of NewDeal
  ('city_delegate'), -- city_trust
  ('state_delegate'), -- state_trust
  ('senator'), -- land_trust
  ('councilor'), -- city_councilor (X councilors)
  ('congress'); -- state_councilor


CREATE TABLE `NewDeal`.`Project` (
    id INT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    expiration_date DATE,
    vote_system INT,
    owner INT,
    FOREIGN KEY (owner) REFERENCES `NewDeal`.`User`(id)
);

CREATE TABLE `NewDeal`.`Survey` (
  project_id INT,
  hashed_user VARCHAR(255),
  decision VARCHAR(255) CHECK (decision IN ('YES', 'NO', 'WHITE', NULL))
  FOREIGN KEY (owner) REFERENCES `NewDeal`.`Project`(id)
);
