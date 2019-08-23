<?php

namespace JonoM\BetterNavigator\Extension;

use SilverStripe\CMS\Controllers\SilverStripeNavigator;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Security\LogoutForm;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\Versioned\Versioned;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\Requirements;

class BetterNavigatorExtension extends DataExtension
{

    /**
     * @var bool|null
     */
    private $shouldDisplay = null;

    /**
     * Load requirements in before final render. When the next extension point is called, it's too late.
     * @return void
     */
    public function beforeCallActionHandler()
    {
        if ($this->shouldDisplay()) {
            Requirements::javascript('jonom/silverstripe-betternavigator: javascript/betternavigator.js');
            Requirements::css('jonom/silverstripe-betternavigator: css/betternavigator.css');
        }
    }

    /**
     * @param $request
     * @param $action
     * @param DBHTMLText $result
     * @return DBHTMLText
     */
    public function afterCallActionHandler($request, $action, $result)
    {
        if (!$this->shouldDisplay()) {
            return $result;
        }

        $html = $result->getValue();
        $navigatorHTML = $this->generateNavigator()->getValue();

        // Inject the NavigatorHTML before the closing </body> tag
        $html = preg_replace(
            '/(<\/body[^>]*>)/i',
            $navigatorHTML . '\\1',
            $html
        );
        $result->setValue($html);

        return $result;
    }

    /**
     * Override on a per-controller basis to add custom logic
     * @return bool
     */
    public function showBetterNavigator()
    {
        return true;
    }

    /**
     * Provides a front-end utility menu with administrative functions and developer tools
     * Relies on SilverStripeNavigator
     *
     * @return DBHTMLText
     */
    private function generateNavigator()
    {
        // Get SilverStripeNavigator links & stage info (CMS/Stage/Live/Archive)
        $nav = [];
        $viewing = '';
        $navigator = SilverStripeNavigator::create($this->owner->dataRecord);
        $isDev = Director::isDev();

        $items = $navigator->getItems();
        foreach ($items as $item) {
            $name = $item->getName();
            $active = $item->isActive();
            $nav[$name] = [
                'Link' => $item->getLink(),
                'Active' => $active
            ];
            if ($active) {
                if ($name == 'LiveLink') $viewing = 'Live';
                if ($name == 'StageLink') $viewing = 'Draft';
                if ($name == 'ArchiveLink') $viewing = 'Archived';
            }
        }
        // Only show edit link if user has permission to edit this page
        $editLink = array_key_exists('CMSLink', $nav)
        && ($isDev || $this->owner->dataRecord->canEdit() && Permission::check('CMS_ACCESS_CMSMain'))
            ? $nav['CMSLink']['Link'] : false;

        // Is the logged in member nominated as a developer?
        $member = Member::currentUser();
        $devs = Config::inst()->get('BetterNavigator', 'developers');
        $identifierField = Member::config()->unique_identifier_field;
        $isDeveloper = $member && is_array($devs) ? in_array($member->{$identifierField}, $devs) : false;

        // Add other data for template
        $backURL = '?BackURL=' . urlencode($this->owner->Link());
        $logoutForm = LogoutForm::create($this->owner)->setName('BetterNavigatorLogoutForm');
        $logoutForm->Fields()->fieldByName('BackURL')->setValue($this->owner->Link());
        $bNData = array_merge($nav, [
            'Member' => $member,
            'Stage' => Versioned::get_stage(),
            'Viewing' => $viewing, // What we're viewing doesn't necessarily align with the active Stage
            'LoginLink' => Controller::join_links(Director::absoluteBaseURL(), Security::config()->login_url, $backURL),
            'LogoutLink' => Controller::join_links(Director::absoluteBaseURL() . Security::config()->logout_url, $backURL),
            'LogoutForm' => $logoutForm,
            'EditLink' => $editLink,
            'Mode' => Director::get_environment_type(),
            'IsDeveloper' => $isDeveloper
        ]);

        // Merge with page data, send to template and render
        $navigator = new ArrayData($bNData);

        return $this->owner->customise($navigator)->renderWith('BetterNavigator\\BetterNavigator');
    }

    /**
     * Internally compute and cache weather the navigator should display
     * @return bool
     */
    private function shouldDisplay()
    {
        if ($this->shouldDisplay !== null) {
            return $this->shouldDisplay;
        }

        // Make sure this is a page
        if (!$this->isAPage() || !$this->owner->showBetterNavigator()) {
            return $this->shouldDisplay = false;
        }

        // Only show navigator to appropriate users
        $isDev = Director::isDev();
        $canViewDraft = (Permission::check('VIEW_DRAFT_CONTENT') || Permission::check('CMS_ACCESS_CMSMain'));

        return $this->shouldDisplay = ($isDev || $canViewDraft);
    }

    /**
     * @return boolean
     */
    private function isAPage()
    {
        return $this->owner
            && $this->owner->dataRecord
            && $this->owner->dataRecord instanceof SiteTree
            && $this->owner->dataRecord->ID > 0;
    }
}
