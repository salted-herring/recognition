<?php use SaltedHerring\Debugger as Debugger;

class Point extends DataObject
{
    protected static $db = array(
        'x'         =>  'Int',
        'y'         =>  'Int'
    );

    protected static $default_sort = array(
        'ID'        =>  'ASC'
    );

    protected static $has_one = array(
        'Vector'    =>  'Vector'
    );

    protected static $summary_fields = array(
        'Title'
    );

    protected static $field_labels = array(
        'Title'     =>  'Co-ordinate'
    );

    public function Title()
    {
        return 'x: ' . $this->x . ', y: ' . $this->y;
    }

    public function getTitle()
    {
        return $this->Title();
    }

    public function format()
    {
        return
            array(
                'x' =>  $this->x,
                'y' =>  $this->y
            );
    }

}
