<?php

/**
 * @file OpenGraph
 *
 * Extension to provide Open Graph tags to page types.
 */
class OpenGraphExtension extends DataExtension {
	private static $db = array(
		'OGType'			=> 'Enum("website,article,blog,product")',
		'OGTitle' 			=> 'Varchar(255)',
		'OGDescription'		=> 'Varchar(255)',
	);

	private static $has_one =  array(
		'OGImage' 			=> 'Image'
	);


	public function updateCMSFields(FieldList $fields) {
		$baseClass = ClassInfo::baseDataClass($this->owner->class);

		$title = new TextField('OGTitle', 'Title');

		// If extending a page, disable access to the title - the page title will suffice.
		if ($baseClass == 'SiteTree') {
			$title = $title->performReadonlyTransformation();
		}

		$og = ToggleCompositeField::create(
			'OG',
			new LabelField('Open', 'Open Graph Tags'),
			array(
				new DropdownField('OGType', 'Type',
					singleton($this->owner->ClassName)->dbObject('OGType')->enumValues()
				),
				$title,
				new TextareaField('OGDescription', 'Description'),
				$OGImage = SaltedUploader::create('OGImage', 'Image')->setCropperRatio(470/246)
			)
		);

		$fields->removeFieldsFromTab('Root.Main', array(
			'OGTitle',
			'OGTitle',
			'OGDescription'
		));

		$OGImage->setDescription('Image must be at least 1200px x 630px.');
		$fields->addFieldToTab('Root.Social', $og);
	}
}
