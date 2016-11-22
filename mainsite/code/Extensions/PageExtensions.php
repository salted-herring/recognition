<?php
	/**
	 * @file PageExtensions.php
	 * ------------------------
	 * Generic extensions to page controller.
	 * */
	class PageControllerDecorator extends Extension {

		/**
		 * Load & combine css.
		 * */
		public function getCSS() {
			$css = strtolower($this->owner->data()->class);
			$themeFolder = $this->owner->ThemeDir();
			$files = array(
				$themeFolder . '/css/styles.css'
			);

			if(file_exists(BASE_PATH . '/' . $this->owner->ThemeDir() . '/css/pagetypes/' . $css . '.css')) {
				$files[] = $themeFolder . '/css/pagetypes/' . $css . '.css';
			}

			/**
			 * No longer using requirements in live mode - there are issues with the css paths.
			 * */

			$outputFiles = array();

			foreach ($files as $file) {
				$outputFiles[] = sprintf('<link rel="stylesheet" type="text/css" href="%s" />', $file);
			}

			return implode("\n\t\t", $outputFiles);
		}

		public function getGACode() {
			$GA = SiteConfig::current_site_config()->GoogleAnalyticsCode;

			if($GA) {
				return $GA;
			}
			return false;
		}

		public function getSiteVersion() {
			$version = SiteConfig::current_site_config()->SiteVersion;

			if($version) {
				return $version;
			}
			return false;
		}

		public function getCustomGACode() {
			$custom = SiteConfig::current_site_config()->GoogleCustomCode;

			if($custom) {
				return $custom;
			}
			return false;
		}

		public function isMobile() {
			$mobi = new Mobile_Detect();
			return $mobi->isMobile();
		}

		/**
		 * Retrieve all Open graph & twitter tags:
		 * - If the page has the tags, show them.
		 * - If not then get the site config defaults.
		 * */
		public function getSocialTags() {
			$siteConfig = SiteConfig::current_site_config();
			$data = array();

			foreach(array('TitterCard', 'TwitterTitle', 'TwitterDescription') as $tw) {
				if ($this->owner->$tw) {
					$data[$tw] = $this->owner->$tw;
				} else {
					if ($siteConfig->$tw) {
						$data[$tw] = $siteConfig->$tw;
					}
				}
			}

			if ($this->owner->TwitterImage()->Cropped()->exists() || $siteConfig->TwitterImage()->Cropped()->exists()) {
				if ($this->owner->TwitterImage()->Cropped()->exists()) {
					$data['TwitterImage'] = $this->owner->TwitterImage()->Cropped()->AbsoluteURL;
				} elseif ($siteConfig->TwitterImage()->Cropped()->exists()) {
					$data['TwitterImage'] = $siteConfig->TwitterImage()->Cropped()->AbsoluteURL;
				}
			}

			foreach(array('OGType', 'OGTitle', 'OGDescription') as $tw) {
				if ($this->owner->$tw) {
					$data[$tw] = $this->owner->$tw;
				} else {
					if ($siteConfig->$tw) {
						$data[$tw] = $siteConfig->$tw;
					}
				}
			}

			if ($this->owner->OGImage()->Cropped()->exists() || $siteConfig->OGImage()->Cropped()->exists()) {
				if ($this->owner->OGImage()->Cropped()->exists()) {
					$data['OGImage'] = $this->owner->OGImage()->Cropped()->AbsoluteURL;
				} elseif ($siteConfig->OGImage()->Cropped()->exists()) {
					$data['OGImage'] = $siteConfig->OGImage()->Cropped()->AbsoluteURL;
				}
			}

			return Controller::curr()->customise(new ArrayData($data))->renderWith("OG");
		}
	}
