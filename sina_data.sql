

CREATE TABLE IF NOT EXISTS `add_data_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_info` varchar(255) NOT NULL,
  `log_time` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;




CREATE TABLE IF NOT EXISTS `data_top_hourly` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `source_id` int(10) NOT NULL COMMENT '数据源id对应data_top_source表',
  `key_words` varchar(255) NOT NULL,
  `number` int(10) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;




CREATE TABLE IF NOT EXISTS `data_top_source` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `origin` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `html_source` text NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

