<?php

class BetterNavigatorExtension extends DataExtension {

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
     * Force use of absolute links starting with a slash for sites not using base tag
     * Example YAML:
     *
     * BetterNavigator:
     *   useAbsoluteLinks: true
     *
     * @config
     * @var boolean
     */
    private static $useAbsoluteLinks = false;

    /**
     * Provides a front-end utility menu with administrative functions and developer tools
     * Relies on SilverStripeNavigator
     *
     * @return string
     */
    public function BetterNavigator() {
        // Make sure this is a page
        if (!($this->owner && $this->owner->dataRecord && $this->owner->dataRecord instanceof SiteTree && $this->owner->dataRecord->ID > 0)) return false;

        // Only show navigator to appropriate users
        $isDev = Director::isDev();
        $canViewDraft = (Permission::check('VIEW_DRAFT_CONTENT') || Permission::check('CMS_ACCESS_CMSMain'));
        if($isDev || $canViewDraft) {
            // use absolute links
            $useAbsLinks = Config::inst()->get('BetterNavigator', 'useAbsoluteLinks');
            $linkPrefix = ($useAbsLinks ? '/' : '');

            // Get SilverStripeNavigator links & stage info (CMS/Stage/Live/Archive)
            $nav = array();
            $viewing = '';
            $navigator = new SilverStripeNavigator($this->owner->dataRecord);
            $items = $navigator->getItems();
            foreach($items as $item) {
                $name = $item->getName();
                $active = $item->isActive();
                $nav[$name] = array(
                    'Link' => (($name == 'CMSLink') ? $linkPrefix : '') . $item->getLink(),
                    'Active' => $active,
                );
                if ($active) {
                    if ($name == 'LiveLink') $viewing = 'Live';
                    if ($name == 'StageLink') $viewing = 'Draft';
                    if ($name == 'ArchiveLink') $viewing = 'Archived';
                }
            }
            // Only show edit link if user has permission to edit this page
            $editLink = (($this->owner->dataRecord->canEdit() && Permission::check('CMS_ACCESS_CMSMain')) || $isDev) ? $nav['CMSLink']['Link'] : false;

            // Is the logged in member nominated as a developer?
            $member = Member::currentUser();
            $devs = Config::inst()->get('BetterNavigator', 'developers');
            $isDeveloper = $member && is_array($devs) ? in_array($member->Email, $devs) : false;

            // Add other data for template
            $backURL = '?BackURL=' . urlencode($this->owner->Link());
            $bNData = array_merge($nav, array(
                'Member' => $member,
                'Stage' => Versioned::current_stage(),
                'Viewing' => $viewing, // What we're viewing doesn't necessarily align with the active Stage
                'LoginLink' => $linkPrefix . Config::inst()->get('Security', 'login_url') . $backURL,
                'LogoutLink' => $linkPrefix . 'Security/logout' . $backURL,
                'EditLink' => $editLink,
                'Mode' => Director::get_environment_type(),
                'IsDeveloper' => $isDeveloper,
                'LinkPrefix' => $linkPrefix,
            ));

            // Merge with page data, send to template and render
            $bNData = new ArrayData($bNData);
            $page = $this->owner->customise(array('BetterNavigator' => $bNData));
            return $page->renderWith('BetterNavigator');
        }
        return false;
    }
}
