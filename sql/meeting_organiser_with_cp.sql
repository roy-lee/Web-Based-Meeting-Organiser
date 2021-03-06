-- MySQL Script generated by MySQL Workbench
-- Thu Nov 23 13:19:07 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema meeting_organiser
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema meeting_organiser
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `meeting_organiser` DEFAULT CHARACTER SET utf8 ;
USE `meeting_organiser` ;

-- -----------------------------------------------------
-- Table `meeting_organiser`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meeting_organiser`.`user` (
  `userID` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NULL,
  `username` VARCHAR(45) NULL,
  `password` VARCHAR(255) NULL,
  `fullName` VARCHAR(50) NULL,
  `verified` VARCHAR(5) BINARY NULL,
  PRIMARY KEY (`userID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meeting_organiser`.`venue`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meeting_organiser`.`venue` (
  `venueID` INT NOT NULL AUTO_INCREMENT,
  `venue` VARCHAR(255) NULL,
  PRIMARY KEY (`venueID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meeting_organiser`.`meeting`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meeting_organiser`.`meeting` (
  `meetingID` INT NOT NULL AUTO_INCREMENT,
  `startDate` DATE NULL,
  `endDate` DATE NULL,
  `startTime` TIME(6) NULL,
  `endTime` TIME(6) NULL,
  `title` VARCHAR(45) NULL,
  `description` VARCHAR(1024) NULL,
  `eventStatus` VARCHAR(10) NULL,
  `venue_venueID` INT NOT NULL,
  `user_userID` INT NOT NULL,
  PRIMARY KEY (`meetingID`, `venue_venueID`, `user_userID`),
  INDEX `fk_meeting_venue_idx` (`venue_venueID` ASC),
  INDEX `fk_meeting_user1_idx` (`user_userID` ASC),
  CONSTRAINT `fk_meeting_venue`
    FOREIGN KEY (`venue_venueID`)
    REFERENCES `meeting_organiser`.`venue` (`venueID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_meeting_user1`
    FOREIGN KEY (`user_userID`)
    REFERENCES `meeting_organiser`.`user` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meeting_organiser`.`meeting_participants`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meeting_organiser`.`meeting_participants` (
  `meeting_meetingID` INT NOT NULL,
  `meeting_venue_venueID` INT NOT NULL,
  `meeting_user_userID` INT NOT NULL,
  `user_userID` INT NOT NULL,
  `status` INT NULL,
  PRIMARY KEY (`meeting_meetingID`, `meeting_venue_venueID`, `meeting_user_userID`, `user_userID`),
  INDEX `fk_meeting_participants_meeting1_idx` (`meeting_meetingID` ASC, `meeting_venue_venueID` ASC, `meeting_user_userID` ASC),
  INDEX `fk_meeting_participants_user1_idx` (`user_userID` ASC),
  CONSTRAINT `fk_meeting_participants_meeting1`
    FOREIGN KEY (`meeting_meetingID` , `meeting_venue_venueID` , `meeting_user_userID`)
    REFERENCES `meeting_organiser`.`meeting` (`meetingID` , `venue_venueID` , `user_userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_meeting_participants_user1`
    FOREIGN KEY (`user_userID`)
    REFERENCES `meeting_organiser`.`user` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meeting_organiser`.`counter_proposal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meeting_organiser`.`counter_proposal` (
  `counter_proposalid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `startDate` DATE NULL,
  `endDate` DATE NULL,
  `startTime` TIME(6) NULL,
  `endTime` TIME(6) NULL,
  `status` INT NULL,
  `user_userID` INT NOT NULL,
  `meeting_meetingID` INT NOT NULL,
  `meeting_venue_venueID` INT NOT NULL,
  `meeting_user_userID` INT NOT NULL,
  PRIMARY KEY (`counter_proposalid`, `user_userID`, `meeting_meetingID`, `meeting_venue_venueID`, `meeting_user_userID`),
  INDEX `fk_counter_proposal_user1_idx` (`user_userID` ASC),
  INDEX `fk_counter_proposal_meeting1_idx` (`meeting_meetingID` ASC, `meeting_venue_venueID` ASC, `meeting_user_userID` ASC),
  CONSTRAINT `fk_counter_proposal_user1`
    FOREIGN KEY (`user_userID`)
    REFERENCES `meeting_organiser`.`user` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_counter_proposal_meeting1`
    FOREIGN KEY (`meeting_meetingID` , `meeting_venue_venueID` , `meeting_user_userID`)
    REFERENCES `meeting_organiser`.`meeting` (`meetingID` , `venue_venueID` , `user_userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
