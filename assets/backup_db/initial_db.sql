-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2019 at 11:49 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ssem_updated`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_ons`
--

CREATE TABLE IF NOT EXISTS `add_ons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `add_on_name` varchar(255) NOT NULL,
  `unique_name` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `installed_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  `purchase_code` varchar(100) NOT NULL,
  `module_folder_name` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`unique_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `birthday_reminder`
--

CREATE TABLE IF NOT EXISTS `birthday_reminder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_id` int(11) NOT NULL COMMENT 'sms_api_config.id',
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sms_template` text CHARACTER SET utf8 NOT NULL,
  `time_zone` varchar(100) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT 'pending,complete,processing',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `birthday_reminder_email`
--

CREATE TABLE IF NOT EXISTS `birthday_reminder_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_id` int(11) NOT NULL COMMENT 'configure_table_name.id',
  `configure_table_name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` text CHARACTER SET utf8 NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `time_zone` varchar(100) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT 'pending,complete,processing',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_birth` date NOT NULL,
  `contact_type_id` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `unsubscribed` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `sms_last_wished_year` year(4) NOT NULL,
  `email_last_wished_year` year(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `contact_type`
--

CREATE TABLE IF NOT EXISTS `contact_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `smtp_host` varchar(200) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_type` enum('Default','tls','ssl') NOT NULL DEFAULT 'Default',
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- Table structure for table `email_history`
--

CREATE TABLE IF NOT EXISTS `email_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `api_id` int(11) NOT NULL COMMENT 'configure_table_name.id',
  `configure_table_name` varchar(100) NOT NULL,
  `to_email` varchar(200) NOT NULL,
  `send_status` varchar(250) DEFAULT NULL,
  `sent_time` datetime NOT NULL,
  `subject` text CHARACTER SET utf8 NOT NULL,
  `email_message` longtext CHARACTER SET utf8 NOT NULL,
  `attachment` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `email_mailgun_config`
--

CREATE TABLE IF NOT EXISTS `email_mailgun_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `domain_name` varchar(200) NOT NULL,
  `api_key` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `email_mandrill_config`
--

CREATE TABLE IF NOT EXISTS `email_mandrill_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `your_name` varchar(200) NOT NULL,
  `user_id` int(12) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `api_key` varchar(200) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- Table structure for table `email_sendgrid_config`
--

CREATE TABLE IF NOT EXISTS `email_sendgrid_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `email_sending_campaign`
--

CREATE TABLE IF NOT EXISTS `email_sending_campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `configure_email_table` varchar(255) NOT NULL,
  `api_id` int(11) NOT NULL COMMENT 'configure_email_table_id',
  `contact_ids` mediumtext NOT NULL,
  `contact_type_id` mediumtext NOT NULL COMMENT 'contact_group_table_id',
  `campaign_name` mediumtext NOT NULL,
  `email_subject` mediumtext NOT NULL,
  `email_message` longtext NOT NULL,
  `email_attachment` text NOT NULL,
  `posting_status` enum('0','1','2','3') NOT NULL,
  `schedule_time` datetime NOT NULL,
  `time_zone` mediumtext NOT NULL,
  `total_thread` int(11) NOT NULL,
  `successfully_sent` int(11) NOT NULL,
  `added_at` datetime NOT NULL,
  `completed_at` datetime NOT NULL,
  `report` longtext NOT NULL,
  `is_try_again` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


--
-- Table structure for table `email_sending_campaign_send`
--

CREATE TABLE IF NOT EXISTS `email_sending_campaign_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_table_name` varchar(255) NOT NULL COMMENT 'configure_email_table_name',
  `email_api_id` int(11) NOT NULL COMMENT 'configure_email_table_id',
  `campaign_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `contact_first_name` varchar(255) NOT NULL,
  `contact_last_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `delivery_id` varchar(255) NOT NULL,
  `sent_time` datetime NOT NULL,
  `processed` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


--
-- Table structure for table `forget_password`
--

CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(15) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `forget_password_config`
--

CREATE TABLE IF NOT EXISTS `forget_password_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `smtp_host` varchar(200) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_type` enum('Default','tls','ssl') NOT NULL DEFAULT 'Default',
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Table structure for table `message_template`
--

CREATE TABLE IF NOT EXISTS `message_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `template_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `message_template_email`
--

CREATE TABLE IF NOT EXISTS `message_template_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `template_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `message` longtext CHARACTER SET utf8 NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


--
-- Table structure for table `payment_config`
--

CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) NOT NULL,
  `monthly_fee` double NOT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- Dumping data for table `payment_config`
--

INSERT INTO `payment_config` (`paypal_email`, `monthly_fee`, `currency`, `deleted`) VALUES
('yourPaypalEmail@example.com', 0, 'USD', '0');

-- --------------------------------------------------------

--
-- Table structure for table `paypal_error_log`
--

CREATE TABLE IF NOT EXISTS `paypal_error_log` ( 
  `id` INT(11) NOT NULL AUTO_INCREMENT , 
  `call_time` DATETIME NULL , 
  `ipn_value` TEXT NULL , 
  `error_log` VARCHAR(255) NULL , 
  PRIMARY KEY (`id`)
  ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

--
-- Table structure for table `sms_api_config`
--

CREATE TABLE IF NOT EXISTS `sms_api_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gateway_name` enum('planet','plivo','twilio','clickatell','clickatell-platform','nexmo','msg91.com','textlocal.in','sms4connect.com','telnor.com','mvaayoo.com','routesms.com','trio-mobile.com','sms40.com','africastalking.com','infobip.com','smsgatewayme','semysms.net') NOT NULL,
  `username_auth_id` tinytext NOT NULL,
  `password_auth_token` tinytext NOT NULL,
  `api_id` tinytext NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


--
-- Table structure for table `sms_history`
--

CREATE TABLE IF NOT EXISTS `sms_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gateway_id` int(11) NOT NULL COMMENT 'sms_api_config.id',
  `to_number` varchar(50) NOT NULL,
  `sms_status` varchar(250) DEFAULT NULL,
  `sms_uid` varchar(100) NOT NULL,
  `sent_time` datetime NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;



--
-- Table structure for table `sms_sending_campaign`
--

CREATE TABLE IF NOT EXISTS `sms_sending_campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_id` int(11) NOT NULL,
  `contact_ids` mediumtext NOT NULL,
  `contact_type_id` mediumtext NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `campaign_message` mediumtext NOT NULL,
  `manual_phone` varchar(255) NOT NULL,
  `posting_status` enum('0','1','2','3') NOT NULL,
  `schedule_time` datetime NOT NULL,
  `time_zone` mediumtext NOT NULL,
  `total_thread` int(11) NOT NULL,
  `successfully_sent` int(11) NOT NULL,
  `added_at` datetime NOT NULL,
  `completed_at` datetime NOT NULL,
  `report` longtext NOT NULL,
  `is_try_again` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `sms_sending_campaign_send`
--

CREATE TABLE IF NOT EXISTS `sms_sending_campaign_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sms_api_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `contact_first_name` varchar(255) NOT NULL,
  `contact_last_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone_number` varchar(255) NOT NULL,
  `delivery_id` varchar(250) NOT NULL,
  `sent_time` datetime NOT NULL,
  `processed` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `ssem_api`
--

CREATE TABLE IF NOT EXISTS `ssem_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- Table structure for table `transaction_history`
--

CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_status` varchar(200) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `paypal_email` varchar(200) NOT NULL,
  `receiver_email` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `payment_date` varchar(250) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cycle_start_date` date NOT NULL,
  `cycle_expired_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `update_list`
--

CREATE TABLE IF NOT EXISTS `update_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `files` text NOT NULL,
  `sql_query` text NOT NULL,
  `update_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` enum('Admin','User') NOT NULL,
  `status` enum('1','0') NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `activation_code` int(11) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expired_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `mobile` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`first_name`, `last_name`, `email`, `mobile`, `username`, `password`, `user_type`, `status`, `deleted`, `activation_code`, `add_date`, `expired_date`) VALUES
('Admin', '', 'admin@gmail.com', '8801723309003', 'admin', '259534db5d66c3effb7aa2dbbee67ab0', 'Admin', '1', '0', 0, '2016-04-10 14:06:15', '0000-00-00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Table structure for table `version`
--

CREATE TABLE IF NOT EXISTS `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `current` enum('1','0') NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


INSERT INTO `version` (`version`, `current`, `date`) VALUES
('4.1', '1', '2019-05-18 14:22:27');



CREATE ALGORITHM = UNDEFINED VIEW `view_sms_sent_history_user_wise_total` (user_id,first_name,last_name,gateway_name,email,mobile,username,total_sms_sent) 
AS SELECT sms_history.user_id, first_name, last_name, gateway_name, email, phone_number, username, count( sms_history.id ) AS total_sms_sent 
FROM sms_history 
LEFT JOIN users ON ( users.id = sms_history.user_id ) 
LEFT JOIN sms_api_config ON (sms_api_config.id = sms_history.gateway_id) 
GROUP BY sms_history.user_id;



CREATE ALGORITHM = UNDEFINED VIEW `view_sms_sent_history_user_wise_this_month` (user_id,month_name,first_name,last_name,gatway_name,email,mobile,username,total_sms_sent) 
AS select sms_history.user_id,MONTHNAME(sent_time) as month_name,first_name,last_name,gateway_name,email,phone_number,username,count(sms_history.id) as total_sms_sent from sms_history 
LEFT JOIN users on (users.id=sms_history.user_id) 
LEFT JOIN sms_api_config ON (sms_api_config.id = sms_history.gateway_id) 
where month(sent_time)=month(CURDATE()) group by user_id; 



CREATE ALGORITHM = UNDEFINED VIEW `view_month_user_sms_history` (user_id,month_name,month_number,years,first_name,last_name,gateway_name,email,mobile,username,total_sms_sent) 
AS select sms_history.user_id,MONTHNAME(sent_time) as month_name,MONTH(sent_time), YEAR(sent_time) as years, first_name,last_name,gateway_name,email,phone_number,username,count(sms_history.id) as total_sms_sent 
from sms_history 
LEFT JOIN users on (users.id=sms_history.user_id) 
LEFT JOIN sms_api_config ON (sms_api_config.id = sms_history.gateway_id) 
group by user_id, MONTH(sent_time), YEAR(sent_time); 


CREATE ALGORITHM = UNDEFINED VIEW `view_email_sent_history_user_wise_total` (user_id,first_name,last_name,email,username,total_email_sent) 
AS SELECT email_history.user_id, first_name, last_name, email,username, count( email_history.id ) AS total_email_sent 
FROM email_history 
LEFT JOIN users ON ( users.id = email_history.user_id ) 
GROUP BY email_history.user_id;



CREATE ALGORITHM = UNDEFINED VIEW `view_email_sent_history_user_wise_this_month` (user_id,month_name,first_name,last_name,email,username,total_email_sent) 
AS select email_history.user_id,MONTHNAME(sent_time) as month_name,first_name,last_name,email,username,count(email_history.id) as total_email_sent 
from email_history 
LEFT JOIN users on (users.id=email_history.user_id) 
where month(sent_time)=month(CURDATE()) group by user_id; 


CREATE ALGORITHM = UNDEFINED VIEW `view_month_user_email_history` (user_id,month_name,month_number,years,first_name,last_name,email,username,total_email_sent) 
AS select email_history.user_id,MONTHNAME(sent_time) as month_name,MONTH(sent_time), YEAR(sent_time) as years, first_name,last_name,email,username,count(email_history.id) as total_email_sent 
from email_history 
LEFT JOIN users on (users.id=email_history.user_id) 
group by user_id, MONTH(sent_time), YEAR(sent_time); 




ALTER TABLE `contact_type` CHANGE `type` `type` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `contacts` CHANGE `first_name` `first_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `contacts` CHANGE `last_name` `last_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `users` CHANGE `first_name` `first_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `users` CHANGE `last_name` `last_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `users` CHANGE `username` `username` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

