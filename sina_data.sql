-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 07 月 19 日 11:07
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `sina_data`
--

-- --------------------------------------------------------

--
-- 表的结构 `add_data_log`
--

CREATE TABLE IF NOT EXISTS `add_data_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_info` varchar(255) NOT NULL,
  `log_time` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1139 ;

-- --------------------------------------------------------

--
-- 表的结构 `data_search_keywords`
--

CREATE TABLE IF NOT EXISTS `data_search_keywords` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `keywords` varchar(255) NOT NULL,
  `keywords_table` varchar(255) NOT NULL COMMENT '微博搜索关键词对应表',
  `add_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `data_search_keywords_wanhao`
--

CREATE TABLE IF NOT EXISTS `data_search_keywords_wanhao` (
  `keywords_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '微博用户名称 ',
  `weibo_content` varchar(255) NOT NULL COMMENT '微博内容 ',
  `weibo_time` datetime NOT NULL COMMENT '发微薄时间  ',
  `forward_num` int(10) NOT NULL COMMENT '转发次数  ',
  `comment_num` int(10) NOT NULL COMMENT '评论数',
  `weibo_thumbimg` varchar(255) NOT NULL COMMENT '微博图片 ',
  `weibo_middleimg` varchar(255) NOT NULL,
  `weibo_largeimg` varchar(255) NOT NULL,
  PRIMARY KEY (`keywords_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `data_top_hourly`
--

CREATE TABLE IF NOT EXISTS `data_top_hourly` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `source_id` int(10) NOT NULL COMMENT '数据源id对应data_top_source表',
  `key_words` varchar(255) NOT NULL,
  `number` int(10) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55802 ;

-- --------------------------------------------------------

--
-- 表的结构 `data_top_source`
--

CREATE TABLE IF NOT EXISTS `data_top_source` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `origin` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `html_source` text NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1139 ;

-- --------------------------------------------------------

--
-- 表的结构 `error_data_log`
--

CREATE TABLE IF NOT EXISTS `error_data_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `error_info` varchar(255) NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '错误状态0未查看,1以查看',
  `add_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
