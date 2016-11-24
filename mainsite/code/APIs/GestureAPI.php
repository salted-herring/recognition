<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;

class GestureAPI extends BaseRestController
{

    private static $allowed_actions = array (
		'post'			=>	"->isAuthenticated",
        'get'			=>	"->isAuthenticated"
    );

	public function isAuthenticated()
    {
		return true;
	}

	public function post($request)
    {
        if ($id = $request->param('ID')) {

        } else {
            $title = $request->postVar('Title');
            $points = $request->postVar('Points');

            if (!empty($title) && !empty($points)) {
                $title = trim(strtolower($title));
                $points = json_decode($points);

                $gesture = Gesture::get()->filter(array('Title' => $title))->first();
                if (empty($gesture)) {
                    $gesture = new Gesture();
                    $gesture->Title = $title;
                    $gesture->write();
                }
                
                $vector = new Vector();
                $vector->GestureID = $gesture->ID;
                $vector->write();
                foreach ($points as $xy)
                {
                    $point = new Point();
                    $point->x = $xy->x;
                    $point->y = $xy->y;
                    $point->VectorID = $vector->ID;
                    $point->write();
                }

                return $gesture->format(array(
                    'title'     =>  'Title',
                    'vectors'   =>  'formatVectors'
                ));
            }
        }

		return false;
	}

	public function get($request)
    {
        //if id given = retrieving a particular gesture
        //otherwise it pulls the whole list
        if ($id = $request->param('ID')) {

        } else {
            return
                Gesture::get()->format(array(
                    'title'     =>  'Title',
                    'vectors'   =>  'formatVectors'
                ));
        }

		return false;
    }
}
