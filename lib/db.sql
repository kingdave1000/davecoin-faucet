CREATE TABLE `davecoin`.`instadvk` (
`id` INT( 5 ) NOT NULL AUTO_INCREMENT ,
`dvkaddress` CHAR( 34 ) NOT NULL ,
`salt` CHAR( 10 ) NOT NULL ,
`url` CHAR( 64 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;