DROP TABLE IF EXISTS p4_Feeds;
DROP TABLE IF EXISTS p4_Categories;

CREATE TABLE `p4_Categories` (
  `ID` int(255) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Category` varchar(255) COLLATE 'utf8_general_ci' NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_general_ci';

CREATE TABLE `p4_Feeds` (
  `ID` int(255) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `CategoryID` int(255) unsigned NOT NULL,
  `Title` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `URL` varchar(255) COLLATE 'utf16_general_ci' NOT NULL,
  `LastUpdated` datetime(6) NOT NULL,
  FOREIGN KEY (`CategoryID`) REFERENCES `p4_Categories` (`ID`)
) ENGINE='InnoDB';

INSERT INTO `p4_Categories` (`Category`) VALUES ('Technology');
INSERT INTO `p4_Categories` (`Category`) VALUES ('Seattle Sports');
INSERT INTO `p4_Categories` (`Category`) VALUES ('Politics');

INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('1', 'Robotics', 'https://news.google.com/news/rss/search/section/q/robotics/robotics?hl=en&gl=US&ned=us', now());
INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('1', 'Computer Science', 'https://news.google.com/news/rss/search/section/q/computer%20science/computer%20science?hl=en&gl=US&ned=us', now());
INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('1', 'Mobile Phones', 'https://news.google.com/news/rss/search/section/q/mobile%20phones/mobile%20phones?hl=en&gl=US&ned=us', now());
INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('2', 'Seahawks', 'https://news.google.com/news/rss/search/section/q/seahawks/seahawks?hl=en&gl=US&ned=us', now());
INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('2', 'Mariners', 'https://news.google.com/news/rss/search/section/q/seattle%20mariners/seattle%20mariners?hl=en&gl=US&ned=us', now());
INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('2', 'Sounders', 'https://news.google.com/news/rss/search/section/q/seattle%20sounders/seattle%20sounders?hl=en&gl=US&ned=us', now());
INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('3', 'US Politics', 'https://news.google.com/news/rss/search/section/q/us%20politics/us%20politics?hl=en&gl=US&ned=us', now());
INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('3', 'European Politics', 'https://news.google.com/news/rss/search/section/q/european%20politics/european%20politics?hl=en&gl=US&ned=us', now());
INSERT INTO `p4_Feeds` (`CategoryID`, `Title`, `URL`, `LastUpdated`) VALUES ('3', 'Robotics', 'https://news.google.com/news/rss/search/section/q/middle%20east%20politics/middle%20east%20politics?hl=en&gl=US&ned=us', now());
