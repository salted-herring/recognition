<?php
use SaltedHerring\Utilities as Utilities;
class Page extends SiteTree {

	private static $db = array(
	);

	private static $has_one = array(
	);
}
class Page_Controller extends ContentController {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	private static $allowed_actions = array (
	);

	public function init() {
		parent::init();

		Requirements::combine_files(
			'scripts.js',
			array(
				'themes/default/js/components/jquery/dist/jquery.min.js',
				'themes/default/js/components/gsap/src/minified/TweenMax.min.js',
				'themes/default/js/components/gsap/src/minified/easing/EasePack.min.js',
				'themes/default/js/components/salted-js/dist/salted-js.min.js',
				'themes/default/js/custom.scripts.js'
			)
		);
	}

	protected function getSessionID() {
		return session_id();
	}

	protected function getHTTPProtocol() {
		$protocol = 'http';
		if (isset($_SERVER['SCRIPT_URI']) && substr($_SERVER['SCRIPT_URI'], 0, 5) == 'https') {
			$protocol = 'https';
		} elseif (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
			$protocol = 'https';
		}
		return $protocol;
	}

	protected function getCurrentPageURL() {
		return $this->getHTTPProtocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	public function MetaTags($includeTitle = true) {
		$tags = parent::MetaTags();

		/**
		 * Find title & replace with MetaTitle (if it exists).
		 * */
		$title = '/(\<title\>)(.*)(\<\/title\>)/';
		preg_match($title, $tags, $matches);

		if (count($matches) > 0) {
			if ($this->MetaTitle) {
				$tags = preg_replace($title, '$1' . $this->MetaTitle . '$3', $tags);
			}
		}

		$charset = ContentNegotiator::get_encoding();
		$tags .= "<meta http-equiv=\"Content-type\" content=\"text/html; charset=$charset\" />\n";
		if($this->MetaKeywords) {
			$tags .= "<meta name=\"keywords\" content=\"" . Convert::raw2att($this->MetaKeywords) . "\" />\n";
		}
		if($this->ExtraMeta) {
			$tags .= $this->ExtraMeta . "\n";
		}

		if($this->URLSegment == 'home' && SiteConfig::current_site_config()->GoogleSiteVerificationCode) {
			$tags .= '<meta name="google-site-verification" content="'
					. SiteConfig::current_site_config()->GoogleSiteVerificationCode . '" />\n';
		}

		// prevent bots from spidering the site whilest in dev.
		if(!Director::isLive()) {
			$tags .= "<meta name=\"robots\" content=\"noindex, nofollow, noarchive\" />\n";
		}

		$this->extend('MetaTags', $tags);

		return $tags;
	}

	public function getTheTitle() {
		return Convert::raw2xml(($this->MetaTitle) ? $this->MetaTitle : $this->Title);
	}

	public function getBodyClass() {
		return Utilities::sanitiseClassName($this->singular_name(),'-');
	}
}
