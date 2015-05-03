CREATE DATABASE IF NOT EXISTS gmail;
USE  gmail;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` LONGTEXT NOT NULL COMMENT "email id of the user",
  `password` LONGTEXT NOT NULL COMMENT "password of the user",
  PRIMARY KEY (`user_id`),
  Unique (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `thread` (
 `thread_id` int(11) NOT NULL AUTO_INCREMENT, 
 `subject` LONGTEXT NOT NULL COMMENT 'subject',
  PRIMARY KEY (`thread_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mail` (
 `mail_id` int(11) NOT NULL AUTO_INCREMENT, 
 `fk_thread_id` int(11) NOT NULL COMMENT 'mail comes under which thread',
 `fk_user_id` int(11) NOT NULL COMMENT 'mail created by which user',
 `content` LONGTEXT NOT NULL COMMENT 'content of the mail',
 `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mail_id`),
  FOREIGN KEY (`fk_thread_id`) REFERENCES `thread` (`thread_id`),
  FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_mail_sent_mapping` (
 `fk_mail_id` int(11) NOT NULL COMMENT 'mail id',
 `fk_user_id` int(11) NOT NULL COMMENT 'mail sent to which user',
 `is_cc` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is the user cc', 
 `is_bcc` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is the user bcc',
  FOREIGN KEY (`fk_mail_id`) REFERENCES `mail` (`mail_id`),
  FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

insert into `user`(`email_id`, `password`) values("kshah215@gmail.com", "02091993");
insert into `user`(`email_id`, `password`) values("zeel.shah@gmail.com", "02091993");
INSERT INTO `thread`(`subject`) VALUES("Hello");
INSERT INTO `mail`(`fk_thread_id`, `fk_user_id`, `content`) VALUES(1, 1,"welcome to email client!");
INSERT INTO `user_mail_sent_mapping`(`fk_mail_id`, `fk_user_id`) VALUES(1, 2);
