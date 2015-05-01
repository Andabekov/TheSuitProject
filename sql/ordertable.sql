CREATE TABLE `ordertable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `dateofsale` date DEFAULT NULL,
  `cityofsale_id` int(11) DEFAULT NULL,
  `pointofsale` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ordertable_customer_id_ind` (`customer_id`),
  KEY `ordertable_seller_id_ind` (`seller_id`),
  KEY `ordertable_cityofsale_id_ind` (`cityofsale_id`),
  CONSTRAINT `ordertable_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  CONSTRAINT `citiestable_ibfk_1` FOREIGN KEY (`cityofsale_id`) REFERENCES `citiestable` (`id`),
  CONSTRAINT `userstable_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `userstable` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
