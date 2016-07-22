<?php

class BetterNavigatorExtension extends DataExtension {

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
            // Get SilverStripeNavigator links & stage info (CMS/Stage/Live/Archive)
            $nav = array();
            $viewing = '';
            $navigator = new SilverStripeNavigator($this->owner->dataRecord);
            $items = $navigator->getItems();
            foreach($items as $item) {
                $name = $item->getName();
                $active = $item->isActive();
                $nav[$name] = array(
                    'Link' => $item->getLink(),
                    'Active' => $active
                );
                if ($active) {
                    if ($name == 'LiveLink') $viewing = 'Live';
                    if ($name == 'StageLink') $viewing = 'Draft';
                    if ($name == 'ArchiveLink') $viewing = 'Archived';
                }
            }
            // Only show edit link if user has permission to edit this page
            $editLink = (($this->owner->dataRecord->canEdit() && Permission::check('CMS_ACCESS_CMSMain')) || $isDev) ? Controller::join_links('/', $nav['CMSLink']['Link']) : false;

            // Is the logged in member nominated as a developer?
            $member = Member::currentUser();
            $devs = Config::inst()->get('BetterNavigator', 'developers');
            $identifierField = Member::config()->unique_identifier_field;
            $isDeveloper = $member && is_array($devs) ? in_array($member->{$identifierField}, $devs) : false;

            // Add other data for template
            $backURL = '?BackURL=' . urlencode($this->owner->Link());
            $bNData = array_merge($nav, array(
                'Member' => $member,
                'Stage' => Versioned::current_stage(),
                'Viewing' => $viewing, // What we're viewing doesn't necessarily align with the active Stage
                'LoginLink' => Controller::join_links('/', Config::inst()->get('Security', 'login_url'), $backURL),
                'LogoutLink' => Controller::join_links('/Security/logout', $backURL),
                'EditLink' => $editLink,
                'Mode' => Director::get_environment_type(),
                'IsDeveloper' => $isDeveloper
            ));

            // Merge with page data, send to template and render
            $bNData = new ArrayData($bNData);
            $page = $this->owner->customise(array('BetterNavigator' => $bNData));
            return $page->renderWith('BetterNavigator');
        }
        return false;
    }
}
