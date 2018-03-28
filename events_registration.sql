-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 14, 2018 at 08:54 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31-6+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `events_registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `card_payments`
--

CREATE TABLE `card_payments` (
  `id` int(11) NOT NULL,
  `transaction_data` text COLLATE utf8_unicode_ci,
  `status` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PENDING',
  `comment` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_captcha`
--

CREATE TABLE `core_captcha` (
  `id` int(11) UNSIGNED NOT NULL,
  `captcha_time` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) CHARACTER SET latin1 NOT NULL,
  `word` varchar(20) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `core_captcha`
--

INSERT INTO `core_captcha` (`id`, `captcha_time`, `ip_address`, `word`) VALUES
(1, 1517013543, '::1', 'XYwM4XMa'),
(2, 1517132883, '::1', '5CNEPYvu'),
(3, 1517909867, '::1', 'kRR96CnK'),
(4, 1517918369, '::1', 'CS9G5C8m');

-- --------------------------------------------------------

--
-- Table structure for table `core_command_queue`
--

CREATE TABLE `core_command_queue` (
  `id` int(11) UNSIGNED NOT NULL,
  `command` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `command_ref` int(11) UNSIGNED NOT NULL,
  `parameters_json` text COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'PENDING, RUNNING, ERROR, COMPLETE',
  `comment` text COLLATE utf8_unicode_ci,
  `actor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_countries`
--

CREATE TABLE `core_countries` (
  `id` int(11) NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `code` varchar(10) NOT NULL,
  `dial_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `core_countries`
--

INSERT INTO `core_countries` (`id`, `name`, `code`, `dial_code`) VALUES
(1, 'Afghanistan', 'AF / AFG', '93'),
(2, 'Albania', 'AL / ALB', '355'),
(3, 'Algeria', 'DZ / DZA', '213'),
(4, 'American Samoa', 'AS / ASM', '1-684'),
(5, 'Andorra', 'AD / AND', '376'),
(6, 'Angola', 'AO / AGO', '244'),
(7, 'Anguilla', 'AI / AIA', '1-264'),
(8, 'Antarctica', 'AQ / ATA', '672'),
(9, 'Antigua and Barbuda', 'AG / ATG', '1-268'),
(10, 'Argentina', 'AR / ARG', '54'),
(11, 'Armenia', 'AM / ARM', '374'),
(12, 'Aruba', 'AW / ABW', '297'),
(13, 'Australia', 'AU / AUS', '61'),
(14, 'Austria', 'AT / AUT', '43'),
(15, 'Azerbaijan', 'AZ / AZE', '994'),
(16, 'Bahamas', 'BS / BHS', '1-242'),
(17, 'Bahrain', 'BH / BHR', '973'),
(18, 'Bangladesh', 'BD / BGD', '880'),
(19, 'Barbados', 'BB / BRB', '1-246'),
(20, 'Belarus', 'BY / BLR', '375'),
(21, 'Belgium', 'BE / BEL', '32'),
(22, 'Belize', 'BZ / BLZ', '501'),
(23, 'Benin', 'BJ / BEN', '229'),
(24, 'Bermuda', 'BM / BMU', '1-441'),
(25, 'Bhutan', 'BT / BTN', '975'),
(26, 'Bolivia', 'BO / BOL', '591'),
(27, 'Bosnia and Herzegovina', 'BA / BIH', '387'),
(28, 'Botswana', 'BW / BWA', '267'),
(29, 'Brazil', 'BR / BRA', '55'),
(30, 'British Indian Ocean Territory', 'IO / IOT', '246'),
(31, 'British Virgin Islands', 'VG / VGB', '1-284'),
(32, 'Brunei', 'BN / BRN', '673'),
(33, 'Bulgaria', 'BG / BGR', '359'),
(34, 'Burkina Faso', 'BF / BFA', '226'),
(35, 'Burundi', 'BI / BDI', '257'),
(36, 'Cambodia', 'KH / KHM', '855'),
(37, 'Cameroon', 'CM / CMR', '237'),
(38, 'Canada', 'CA / CAN', '1'),
(39, 'Cape Verde', 'CV / CPV', '238'),
(40, 'Cayman Islands', 'KY / CYM', '1-345'),
(41, 'Central African Republic', 'CF / CAF', '236'),
(42, 'Chad', 'TD / TCD', '235'),
(43, 'Chile', 'CL / CHL', '56'),
(44, 'China', 'CN / CHN', '86'),
(45, 'Christmas Island', 'CX / CXR', '61'),
(46, 'Cocos Islands', 'CC / CCK', '61'),
(47, 'Colombia', 'CO / COL', '57'),
(48, 'Comoros', 'KM / COM', '269'),
(49, 'Cook Islands', 'CK / COK', '682'),
(50, 'Costa Rica', 'CR / CRI', '506'),
(51, 'Croatia', 'HR / HRV', '385'),
(52, 'Cuba', 'CU / CUB', '53'),
(53, 'Curacao', 'CW / CUW', '599'),
(54, 'Cyprus', 'CY / CYP', '357'),
(55, 'Czech Republic', 'CZ / CZE', '420'),
(56, 'Democratic Republic of the Congo', 'CD / COD', '243'),
(57, 'Denmark', 'DK / DNK', '45'),
(58, 'Djibouti', 'DJ / DJI', '253'),
(59, 'Dominica', 'DM / DMA', '1-767'),
(60, 'Dominican Republic', 'DO / DOM', '1-809'),
(61, 'East Timor', 'TL / TLS', '670'),
(62, 'Ecuador', 'EC / ECU', '593'),
(63, 'Egypt', 'EG / EGY', '20'),
(64, 'El Salvador', 'SV / SLV', '503'),
(65, 'Equatorial Guinea', 'GQ / GNQ', '240'),
(66, 'Eritrea', 'ER / ERI', '291'),
(67, 'Estonia', 'EE / EST', '372'),
(68, 'Ethiopia', 'ET / ETH', '251'),
(69, 'Falkland Islands', 'FK / FLK', '500'),
(70, 'Faroe Islands', 'FO / FRO', '298'),
(71, 'Fiji', 'FJ / FJI', '679'),
(72, 'Finland', 'FI / FIN', '358'),
(73, 'France', 'FR / FRA', '33'),
(74, 'French Polynesia', 'PF / PYF', '689'),
(75, 'Gabon', 'GA / GAB', '241'),
(76, 'Gambia', 'GM / GMB', '220'),
(77, 'Georgia', 'GE / GEO', '995'),
(78, 'Germany', 'DE / DEU', '49'),
(79, 'Ghana', 'GH / GHA', '233'),
(80, 'Gibraltar', 'GI / GIB', '350'),
(81, 'Greece', 'GR / GRC', '30'),
(82, 'Greenland', 'GL / GRL', '299'),
(83, 'Grenada', 'GD / GRD', '1-473'),
(84, 'Guam', 'GU / GUM', '1-671'),
(85, 'Guatemala', 'GT / GTM', '502'),
(86, 'Guernsey', 'GG / GGY', '44-1481'),
(87, 'Guinea', 'GN / GIN', '224'),
(88, 'Guinea-Bissau', 'GW / GNB', '245'),
(89, 'Guyana', 'GY / GUY', '592'),
(90, 'Haiti', 'HT / HTI', '509'),
(91, 'Honduras', 'HN / HND', '504'),
(92, 'Hong Kong', 'HK / HKG', '852'),
(93, 'Hungary', 'HU / HUN', '36'),
(94, 'Iceland', 'IS / ISL', '354'),
(95, 'India', 'IN / IND', '91'),
(96, 'Indonesia', 'ID / IDN', '62'),
(97, 'Iran', 'IR / IRN', '98'),
(98, 'Iraq', 'IQ / IRQ', '964'),
(99, 'Ireland', 'IE / IRL', '353'),
(100, 'Isle of Man', 'IM / IMN', '44-1624'),
(101, 'Israel', 'IL / ISR', '972'),
(102, 'Italy', 'IT / ITA', '39'),
(103, 'Ivory Coast', 'CI / CIV', '225'),
(104, 'Jamaica', 'JM / JAM', '1-876'),
(105, 'Japan', 'JP / JPN', '81'),
(106, 'Jersey', 'JE / JEY', '44-1534'),
(107, 'Jordan', 'JO / JOR', '962'),
(108, 'Kazakhstan', 'KZ / KAZ', '7'),
(109, 'Kenya', 'KE / KEN', '254'),
(110, 'Kiribati', 'KI / KIR', '686'),
(111, 'Kosovo', 'XK / XKX', '383'),
(112, 'Kuwait', 'KW / KWT', '965'),
(113, 'Kyrgyzstan', 'KG / KGZ', '996'),
(114, 'Laos', 'LA / LAO', '856'),
(115, 'Latvia', 'LV / LVA', '371'),
(116, 'Lebanon', 'LB / LBN', '961'),
(117, 'Lesotho', 'LS / LSO', '266'),
(118, 'Liberia', 'LR / LBR', '231'),
(119, 'Libya', 'LY / LBY', '218'),
(120, 'Liechtenstein', 'LI / LIE', '423'),
(121, 'Lithuania', 'LT / LTU', '370'),
(122, 'Luxembourg', 'LU / LUX', '352'),
(123, 'Macau', 'MO / MAC', '853'),
(124, 'Macedonia', 'MK / MKD', '389'),
(125, 'Madagascar', 'MG / MDG', '261'),
(126, 'Malawi', 'MW / MWI', '265'),
(127, 'Malaysia', 'MY / MYS', '60'),
(128, 'Maldives', 'MV / MDV', '960'),
(129, 'Mali', 'ML / MLI', '223'),
(130, 'Malta', 'MT / MLT', '356'),
(131, 'Marshall Islands', 'MH / MHL', '692'),
(132, 'Mauritania', 'MR / MRT', '222'),
(133, 'Mauritius', 'MU / MUS', '230'),
(134, 'Mayotte', 'YT / MYT', '262'),
(135, 'Mexico', 'MX / MEX', '52'),
(136, 'Micronesia', 'FM / FSM', '691'),
(137, 'Moldova', 'MD / MDA', '373'),
(138, 'Monaco', 'MC / MCO', '377'),
(139, 'Mongolia', 'MN / MNG', '976'),
(140, 'Montenegro', 'ME / MNE', '382'),
(141, 'Montserrat', 'MS / MSR', '1-664'),
(142, 'Morocco', 'MA / MAR', '212'),
(143, 'Mozambique', 'MZ / MOZ', '258'),
(144, 'Myanmar', 'MM / MMR', '95'),
(145, 'Namibia', 'NA / NAM', '264'),
(146, 'Nauru', 'NR / NRU', '674'),
(147, 'Nepal', 'NP / NPL', '977'),
(148, 'Netherlands', 'NL / NLD', '31'),
(149, 'Netherlands Antilles', 'AN / ANT', '599'),
(150, 'New Caledonia', 'NC / NCL', '687'),
(151, 'New Zealand', 'NZ / NZL', '64'),
(152, 'Nicaragua', 'NI / NIC', '505'),
(153, 'Niger', 'NE / NER', '227'),
(154, 'Nigeria', 'NG / NGA', '234'),
(155, 'Niue', 'NU / NIU', '683'),
(156, 'North Korea', 'KP / PRK', '850'),
(157, 'Northern Mariana Islands', 'MP / MNP', '1-670'),
(158, 'Norway', 'NO / NOR', '47'),
(159, 'Oman', 'OM / OMN', '968'),
(160, 'Pakistan', 'PK / PAK', '92'),
(161, 'Palau', 'PW / PLW', '680'),
(162, 'Palestine', 'PS / PSE', '970'),
(163, 'Panama', 'PA / PAN', '507'),
(164, 'Papua New Guinea', 'PG / PNG', '675'),
(165, 'Paraguay', 'PY / PRY', '595'),
(166, 'Peru', 'PE / PER', '51'),
(167, 'Philippines', 'PH / PHL', '63'),
(168, 'Pitcairn', 'PN / PCN', '64'),
(169, 'Poland', 'PL / POL', '48'),
(170, 'Portugal', 'PT / PRT', '351'),
(171, 'Puerto Rico', 'PR / PRI', '1-787'),
(172, 'Qatar', 'QA / QAT', '974'),
(173, 'Republic of the Congo', 'CG / COG', '242'),
(174, 'Reunion', 'RE / REU', '262'),
(175, 'Romania', 'RO / ROU', '40'),
(176, 'Russia', 'RU / RUS', '7'),
(177, 'Rwanda', 'RW / RWA', '250'),
(178, 'Saint Barthelemy', 'BL / BLM', '590'),
(179, 'Saint Helena', 'SH / SHN', '290'),
(180, 'Saint Kitts and Nevis', 'KN / KNA', '1-869'),
(181, 'Saint Lucia', 'LC / LCA', '1-758'),
(182, 'Saint Martin', 'MF / MAF', '590'),
(183, 'Saint Pierre and Miquelon', 'PM / SPM', '508'),
(184, 'Saint Vincent and the Grenadines', 'VC / VCT', '1-784'),
(185, 'Samoa', 'WS / WSM', '685'),
(186, 'San Marino', 'SM / SMR', '378'),
(187, 'Sao Tome and Principe', 'ST / STP', '239'),
(188, 'Saudi Arabia', 'SA / SAU', '966'),
(189, 'Senegal', 'SN / SEN', '221'),
(190, 'Serbia', 'RS / SRB', '381'),
(191, 'Seychelles', 'SC / SYC', '248'),
(192, 'Sierra Leone', 'SL / SLE', '232'),
(193, 'Singapore', 'SG / SGP', '65'),
(194, 'Sint Maarten', 'SX / SXM', '1-721'),
(195, 'Slovakia', 'SK / SVK', '421'),
(196, 'Slovenia', 'SI / SVN', '386'),
(197, 'Solomon Islands', 'SB / SLB', '677'),
(198, 'Somalia', 'SO / SOM', '252'),
(199, 'South Africa', 'ZA / ZAF', '27'),
(200, 'South Korea', 'KR / KOR', '82'),
(201, 'South Sudan', 'SS / SSD', '211'),
(202, 'Spain', 'ES / ESP', '34'),
(203, 'Sri Lanka', 'LK / LKA', '94'),
(204, 'Sudan', 'SD / SDN', '249'),
(205, 'Suriname', 'SR / SUR', '597'),
(206, 'Svalbard and Jan Mayen', 'SJ / SJM', '47'),
(207, 'Swaziland', 'SZ / SWZ', '268'),
(208, 'Sweden', 'SE / SWE', '46'),
(209, 'Switzerland', 'CH / CHE', '41'),
(210, 'Syria', 'SY / SYR', '963'),
(211, 'Taiwan', 'TW / TWN', '886'),
(212, 'Tajikistan', 'TJ / TJK', '992'),
(213, 'Tanzania', 'TZ / TZA', '255'),
(214, 'Thailand', 'TH / THA', '66'),
(215, 'Togo', 'TG / TGO', '228'),
(216, 'Tokelau', 'TK / TKL', '690'),
(217, 'Tonga', 'TO / TON', '676'),
(218, 'Trinidad and Tobago', 'TT / TTO', '1-868'),
(219, 'Tunisia', 'TN / TUN', '216'),
(220, 'Turkey', 'TR / TUR', '90'),
(221, 'Turkmenistan', 'TM / TKM', '993'),
(222, 'Turks and Caicos Islands', 'TC / TCA', '1-649'),
(223, 'Tuvalu', 'TV / TUV', '688'),
(224, 'U.S. Virgin Islands', 'VI / VIR', '1-340'),
(225, 'Uganda', 'UG / UGA', '256'),
(226, 'Ukraine', 'UA / UKR', '380'),
(227, 'United Arab Emirates', 'AE / ARE', '971'),
(228, 'United Kingdom', 'GB / GBR', '44'),
(229, 'United States', 'US / USA', '1'),
(230, 'Uruguay', 'UY / URY', '598'),
(231, 'Uzbekistan', 'UZ / UZB', '998'),
(232, 'Vanuatu', 'VU / VUT', '678'),
(233, 'Vatican', 'VA / VAT', '379'),
(234, 'Venezuela', 'VE / VEN', '58'),
(235, 'Vietnam', 'VN / VNM', '84'),
(236, 'Wallis and Futuna', 'WF / WLF', '681'),
(237, 'Western Sahara', 'EH / ESH', '212'),
(238, 'Yemen', 'YE / YEM', '967'),
(239, 'Zambia', 'ZM / ZMB', '260'),
(240, 'Zimbabwe', 'ZW / ZWE', '263');

-- --------------------------------------------------------

--
-- Table structure for table `core_groups`
--

CREATE TABLE `core_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `allow_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `core_groups`
--

INSERT INTO `core_groups` (`id`, `name`, `description`, `allow_delete`, `remote_id`, `sync`) VALUES
(1, 'Administrators', 'Administrators Group', 0, 1, 1),
(2, 'Exams Group', 'Creation of new clients and modification of existing clients', 0, 2, 1),
(3, 'Members\' Group', 'Management of members\' related data', 1, 3, 1),
(4, 'Viewing Group', 'Simply view existing data', 1, 4, 1),
(5, 'Exams Admin', NULL, 1, 5, 1),
(6, 'API Administration', 'Viewing incoming API Transactions', 1, 6, 1),
(7, 'IT Administrators', 'IT Administration', 1, 7, 1),
(8, 'Technical', 'Technical group', 1, 8, 1),
(9, 'exams results', NULL, 1, 9, 1),
(10, 'Human resource', 'Human resource', 1, 10, 1),
(11, 'PET Administrator', 'PET Administrator', 1, 11, 1),
(12, 'Leave Administration', 'leave balances, leave statements, leave operations, Leave holidays', 1, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `core_hotels`
--

CREATE TABLE `core_hotels` (
  `id` int(11) NOT NULL,
  `hotel` varchar(50) DEFAULT NULL,
  `distance_to_airport` float(5,2) DEFAULT NULL,
  `distance_to_venue` float(5,2) DEFAULT NULL,
  `price` text,
  `remarks` text,
  `link_to_book` text,
  `link_to_google_maps` text,
  `contacts` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_hotels`
--

INSERT INTO `core_hotels` (`id`, `hotel`, `distance_to_airport`, `distance_to_venue`, `price`, `remarks`, `link_to_book`, `link_to_google_maps`, `contacts`) VALUES
(1, 'Speke Resort Munyonyo', 44.60, 0.00, '$100 single<br>$125 double room', 'Bed and Breakfast', 'http://spekeresort.com/reservation/acoa-2017', NULL, 'Tel: +256-414227111 / +256-752711714'),
(2, 'Rwizi Arch Hotel (2 single & 14 double rooms)', 51.60, 7.00, '$40 single<br>$50 double room(single occupancy)<br>$50 double room', 'Bed and Breakfast', NULL, NULL, 'Email: info@rwizihotels.com;<br> hotel.rwiziarch@gmail.com<br>\nTel: +256-782915164 / +256-772684839<br>\nwww.rwizihotels.com/kasanga'),
(3, 'Victoria Travel Hotel (8 single & 15 double rooms)', 47.70, 3.10, '$25 single<br>$35 double room(single occupancy)<br>$40 double room', 'Bed and Breakfast', NULL, NULL, 'Email: victoriatravelhotel@yahoo.co.uk<br>\nTel: +256-41501084/+256-701573606<br>\nwww.victoriatravelhotels.com\n'),
(4, 'Marie’s Royale Hotel', 49.80, 5.20, '$45 single<br>$55 double room(single occupancy)<br>$60 double room', 'Bed and Breakfast', NULL, NULL, 'Email: mariesroyale@gmail.com;<br>\nTel: +256-392176181<br>\nwww.mariesroyalehotel.com\n'),
(5, 'Sir Jose Hotel', 47.80, 3.20, '$35 single<br>$55 double room(single occupancy)<br>$55 double room', 'Bed and Breakfast', NULL, NULL, 'Email: sirjosehotel@hotmail.com<br>\nTel: +256-414667008<br>\nwww.sirjosehotel.com\n'),
(6, 'National Seminary Ggaba', 48.10, 3.50, '$40 single', 'Bed and Breakfast', NULL, NULL, 'Email: masolopaul@gmail.com;<br>\nTel: +256-772481335<br>\n\n');

-- --------------------------------------------------------

--
-- Table structure for table `core_invoices`
--

CREATE TABLE `core_invoices` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `amount` float NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_made` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `payment_date` date DEFAULT NULL,
  `payment_proof` text COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `invoice_email_sent` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `resend_invoice_email` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `payment_email_sent` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_lookups`
--

CREATE TABLE `core_lookups` (
  `id` int(11) UNSIGNED NOT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ordering` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `grouping` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `parent_value` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_grouping` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `core_lookups`
--

INSERT INTO `core_lookups` (`id`, `label`, `value`, `ordering`, `grouping`, `parent_value`, `parent_grouping`, `remote_id`, `sync`) VALUES
(1, 'Female', 'FEMALE', 1, 'GENDER', NULL, NULL, NULL, NULL),
(2, 'Male', 'MALE', 2, 'GENDER', NULL, NULL, NULL, NULL),
(3, 'Yes', '1', 0, 'BOOL_YES_NO', NULL, NULL, NULL, NULL),
(4, 'No', '0', 0, 'BOOL_YES_NO', NULL, NULL, NULL, NULL),
(5, 'Ordre Des Experts Comptables et Comptables Agréés du Bénin (OECCA)', 'Ordre Des Experts Comptables et Comptables Agréés du Bénin (OECCA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(6, 'Botswana Institute of Chartered Accountants (BICA)', 'Botswana Institute of Chartered Accountants (BICA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(7, 'Ordre National des Experts Comptables du Burkina Faso (ONECCA)', 'Ordre National des Experts Comptables du Burkina Faso (ONECCA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(8, 'Ordre des Professionnels Comptables du Burundi (OPC)', 'Ordre des Professionnels Comptables du Burundi (OPC)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(9, 'Ordre National des Experts Comptables du Cameroun (ONECCA)', 'Ordre National des Experts Comptables du Cameroun (ONECCA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(10, 'Association des Professionnels de la Comptabilité du Congo (APC)', 'Association des Professionnels de la Comptabilité du Congo (APC)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(11, 'Institut Des Réviseurs Comptables du Congo (IRC)', 'Institut Des Réviseurs Comptables du Congo (IRC)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(12, 'Ordre Des Experts-Comptables et des Comptables Agréés Cote D\'Ivoire (OECCA)', 'Ordre Des Experts-Comptables et des Comptables Agréés Cote D\'Ivoire (OECCA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(13, 'Ethiopian Professional Association of Accountants and Auditors (EPAAA)', 'Ethiopian Professional Association of Accountants and Auditors (EPAAA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(14, 'Gambia Association of Accountants (GAA)', 'Gambia Association of Accountants (GAA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(15, 'Institute of Chartered Accountants of Ghana  (ICAG)', 'Institute of Chartered Accountants of Ghana  (ICAG)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(16, 'Ordem Nacional dos Técnicos Oficiais de Contas da Guiné-Bissau (ORNATOC)', 'Ordem Nacional dos Técnicos Oficiais de Contas da Guiné-Bissau (ORNATOC)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(17, 'Institute of Certified Public Accountants of Kenya (ICPAK)', 'Institute of Certified Public Accountants of Kenya (ICPAK)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(18, 'Lesotho Institute of Accountants (LIA)', 'Lesotho Institute of Accountants (LIA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(19, 'Liberian Institute of Certified Public Accountants (LICPA)', 'Liberian Institute of Certified Public Accountants (LICPA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(20, 'Libyan Institute of Accountants (LIA)', 'Libyan Institute of Accountants (LIA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(21, 'Institute of Chartered Accountants of Malawi', 'Institute of Chartered Accountants of Malawi', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(22, 'Ordre National Des Experts-Comptables et Comptables Agréés du Mali (ONECCA)', 'Ordre National Des Experts-Comptables et Comptables Agréés du Mali (ONECCA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(23, 'Ordre des Experts Comptables et Financiers de Madagascar (OECFM)', 'Ordre des Experts Comptables et Financiers de Madagascar (OECFM)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(24, 'Mauritius Institute of Professional Accountants (MIPA)', 'Mauritius Institute of Professional Accountants (MIPA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(25, 'Ordre des Experts Comptables du Royaume du Maroc (OEC)', 'Ordre des Experts Comptables du Royaume du Maroc (OEC)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(26, 'Institute of Commercial and Financial Accountants Namibia (CFA)', 'Institute of Commercial and Financial Accountants Namibia (CFA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(27, 'Institute of Chartered Accountants (ICAN)', 'Institute of Chartered Accountants (ICAN)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(28, 'Ordre National des Experts Comptables et des Comptables Agréés du Niger (ONECCA)', 'Ordre National des Experts Comptables et des Comptables Agréés du Niger (ONECCA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(29, 'Association of National Accountants of Nigeria (ANAN)', 'Association of National Accountants of Nigeria (ANAN)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(30, 'Institute of Chartered Accountants of Nigeria (ICAN)', 'Institute of Chartered Accountants of Nigeria (ICAN)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(31, 'Institute of Certified Public Accountants of Rwanda (ICPAR)', 'Institute of Certified Public Accountants of Rwanda (ICPAR)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(32, 'Ordre National des Experts Comptables et Comptables Agréés du Sénégal (ONECCA)', 'Ordre National des Experts Comptables et Comptables Agréés du Sénégal (ONECCA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(33, 'Institute of Chartered Accountants Sierra Leone (ICA)', 'Institute of Chartered Accountants Sierra Leone (ICA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(34, 'The Accountancy and Audit Professional ORG. Council Sudan (AAPOC)', 'The Accountancy and Audit Professional ORG. Council Sudan (AAPOC)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(35, 'South African Institute of Chartered Accountants (SAICA)', 'South African Institute of Chartered Accountants (SAICA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(36, 'South African Institute of Professional Accountants (SAIPA)', 'South African Institute of Professional Accountants (SAIPA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(37, 'Swaziland Institute of Accountants (SIA)', 'Swaziland Institute of Accountants (SIA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(38, 'National Board of Accountants and Auditors Tanzania (NBAA)', 'National Board of Accountants and Auditors Tanzania (NBAA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(39, 'Ordre National des Experts Comptables et des Comptables Agréés du Togo (OECCA)', 'Ordre National des Experts Comptables et des Comptables Agréés du Togo (OECCA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(40, 'Ordre des Experts Comptables de Tunisie (OECT)', 'Ordre des Experts Comptables de Tunisie (OECT)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(41, 'Institute of Certified Public Accountants of Uganda (ICPAU)', 'Institute of Certified Public Accountants of Uganda (ICPAU)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(42, 'Zambia Institute of Chartered Accountants (ZICA)', 'Zambia Institute of Chartered Accountants (ZICA)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(43, 'Institute of Chartered Accountants of Zimbabwe (ICAZ)', 'Institute of Chartered Accountants of Zimbabwe (ICAZ)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(44, 'Institute of Certified Public Accountants of Zimbabwe (ICPAZ)', 'Institute of Certified Public Accountants of Zimbabwe (ICPAZ)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(45, 'Insitute of Chartered Secretaries and Administrators in Zimbabwe (ICSAZ)', 'Insitute of Chartered Secretaries and Administrators in Zimbabwe (ICSAZ)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL),
(46, 'Banking', 'Banking', 1, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(47, 'Insurance', 'Insurance', 2, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(48, 'Agriculture', 'Agriculture', 3, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(49, 'Infrastructure, Construction and Real Estate', 'Infrastructure, Construction and Real Estate', 4, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(50, 'Manufacturing and Processing', 'Manufacturing and Processing', 5, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(51, 'Technology and Communication', 'Technology and Communication', 6, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(52, 'Mining and Petroleum', 'Mining and Petroleum', 7, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(53, 'Professional', 'Professional', 8, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(54, 'Trading', 'Trading', 9, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(55, 'Hospitality & Leisure', 'Hospitality & Leisure', 10, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(56, 'Government Agencies/Authorities', 'Government Agencies/Authorities', 11, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(57, 'Local Government', 'Local Government', 12, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(58, 'Central Government', 'Central Government', 13, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(59, 'NGOs/ Not-for-Profit', 'NGOs/ Not-for-Profit', 14, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(60, 'Education Institutions', 'Educational Institution', 15, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(61, 'Medical Institutions', 'Medical Institution', 16, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(62, 'SACCOs and Other Cooperatives', 'SACCOs and Other Cooperatives', 17, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(63, 'Religous institutions', 'Religous institution', 18, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(64, 'Government Corporations', 'Government Corporation', 19, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(65, 'Regulatory Authorities', 'Regulatory Authorities', 20, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(66, 'Media', 'Media', 21, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(67, 'Other', 'Other', 22, 'ORG_INDUSTRIES', NULL, NULL, NULL, NULL),
(68, 'Ordre National des Experts Comptables du Congo (ONEC)', 'Ordre National des Experts Comptables du Congo (ONEC)', 1, 'ACCOUNTANCY_BODIES', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `core_registrations`
--

CREATE TABLE `core_registrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shirt_size` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hotel` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `leisure_activity` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_code` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_code` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_names` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `national_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rate_category` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `member_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delegate_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emp_organisation` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emp_industry` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emp_country` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emp_job_title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emp_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emp_telephone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acc_body_member` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acc_body` text COLLATE utf8_unicode_ci,
  `insurance` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `insurance_body` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passport_no` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passport_issue_date` date DEFAULT NULL,
  `passport_expiry_date` date DEFAULT NULL,
  `passport_issue_place` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flight_no` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `travel_from_country` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `travel_arrival_date` datetime DEFAULT NULL,
  `travel_departure_date` datetime DEFAULT NULL,
  `tour_route` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hotel_room_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nok_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nok_country` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nok_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nok_telephone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_registration_code` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_registration_code` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `reg_email_sent` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `invitation_email_sent` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plot_no` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `box` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `core_registrations`
--

INSERT INTO `core_registrations` (`id`, `payment_status`, `shirt_size`, `hotel`, `leisure_activity`, `registration_code`, `invoice_code`, `first_name`, `last_name`, `other_names`, `nationality`, `dob`, `gender`, `national_id`, `rate_category`, `member_number`, `delegate_type`, `email`, `telephone`, `emp_organisation`, `emp_industry`, `emp_country`, `emp_job_title`, `emp_email`, `emp_telephone`, `acc_body_member`, `acc_body`, `insurance`, `insurance_body`, `passport_no`, `passport_issue_date`, `passport_expiry_date`, `passport_issue_place`, `flight_no`, `travel_from_country`, `travel_arrival_date`, `travel_departure_date`, `tour_route`, `hotel_room_type`, `nok_name`, `nok_country`, `nok_email`, `nok_telephone`, `parent_registration_code`, `update_registration_code`, `created_at`, `updated_at`, `reg_email_sent`, `invitation_email_sent`, `title`, `street`, `plot_no`, `box`, `city`, `remote_id`, `sync`) VALUES
(1, 'COM', 'XXL', NULL, NULL, 'ESAAG0000001', NULL, 'Lawrence ', 'Semakula', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'lawrence.semakula@finance.go.ug', '256752 644457', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 'COM', 'L', NULL, NULL, 'ESAAG0000002', NULL, 'Godfrey ', 'B. Ssemugooma', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'godfrey.ssemugooma@finance.go.ug', '256772 409983', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(3, 'COM', 'M', NULL, NULL, 'ESAAG0000003', NULL, 'Jeniffer', 'Muhuruzi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'jennifer.muhuruzi@finance.go.ug', '256772 495464', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(4, 'COM', 'XL', NULL, NULL, 'ESAAG0000004', NULL, 'Stephen', 'Ojiambo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'stephen.ojambo@finance.go.ug', '256772 443080', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(5, 'COM', NULL, NULL, NULL, 'ESAAG0000005', NULL, 'David Nyimba', 'Kiyingi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(6, 'COM', NULL, NULL, NULL, 'ESAAG0000006', NULL, 'Arthur ', 'Mugweri', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(7, 'COM', NULL, NULL, NULL, 'ESAAG0000007', NULL, 'Aiden David', 'Rujumba', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(8, 'COM', NULL, NULL, NULL, 'ESAAG0000008', NULL, 'Daniel', 'Kigenyi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(9, 'COM', 'L', NULL, NULL, 'ESAAG0000009', NULL, 'Mubarak', 'Nasamba', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'mubarak.nasamba@finance.go.ug', '256702 926464', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(10, 'COM', 'S', NULL, NULL, 'ESAAG0000010', NULL, 'Aziz', 'Ssettaala', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'aziz.ssettaala@finance.go.ug', '256702 940179', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(11, 'COM', NULL, NULL, NULL, 'ESAAG0000011', NULL, 'Yakub', 'Lubega', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(12, 'COM', 'M', NULL, NULL, 'ESAAG0000012', NULL, 'Pedson', 'Twesigomwe', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'pedson.twesigomwe@finance.go.ug', '256772 320921', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(13, 'COM', 'L', NULL, NULL, 'ESAAG0000013', NULL, 'Steven', 'Balisanyuka', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'sanustev@yahoo.com', '256772 507055', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(14, 'COM', NULL, NULL, NULL, 'ESAAG0000014', NULL, 'Richard', 'Tabaro', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(15, 'COM', 'XL', NULL, NULL, 'ESAAG0000015', NULL, 'Norbert Hillary', 'Okello', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'norbert.okello@finance.go.ug', '256782 378083', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(16, 'COM', NULL, NULL, NULL, 'ESAAG0000016', NULL, 'Rogers ', 'Baguma', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(17, 'COM', NULL, NULL, NULL, 'ESAAG0000017', NULL, 'Bernadette', 'Nakabuye Kizito', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(18, 'COM', NULL, NULL, NULL, 'ESAAG0000018', NULL, 'Stephen', 'Barungi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(19, 'COM', 'SS', NULL, NULL, 'ESAAG0000019', NULL, 'Sophie', 'Ngira', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'sofiann6@gmail.com', '256750 984258', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(20, 'COM', 'S', NULL, NULL, 'ESAAG0000020', NULL, 'Immaculate', 'Nabateesa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'immaculate.nabateesa@finance.go.ug', '256706 255351', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(21, 'COM', 'M', NULL, NULL, 'ESAAG0000021', NULL, 'Samson', 'Budeyo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'budeyo.samson@gmail.com', '256774 199521', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(22, 'COM', NULL, NULL, NULL, 'ESAAG0000022', NULL, 'Deo', 'Lutaaya', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(23, 'COM', NULL, NULL, NULL, 'ESAAG0000023', NULL, 'Godfrey Luggya', 'Makedi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(24, 'COM', NULL, NULL, NULL, 'ESAAG0000024', NULL, 'Chris ', 'Otim', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(25, 'COM', NULL, NULL, NULL, 'ESAAG0000025', NULL, 'Immaculate', 'Nabayinda', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(26, 'COM', NULL, NULL, NULL, 'ESAAG0000026', NULL, 'Ronald ', 'Turihoahabwe', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(27, 'COM', 'M', NULL, NULL, 'ESAAG0000027', NULL, 'Deborah Dorothy', 'Kusiima', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'ddeby.siima@gmail.com', '256779 198097', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(28, 'COM', 'XXL', NULL, NULL, 'ESAAG0000028', NULL, 'Linnet ', 'Namanya', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'lynnetnamanya@gmail.com', '256784 586861', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(29, 'COM', 'S', NULL, NULL, 'ESAAG0000029', NULL, 'Barbara', 'Nakintu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'barbara.nakintu@finance.go.ug', '256786 086688', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(30, 'COM', 'L', NULL, NULL, 'ESAAG0000030', NULL, 'Melanie Kizito', 'Nansubuga', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'melanie.nansubuga@finance.go.ug', '256776 112919', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(31, 'COM', 'S', NULL, NULL, 'ESAAG0000031', NULL, 'Elizabeth', 'Tushemerirwe', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'elizabeth.tushemerirwe@finance.go.ug', '256787 124110', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(32, 'COM', 'L', NULL, NULL, 'ESAAG0000032', NULL, 'Barbara', 'Rhada', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'barbara.rhada@finance.go.ug', '256775 655910', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(33, 'COM', NULL, NULL, NULL, 'ESAAG0000033', NULL, 'Edward ', 'Ekonga', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(34, 'COM', 'L', NULL, NULL, 'ESAAG0000034', NULL, 'Tracy', 'Atuhirwe', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'atuhirwetracy@yahoo.com', '256705 423303', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(35, 'COM', NULL, NULL, NULL, 'ESAAG0000035', NULL, 'Ivy ', 'Nantumbwe', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Finance, Plann & Econ Devt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(36, 'Paid', 'M', NULL, NULL, 'ESAAG0000036', NULL, 'Erisa', 'Ngono', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'erisangono@yahoo.com', '256776 696810', 'Ministry of Water & Environment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(37, 'Paid', 'XL', NULL, NULL, 'ESAAG0000037', NULL, 'Ambrose', 'Asiimwe', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'rchasiimwe@yahoo.com', '256772 410215', 'Ministry of Water & Environment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(38, 'Paid', 'XXXL', NULL, NULL, 'ESAAG0000038', NULL, 'Olive Abwola', 'Labongo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'abwolaolive2000@yahoo.co.uk', '256772 322811', 'Ministry of Water & Environment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(39, 'Paid', 'M', NULL, NULL, 'ESAAG0000039', NULL, 'James Mwangalasa', 'oundo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'mwangalasa@gmail.com', '256777 912186', 'Ministry of Water & Environment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(40, 'Paid', 'L', NULL, NULL, 'ESAAG0000040', NULL, 'Allan', 'Kasagga', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'akasagga12@gmail.com', '256772 489997', 'NEMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(41, 'Paid', 'XL', NULL, NULL, 'ESAAG0000041', NULL, 'Patrick', 'Rwera', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'prwera@nemaug.org', '256772 207159', 'NEMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(42, 'Paid', 'L', NULL, NULL, 'ESAAG0000042', NULL, 'James ', 'Elungat', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'jelungat@nemaug.org', '256772 537494', 'NEMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(43, 'Paid', 'XL', NULL, NULL, 'ESAAG0000043', NULL, 'Francis', 'Katerega', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256772 381340', 'NEMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(44, 'Paid', 'S', NULL, NULL, 'ESAAG0000044', NULL, 'Florence', 'Nampeera', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'fnampeera@nemaug.org', '256772 916461', 'NEMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(45, 'Paid', 'L', NULL, NULL, 'ESAAG0000045', NULL, 'Amina ', 'Nakacwa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'aminah256@gmail.com', '256782 466043', 'NEMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(46, NULL, 'XXL', NULL, NULL, 'ESAAG0000046', NULL, 'Chris Nsheme', 'Barungi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'chrisbarungi17@gmail.com', '256392 962224', 'MoLG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(47, NULL, NULL, NULL, NULL, 'ESAAG0000047', NULL, 'Tobias W', 'Semakula', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UVRI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(48, NULL, NULL, NULL, NULL, 'ESAAG0000048', NULL, 'Henry Agaba', 'Tumwine', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UVRI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(49, 'Paid', NULL, NULL, NULL, 'ESAAG0000049', NULL, 'Yahaya', 'Kasolo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(50, 'Paid', NULL, NULL, NULL, 'ESAAG0000050', NULL, 'Deborah Aweebwa', 'Nabukeera', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(51, 'Paid', NULL, NULL, NULL, 'ESAAG0000051', NULL, 'Annet', 'Nakiwala', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(52, 'Paid', NULL, NULL, NULL, 'ESAAG0000052', NULL, 'Francis Ronald ', 'Opapan', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(53, NULL, NULL, NULL, NULL, 'ESAAG0000053', NULL, 'William E G', 'Owachi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Cotton Devt Orgn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(54, NULL, NULL, NULL, NULL, 'ESAAG0000054', NULL, 'Margaret', 'Nnankabirwa Ngabo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Cotton Devt Orgn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(55, NULL, NULL, NULL, NULL, 'ESAAG0000055', NULL, 'Juma Hussein', 'Juma', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Cotton Devt Orgn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(56, NULL, NULL, NULL, NULL, 'ESAAG0000056', NULL, 'Rogers ', 'Wamanya', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Cotton Devt Orgn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(57, 'Paid', 'XL', NULL, NULL, 'ESAAG0000057', NULL, 'Henry Patrick', 'Kunobwa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'patrickkunobwa@gmail.com', '256752 721103', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(58, 'Paid', 'L', NULL, NULL, 'ESAAG0000058', NULL, 'Fredrick Nganda', 'Kaweesa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'knganda@parliament.go.ug', '256701 409161', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(59, 'Paid', 'XL', NULL, NULL, 'ESAAG0000059', NULL, 'Rebecca ', 'Tendo Babirye', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'rebeccatendo@gmail.com', '256775 453670', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(60, 'Paid', 'XXL', NULL, NULL, 'ESAAG0000060', NULL, 'Justus ', 'Mugisa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'jmugisa@parliament.go.ug', '256772 400136', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(61, 'Paid', 'XXL', NULL, NULL, 'ESAAG0000061', NULL, 'Charles ', 'Olicho', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'olichcharles@gmail.com', '256783 194815', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(62, 'Paid', 'XL', NULL, NULL, 'ESAAG0000062', NULL, 'Sula', 'Ssebugwawo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'sssebugwawo@yahoo.com', '256772 519065', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(63, 'Paid', 'L', NULL, NULL, 'ESAAG0000063', NULL, 'Aaron', 'Ssemakula', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'aronsema@gmail.com', '256782 010631', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(64, 'Paid', 'XL', NULL, NULL, 'ESAAG0000064', NULL, 'Ronald ', 'Kiggundu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'ronaldkiggundu@gmail.com', '256704 764411', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(65, 'Paid', 'S', NULL, NULL, 'ESAAG0000065', NULL, 'Susan', 'Anyait', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'sanyait@parliament.go.ug', '256788 755655', 'Parlaiment of Uganda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(66, NULL, 'L', NULL, NULL, 'ESAAG0000066', NULL, 'David Livingstone', 'Matovu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'matovud@gmail.com', '256712 854974', 'Uganda Heart Institute', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(67, NULL, 'M', NULL, NULL, 'ESAAG0000067', NULL, 'Daniel', 'Eling', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'elingdaniel@yahoo.com', '256774 884727', 'Uganda Heart Institute', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(68, NULL, 'L', NULL, NULL, 'ESAAG0000068', NULL, 'Roselyne', 'Amadrio', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'roselyneamadrio@yahoo.com', '256772 449734', 'Uganda Heart Institute', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(69, NULL, NULL, NULL, NULL, 'ESAAG0000069', NULL, 'Julius Tegiike', 'Mununuzi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NFA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(70, NULL, NULL, NULL, NULL, 'ESAAG0000070', NULL, 'Ronald ', 'Dongo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NFA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(71, NULL, NULL, NULL, NULL, 'ESAAG0000071', NULL, 'Abdul', 'Mubiru', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NFA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(72, NULL, NULL, NULL, NULL, 'ESAAG0000072', NULL, 'Joyce Falea', 'Lekuru', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Moroto RRH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(73, 'Paid', 'L', NULL, NULL, 'ESAAG0000073', NULL, 'Tom ', 'Byaruhanga', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'tombyaru@yahoo.co.uk', '256772 486828', 'UNCST', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(74, 'Paid', 'L', NULL, NULL, 'ESAAG0000074', NULL, 'Tucker', 'Kato', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'tuckerkato@yahoo.com', '256701 815957', 'UNCST', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(75, NULL, 'L', NULL, NULL, 'ESAAG0000075', NULL, 'Moses Charles ', 'Waibi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'charles.waibi@ubos.org', '256', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(76, NULL, 'L', NULL, NULL, 'ESAAG0000076', NULL, 'Alex', 'Nkerewe', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'alex.nkerewe@ubos.org', '256787 422337', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(77, NULL, 'XL', NULL, NULL, 'ESAAG0000077', NULL, 'Joseph Lister', 'Andama', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'joseph.andama@ubos.org', '256772 584737', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(78, NULL, 'XL', NULL, NULL, 'ESAAG0000078', NULL, 'Geoffrey', 'Kakuta', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'geoffrey.kakuta@ubos.org', '256772 099792', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(79, NULL, 'XL', NULL, NULL, 'ESAAG0000079', NULL, 'Alfred', 'Okurut', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'alfred.okurut@ubos.org', '256787 422337', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(80, NULL, 'XL', NULL, NULL, 'ESAAG0000080', NULL, 'Esther', 'Muyisa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'esther.muyisa@ubos.org', '256712 985079', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(81, NULL, 'XL', NULL, NULL, 'ESAAG0000081', NULL, 'Wilberforce ', 'Kaiire Walyomu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'wilberforcewalyomu@yahoo.co.uk', '256772 864378', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(82, NULL, 'XL', NULL, NULL, 'ESAAG0000082', NULL, 'Florence', 'Abeja Obiro', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'florence.obiro@ubos.org', '256702 555917', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(83, NULL, 'XL', NULL, NULL, 'ESAAG0000083', NULL, 'Moses Dairus', 'Okello', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'okellm@tahoo.com', '256752 305850', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(84, NULL, 'XL', NULL, NULL, 'ESAAG0000084', NULL, 'David ', 'Ocheng', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'davidocheng@ubos.org', '256772 507132', 'UBOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(85, NULL, 'XL', NULL, NULL, 'ESAAG0000085', NULL, 'Samson', 'Ddamulira', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'damulirasamson@gmail.com', '256772 328556', 'MoLG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(86, NULL, 'XL', NULL, NULL, 'ESAAG0000086', NULL, 'Joyce', 'Angiji', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'joyceangiji@yahoo.co.uk', '256772 440718', 'MoLG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(87, NULL, 'XL', NULL, NULL, 'ESAAG0000087', NULL, 'Sirajje Lukomu', 'Kyazze', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'slkyazze@gmail.com', '256782 449567', 'MoLG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(88, NULL, 'L', NULL, NULL, 'ESAAG0000088', NULL, 'Charles Kazimoto', 'Muhindo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'kazimoto2001@gmail.com', '256774 334915', 'MoLG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(89, NULL, 'L', NULL, NULL, 'ESAAG0000089', NULL, 'Aidati', 'Nandudu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'nandudati@yahoo.com', '256772 442173', 'MoLG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(90, NULL, NULL, NULL, NULL, 'ESAAG0000090', NULL, 'Andrew ', 'Mugerwa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'LGFC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(91, NULL, NULL, NULL, NULL, 'ESAAG0000091', NULL, 'Mary T', 'Kiggundu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NARO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(92, NULL, NULL, NULL, NULL, 'ESAAG0000092', NULL, 'Denis', 'Owor', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NARO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(93, NULL, NULL, NULL, NULL, 'ESAAG0000093', NULL, 'Ali', 'Oloka', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NARO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(94, NULL, NULL, NULL, NULL, 'ESAAG0000094', NULL, 'Michael', 'Okello', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NARO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(95, NULL, NULL, NULL, NULL, 'ESAAG0000095', NULL, 'Andrew Kilama', 'Lajul', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UCDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(96, NULL, NULL, NULL, NULL, 'ESAAG0000096', NULL, 'William', 'Rugadya', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UCDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(97, NULL, NULL, NULL, NULL, 'ESAAG0000097', NULL, 'Freda', 'Muhumuza', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UCDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(98, NULL, NULL, NULL, NULL, 'ESAAG0000098', NULL, 'Andrew Alex', 'Mawanda', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UCDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(99, NULL, NULL, NULL, NULL, 'ESAAG0000099', NULL, 'David Luzinda ', 'Kyeswa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UCDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(100, NULL, NULL, NULL, NULL, 'ESAAG0000100', NULL, 'Rebecca Mella', 'Nanyama', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'FIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(101, NULL, NULL, NULL, NULL, 'ESAAG0000101', NULL, 'Livingstone Samuel', 'Waako', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'FIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(102, NULL, NULL, NULL, NULL, 'ESAAG0000102', NULL, 'Dennis ', 'Barigye ', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Defence ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(103, NULL, NULL, NULL, NULL, 'ESAAG0000103', NULL, 'Johnson', 'Wepukhulu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Ministry of Defence ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(104, 'Paid', 'XL', NULL, NULL, 'ESAAG0000104', NULL, 'Charles ', 'Okello', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'okellocharles@gmail.com', '256772 621479', 'Soroti University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(105, 'Paid', 'XL', NULL, NULL, 'ESAAG0000105', NULL, 'James', 'Odongo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'odongojames2013@gmail.com', '256789 135135', 'Soroti University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(106, 'Paid', 'XL', NULL, NULL, 'ESAAG0000106', NULL, 'Edward ', 'Obeele', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'eobeele@gmail.com', '256772 853163', 'Soroti University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(107, NULL, NULL, NULL, NULL, 'ESAAG0000107', NULL, 'Hellen Jenny', 'Owechi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'owechi2146@yahoo.com', '256772 461633', 'MAAIF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(108, NULL, NULL, NULL, NULL, 'ESAAG0000108', NULL, 'Joseph', 'Lwanga', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(109, NULL, NULL, NULL, NULL, 'ESAAG0000109', NULL, 'George ', 'Kyeyune', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(110, NULL, NULL, NULL, NULL, 'ESAAG0000110', NULL, 'Abdul', 'Kibesi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(111, NULL, NULL, NULL, NULL, 'ESAAG0000111', NULL, 'Apollo', 'Muhunguzi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(112, NULL, NULL, NULL, NULL, 'ESAAG0000112', NULL, 'Grace ', 'Mukalazi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(113, NULL, NULL, NULL, NULL, 'ESAAG0000113', NULL, 'Rogers ', 'Serunjogi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(114, NULL, NULL, NULL, NULL, 'ESAAG0000114', NULL, 'Sylvia', 'Neumbe', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(115, NULL, NULL, NULL, NULL, 'ESAAG0000115', NULL, 'Joseph', 'Akuza', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(116, NULL, NULL, NULL, NULL, 'ESAAG0000116', NULL, 'Dorothy', 'Achan', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(117, NULL, NULL, NULL, NULL, 'ESAAG0000117', NULL, 'Simon Peter ', 'Tamale', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(118, NULL, NULL, NULL, NULL, 'ESAAG0000118', NULL, 'Denis ', 'Otim', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(119, NULL, NULL, NULL, NULL, 'ESAAG0000119', NULL, 'Teddy', 'Nabawesi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(120, NULL, NULL, NULL, NULL, 'ESAAG0000120', NULL, 'John ', 'Sebuyira', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(121, NULL, NULL, NULL, NULL, 'ESAAG0000121', NULL, 'Peter', 'Mali', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(122, NULL, NULL, NULL, NULL, 'ESAAG0000122', NULL, 'Faith ', 'Okema', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(123, NULL, NULL, NULL, NULL, 'ESAAG0000123', NULL, 'Augusto', 'Barigye ', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(124, NULL, NULL, NULL, NULL, 'ESAAG0000124', NULL, 'Yoweri ', 'Mpora', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Electoral Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(125, NULL, 'XL', NULL, NULL, 'ESAAG0000125', NULL, 'Deborah', 'Nalule', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'debbienals@yahoo.com', '256772 616230', 'Makerere University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(126, NULL, 'XL', NULL, NULL, 'ESAAG0000126', NULL, 'Rosette Ndagire', 'Senoga', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'rnsenoga@gmail.com', '256772 433909', 'Makerere University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(127, 'Paid', 'L', NULL, NULL, 'ESAAG0000127', NULL, 'Florence', 'Nassuna', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'fnassuna@finance.mak.ac.ug', '256752 837622', 'Makerere University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0);
INSERT INTO `core_registrations` (`id`, `payment_status`, `shirt_size`, `hotel`, `leisure_activity`, `registration_code`, `invoice_code`, `first_name`, `last_name`, `other_names`, `nationality`, `dob`, `gender`, `national_id`, `rate_category`, `member_number`, `delegate_type`, `email`, `telephone`, `emp_organisation`, `emp_industry`, `emp_country`, `emp_job_title`, `emp_email`, `emp_telephone`, `acc_body_member`, `acc_body`, `insurance`, `insurance_body`, `passport_no`, `passport_issue_date`, `passport_expiry_date`, `passport_issue_place`, `flight_no`, `travel_from_country`, `travel_arrival_date`, `travel_departure_date`, `tour_route`, `hotel_room_type`, `nok_name`, `nok_country`, `nok_email`, `nok_telephone`, `parent_registration_code`, `update_registration_code`, `created_at`, `updated_at`, `reg_email_sent`, `invitation_email_sent`, `title`, `street`, `plot_no`, `box`, `city`, `remote_id`, `sync`) VALUES
(128, NULL, 'XL', NULL, NULL, 'ESAAG0000128', NULL, 'Gyaviira Ssebina', 'Lubowa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'glubowa@gmail.com', '256775 901292', 'Makerere University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(129, NULL, 'XL', NULL, NULL, 'ESAAG0000129', NULL, 'Augustine', 'Tamale', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'aktamale@yahoo.co.uk', '256772 419501', 'Makerere University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(130, NULL, 'L', NULL, NULL, 'ESAAG0000130', NULL, 'Jackie Ayorekire', 'Keirungi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'keirungi112@gmail.com', '256772 450203', 'Makerere University', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(131, 'Paid', 'XXXL', NULL, NULL, 'ESAAG0000131', NULL, 'Joseph Eseru', 'Engwau', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'josengwau@yahoo.com', '256780 178259', 'Jinja Hospital', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(132, 'Paid', 'XXL', NULL, NULL, 'ESAAG0000132', NULL, 'Milton Kaitwebye ', 'Byaruhanga', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'kaitwebye@gmail.com', '256772 348093', 'Jinja Hospital', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(133, 'Paid', 'M', NULL, NULL, 'ESAAG0000133', NULL, 'Muheirwe', 'Nyogire', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'nmuheirwe@gmail.com', '256779 440180', 'Uganda National Meteorological Authority', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(134, 'Paid', 'S', NULL, NULL, 'ESAAG0000134', NULL, 'Salimu', 'Muhamed', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'salim.muhamed88@gmail.com', '256782 748151', 'Uganda National Meteorological Authority', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(135, 'Paid', 'XXL', NULL, NULL, 'ESAAG0000135', NULL, 'Eugenia Batenea', 'Kayondo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'batengae@yahoo.com', '256712 996404', 'Uganda National Meteorological Authority', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(136, 'Paid', 'L', NULL, NULL, 'ESAAG0000136', NULL, 'Ronald Rodney', 'Kalema', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'ronald.kalema@unma.go.ug', '256 704534680', 'Uganda National Meteorological Authority', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(137, 'Paid', 'XXL', NULL, NULL, 'ESAAG0000137', NULL, 'Alex', 'Rutafa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'alex.rutafa@gmail.com', '256777 543080', 'Uganda National Meteorological Authority', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(138, NULL, 'XXL', NULL, NULL, 'ESAAG0000138', NULL, 'Stevens Bukulu', 'Kasirye', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'kasiryestevens@yahoo.com', '256772 449192', 'National Curriculum Development Centre', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(139, NULL, 'L', NULL, NULL, 'ESAAG0000139', NULL, 'Sulayi ', 'Wandera', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'wanderasulayi@gmail.com', '256700 666666', 'MoICT & NG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(140, NULL, 'XXXL', NULL, NULL, 'ESAAG0000140', NULL, 'Peter', 'Kaggwa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'batengopeter@yahoo.com', '256701 003425', 'MoICT & NG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(141, NULL, 'M', NULL, NULL, 'ESAAG0000141', NULL, 'Sarah', 'Mbabazi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'shalmags@yahoo.co.uk', '256787 556878', 'MoICT & NG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(142, NULL, 'XXL', NULL, NULL, 'ESAAG0000142', NULL, 'Mary', 'Kabyanga', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'marykabyanga@yahoo.com', '256772 902634', 'MoICT & NG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(143, NULL, 'XXL', NULL, NULL, 'ESAAG0000143', NULL, 'Fred', 'Andema', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'fandema@kcca.go.ug', '256794 660272', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(144, NULL, 'XL', NULL, NULL, 'ESAAG0000144', NULL, 'Julius Raymond', 'Kabugo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'jkabugo@kcca.go.ug', '256794 660271', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(145, NULL, 'XXL', NULL, NULL, 'ESAAG0000145', NULL, 'Donny Muganzi', 'Kitabire', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'dkitabire@kcca.go.ug', '256794 660273', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(146, NULL, 'XXL', NULL, NULL, 'ESAAG0000146', NULL, 'Henry Odongo', 'Abunyang Emoit', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'hodongo@kcca.go.ug', '256794 660308', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(147, NULL, 'XL', NULL, NULL, 'ESAAG0000147', NULL, 'Sarah Elizabeth', 'Nafuna', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'snafuna@kcca.go.ug', '256794 660809', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(148, NULL, 'M', NULL, NULL, 'ESAAG0000148', NULL, 'Isaac', 'Kyaligonza', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'ikyaligonza@kcca.go.ug', '256794 660318', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(149, NULL, 'XL', NULL, NULL, 'ESAAG0000149', NULL, 'James Yonah', 'Odoi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'jodoi@kcca.go.ug', '256 794 660317', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(150, NULL, 'XL', NULL, NULL, 'ESAAG0000150', NULL, 'Seruwagi', 'Norbert', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'nseruwagi@kcca.go.ug', '257 794 660798', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(151, NULL, 'L', NULL, NULL, 'ESAAG0000151', NULL, 'Elizabeth Nabirye', 'Kamanyire', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'betikama@live.co.uk', '256794 660646', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(152, NULL, 'M', NULL, NULL, 'ESAAG0000152', NULL, 'Alice Peter', 'Zawadi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'azawadi@kcca.go.ug', '256794 660647', 'KCCA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(153, NULL, NULL, NULL, NULL, 'ESAAG0000153', NULL, 'Joweria', 'Kamariza', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoLHUD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(154, NULL, NULL, NULL, NULL, 'ESAAG0000154', NULL, 'Leonard L', 'Sittankya', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoLHUD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(155, NULL, NULL, NULL, NULL, 'ESAAG0000155', NULL, 'Elizabeth', 'Nabongo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoLHUD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(156, NULL, NULL, NULL, NULL, 'ESAAG0000156', NULL, 'Francis ', 'Kaggwa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'URSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(157, NULL, NULL, NULL, NULL, 'ESAAG0000157', NULL, 'Alex ', 'Anganya', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'URSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(158, NULL, NULL, NULL, NULL, 'ESAAG0000158', NULL, 'Adeline', 'Kushemererwa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'URSB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(159, NULL, 'M', NULL, NULL, 'ESAAG0000159', NULL, 'Washington Steven', 'Musamali', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'wmusamali@yahoo.com', '256782 625607', 'Uganda Land Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(160, NULL, NULL, NULL, NULL, 'ESAAG0000160', NULL, 'Irene', 'Rukundo', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Uganda Blood Transfusion Services', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(161, NULL, NULL, NULL, NULL, 'ESAAG0000161', NULL, 'Beatrice', 'Aol', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'Uganda Blood Transfusion Services', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(162, NULL, 'XL', NULL, NULL, 'ESAAG0000162', NULL, 'Noah Deogratius ', 'Luwalira', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'noahdeo@yahoo.com', '256772 449848', 'Atomic Energy Council', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(163, NULL, 'L', NULL, NULL, 'ESAAG0000163', NULL, 'Caroline Alowo', 'Mukalazi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'calowo@atomiccouncil.go.ug', '256712 844724', 'Atomic Energy Council', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(164, NULL, 'L', NULL, NULL, 'ESAAG0000164', NULL, 'Geoffrey', 'Muhanguzi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'geoffmuhanguzi@gmail.com', '256703 192926', 'Atomic Energy Council', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(165, NULL, 'M', NULL, NULL, 'ESAAG0000165', NULL, 'Rachel Dilly', 'Mutesi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'tesidilly@gmail.com', '256771 871686', 'Atomic Energy Council', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(166, NULL, 'XXL', NULL, NULL, 'ESAAG0000166', NULL, 'Louise Ssegwanyi', 'Naggirinya', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'lnaggirinya@yahoo.com', '256752 998716', 'Judiciary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(167, NULL, 'L', NULL, NULL, 'ESAAG0000167', NULL, 'Toto', 'Adrisi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'totoadrisi@yahoo.com', '256772 355044', 'Judiciary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(168, NULL, 'L', NULL, NULL, 'ESAAG0000168', NULL, 'Norman', 'Bbosa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'normanbbosa@yahoo.com', '256782 438558', 'Judiciary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(169, NULL, 'L', NULL, NULL, 'ESAAG0000169', NULL, 'Mariam Kizza', 'Okonye', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'okonyemariam@yahoo.com', '256772 486356', 'Judiciary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(170, 'Paid', 'XL', NULL, NULL, 'ESAAG0000170', NULL, 'Felix', 'Abunyang', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'felixabn2006@yahoo.com', '256772 525589', 'ESC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(171, NULL, 'XXXXL', NULL, NULL, 'ESAAG0000171', NULL, 'Guna Anthony ', 'Ogwang', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'ogwanganthony@gmail.com', '256776 873486', 'MTWA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(172, NULL, 'XL', NULL, NULL, 'ESAAG0000172', NULL, 'David ', 'Tumwesigye', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'scout70@yahoo.com', '256772 405338', 'MTWA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(173, NULL, 'XL', NULL, NULL, 'ESAAG0000173', NULL, 'Rogers S H M', 'Kyewalabye', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'rogerkye@gmail.com', '256772 438151', 'MTWA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(174, NULL, 'XL', NULL, NULL, 'ESAAG0000174', NULL, 'Thomas', 'Ongom', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'tomongom@gmail.com', '256783 841895', 'MTWA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(175, NULL, 'XL', NULL, NULL, 'ESAAG0000175', NULL, 'Belly Sam', 'Okwir', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'bellysam52@yahoo.com', '256772 993166', 'Mbale Referral Hospital', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(176, NULL, 'L', NULL, NULL, 'ESAAG0000176', NULL, 'Emmanuel', 'Kamukama', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'emmanuel_kamukama@yahoo.com', '256772 416260', 'Kabaale Referral Hospital', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(177, NULL, 'L', NULL, NULL, 'ESAAG0000177', NULL, 'Samuel', 'Maedero', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'maederosamuel@yahoo.com', '256782 612169', 'Kabaale Referral Hospital', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(178, NULL, 'L', NULL, NULL, 'ESAAG0000178', NULL, 'Proscovia Nabachwa', 'Kintu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'proscoviayiga@gmail.com', '256772 591117', 'Desert Locust Control Org for East.Africa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(179, NULL, NULL, NULL, NULL, 'ESAAG0000179', NULL, 'Benson ', 'Kigenyi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoGLSD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(180, NULL, NULL, NULL, NULL, 'ESAAG0000180', NULL, 'Emily', 'Birekeyaho', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoGLSD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(181, NULL, NULL, NULL, NULL, 'ESAAG0000181', NULL, 'Edward ', 'Turyahebwa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoGLSD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(182, NULL, NULL, NULL, NULL, 'ESAAG0000182', NULL, 'Ainea', 'Muheki', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoGLSD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(183, NULL, NULL, NULL, NULL, 'ESAAG0000183', NULL, 'Eriver', 'Mukasa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoGLSD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(184, NULL, NULL, NULL, NULL, 'ESAAG0000184', NULL, 'Dhakaba', 'Kirunda', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoGLSD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(185, NULL, NULL, NULL, NULL, 'ESAAG0000185', NULL, 'Betty', 'Atieno', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MoGLSD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(186, NULL, NULL, NULL, NULL, 'ESAAG0000186', NULL, 'Tadeo', 'Mbaziira', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NAGRC&DB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(187, NULL, NULL, NULL, NULL, 'ESAAG0000187', NULL, 'Patrick ', 'Mangusho', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'NAGRC&DB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(188, NULL, NULL, NULL, NULL, 'ESAAG0000188', NULL, 'Stephen Naigo', 'Emitu', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UCI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(189, NULL, NULL, NULL, NULL, 'ESAAG0000189', NULL, 'Paul', 'Musimami', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'UCI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(190, 'Paid', 'XL', NULL, NULL, 'ESAAG0000190', NULL, 'Christine', 'Ojeka', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'ojekacri@yahoo.co.uk', '256782 598761', 'NDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(191, 'Paid', 'M', NULL, NULL, 'ESAAG0000191', NULL, 'Cyprian', 'Mwesigwa', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, 'cyprianmwesigwa@gmail.com', '256772 932905', 'NDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(192, NULL, NULL, NULL, NULL, 'ESAAG0000192', NULL, 'Martha', 'Ajulong', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'DEI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(193, NULL, NULL, NULL, NULL, 'ESAAG0000193', NULL, 'Harriet', 'Natukunda', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MUBS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(194, NULL, NULL, NULL, NULL, 'ESAAG0000194', NULL, 'Betty', 'Nabunya', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MUBS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(195, NULL, NULL, NULL, NULL, 'ESAAG0000195', NULL, 'James', 'Mafabi', NULL, 'UGANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '256', 'MUBS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `core_sessions`
--

CREATE TABLE `core_sessions` (
  `id` int(11) NOT NULL,
  `session` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `remote_id` int(10) DEFAULT NULL,
  `sync` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_sessions`
--

INSERT INTO `core_sessions` (`id`, `session`, `date`, `status`, `remote_id`, `sync`) VALUES
(8, 'ESSAG DAY 1 REG', '2017-09-25', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `core_session_attendees`
--

CREATE TABLE `core_session_attendees` (
  `id` int(255) NOT NULL,
  `id_core_registrations` int(10) UNSIGNED DEFAULT NULL,
  `id_core_sessions` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remote_id` int(10) DEFAULT NULL,
  `sync` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_users`
--

CREATE TABLE `core_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `identity_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_endpoint` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'LOCAL' COMMENT 'LOCAL, LDAP',
  `is_active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `core_users`
--

INSERT INTO `core_users` (`id`, `identity_id`, `name`, `username`, `password`, `auth_endpoint`, `is_active`, `last_login`) VALUES
(1, 1, 'Administrator', 'administrator', '$P$DXAjZrxKPHs3fp4TtDCfM5h/pyrq3X1', 'LOCAL', 1, '2018-02-14 19:36:07'),
(2, 1, 'User', 'user', '$P$DXAjZrxKPHs3fp4TtDCfM5h/pyrq3X1', 'LOCAL', 1, '2018-02-06 11:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `core_user_groups`
--

CREATE TABLE `core_user_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_core_users` int(11) UNSIGNED NOT NULL,
  `id_core_groups` int(11) UNSIGNED NOT NULL,
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `core_user_groups`
--

INSERT INTO `core_user_groups` (`id`, `id_core_users`, `id_core_groups`, `remote_id`, `sync`) VALUES
(1, 1, 1, 1, 1),
(3, 2007, 2, 3, 1),
(6, 2, 1, 6, 1),
(8, 24094, 2, 8, 1),
(11, 2591, 4, 11, 1),
(13, 1946, 3, 13, 1),
(16, 2603, 4, 16, 1),
(17, 2863, 4, 17, 1),
(18, 20175, 4, 18, 1),
(19, 2002, 4, 19, 1),
(20, 2006, 4, 20, 1),
(26, 2626, 4, 26, 1),
(27, 1156, 1, 27, 1),
(29, 859, 1, 29, 1),
(30, 19824, 2, 30, 1),
(31, 13189, 8, 31, 1),
(38, 2012, 5, 38, 1),
(39, 2012, 2, 39, 1),
(40, 2004, 4, 40, 1),
(44, 2000, 5, NULL, 1),
(45, 2000, 9, 45, 1),
(52, 20400, 5, 52, 1),
(54, 22677, 10, 54, 1),
(55, 22677, 3, 55, 1),
(56, 22677, 8, 56, 1),
(57, 22677, 4, 57, 1),
(58, 2011, 5, NULL, 1),
(59, 2011, 2, NULL, 1),
(66, 9438, 1, 66, 1),
(74, 230, 1, 74, 1),
(75, 2010, 1, 75, 1),
(78, 24199, 1, 78, 1),
(79, 1945, 1, 79, 1),
(81, 2014, 1, 81, 1),
(82, 2362, 8, 82, 1),
(85, 754, 12, 85, 1),
(86, 754, 8, NULL, 1),
(87, 733, 12, 87, 1),
(88, 733, 8, NULL, 1),
(89, 2001, 1, NULL, 1),
(90, 15606, 8, NULL, 1),
(91, 797, 1, 91, 1),
(92, 26636, 2, 92, 1),
(93, 1656, 11, 93, 1),
(94, 1656, 8, 94, 1),
(95, 1656, 4, 95, 1),
(96, 14069, 8, 96, 1),
(97, 1750, 1, 97, 1);

-- --------------------------------------------------------

--
-- Table structure for table `essag_reg`
--

CREATE TABLE `essag_reg` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `emp_organisation` varchar(200) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `payment_status` varchar(10) DEFAULT NULL,
  `shirt_size` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `essag_reg`
--

INSERT INTO `essag_reg` (`id`, `first_name`, `last_name`, `nationality`, `emp_organisation`, `telephone`, `email`, `payment_status`, `shirt_size`) VALUES
(1, 'Lawrence ', 'Semakula', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256752 644457', 'lawrence.semakula@finance.go.ug', 'COM', 'XXL'),
(2, 'Godfrey ', 'B. Ssemugooma', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256772 409983', 'godfrey.ssemugooma@finance.go.ug', 'COM', 'L'),
(3, 'Jeniffer', 'Muhuruzi', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256772 495464', 'jennifer.muhuruzi@finance.go.ug', 'COM', 'M'),
(4, 'Stephen', 'Ojiambo', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256772 443080', 'stephen.ojambo@finance.go.ug', 'COM', 'XL'),
(5, 'David Nyimba', 'Kiyingi', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '', '', 'COM', ''),
(6, 'Arthur ', 'Mugweri', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '', '', 'COM', ''),
(7, 'Aiden David', 'Rujumba', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(8, 'Daniel', 'Kigenyi', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(9, 'Mubarak', 'Nasamba', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256702 926464', 'mubarak.nasamba@finance.go.ug', 'COM', 'L'),
(10, 'Aziz', 'Ssettaala', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256702 940179', 'aziz.ssettaala@finance.go.ug', 'COM', 'S'),
(11, 'Yakub', 'Lubega', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(12, 'Pedson', 'Twesigomwe', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256772 320921', 'pedson.twesigomwe@finance.go.ug', 'COM', 'M'),
(13, 'Steven', 'Balisanyuka', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256772 507055', 'sanustev@yahoo.com', 'COM', 'L'),
(14, 'Richard', 'Tabaro', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(15, 'Norbert Hillary', 'Okello', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256782 378083', 'norbert.okello@finance.go.ug', 'COM', 'XL'),
(16, 'Rogers ', 'Baguma', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(17, 'Bernadette', 'Nakabuye Kizito', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(18, 'Stephen', 'Barungi', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(19, 'Sophie', 'Ngira', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256750 984258', 'sofiann6@gmail.com', 'COM', 'SS'),
(20, 'Immaculate', 'Nabateesa', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256706 255351', 'immaculate.nabateesa@finance.go.ug', 'COM', 'S'),
(21, 'Samson', 'Budeyo', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256774 199521', 'budeyo.samson@gmail.com', 'COM', 'M'),
(22, 'Deo', 'Lutaaya', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(23, 'Godfrey Luggya', 'Makedi', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(24, 'Chris ', 'Otim', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(25, 'Immaculate', 'Nabayinda', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(26, 'Ronald ', 'Turihoahabwe', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(27, 'Deborah Dorothy', 'Kusiima', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256779 198097', 'ddeby.siima@gmail.com', 'COM', 'M'),
(28, 'Linnet ', 'Namanya', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256784 586861', 'lynnetnamanya@gmail.com', 'COM', 'XXL'),
(29, 'Barbara', 'Nakintu', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256786 086688', 'barbara.nakintu@finance.go.ug', 'COM', 'S'),
(30, 'Melanie Kizito', 'Nansubuga', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256776 112919', 'melanie.nansubuga@finance.go.ug', 'COM', 'L'),
(31, 'Elizabeth', 'Tushemerirwe', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256787 124110', 'elizabeth.tushemerirwe@finance.go.ug', 'COM', 'S'),
(32, 'Barbara', 'Rhada', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256775 655910', 'barbara.rhada@finance.go.ug', 'COM', 'L'),
(33, 'Edward ', 'Ekonga', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(34, 'Tracy', 'Atuhirwe', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256705 423303', 'atuhirwetracy@yahoo.com', 'COM', 'L'),
(35, 'Ivy ', 'Nantumbwe', 'UGANDA', 'Ministry of Finance, Plann & Econ Devt', '256', '', 'COM', ''),
(36, 'Erisa', 'Ngono', 'UGANDA', 'Ministry of Water & Environment', '256776 696810', 'erisangono@yahoo.com', 'Paid', 'M'),
(37, 'Ambrose', 'Asiimwe', 'UGANDA', 'Ministry of Water & Environment', '256772 410215', 'rchasiimwe@yahoo.com', 'Paid', 'XL'),
(38, 'Olive Abwola', 'Labongo', 'UGANDA', 'Ministry of Water & Environment', '256772 322811', 'abwolaolive2000@yahoo.co.uk', 'Paid', 'XXXL'),
(39, 'James Mwangalasa', 'oundo', 'UGANDA', 'Ministry of Water & Environment', '256777 912186', 'mwangalasa@gmail.com', 'Paid', 'M'),
(40, 'Allan', 'Kasagga', 'UGANDA', 'NEMA', '256772 489997', 'akasagga12@gmail.com', 'Paid', 'L'),
(41, 'Patrick', 'Rwera', 'UGANDA', 'NEMA', '256772 207159', 'prwera@nemaug.org', 'Paid', 'XL'),
(42, 'James ', 'Elungat', 'UGANDA', 'NEMA', '256772 537494', 'jelungat@nemaug.org', 'Paid', 'L'),
(43, 'Francis', 'Katerega', 'UGANDA', 'NEMA', '256772 381340', '', 'Paid', 'XL'),
(44, 'Florence', 'Nampeera', 'UGANDA', 'NEMA', '256772 916461', 'fnampeera@nemaug.org', 'Paid', 'S'),
(45, 'Amina ', 'Nakacwa', 'UGANDA', 'NEMA', '256782 466043', 'aminah256@gmail.com', 'Paid', 'L'),
(46, 'Chris Nsheme', 'Barungi', 'UGANDA', 'MoLG', '256392 962224', 'chrisbarungi17@gmail.com', '', 'XXL'),
(47, 'Tobias W', 'Semakula', 'UGANDA', 'UVRI', '256', '', '', ''),
(48, 'Henry Agaba', 'Tumwine', 'UGANDA', 'UVRI', '256', '', '', ''),
(49, 'Yahaya', 'Kasolo', 'UGANDA', 'MoIA', '256', '', 'Paid', ''),
(50, 'Deborah Aweebwa', 'Nabukeera', 'UGANDA', 'MoIA', '256', '', 'Paid', ''),
(51, 'Annet', 'Nakiwala', 'UGANDA', 'MoIA', '256', '', 'Paid', ''),
(52, 'Francis Ronald ', 'Opapan', 'UGANDA', 'MoIA', '256', '', 'Paid', ''),
(53, 'William E G', 'Owachi', 'UGANDA', 'Cotton Devt Orgn', '256', '', '', ''),
(54, 'Margaret', 'Nnankabirwa Ngabo', 'UGANDA', 'Cotton Devt Orgn', '256', '', '', ''),
(55, 'Juma Hussein', 'Juma', 'UGANDA', 'Cotton Devt Orgn', '256', '', '', ''),
(56, 'Rogers ', 'Wamanya', 'UGANDA', 'Cotton Devt Orgn', '256', '', '', ''),
(57, 'Henry Patrick', 'Kunobwa', 'UGANDA', 'Parlaiment of Uganda', '256752 721103', 'patrickkunobwa@gmail.com', 'Paid', 'XL'),
(58, 'Fredrick Nganda', 'Kaweesa', 'UGANDA', 'Parlaiment of Uganda', '256701 409161', 'knganda@parliament.go.ug', 'Paid', 'L'),
(59, 'Rebecca ', 'Tendo Babirye', 'UGANDA', 'Parlaiment of Uganda', '256775 453670', 'rebeccatendo@gmail.com', 'Paid', 'XL'),
(60, 'Justus ', 'Mugisa', 'UGANDA', 'Parlaiment of Uganda', '256772 400136', 'jmugisa@parliament.go.ug', 'Paid', 'XXL'),
(61, 'Charles ', 'Olicho', 'UGANDA', 'Parlaiment of Uganda', '256783 194815', 'olichcharles@gmail.com', 'Paid', 'XXL'),
(62, 'Sula', 'Ssebugwawo', 'UGANDA', 'Parlaiment of Uganda', '256772 519065', 'sssebugwawo@yahoo.com', 'Paid', 'XL'),
(63, 'Aaron', 'Ssemakula', 'UGANDA', 'Parlaiment of Uganda', '256782 010631', 'aronsema@gmail.com', 'Paid', 'L'),
(64, 'Ronald ', 'Kiggundu', 'UGANDA', 'Parlaiment of Uganda', '256704 764411', 'ronaldkiggundu@gmail.com', 'Paid', 'XL'),
(65, 'Susan', 'Anyait', 'UGANDA', 'Parlaiment of Uganda', '256788 755655', 'sanyait@parliament.go.ug', 'Paid', 'S'),
(66, 'David Livingstone', 'Matovu', 'UGANDA', 'Uganda Heart Institute', '256712 854974', 'matovud@gmail.com', '', 'L'),
(67, 'Daniel', 'Eling', 'UGANDA', 'Uganda Heart Institute', '256774 884727', 'elingdaniel@yahoo.com', '', 'M'),
(68, 'Roselyne', 'Amadrio', 'UGANDA', 'Uganda Heart Institute', '256772 449734', 'roselyneamadrio@yahoo.com', '', 'L'),
(69, 'Julius Tegiike', 'Mununuzi', 'UGANDA', 'NFA', '256', '', '', ''),
(70, 'Ronald ', 'Dongo', 'UGANDA', 'NFA', '256', '', '', ''),
(71, 'Abdul', 'Mubiru', 'UGANDA', 'NFA', '256', '', '', ''),
(72, 'Joyce Falea', 'Lekuru', 'UGANDA', 'Moroto RRH', '256', '', '', ''),
(73, 'Tom ', 'Byaruhanga', 'UGANDA', 'UNCST', '256772 486828', 'tombyaru@yahoo.co.uk', 'Paid', 'L'),
(74, 'Tucker', 'Kato', 'UGANDA', 'UNCST', '256701 815957', 'tuckerkato@yahoo.com', 'Paid', 'L'),
(75, 'Moses Charles ', 'Waibi', 'UGANDA', 'UBOS', '256', 'charles.waibi@ubos.org', '', 'L'),
(76, 'Alex', 'Nkerewe', 'UGANDA', 'UBOS', '256787 422337', 'alex.nkerewe@ubos.org', '', 'L'),
(77, 'Joseph Lister', 'Andama', 'UGANDA', 'UBOS', '256772 584737', 'joseph.andama@ubos.org', '', 'XL'),
(78, 'Geoffrey', 'Kakuta', 'UGANDA', 'UBOS', '256772 099792', 'geoffrey.kakuta@ubos.org', '', 'XL'),
(79, 'Alfred', 'Okurut', 'UGANDA', 'UBOS', '256787 422337', 'alfred.okurut@ubos.org', '', 'XL'),
(80, 'Esther', 'Muyisa', 'UGANDA', 'UBOS', '256712 985079', 'esther.muyisa@ubos.org', '', 'XL'),
(81, 'Wilberforce ', 'Kaiire Walyomu', 'UGANDA', 'UBOS', '256772 864378', 'wilberforcewalyomu@yahoo.co.uk', '', 'XL'),
(82, 'Florence', 'Abeja Obiro', 'UGANDA', 'UBOS', '256702 555917', 'florence.obiro@ubos.org', '', 'XL'),
(83, 'Moses Dairus', 'Okello', 'UGANDA', 'UBOS', '256752 305850', 'okellm@tahoo.com', '', 'XL'),
(84, 'David ', 'Ocheng', 'UGANDA', 'UBOS', '256772 507132', 'davidocheng@ubos.org', '', 'XL'),
(85, 'Samson', 'Ddamulira', 'UGANDA', 'MoLG', '256772 328556', 'damulirasamson@gmail.com', '', 'XL'),
(86, 'Joyce', 'Angiji', 'UGANDA', 'MoLG', '256772 440718', 'joyceangiji@yahoo.co.uk', '', 'XL'),
(87, 'Sirajje Lukomu', 'Kyazze', 'UGANDA', 'MoLG', '256782 449567', 'slkyazze@gmail.com', '', 'XL'),
(88, 'Charles Kazimoto', 'Muhindo', 'UGANDA', 'MoLG', '256774 334915', 'kazimoto2001@gmail.com', '', 'L'),
(89, 'Aidati', 'Nandudu', 'UGANDA', 'MoLG', '256772 442173', 'nandudati@yahoo.com', '', 'L'),
(90, 'Andrew ', 'Mugerwa', 'UGANDA', 'LGFC', '256', '', '', ''),
(91, 'Mary T', 'Kiggundu', 'UGANDA', 'NARO', '256', '', '', ''),
(92, 'Denis', 'Owor', 'UGANDA', 'NARO', '256', '', '', ''),
(93, 'Ali', 'Oloka', 'UGANDA', 'NARO', '256', '', '', ''),
(94, 'Michael', 'Okello', 'UGANDA', 'NARO', '256', '', '', ''),
(95, 'Andrew Kilama', 'Lajul', 'UGANDA', 'UCDA', '256', '', '', ''),
(96, 'William', 'Rugadya', 'UGANDA', 'UCDA', '256', '', '', ''),
(97, 'Freda', 'Muhumuza', 'UGANDA', 'UCDA', '256', '', '', ''),
(98, 'Andrew Alex', 'Mawanda', 'UGANDA', 'UCDA', '256', '', '', ''),
(99, 'David Luzinda ', 'Kyeswa', 'UGANDA', 'UCDA', '256', '', '', ''),
(100, 'Rebecca Mella', 'Nanyama', 'UGANDA', 'FIA', '256', '', '', ''),
(101, 'Livingstone Samuel', 'Waako', 'UGANDA', 'FIA', '256', '', '', ''),
(102, 'Dennis ', 'Barigye ', 'UGANDA', 'Ministry of Defence ', '256', '', '', ''),
(103, 'Johnson', 'Wepukhulu', 'UGANDA', 'Ministry of Defence ', '256', '', '', ''),
(104, 'Charles ', 'Okello', 'UGANDA', 'Soroti University', '256772 621479', 'okellocharles@gmail.com', 'Paid', 'XL'),
(105, 'James', 'Odongo', 'UGANDA', 'Soroti University', '256789 135135', 'odongojames2013@gmail.com', 'Paid', 'XL'),
(106, 'Edward ', 'Obeele', 'UGANDA', 'Soroti University', '256772 853163', 'eobeele@gmail.com', 'Paid', 'XL'),
(107, 'Hellen Jenny', 'Owechi', 'UGANDA', 'MAAIF', '256772 461633', 'owechi2146@yahoo.com', '', ''),
(108, 'Joseph', 'Lwanga', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(109, 'George ', 'Kyeyune', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(110, 'Abdul', 'Kibesi', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(111, 'Apollo', 'Muhunguzi', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(112, 'Grace ', 'Mukalazi', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(113, 'Rogers ', 'Serunjogi', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(114, 'Sylvia', 'Neumbe', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(115, 'Joseph', 'Akuza', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(116, 'Dorothy', 'Achan', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(117, 'Simon Peter ', 'Tamale', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(118, 'Denis ', 'Otim', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(119, 'Teddy', 'Nabawesi', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(120, 'John ', 'Sebuyira', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(121, 'Peter', 'Mali', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(122, 'Faith ', 'Okema', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(123, 'Augusto', 'Barigye ', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(124, 'Yoweri ', 'Mpora', 'UGANDA', 'Electoral Commission', '256', '', '', ''),
(125, 'Deborah', 'Nalule', 'UGANDA', 'Makerere University', '256772 616230', 'debbienals@yahoo.com', '', 'XL'),
(126, 'Rosette Ndagire', 'Senoga', 'UGANDA', 'Makerere University', '256772 433909', 'rnsenoga@gmail.com', '', 'XL'),
(127, 'Florence', 'Nassuna', 'UGANDA', 'Makerere University', '256752 837622', 'fnassuna@finance.mak.ac.ug', 'Paid', 'L'),
(128, 'Gyaviira Ssebina', 'Lubowa', 'UGANDA', 'Makerere University', '256775 901292', 'glubowa@gmail.com', '', 'XL'),
(129, 'Augustine', 'Tamale', 'UGANDA', 'Makerere University', '256772 419501', 'aktamale@yahoo.co.uk', '', 'XL'),
(130, 'Jackie Ayorekire', 'Keirungi', 'UGANDA', 'Makerere University', '256772 450203', 'keirungi112@gmail.com', '', 'L'),
(131, 'Joseph Eseru', 'Engwau', 'UGANDA', 'Jinja Hospital', '256780 178259', 'josengwau@yahoo.com', 'Paid', 'XXXL'),
(132, 'Milton Kaitwebye ', 'Byaruhanga', 'UGANDA', 'Jinja Hospital', '256772 348093', 'kaitwebye@gmail.com', 'Paid', 'XXL'),
(133, 'Muheirwe', 'Nyogire', 'UGANDA', 'Uganda National Meteorological Authority', '256779 440180', 'nmuheirwe@gmail.com', 'Paid', 'M'),
(134, 'Salimu', 'Muhamed', 'UGANDA', 'Uganda National Meteorological Authority', '256782 748151', 'salim.muhamed88@gmail.com', 'Paid', 'S'),
(135, 'Eugenia Batenea', 'Kayondo', 'UGANDA', 'Uganda National Meteorological Authority', '256712 996404', 'batengae@yahoo.com', 'Paid', 'XXL'),
(136, 'Ronald Rodney', 'Kalema', 'UGANDA', 'Uganda National Meteorological Authority', '256 704534680', 'ronald.kalema@unma.go.ug', 'Paid', 'L'),
(137, 'Alex', 'Rutafa', 'UGANDA', 'Uganda National Meteorological Authority', '256777 543080', 'alex.rutafa@gmail.com', 'Paid', 'XXL'),
(138, 'Stevens Bukulu', 'Kasirye', 'UGANDA', 'National Curriculum Development Centre', '256772 449192', 'kasiryestevens@yahoo.com', '', 'XXL'),
(139, 'Sulayi ', 'Wandera', 'UGANDA', 'MoICT & NG', '256700 666666', 'wanderasulayi@gmail.com', '', 'L'),
(140, 'Peter', 'Kaggwa', 'UGANDA', 'MoICT & NG', '256701 003425', 'batengopeter@yahoo.com', '', 'XXXL'),
(141, 'Sarah', 'Mbabazi', 'UGANDA', 'MoICT & NG', '256787 556878', 'shalmags@yahoo.co.uk', '', 'M'),
(142, 'Mary', 'Kabyanga', 'UGANDA', 'MoICT & NG', '256772 902634', 'marykabyanga@yahoo.com', '', 'XXL'),
(143, 'Fred', 'Andema', 'UGANDA', 'KCCA', '256794 660272', 'fandema@kcca.go.ug', '', 'XXL'),
(144, 'Julius Raymond', 'Kabugo', 'UGANDA', 'KCCA', '256794 660271', 'jkabugo@kcca.go.ug', '', 'XL'),
(145, 'Donny Muganzi', 'Kitabire', 'UGANDA', 'KCCA', '256794 660273', 'dkitabire@kcca.go.ug', '', 'XXL'),
(146, 'Henry Odongo', 'Abunyang Emoit', 'UGANDA', 'KCCA', '256794 660308', 'hodongo@kcca.go.ug', '', 'XXL'),
(147, 'Sarah Elizabeth', 'Nafuna', 'UGANDA', 'KCCA', '256794 660809', 'snafuna@kcca.go.ug', '', 'XL'),
(148, 'Isaac', 'Kyaligonza', 'UGANDA', 'KCCA', '256794 660318', 'ikyaligonza@kcca.go.ug', '', 'M'),
(149, 'James Yonah', 'Odoi', 'UGANDA', 'KCCA', '256 794 660317', 'jodoi@kcca.go.ug', '', 'XL'),
(150, 'Seruwagi', 'Norbert', 'UGANDA', 'KCCA', '257 794 660798', 'nseruwagi@kcca.go.ug', '', 'XL'),
(151, 'Elizabeth Nabirye', 'Kamanyire', 'UGANDA', 'KCCA', '256794 660646', 'betikama@live.co.uk', '', 'L'),
(152, 'Alice Peter', 'Zawadi', 'UGANDA', 'KCCA', '256794 660647', 'azawadi@kcca.go.ug', '', 'M'),
(153, 'Joweria', 'Kamariza', 'UGANDA', 'MoLHUD', '256', '', '', ''),
(154, 'Leonard L', 'Sittankya', 'UGANDA', 'MoLHUD', '256', '', '', ''),
(155, 'Elizabeth', 'Nabongo', 'UGANDA', 'MoLHUD', '256', '', '', ''),
(156, 'Francis ', 'Kaggwa', 'UGANDA', 'URSB', '256', '', '', ''),
(157, 'Alex ', 'Anganya', 'UGANDA', 'URSB', '256', '', '', ''),
(158, 'Adeline', 'Kushemererwa', 'UGANDA', 'URSB', '256', '', '', ''),
(159, 'Washington Steven', 'Musamali', 'UGANDA', 'Uganda Land Commission', '256782 625607', 'wmusamali@yahoo.com', '', 'M'),
(160, 'Irene', 'Rukundo', 'UGANDA', 'Uganda Blood Transfusion Services', '256', '', '', ''),
(161, 'Beatrice', 'Aol', 'UGANDA', 'Uganda Blood Transfusion Services', '256', '', '', ''),
(162, 'Noah Deogratius ', 'Luwalira', 'UGANDA', 'Atomic Energy Council', '256772 449848', 'noahdeo@yahoo.com', '', 'XL'),
(163, 'Caroline Alowo', 'Mukalazi', 'UGANDA', 'Atomic Energy Council', '256712 844724', 'calowo@atomiccouncil.go.ug', '', 'L'),
(164, 'Geoffrey', 'Muhanguzi', 'UGANDA', 'Atomic Energy Council', '256703 192926', 'geoffmuhanguzi@gmail.com', '', 'L'),
(165, 'Rachel Dilly', 'Mutesi', 'UGANDA', 'Atomic Energy Council', '256771 871686', 'tesidilly@gmail.com', '', 'M'),
(166, 'Louise Ssegwanyi', 'Naggirinya', 'UGANDA', 'Judiciary', '256752 998716', 'lnaggirinya@yahoo.com', '', 'XXL'),
(167, 'Toto', 'Adrisi', 'UGANDA', 'Judiciary', '256772 355044', 'totoadrisi@yahoo.com', '', 'L'),
(168, 'Norman', 'Bbosa', 'UGANDA', 'Judiciary', '256782 438558', 'normanbbosa@yahoo.com', '', 'L'),
(169, 'Mariam Kizza', 'Okonye', 'UGANDA', 'Judiciary', '256772 486356', 'okonyemariam@yahoo.com', '', 'L'),
(170, 'Felix', 'Abunyang', 'UGANDA', 'ESC', '256772 525589', 'felixabn2006@yahoo.com', 'Paid', 'XL'),
(171, 'Guna Anthony ', 'Ogwang', 'UGANDA', 'MTWA', '256776 873486', 'ogwanganthony@gmail.com', '', 'XXXXL'),
(172, 'David ', 'Tumwesigye', 'UGANDA', 'MTWA', '256772 405338', 'scout70@yahoo.com', '', 'XL'),
(173, 'Rogers S H M', 'Kyewalabye', 'UGANDA', 'MTWA', '256772 438151', 'rogerkye@gmail.com', '', 'XL'),
(174, 'Thomas', 'Ongom', 'UGANDA', 'MTWA', '256783 841895', 'tomongom@gmail.com', '', 'XL'),
(175, 'Belly Sam', 'Okwir', 'UGANDA', 'Mbale Referral Hospital', '256772 993166', 'bellysam52@yahoo.com', '', 'XL'),
(176, 'Emmanuel', 'Kamukama', 'UGANDA', 'Kabaale Referral Hospital', '256772 416260', 'emmanuel_kamukama@yahoo.com', '', 'L'),
(177, 'Samuel', 'Maedero', 'UGANDA', 'Kabaale Referral Hospital', '256782 612169', 'maederosamuel@yahoo.com', '', 'L'),
(178, 'Proscovia Nabachwa', 'Kintu', 'UGANDA', 'Desert Locust Control Org for East.Africa', '256772 591117', 'proscoviayiga@gmail.com', '', 'L'),
(179, 'Benson ', 'Kigenyi', 'UGANDA', 'MoGLSD', '256', '', '', ''),
(180, 'Emily', 'Birekeyaho', 'UGANDA', 'MoGLSD', '256', '', '', ''),
(181, 'Edward ', 'Turyahebwa', 'UGANDA', 'MoGLSD', '256', '', '', ''),
(182, 'Ainea', 'Muheki', 'UGANDA', 'MoGLSD', '256', '', '', ''),
(183, 'Eriver', 'Mukasa', 'UGANDA', 'MoGLSD', '256', '', '', ''),
(184, 'Dhakaba', 'Kirunda', 'UGANDA', 'MoGLSD', '256', '', '', ''),
(185, 'Betty', 'Atieno', 'UGANDA', 'MoGLSD', '256', '', '', ''),
(186, 'Tadeo', 'Mbaziira', 'UGANDA', 'NAGRC&DB', '256', '', '', ''),
(187, 'Patrick ', 'Mangusho', 'UGANDA', 'NAGRC&DB', '256', '', '', ''),
(188, 'Stephen Naigo', 'Emitu', 'UGANDA', 'UCI', '256', '', '', ''),
(189, 'Paul', 'Musimami', 'UGANDA', 'UCI', '256', '', '', ''),
(190, 'Christine', 'Ojeka', 'UGANDA', 'NDA', '256782 598761', 'ojekacri@yahoo.co.uk', 'Paid', 'XL'),
(191, 'Cyprian', 'Mwesigwa', 'UGANDA', 'NDA', '256772 932905', 'cyprianmwesigwa@gmail.com', 'Paid', 'M'),
(192, 'Martha', 'Ajulong', 'UGANDA', 'DEI', '256', '', '', ''),
(193, 'Harriet', 'Natukunda', 'UGANDA', 'MUBS', '256', '', '', ''),
(194, 'Betty', 'Nabunya', 'UGANDA', 'MUBS', '256', '', '', ''),
(195, 'James', 'Mafabi', 'UGANDA', 'MUBS', '256', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `event_id_series`
--

CREATE TABLE `event_id_series` (
  `id` int(11) NOT NULL,
  `ref` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `event_id_series`
--

INSERT INTO `event_id_series` (`id`, `ref`) VALUES
(1, '2017-08-25 09:44:25'),
(2, '2017-08-25 09:45:18'),
(3, '2017-08-25 09:45:43'),
(4, '2017-08-25 09:46:16'),
(5, '2017-08-25 09:46:51'),
(6, '2017-08-25 09:47:17'),
(7, '2017-09-06 10:38:53'),
(8, '2017-09-06 10:39:15'),
(9, '2017-09-06 10:54:19'),
(10, '2017-09-06 10:55:36'),
(11, '2017-09-06 10:57:22'),
(12, '2017-09-06 11:02:27'),
(13, '2017-09-06 11:02:41'),
(14, '2017-09-06 11:05:43'),
(15, '2017-09-06 11:08:28'),
(16, '2017-09-06 11:08:28'),
(17, '2017-09-06 11:08:50'),
(18, '2017-09-06 11:11:28'),
(19, '2017-09-06 11:16:54'),
(20, '2017-09-06 11:19:50'),
(21, '2017-09-06 11:20:47'),
(22, '2017-09-06 11:24:59'),
(23, '2017-09-06 11:29:12'),
(24, '2017-09-06 11:34:25'),
(25, '2017-09-06 11:39:47'),
(26, '2017-09-06 11:47:42'),
(27, '2017-09-06 11:50:01'),
(28, '2017-09-06 11:50:47'),
(29, '2017-09-06 11:56:15'),
(30, '2017-09-06 11:58:16'),
(31, '2017-09-06 11:59:51'),
(32, '2017-09-06 12:01:42'),
(33, '2017-09-06 12:04:15'),
(34, '2017-09-06 12:09:35'),
(35, '2017-09-06 12:12:46'),
(36, '2017-09-06 12:18:04'),
(37, '2017-09-06 12:25:44'),
(38, '2017-09-06 12:44:35'),
(39, '2017-09-06 13:02:55'),
(40, '2017-09-06 13:11:59'),
(41, '2017-09-06 13:19:07'),
(42, '2017-09-06 13:22:22'),
(43, '2017-09-06 13:52:24'),
(44, '2017-09-06 14:04:55'),
(45, '2017-09-06 14:20:40'),
(46, '2017-09-06 14:31:34'),
(47, '2017-09-06 15:31:28'),
(48, '2017-09-06 16:09:17'),
(49, '2017-09-07 08:54:39'),
(50, '2017-09-07 09:11:50'),
(51, '2017-09-07 12:30:14'),
(52, '2017-09-08 09:30:47'),
(53, '2017-09-08 09:34:36'),
(54, '2017-09-08 09:49:50'),
(55, '2017-09-08 10:21:18'),
(56, '2017-09-08 10:24:31'),
(57, '2017-09-08 13:22:30'),
(58, '2017-09-08 13:22:51'),
(59, '2018-01-27 03:39:02'),
(60, '2018-01-28 12:48:02'),
(61, '2018-02-06 12:37:47'),
(62, '2018-02-06 14:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `examiners_training`
--

CREATE TABLE `examiners_training` (
  `id` int(100) NOT NULL,
  `regno` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `hours` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `examiners_training`
--

INSERT INTO `examiners_training` (`id`, `regno`, `name`, `hours`, `email`) VALUES
(1, NULL, 'Frederick Kibbedi', '7', 'pahumuza@icpau.co.ug'),
(863, '', 'AARON ONGOM', '7', 'ojunguassociates@gmail.com'),
(864, 'FM2408', 'AHIMBISIWE SAMUEL AGABA', '7', 'samagaba@ymail.com'),
(865, 'FM2452', 'AKAMPOMUGISHA PAMELLA', '7', 'pakampomugisha@yahoo.com'),
(866, 'FM1535', 'GAHWERA HENRY', '7', 'willibrordokungi@yahoo.co.uk'),
(867, 'FM2736', 'KANSIIME  MOLLIAN', '7', 'kmollian@yahoo.com'),
(868, ' ', 'KASULE INNOCENT', '7', 'Innocentkasule@yahoo.com'),
(869, 'FM2242', 'KIHEMBO MAKOKO VASTINE', '7', 'prizev@yahoo.com'),
(870, 'FM2395', 'KISAAKYE WINNIE AGNES', '7', 'winniekyeyune@gmail.com'),
(871, 'FM1728', 'KOTECHA BHAVIK', '7', 'BHAVIKKOTECHACPA@GMAIL.COM'),
(872, 'FM963', 'KUGONZA GLORIA', '7', 'gloriakugonza@yahoo.com'),
(873, ' ', 'MASEREKA FAROUK', '7', 'faroukm@knwi.co.ug'),
(874, 'FM2335', 'MPUMWIRE BENJAMIN', '7', 'mbkbenja@gmail.com'),
(875, 'FM295', 'MUGERWA CHARLES GERALD', '7', 'cgmugerwa@gmail.com'),
(876, 'FM1391', 'NAGADDYA MERCY', '7', 'mercyn@jms.co.ug'),
(877, '114200', 'NAKAGGWA OLIVIER', '7', 'olivier.nakaggwa@gmail.com'),
(878, 'FM2738', 'NAMATOVU ASHAR AJROUSH', '7', 'ajasher25@yahoo.com'),
(879, 'FM1797', 'NASSANGA  HALIMA', '7', 'nassanga.halima@gmail.com'),
(880, 'FM291', 'NYOMBI FREDRICK', '7', 'fred.nyombi@enhas.com'),
(881, 'FM2100', 'OJIAMBO WILBERFORCE', '7', 'wojiambo@rhu.or.ug'),
(882, 'AM016', 'OJUNGU EDWIN ALFERD OTILE', '7', 'ojunguassociates@gmail.com'),
(883, 'FM2565', 'OKUNGI WILLIBRORD OKELLO', '7', 'willibrordokungi@yahoo.co.uk'),
(884, 'FM729', 'OKWONGA CHARLES KENNETH', '7', 'charles.okwonga@dakscouriers.com'),
(885, 'FM1495', 'OPIO RICHARD', '7', 'ropio@spring-nutrition.org'),
(886, 'FM2010', 'SSEDEVU YUSUF  BAFILAWALA', '7', 'yousoofss@yahoo.com'),
(887, 'FM1952', 'WAISWA JOHN', '7', 'johnwaiswa10@yahoo.com'),
(888, 'FM2460', 'AHIMBISIBWE  APOLLO', '7', 'ahimbisibweapollo@yahoo.com'),
(889, 'FM779', 'AHIMBISIBWE DENNIS', '7', 'dahimbisibwe@gmail.com'),
(890, 'FM2281', 'AHMED NAZIMA ISMAIL', '7', 'nazima.afzal2014@yahoo.com'),
(891, 'FM754', 'AKETCH PATRICIAH', '7', 'paketch@chau.co.ug'),
(892, 'FM2283', 'AKUZA JOSEPH', '7', 'akuzajk@yahoo.com'),
(893, 'FM2767', 'AMUTUHAIRE  RODGERS', '7', 'amurodgers@gmail.com'),
(894, 'AM006', 'BALIRUNO FRANCIS ROBERT', '7', 'mbs_auditors@hotmail.com'),
(895, 'FM1042', 'BARUZALIRE CHRISTOPHER', '7', 'cbaruzalire@yahoo.com'),
(896, 'FM2705', 'BWIRE JOHN PATRICK', '7', 'bwirejohn52@yahoo.com'),
(897, 'FM 2727', 'DAMBA GRAHAM ALEX', '7', 'dgrahamzug@gmail.com'),
(898, 'Fm2107', 'ELWENYU  JOHN ROBERT', '7', 'j.elwenyu@yahoo.com'),
(899, 'FM1357', 'KATEZA PAUL MUKANGA', '7', 'pkateza@gmail.com'),
(900, 'FM2346', 'KERMU JASINTO', '7', 'jasintoker@yahoo.com'),
(901, 'FM 1765', 'KIGURU DAVID NG\'ANG\'A', '7', 'dkiguru@kiguru.com'),
(902, ' ', 'KIKOOLA ALEX', '7', 'akikoola@ucu.ac.ug'),
(903, 'FM1491', 'LUTWAMA GODFREY', '7', 'glutwama@gmail.com'),
(904, '', 'MASIGA CHARLES MAGENI', '7', 'charles.masiga@fincaug.org'),
(905, 'FM749', 'MATOVU PATRICK KAYISALI', '7', 'mtv_patrick@yahoo.co.uk'),
(906, 'FM509', 'MBONIGABA DIDAS MULEKEZI', '7', 'didacus.mbonigaba@yahoo.com'),
(907, ' ', 'MUKIIBI YUSUF HUSEIN', '7', 'jmukiibi100@gmail.com'),
(908, 'FM993', 'MUSOKE DAVID BULEGA', '7', 'mukubiriza@yahoo.com'),
(909, 'FM655', 'MUSOKE SAMANTHA LOUISE', '7', 'smusoke@mango.org.uk'),
(910, 'fm1864', 'MUTEBI SIRIMANI', '7', 'mutebisiriman@yahoo.com'),
(911, 'FM2406', 'MWEBESA SILVER BOSS', '7', 'msilverboss@gmail.com'),
(912, 'FM1388', 'MWOGEZA GRACE ASIIMWE', '7', 'gracekim2k2@yahoo.com'),
(913, 'FM2812', 'NABAASA HENRY', '7', 'nabaasahenry@ymail.com'),
(914, 'FM1562', 'NAKAMYA MAUREEN', '7', 'mnakamya@newvision.co.ug'),
(915, 'FM1106', 'NALUBEGA CHRISTINE KATWE', '7', 'cnalukatwe@gmail.com'),
(916, 'FM2233', 'NALUMANSI ESTA MUSOKE', '7', 'musoke@accamail.com'),
(917, 'FM2092', 'NALUNGA  MARGARET VERONICA', '7', 'veronica@allianceug.com'),
(918, 'CASH', 'NAMARA  WINIFRED', '7', 'wnamara@newvision.co.ug'),
(919, 'FM1591', 'NAMONO SOLOME', '7', 'salnamono@gmail.com'),
(920, 'FM2673', 'NAMULINDWA LILIAN VICTO', '7', 'lilian.namulindwa@umeme.co.ug'),
(921, 'FM 1041', 'NAMUYIGE MARIAM', '7', 'namuyige@yahoo.com'),
(922, 'FM2816', 'NANKABIRWA LYDIA', '7', 'lnankabirwa@icpau.co.ug'),
(923, 'FM1817', 'NANONO JANE KIGAI', '7', 'jane.nanono@watotochurch.com'),
(924, 'FM 398', 'NKUGWA EDWARD', '7', 'gwanku@yahoo.com'),
(925, 'FM1082', 'OBET\'RE CHARLES TWENY', '7', 'cobetre@uncc.co.ug'),
(926, 'FM2401', 'OKELLO VINCENT', '7', 'katandi@ymail.com'),
(927, 'FM888', 'OMODING CONSTANTINE ODIIRI', '7', 'odiiri@gmail.com'),
(928, 'FM1716', 'OPENDI JOSHUA MULEKE', '7', 'opendijoshua@yahoo.co.uk'),
(929, 'FM2354', 'ORYEMA FRANCIS RHENG-KHEL', '7', 'foryemar@gmail.com'),
(930, '05585', 'OYESIGYE  FRANK TINKIGAMBA', '7', 'oyesigyefrank@gmail.com'),
(931, 'FM851', 'RUHANGAMANYA MERITO', '7', 'mruhangamanya@newvision.co.ug'),
(932, 'FM066', 'SEMPALA -MBUGA  EDWARD  WILLIAM', '7', 'ewsempmbuga@yahoo.com'),
(933, 'FM2607', 'WAFULA ERIC', '7', 'eric.wafula@housingfinance.co.ug'),
(934, 'FM1059', 'WAMALUGU ANTHONY KITONGO', '7', 'wamalugu@gmail.com'),
(935, 'FM493', 'MATOVU APOLLO', '7', 'matovu.apollo@gmail.com'),
(936, 'FM2687', 'NALUGYA IRENE', '7', 'inalugya@ecobank.com'),
(937, 'FM1583', 'KABIITO ROBERT MAJARA', '7', 'rkmajara@gmail.com'),
(938, 'FM1615', 'KATUSIIME MARY', '7', 'maryk@jms.co.ug'),
(939, ' ', 'APUMERI JOHN WILLIAM', '7', 'apumerijw@gmail.com'),
(940, 'FM2707', 'ALETIRU Z. DRAKUA', '7', 'aletiruzibiah@yahoo.com'),
(941, 'FM2374', 'OMAGOR SAMUEL', '7', 'somagor@yahoo.co.uk'),
(942, 'FM1931', 'KIMASWA M. MUSA', '7', 'mkimaswa@ura.go.ug'),
(943, 'FM2287', 'NYAKOOJO PHILIP', '7', 'nyakphillip@gmail.com'),
(944, 'FM1863', 'NAPAGI PASCHAL', '7', 'mpnapagi@yahoo.com'),
(945, 'FM2371', 'MUWANDIIKE MIRIAM', '7', 'mmuwandiike@yahoo.com'),
(946, 'FM981', 'APOYA DEBORAH', '7', 'dbrapy@gmail.com'),
(947, 'FM2608', 'GINGO ROLAND', '7', 'gingoroland@gmail.com'),
(948, 'FM2096', 'KIBIRIGE WILLIAM', '7', 'kwillis2008@yahoo.com'),
(949, 'FM2728', 'OKELLO JAMES', '7', 'jokello43@yahoo.com'),
(950, 'FM076', 'WANDEREMA N. K. JONATHAN', '7', 'jkwanderema.nangai@yahoo.co.uk'),
(951, 'FM2112', 'BARASA PATRICIA', '7', 'patrickbarasatmc@gmail.com'),
(952, 'FM377', 'MPAATA MOHAMMED', '7', 'muhammedmpaata@gmail.com'),
(953, 'FM2522', 'KISEMBO HILLARY', '7', 'hillarykisembo@yahoo.com'),
(954, 'FM1750', 'KAKUNGULU YUNUSU', '7', 'kakunguluy@yahoo.co.uk'),
(955, 'FM788', 'THEMBO YOSIYA', '7', 'yosiyathembo@yahoo.com'),
(956, 'FM314', 'ATWINE SIRAGI', '7', 'raitzco@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `report_group_access`
--

CREATE TABLE `report_group_access` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_report_reports` int(11) UNSIGNED NOT NULL,
  `id_core_groups` int(11) UNSIGNED NOT NULL,
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report_group_access`
--

INSERT INTO `report_group_access` (`id`, `id_report_reports`, `id_core_groups`, `remote_id`, `sync`) VALUES
(1, 2, 1, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `report_input_fields`
--

CREATE TABLE `report_input_fields` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_report_reports` int(11) UNSIGNED NOT NULL,
  `field` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `field_type` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'INPUT' COMMENT 'INPUT, SELECT, MULTISELECT',
  `is_mandatory` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `default_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `placeholder_text` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `help_text` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `select_field_source_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'SQL, LOOKUP',
  `select_field_source` text COLLATE utf8_unicode_ci,
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_output`
--

CREATE TABLE `report_output` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_report_reports` int(11) UNSIGNED NOT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_command` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_reports`
--

CREATE TABLE `report_reports` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_can_run` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `enable_run_now` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `grouping` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `query` text COLLATE utf8_unicode_ci,
  `enable_pivoting` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `pivot_column_name_separator` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' - ',
  `pivot_sum_rows` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `pivot_sum_columns` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `pivot_on_columns` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pivot_label_columns` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pivot_value_columns` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_output_grouping` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `output_grouping_column` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `remote_id` int(11) UNSIGNED DEFAULT NULL,
  `sync` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `report_reports`
--

INSERT INTO `report_reports` (`id`, `name`, `description`, `user_can_run`, `enable_run_now`, `grouping`, `ordering`, `query`, `enable_pivoting`, `pivot_column_name_separator`, `pivot_sum_rows`, `pivot_sum_columns`, `pivot_on_columns`, `pivot_label_columns`, `pivot_value_columns`, `enable_output_grouping`, `output_grouping_column`, `created_by`, `created_at`, `remote_id`, `sync`) VALUES
(1, 'Registrations', 'Registrations', 1, 1, NULL, 0, 'Select * from core_registrations', 0, ' - ', 0, 0, NULL, NULL, NULL, 0, NULL, 'administrator', '2018-02-10 14:02:58', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `card_payments`
--
ALTER TABLE `card_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_captcha`
--
ALTER TABLE `core_captcha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word` (`word`);

--
-- Indexes for table `core_command_queue`
--
ALTER TABLE `core_command_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `command` (`command`),
  ADD KEY `command_ref` (`command_ref`) USING BTREE;

--
-- Indexes for table `core_countries`
--
ALTER TABLE `core_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_groups`
--
ALTER TABLE `core_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_hotels`
--
ALTER TABLE `core_hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_invoices`
--
ALTER TABLE `core_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `remote_id` (`remote_id`);

--
-- Indexes for table `core_lookups`
--
ALTER TABLE `core_lookups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grouping` (`grouping`),
  ADD KEY `parent_grouping` (`parent_grouping`),
  ADD KEY `parent_value` (`parent_value`) USING BTREE;

--
-- Indexes for table `core_registrations`
--
ALTER TABLE `core_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_code` (`registration_code`),
  ADD UNIQUE KEY `update_registration_code` (`update_registration_code`),
  ADD UNIQUE KEY `remote_id` (`remote_id`),
  ADD KEY `invoice_code` (`invoice_code`),
  ADD KEY `member_number` (`member_number`);

--
-- Indexes for table `core_sessions`
--
ALTER TABLE `core_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_session_attendees`
--
ALTER TABLE `core_session_attendees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_core_registrations` (`id_core_registrations`,`id_core_sessions`),
  ADD KEY `id_core_sessions` (`id_core_sessions`);

--
-- Indexes for table `core_users`
--
ALTER TABLE `core_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_user_groups`
--
ALTER TABLE `core_user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_core_users_2` (`id_core_users`,`id_core_groups`),
  ADD KEY `id_core_users` (`id_core_users`),
  ADD KEY `id_core_groups` (`id_core_groups`);

--
-- Indexes for table `essag_reg`
--
ALTER TABLE `essag_reg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_id_series`
--
ALTER TABLE `event_id_series`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `examiners_training`
--
ALTER TABLE `examiners_training`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_group_access`
--
ALTER TABLE `report_group_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_report_reports` (`id_report_reports`),
  ADD KEY `id_core_groups` (`id_core_groups`);

--
-- Indexes for table `report_input_fields`
--
ALTER TABLE `report_input_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_report_reports_2` (`id_report_reports`,`field`),
  ADD KEY `id_report_reports` (`id_report_reports`);

--
-- Indexes for table `report_output`
--
ALTER TABLE `report_output`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_report_reports` (`id_report_reports`),
  ADD KEY `ref_command` (`ref_command`);

--
-- Indexes for table `report_reports`
--
ALTER TABLE `report_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `card_payments`
--
ALTER TABLE `card_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_captcha`
--
ALTER TABLE `core_captcha`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `core_command_queue`
--
ALTER TABLE `core_command_queue`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_countries`
--
ALTER TABLE `core_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;
--
-- AUTO_INCREMENT for table `core_groups`
--
ALTER TABLE `core_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `core_hotels`
--
ALTER TABLE `core_hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `core_invoices`
--
ALTER TABLE `core_invoices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_lookups`
--
ALTER TABLE `core_lookups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `core_registrations`
--
ALTER TABLE `core_registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;
--
-- AUTO_INCREMENT for table `core_sessions`
--
ALTER TABLE `core_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `core_session_attendees`
--
ALTER TABLE `core_session_attendees`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_users`
--
ALTER TABLE `core_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `core_user_groups`
--
ALTER TABLE `core_user_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
--
-- AUTO_INCREMENT for table `essag_reg`
--
ALTER TABLE `essag_reg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;
--
-- AUTO_INCREMENT for table `event_id_series`
--
ALTER TABLE `event_id_series`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `examiners_training`
--
ALTER TABLE `examiners_training`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=957;
--
-- AUTO_INCREMENT for table `report_group_access`
--
ALTER TABLE `report_group_access`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `report_input_fields`
--
ALTER TABLE `report_input_fields`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `report_output`
--
ALTER TABLE `report_output`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `report_reports`
--
ALTER TABLE `report_reports`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
