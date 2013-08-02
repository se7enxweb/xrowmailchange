CREATE  TABLE xrow_mailchange (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `hash` VARCHAR(255) NOT NULL ,
  `user_id` INT NOT NULL ,
  `new_mail` VARCHAR(255) NOT NULL ,
  `change_time` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`id`, `hash`, `user_id`) );