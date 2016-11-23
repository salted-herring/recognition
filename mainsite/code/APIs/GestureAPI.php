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
