<?php

class BetterNavigator extends DataExtension {

	/**
	 * Nominate developers who can access developer tools on live site
	 * Example YAML:
	 * 
	 * BetterNavigator:
	 *   developers:
	 *     - 'dev@yoursite.com'
	 *     - 'otherdev@yoursite.com' 
	 * 
	 * @config
	 * @var array
	 */
	private static $developers;

	/**
	 * Provides a front-end utility menu with administrative functions and developer tools
	 * Relies on SilverStripeNavigator
	 * 
	 * @return string
	 */
	public function BetterNavigator() {
		$isDev = Director::isDev();

		if($isDev || Permission::check('CMS_ACCESS_CMSMain') || Permission::check('VIEW_DRAFT_CONTENT')) {			
			if($this->owner && $this->owner->dataRecord && $this->owner->dataRecord instanceof SiteTree) {

				//Get SilverStripeNavigator links & stage info (CMS/Stage/Live/Archive)
				$nav = array();
				$navigator = new SilverStripeNavigator($this->owner->dataRecord);
				$items = $navigator->getItems();
				foreach($items as $item) {
					$nav[$item->getName()] = array(
						'Link' => $item->getLink(),
						'Active' => $item->isActive()
					);
				}
				
				//Is the logged in member nominated as a developer?
				$member = Member::currentUser();
				$devs = Config::inst()->get('BetterNavigator', 'developers');
				$isDeveloper = $member && is_array($devs) ? in_array($member->Email, $devs) : false;
				
				//Add other data for template
				$nav = array_merge($nav, array(
					'Member' => $member,
					'Stage' => Versioned::current_stage(),
					'LoginLink' => Config::inst()->get('Security', 'login_url'),
					'Mode' => Director::get_environment_type(),
					'IsDeveloper' => $isDeveloper
				));
				
				//Merge with page data, send to template and render
				$nav = new ArrayData($nav);
				$page = $this->owner->customise($nav);
				return $page->renderWith('BetterNavigator');
			}
		}
		return false;
	}
}
