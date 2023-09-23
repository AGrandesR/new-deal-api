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

INSERT INTO `NewDeal`.`Role` (id,description) VALUES 
  (1,'citizen'), -- Default value
  (2,'state_executive'), -- Admin position of NewDeal
  (3,'city_executive'), -- Admin position of NewDeal
  (4,'city_delegate'), -- city_trust
  (5,'state_delegate'), -- state_trust
  (6,'senator'), -- land_trust
  (7,'councilor'), -- city_councilor (X councilors)
  (8,'congress'); -- state_councilor

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
  `role` INT NOT NULL DEFAULT 1,
  `city` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `mail_UNIQUE` (`mail` ASC) VISIBLE,
  FOREIGN KEY (`role`) REFERENCES `NewDeal`.`Role`(`id`),
  FOREIGN KEY (`city_trust`) REFERENCES `NewDeal`.`User`(`id`),
  FOREIGN KEY (`state_trust`) REFERENCES `NewDeal`.`User`(`id`)
);
CREATE TABLE `NewDeal`.`Party` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `website` VARCHAR(90) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE
)
CREATE TABLE `NewDeal`.`CandidateUser` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(90) NOT NULL,
  `website` VARCHAR(90) NOT NULL,
  `party` INT,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC) VISIBLE,
  FOREIGN KEY (`party`) REFERENCES `NewDeal`.`Party`(`id`),
)

INSERT INTO `NewDeal`.`User` (`mail`, `password`, `city_trust`, `state_trust`, `city`)
VALUES
  ('test1@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL),
  ('test2@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL),
  ('test3@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL),
  ('test4@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL),
  ('test5@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL),
  ('test6@newdeal.com', '$5$5000$z.wVGYiK0FcsG/gFkZqBtsmO6xaVGTI3gkcDevmILO7', NULL, NULL, NULL);

UPDATE `NewDeal`.`User` SET `city_trust`=1 WHERE `id`=2;
UPDATE `NewDeal`.`User` SET `city_trust`=1 WHERE `id`=3;
UPDATE `NewDeal`.`User` SET `city_trust`=2 WHERE `id`=4;
UPDATE `NewDeal`.`User` SET `city_trust`=2 WHERE `id`=5;

CREATE TABLE `NewDeal`.`UserTrustData`(
  `user_id` INT NOT NULL,
  `direct_trust` INT, -- This is the SUM of the trust received
  `all_trust` INT,
  `scope` VARCHAR(25) CHECK (`scope` IN ('city', 'land', 'state', NULL)),
  PRIMARY KEY (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `NewDeal`.`User`(`id`)
);

CREATE TABLE `NewDeal`.`Project` (
    id INT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT NOT NULL,
    expiration_date DATE NOT NULL,
    vote_system INT,
    owner INT,
    city INT, -- if is null is and state proposal
    FOREIGN KEY (owner) REFERENCES `NewDeal`.`User`(id),
    FOREIGN KEY (city) REFERENCES `NewDeal`.`City`(id)
);

CREATE TABLE `NewDeal`.`Survey` (
  project_id INT,
  hashed_user VARCHAR(255),
  decision VARCHAR(25) CHECK (decision IN ('YES', 'NO', 'WHITE', NULL)),
  owner INT,
  FOREIGN KEY (owner) REFERENCES `NewDeal`.`Project` (id)
);

-- CREATE TABLE `NewDeal`.`VoteSystem` (
--   id INT PRIMARY KEY,
--   description VARCHAR(255),
--   FOREIGN KEY (owner) REFERENCES `NewDeal`.`Project`(id)
-- );