<?php
class CustomSiteConfig extends DataExtension { 

	public static $db = array(
		'GoogleSiteVerificationCode' => 'Varchar(128)',
		'GoogleAnalyticsCode' => 'Varchar(20)',
		'SiteVersion' => 'Varchar(10)',
		'GoogleCustomCode' => 'HTMLText'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab("Root.Google", new TextField('GoogleSiteVerificationCode', 'Google Site Verification Code'));
		$fields->addFieldToTab("Root.Google", new TextField('GoogleAnalyticsCode', 'Google Analytics Code'));
		$fields->addFieldToTab("Root.Google", new TextareaField('GoogleCustomCode', 'Custom Google Code'));

		$fields->addFieldToTab('Root.Main', new TextField('SiteVersion', 'Site Version'));
	}

}
