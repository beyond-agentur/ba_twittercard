#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_batwittercard_title tinytext,
	tx_batwittercard_type tinytext,
	tx_batwittercard_image text,
	tx_batwittercard_falimages int(11) unsigned DEFAULT '0',
	tx_batwittercard_description tinytext,
	tx_batwittercard_site tinytext,
	tx_batwittercard_creator tinytext
);

#
# Table structure for table 'pages_language_overlay'
#
CREATE TABLE pages_language_overlay (
	tx_batwittercard_title tinytext,
	tx_batwittercard_type tinytext,
	tx_batwittercard_image text,
	tx_batwittercard_falimages int(11) unsigned DEFAULT '0',
	tx_batwittercard_description tinytext,
	tx_batwittercard_site tinytext,
	tx_batwittercard_creator tinytext
);