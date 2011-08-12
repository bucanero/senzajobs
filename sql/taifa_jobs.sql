/*
MySQL Data Transfer
Source Host: localhost
Source Database: taifa_jobs
Target Host: localhost
Target Database: taifa_jobs
Date: 10/03/2008 16:37:05
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for applicant
-- ----------------------------
CREATE TABLE `applicant` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) default NULL,
  `salutation` varchar(35) default NULL,
  `surname` varchar(30) default NULL,
  `mname` varchar(30) default NULL,
  `fname` varchar(30) default NULL,
  `mstatus` varchar(30) default NULL,
  `sex` char(1) default NULL,
  `dob` date default NULL,
  `nationality` int(11) default NULL,
  `citizenship` int(11) default NULL,
  `ctoforigin` int(11) default NULL,
  `hbox` char(10) default NULL,
  `htown` varchar(65) default NULL,
  `hzip_postal` varchar(15) default NULL,
  `hcountry` int(11) default NULL,
  `hphone` varchar(15) default NULL,
  `hmobile` varchar(15) default NULL,
  `hemail` varchar(100) default NULL,
  `obox` char(10) default NULL,
  `otown` varchar(65) default NULL,
  `ozip_postal` varchar(15) default NULL,
  `ocountry` int(11) default NULL,
  `ophone` varchar(15) default NULL,
  `omobile` varchar(15) default NULL,
  `oemail` varchar(100) default NULL,
  `qualsumm` text,
  `cvviews` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for applications
-- ----------------------------
CREATE TABLE `applications` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) NOT NULL,
  `jobid` int(11) NOT NULL,
  `dateapplied` date NOT NULL,
  `shortlisted` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `applicationid` (`applicantid`,`jobid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for careerlevel
-- ----------------------------
CREATE TABLE `careerlevel` (
  `careerid` int(11) NOT NULL auto_increment,
  `careerlevel` varchar(255) default NULL,
  PRIMARY KEY  (`careerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for countries
-- ----------------------------
CREATE TABLE `countries` (
  `countryid` smallint(6) NOT NULL auto_increment,
  `country` varchar(150) NOT NULL,
  `countrycode` char(10) NOT NULL,
  `subscriber` char(19) default NULL,
  `nationality` varchar(150) default NULL,
  `currency` varchar(45) default NULL,
  PRIMARY KEY  (`countryid`),
  UNIQUE KEY `countrycode` (`countrycode`),
  KEY `country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 121856 kB; InnoDB free: 121856 kB; InnoDB free:';

-- ----------------------------
-- Table structure for education
-- ----------------------------
CREATE TABLE `education` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) default NULL,
  `institution` varchar(255) default NULL,
  `countryid` int(11) default NULL,
  `city` varchar(255) default NULL,
  `award` varchar(255) default NULL,
  `awardcategory` tinyint(1) default NULL,
  `highestlevel` tinyint(1) default NULL,
  `fieldofstudy` varchar(255) default NULL,
  `fieldofstudycategoryid` int(11) default NULL,
  `specialaward` varchar(255) default NULL,
  `yearofgraduation` year(4) default NULL,
  `expectedgraduation` year(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for employer
-- ----------------------------
CREATE TABLE `employer` (
  `id` int(11) NOT NULL auto_increment,
  `employerid` int(11) NOT NULL,
  `organization` varchar(100) default NULL,
  `contact` varchar(65) default NULL,
  `jobtitle` varchar(45) default NULL,
  `telephone` varchar(25) default NULL,
  `extension` varchar(5) default NULL,
  `mobile` varchar(25) default NULL,
  `fax` varchar(25) default NULL,
  `email` varchar(100) NOT NULL,
  `box` varchar(10) default NULL,
  `town` varchar(55) default NULL,
  `zip_postal` varchar(45) default NULL,
  `website` varchar(100) default NULL,
  `countryid` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for emptype
-- ----------------------------
CREATE TABLE `emptype` (
  `id` int(11) NOT NULL auto_increment,
  `employmenttype` varchar(65) NOT NULL,
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for experience
-- ----------------------------
CREATE TABLE `experience` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) default NULL,
  `organization` varchar(255) default NULL,
  `startmonth` tinyint(4) default NULL,
  `startyear` year(4) default NULL,
  `endmonth` tinyint(4) default NULL,
  `endyear` year(4) default NULL,
  `startsalarymonth` decimal(10,0) default NULL,
  `currentsalarymonth` decimal(10,0) default NULL,
  `jobtitle` varchar(255) default NULL,
  `manager_supervisor` tinyint(1) default NULL,
  `duties_responsibilities` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for job
-- ----------------------------
CREATE TABLE `job` (
  `jobid` int(11) NOT NULL auto_increment,
  `employerid` int(11) default NULL,
  `jobcategory` varchar(255) default NULL,
  `employeetype` varchar(65) default NULL,
  `city` varchar(100) default NULL,
  `countryid` int(11) default NULL,
  `jobtitle` varchar(100) NOT NULL,
  `summary` text,
  `description` text,
  `requirements` text,
  `dateposted` date default NULL,
  `dateclosing` date default NULL,
  `contactinfo` text,
  `pay` decimal(10,0) default NULL,
  PRIMARY KEY  (`jobid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for jobcat
-- ----------------------------
CREATE TABLE `jobcat` (
  `id` int(11) NOT NULL auto_increment,
  `jobcategory` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for language
-- ----------------------------
CREATE TABLE `language` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) NOT NULL,
  `language` varchar(255) NOT NULL,
  `orallevel` varchar(20) NOT NULL,
  `writtenlevel` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for languages
-- ----------------------------
CREATE TABLE `languages` (
  `id` int(11) NOT NULL auto_increment,
  `language` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for objective
-- ----------------------------
CREATE TABLE `objective` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) NOT NULL,
  `objective` varchar(255) default NULL,
  `carrierlevelid` int(11) default NULL,
  PRIMARY KEY  (`id`,`applicantid`),
  UNIQUE KEY `applicantid` (`applicantid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for professional
-- ----------------------------
CREATE TABLE `professional` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) NOT NULL,
  `association` varchar(255) NOT NULL,
  `title_role` varchar(255) NOT NULL,
  `membersince` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for publication
-- ----------------------------
CREATE TABLE `publication` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) NOT NULL,
  `pdate` date NOT NULL,
  `ptitle` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for referee
-- ----------------------------
CREATE TABLE `referee` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `organization` varchar(255) default NULL,
  `refposition` varchar(255) default NULL,
  `telephone` varchar(255) NOT NULL,
  `email` varchar(255) default NULL,
  `relation` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for studyfieldcat
-- ----------------------------
CREATE TABLE `studyfieldcat` (
  `id` int(11) NOT NULL auto_increment,
  `fieldcategory` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for training
-- ----------------------------
CREATE TABLE `training` (
  `id` int(11) NOT NULL auto_increment,
  `applicantid` int(11) default NULL,
  `trainingtitle` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for users
-- ----------------------------
CREATE TABLE `users` (
  `userid` smallint(6) NOT NULL auto_increment,
  `fname` varchar(25) default '',
  `mname` varchar(25) default NULL,
  `sname` varchar(25) default '',
  `loginname` varchar(15) NOT NULL default '',
  `pass` varchar(32) NOT NULL default '',
  `email` varchar(65) NOT NULL,
  `dateregistered` date default NULL,
  `admin` tinyint(1) default '0',
  `status` char(1) default NULL COMMENT 'Active,Locked,Disabled',
  `usercategory` char(1) default NULL,
  PRIMARY KEY  (`userid`,`loginname`,`email`),
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `loginname` (`loginname`),
  UNIQUE KEY `email` (`email`),
  KEY `names` (`fname`,`sname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 121856 kB; InnoDB free: 121856 kB; InnoDB free:';