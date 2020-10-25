# phpMyAdmin MySQL-Dump
# version 2.4.0
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Jan 04, 2004 at 04:24 PM
# Server version: 3.23.54
# PHP Version: 4.2.2
# Database : `frlxs`
# --------------------------------------------------------

#
# Table structure for table `freecontent`
#

DROP TABLE IF EXISTS freecontent;
CREATE TABLE freecontent (
    id      INT(5) UNSIGNED      NOT NULL AUTO_INCREMENT,
    title   VARCHAR(150)         NOT NULL DEFAULT '',
    type    TINYINT(10) UNSIGNED NOT NULL DEFAULT '0',
    design  TINYINT(4) UNSIGNED  NOT NULL DEFAULT '0',
    hide    TINYINT(4) UNSIGNED  NOT NULL DEFAULT '0',
    adress  VARCHAR(255)                  DEFAULT NULL,
    comment VARCHAR(255)                  DEFAULT NULL,
    special VARCHAR(255)                  DEFAULT NULL,
    hits    INT(6) UNSIGNED      NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
)
    ENGINE = ISAM;

#
# Dumping data for table `freecontent`
#

INSERT INTO freecontent
VALUES (211, 'Le Monde (French)', 1, 1, 1, 'http://www.lemonde.fr', 'Le journal Le Monde', NULL, 0),
       (212, 'Liberation (Francais)', 1, 0, 0, 'http://www.liberation.fr', 'Liberation Journal', NULL, 0),
       (214, 'China Udn News', 1, 0, 0, 'http://udn.com/NEWS/SITEMAP_TITLE/TOPIC_Title50.shtml', 'China Times', NULL, 0),
       (215, 'wjue (Chinese) Blog', 1, 2, 0, 'http://www.wjue.org', 'My Chinese Blog', NULL, 10),
       (216, 'Google Top Stories', 1, 0, 0, 'http://news.google.com', 'From Google News', NULL, 0),
       (217, 'News about China', 1, 0, 0, 'http://news.google.com/news?hl=en&edition=us&q=china+-xinhua&btnG=Search+News', 'From Google News', NULL, 0),
       (218, 'Yahoo News', 1, 3, 0, 'http://news.yahoo.com', 'News From Yahoo', NULL, 0),
       (219, 'CNN International', 1, 0, 0, 'http://edition.cnn.com/', 'CNN', NULL, 0),
       (220, 'Asahi Shimbun', 1, 0, 0, 'http://www.asahi.com/english/english.html', 'Japan Asahi Shimbun', NULL, 0),
       (221, 'Korea Herald', 1, 4, 0, 'http://www.koreaherald.co.kr/index.asp', 'Korea Herald English', NULL, 0),
       (222, 'WashingPost', 1, 0, 0, 'http://www.washingpost.com/', 'WashingPost', NULL, 0),
       (223, 'The New York Times', 1, 6, 0, 'http://www.nytimes.com/', 'The New York Times', NULL, 0),
       (224, 'Offshore Outsourcing', 1, 0, 0, 'http://news.google.com/news?hl=en&edition=us&num=100&q=offshore+outsourcing&btnG=Search+News&scoring=d', 'Outsourcing latest news', NULL, 0),
       (225, 'Herald Tribune', 1, 3, 0, 'http://www.iht.com/frontpage.html', 'Herald Tribune', NULL, 0),
       (226, 'HiTech Science', 1, 7, 0, 'http://news.google.com/news/en/us/technology.html', 'Technology & Science', NULL, 0),
       (227, 'Washington Times', 1, 0, 0, 'http://washingtontimes.com/', 'Washington Times', NULL, 0),
       (228, 'Muslim Al-Jazeerah', 1, 4, 0, 'http://www.aljazeerah.info/index.htm', 'Al-Jazeerah Info', NULL, 0),
       (229, 'StraitsTimes', 1, 1, 0, 'http://straitstimes.asia1.com.sg/', 'StraitsTimes Singapore', NULL, 0),
       (230, 'Financial Times', 1, 1, 0, 'http://news.ft.com/home/asia/', 'Financial Times', NULL, 0),
       (231, 'Entertainment', 1, 7, 0, 'http://news.google.com/news/en/uk/entertainment.html', 'Entertainment in Europe', NULL, 0),
       (232, 'Business in French', 1, 7, 0, 'http://news.google.com/news/fr/fr/business.html', 'Economie en Francais', NULL, 0),
       (233, 'Deutschland', 1, 7, 0, 'http://news.google.com/news/de/de/nation.html', 'News Deutschland', NULL, 0);
