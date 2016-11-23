<?php
use SaltedHerring\Utilities as Utilities;
use SaltedHerring\Debugger as Debugger;
class App extends Page
{

	private static $db = array(
        'JsPath'        =>  'Text',
        'TemplateName'  =>  'Varchar(64)'
	);

	private static $has_one = array(

	);

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Content');
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextareaField::create(
                    'JsPath'
                ),
                TextField::create(
                    'TemplateName'
                )
            )
        );
        return $fields;
    }


}

class App_Controller extends Page_Controller
{
    public function init()
    {
        parent::init();
        if (!empty($this->JsPath)) {
            $jsfiles = explode(',', $this->JsPath);
            foreach ($jsfiles as &$jsfile)
            {
                $jsfile = trim($jsfile);
            }
            Requirements::combine_files(
                'scripts.js',
                $jsfiles
            );
        }
    }

    public function index($request)
    {

        return $this->renderWith(array(empty($this->TemplateName) ? 'App' : $this->TemplateName, 'AppBase'));
    }
}
