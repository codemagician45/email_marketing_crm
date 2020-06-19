ALTER TABLE `sms_api_config` CHANGE `gateway_name` `gateway_name` ENUM( 'planet', 'plivo', 'twilio', 'clickatell', 'nexmo', 'msg91.com', 'textlocal.in', 'sms4connect.com', 'telnor.com', 'mvaayoo.com' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

CREATE TABLE IF NOT EXISTS `forget_password_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `smtp_host` varchar(200) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) NOT NULL,
  `monthly_fee` double NOT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `users` ADD `add_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `deleted` ,
ADD `expired_date` DATE NOT NULL AFTER `add_date`;

ALTER TABLE `contacts` CHANGE `contact_type_id` `contact_type_id` TEXT NOT NULL;

INSERT INTO `payment_config` (`id`, `paypal_email`, `monthly_fee`, `currency`, `deleted`) VALUES (NULL, 'exaple@gmail.com', '10', 'USD', '0');