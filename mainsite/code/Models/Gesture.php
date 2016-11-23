<?php use SaltedHerring\Debugger as Debugger;

class Gesture extends DataObject
{
    protected static $db = array(
        'Title'     =>  'Varchar(256)'
    );

    protected static $has_many = array(
        'Vectors'    =>  'Vector'
    );

    protected static $extensions = array(
        'ApisedExt'
    );

    protected static $default_sort = array(
        'Title'     =>  'ASC',
        'ID'        =>  'DESC'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        if ($this->exists()) {
            $fields->addFieldToTab(
                'Root.Main',
                TextareaField::create(
                    'StringifiedPoints',
                    'Points'
                )
            );
        }

        return $fields;
    }

    public function formatVectors()
    {
        return $this->Vectors()->format();
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if ($this->exists()) {
            $import = Controller::curr()->request->postVar('StringifiedPoints');
            if (!empty($import)) {
                $imports = explode('),', $import);
                $vector = new Vector();
                $vector->GestureID = $this->ID;
                $vector->write();
                foreach ($imports as &$item)
                {
                    $item = str_replace(')', '', str_replace('new Point(', '', $item));
                    $xy = explode(',', $item);
                    $point = new Point();
                    $point->x = trim($xy[0]);
                    $point->y = trim($xy[1]);
                    $point->VectorID = $vector->ID;
                    $point->write();
                }
            }
        }
    }
}
