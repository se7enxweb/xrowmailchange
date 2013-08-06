CREATE TABLE `xrow_mailchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `new_mail` varchar(255) NOT NULL,
  `change_time` int(11) NOT NULL,
  PRIMARY KEY (`id`,`hash`,`user_id`)
)