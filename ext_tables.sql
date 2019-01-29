#
# Table structure for table 'tt_address'
# add additional fields for newsletter subscribe
#

CREATE TABLE tt_address (
  activation_code varchar(255) DEFAULT '' NOT NULL,
  newsletter_privacy_checked tinyint(3) unsigned DEFAULT '0' NOT NULL,
);