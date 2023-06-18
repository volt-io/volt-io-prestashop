CREATE TABLE IF NOT EXISTS `_DB_PREFIX_volt_transactions`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) DEFAULT NULL,
    `reference` varchar(266) DEFAULT NULL,
    `crc` varchar(266) DEFAULT NULL,
    `amount` int(11) DEFAULT NULL,
    `currency` varchar(32) DEFAULT NULL,
    `status` varchar(128) DEFAULT NULL,
    `date_upd` DATETIME DEFAULT NULL,
    `status_detail` varchar(266) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = _MYSQL_ENGINE_
  DEFAULT CHARSET = UTF8;

CREATE TABLE IF NOT EXISTS `_DB_PREFIX_volt_refunds`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) DEFAULT NULL,
    `crc` varchar(266) DEFAULT NULL,
    `reference` varchar(266) DEFAULT NULL,
    `amount` int(11) DEFAULT NULL,
    `currency` varchar(32) DEFAULT NULL,
    `status` varchar(128) DEFAULT NULL,
    `date_upd` DATETIME DEFAULT NULL,
    `status_detail` varchar(266) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = _MYSQL_ENGINE_
  DEFAULT CHARSET = UTF8;
