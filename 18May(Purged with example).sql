-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 18, 2011 at 08:03 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cdfs_prototype`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_form_team`
--

CREATE TABLE IF NOT EXISTS `access_form_team` (
  `team_access_id` int(4) NOT NULL AUTO_INCREMENT,
  `form_id` int(3) NOT NULL,
  `team_id` int(3) NOT NULL,
  `access_right` int(1) NOT NULL,
  PRIMARY KEY (`team_access_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `access_form_team`
--

INSERT INTO `access_form_team` (`team_access_id`, `form_id`, `team_id`, `access_right`) VALUES
(1, 5, 2, 1),
(2, 5, 1, 2),
(3, 6, 1, 1),
(4, 6, 2, 1),
(5, 7, 2, 2),
(6, 7, 1, 2),
(7, 1, 2, 2),
(8, 1, 1, 2),
(9, 2, 2, 2),
(10, 2, 1, 2),
(11, 3, 2, 2),
(12, 3, 1, 2),
(13, 4, 2, 2),
(14, 4, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `access_form_user`
--

CREATE TABLE IF NOT EXISTS `access_form_user` (
  `user_access_id` int(4) NOT NULL AUTO_INCREMENT,
  `form_id` int(4) DEFAULT NULL,
  `user_id` int(3) DEFAULT NULL,
  `access_right` int(1) DEFAULT NULL,
  PRIMARY KEY (`user_access_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `access_form_user`
--

INSERT INTO `access_form_user` (`user_access_id`, `form_id`, `user_id`, `access_right`) VALUES
(1, 7, 34, 2),
(2, 7, 35, 2),
(3, 7, 33, 1),
(4, 7, 32, 1);

-- --------------------------------------------------------

--
-- Table structure for table `access_role`
--

CREATE TABLE IF NOT EXISTS `access_role` (
  `role_id` int(1) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `access_role`
--


-- --------------------------------------------------------

--
-- Table structure for table `access_team`
--

CREATE TABLE IF NOT EXISTS `access_team` (
  `team_id` int(3) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(50) DEFAULT NULL,
  `team_description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `access_team`
--

INSERT INTO `access_team` (`team_id`, `team_name`, `team_description`) VALUES
(1, 'IT Department', 'Information Technology Department'),
(2, 'Finance', 'Finance section');

-- --------------------------------------------------------

--
-- Table structure for table `access_user`
--

CREATE TABLE IF NOT EXISTS `access_user` (
  `user_id` int(3) NOT NULL AUTO_INCREMENT,
  `user_username` varchar(100) DEFAULT NULL,
  `user_password` varchar(32) DEFAULT NULL,
  `team_id` int(2) DEFAULT NULL,
  `role_id` int(2) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_surname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `access_user`
--

INSERT INTO `access_user` (`user_id`, `user_username`, `user_password`, `team_id`, `role_id`, `user_name`, `user_surname`) VALUES
(20, 'admin', '21232f297a57a5a743894a0e4a801fc3', 0, 1, 'Admin', 'admin'),
(28, 'Director', '7c5ba892645af8d7dba520e3978c726f', 0, 2, 'Cedrict', 'Direct'),
(29, 'admin2', 'c84258e9c39059a89ab77d846ddab909', 0, 1, 'adminer', 'admina'),
(30, 'sluckoo', '58e0a7d200b5b55c18d48e6b38c3bed5', 1, 3, 'Luckoo', 'Shaheel'),
(31, 'Dhanjeet', '7a6a6ed5de0e19f31996c8f66277a8fb', 1, 4, 'Dhanjeet', 'Dhanjeet'),
(32, 'Finch', 'c2a04c497fedb94e64d01fce2ae146c6', 2, 3, 'Finch', 'Nance'),
(33, 'Greed', '7d7a11c1364abd11509f29bcd158ec8b', 2, 4, 'Creed', 'Greed'),
(34, 'Ceras', 'a530ce0fdd69ad0affa60f0491a9d261', 2, 4, 'Cesar', 'Ceras'),
(35, 'Mark', 'b82a9a13f4651e9abcbde90cd24ce2cb', 2, 4, 'Markus', 'March');

-- --------------------------------------------------------

--
-- Table structure for table `log_access`
--

CREATE TABLE IF NOT EXISTS `log_access` (
  `log_access_id` int(5) NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(3) DEFAULT NULL,
  `user_id` varchar(3) DEFAULT NULL,
  `action` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`log_access_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `log_access`
--


-- --------------------------------------------------------

--
-- Table structure for table `log_form`
--

CREATE TABLE IF NOT EXISTS `log_form` (
  `log_form_id` int(5) NOT NULL AUTO_INCREMENT,
  `user_id` int(3) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `form_id` int(4) DEFAULT NULL,
  `action` varchar(30) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`log_form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `log_form`
--

INSERT INTO `log_form` (`log_form_id`, `user_id`, `username`, `form_id`, `action`, `value`, `time`) VALUES
(1, 20, 'admin', 1, 'created form ', '5th May Notice', '2018-05-11 19:49:07'),
(2, 20, 'admin', 2, 'created form ', '20th May', '2018-05-11 19:49:32'),
(3, 20, 'admin', 3, 'created form ', 'Car Reseller', '2018-05-11 19:51:27'),
(4, 20, 'admin', 4, 'created form ', '', '2018-05-11 19:52:01'),
(5, 20, 'admin', 5, 'created form ', 'Toyota-Tommy', '2018-05-11 19:55:10'),
(6, 20, 'admin', 6, 'created form ', 'MegaBright', '2018-05-11 19:56:24'),
(7, 20, 'admin', 7, 'created form ', 'Mitsubishi', '2018-05-11 19:57:22');

-- --------------------------------------------------------

--
-- Table structure for table `log_template`
--

CREATE TABLE IF NOT EXISTS `log_template` (
  `log_template_id` int(5) NOT NULL AUTO_INCREMENT,
  `user_id` int(3) DEFAULT NULL,
  `template_id` int(3) DEFAULT NULL,
  `action` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`log_template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `log_template`
--

INSERT INTO `log_template` (`log_template_id`, `user_id`, `template_id`, `action`, `value`, `time`) VALUES
(1, 20, 1, 'created', '', '2018-05-11 19:35:49'),
(2, 20, 6, 'created', '', '2018-05-11 19:46:09'),
(3, 20, 7, 'created', '', '2018-05-11 19:54:21');

-- --------------------------------------------------------

--
-- Table structure for table `template1`
--

CREATE TABLE IF NOT EXISTS `template1` (
  `form_id` int(4) DEFAULT NULL,
  `form_name` varchar(100) DEFAULT NULL,
  `header_0` varchar(70) DEFAULT NULL,
  `separator_1` varchar(70) DEFAULT NULL,
  `input_2` varchar(70) DEFAULT NULL,
  `input_3` varchar(70) DEFAULT NULL,
  `datepicker_4` varchar(70) DEFAULT NULL,
  `separator_5` varchar(70) DEFAULT NULL,
  `header_6` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `template1`
--

INSERT INTO `template1` (`form_id`, `form_name`, `header_0`, `separator_1`, `input_2`, `input_3`, `datepicker_4`, `separator_5`, `header_6`) VALUES
(3, 'Car Reseller', NULL, NULL, 'Car Reseller', 'Duty-free facilities', '05/13/2011', NULL, NULL),
(4, '', NULL, NULL, 'Computer Company', 'Low Prices on spare parts', '05/06/2011', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `template6`
--

CREATE TABLE IF NOT EXISTS `template6` (
  `form_id` int(4) DEFAULT NULL,
  `form_name` varchar(100) DEFAULT NULL,
  `header_0` varchar(70) DEFAULT NULL,
  `separator_1` varchar(70) DEFAULT NULL,
  `datepicker_2` varchar(70) DEFAULT NULL,
  `header_3` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `template6`
--

INSERT INTO `template6` (`form_id`, `form_name`, `header_0`, `separator_1`, `datepicker_2`, `header_3`) VALUES
(1, '5th May Notice', NULL, NULL, '05/04/2011', NULL),
(2, '20th May', NULL, NULL, '05/20/2011', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `template7`
--

CREATE TABLE IF NOT EXISTS `template7` (
  `form_id` int(4) DEFAULT NULL,
  `form_name` varchar(100) DEFAULT NULL,
  `header_0` varchar(70) DEFAULT NULL,
  `separator_1` varchar(70) DEFAULT NULL,
  `input_2` varchar(70) DEFAULT NULL,
  `input_3` varchar(70) DEFAULT NULL,
  `datepicker_4` varchar(70) DEFAULT NULL,
  `attach_5` varchar(70) DEFAULT NULL,
  `attach_6` varchar(70) DEFAULT NULL,
  `separator_7` varchar(70) DEFAULT NULL,
  `header_8` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `template7`
--

INSERT INTO `template7` (`form_id`, `form_name`, `header_0`, `separator_1`, `input_2`, `input_3`, `datepicker_4`, `attach_5`, `attach_6`, `separator_7`, `header_8`) VALUES
(5, 'Toyota-Tommy', NULL, NULL, 'Toyota SAS', 'Tommy Sach', '05/21/2011', '3', '1', NULL, NULL),
(6, 'MegaBright', NULL, NULL, 'MegaBright', 'Brighton', '05/06/2011', '4', '1', NULL, NULL),
(7, 'Mitsubishi', NULL, NULL, 'Mitsubishi SS', 'Matsui', '05/05/2011', '3', '2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `template_components`
--

CREATE TABLE IF NOT EXISTS `template_components` (
  `t_component_id` int(4) NOT NULL AUTO_INCREMENT,
  `template_id` varchar(4) DEFAULT NULL,
  `t_component_field` varchar(20) DEFAULT NULL,
  `t_component_label` varchar(100) DEFAULT NULL,
  `t_component_fontsize` varchar(2) DEFAULT NULL,
  `t_component_attachid` int(5) DEFAULT NULL,
  `t_component_position` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`t_component_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `template_components`
--

INSERT INTO `template_components` (`t_component_id`, `template_id`, `t_component_field`, `t_component_label`, `t_component_fontsize`, `t_component_attachid`, `t_component_position`) VALUES
(1, '1', 'header_0', 'Business Type', '30', 0, '0'),
(2, '1', 'separator_1', '', '', 0, '1'),
(3, '1', 'input_2', 'Type ', '', 0, '2'),
(4, '1', 'input_3', 'Notes', '', 0, '3'),
(5, '1', 'datepicker_4', 'Date Acquired', '', 0, '4'),
(6, '1', 'separator_5', '', '', 0, '5'),
(7, '1', 'header_6', 'Subject to changes.', '10', 0, '6'),
(16, '6', 'header_0', 'Legal Notice', '30', 0, '0'),
(17, '6', 'separator_1', '', '', 0, '1'),
(18, '6', 'datepicker_2', 'Date', '', 0, '2'),
(19, '6', 'header_3', 'header textstandard dummy text ever since the 1500s, when an unknown printer took a galley of type a', '12', 0, '3'),
(20, '7', 'header_0', 'Business Partner', '30', 0, '0'),
(21, '7', 'separator_1', '', '', 0, '1'),
(22, '7', 'input_2', 'Company Name', '', 0, '2'),
(23, '7', 'input_3', 'Owner Name', '', 0, '3'),
(24, '7', 'datepicker_4', 'Date Acquired', '', 0, '4'),
(25, '7', 'attach_5', 'Business Type', '', 1, '5'),
(26, '7', 'attach_6', 'Legal Notice', '', 6, '6'),
(27, '7', 'separator_7', '', '', 0, '7'),
(28, '7', 'header_8', 'Partners template text', '10', 0, '8');

-- --------------------------------------------------------

--
-- Table structure for table `template_form_properties`
--

CREATE TABLE IF NOT EXISTS `template_form_properties` (
  `form_id` int(5) NOT NULL AUTO_INCREMENT,
  `form_name` varchar(100) NOT NULL,
  `form_creator` varchar(100) NOT NULL,
  `form_editor` varchar(100) DEFAULT NULL,
  `form_date_created` datetime DEFAULT NULL,
  `form_date_edited` datetime DEFAULT NULL,
  `template_id` int(4) DEFAULT NULL,
  PRIMARY KEY (`form_id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `template_form_properties`
--

INSERT INTO `template_form_properties` (`form_id`, `form_name`, `form_creator`, `form_editor`, `form_date_created`, `form_date_edited`, `template_id`) VALUES
(1, '5th May Notice', 'admin', NULL, '2018-05-11 19:49:07', NULL, 6),
(2, '20th May', 'admin', NULL, '2018-05-11 19:49:32', NULL, 6),
(3, 'Car Reseller', 'admin', NULL, '2018-05-11 19:51:27', NULL, 1),
(4, 'Computer Reseller', 'admin', NULL, '2018-05-11 19:52:01', NULL, 1),
(5, 'Toyota-Tommy', 'admin', NULL, '2018-05-11 19:55:10', NULL, 7),
(6, 'MegaBright', 'admin', NULL, '2018-05-11 19:56:24', NULL, 7),
(7, 'Mitsubishi', 'admin', NULL, '2018-05-11 19:57:22', NULL, 7);

-- --------------------------------------------------------

--
-- Table structure for table `template_properties`
--

CREATE TABLE IF NOT EXISTS `template_properties` (
  `template_id` int(4) NOT NULL AUTO_INCREMENT,
  `template_creator` varchar(100) NOT NULL,
  `template_last_editor` varchar(100) DEFAULT NULL,
  `template_date_created` datetime DEFAULT NULL,
  `template_date_edited` datetime DEFAULT NULL,
  `template_description` varchar(200) DEFAULT NULL,
  `template_name` varchar(100) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `template_properties`
--

INSERT INTO `template_properties` (`template_id`, `template_creator`, `template_last_editor`, `template_date_created`, `template_date_edited`, `template_description`, `template_name`) VALUES
(1, 'admin', NULL, '2018-05-11 19:35:49', NULL, 'Detailed Description of the Business Type', 'Business Type'),
(6, 'admin', NULL, '2018-05-11 19:46:09', NULL, NULL, 'Legal Notice'),
(7, 'admin', NULL, '2018-05-11 19:54:21', NULL, 'Business Partner details', 'Business Partner');
