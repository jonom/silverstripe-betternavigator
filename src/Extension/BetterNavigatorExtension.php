<?php

namespace JonoM\BetterNavigator\Extension;

use SilverStripe\CMS\Controllers\SilverStripeNavigator;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\ORM\DataExtension;
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
     * @param $request
     * @param $action
     * @param DBHTMLText|HTTPResponse  $result (actually could be anything such as a string, controller or object)
     * @return DBHTMLText|HTTPResponse
     */
    public function afterCallActionHandler($request, $action, $result)
    {
        // Known issue: if $result is a Controller then BetterNavigator won't be rendered.
        // See https://github.com/jonom/silverstripe-betternavigator/issues/47#issuecomment-682120739

        // Check that we're dealing with HTML
        $isHtmlResponse = $result instanceof DBHTMLText ||
            $result instanceof HTTPResponse && strpos($result->getHeader('content-type'), 'text/html') !== false;

        if (!$isHtmlResponse || !$this->shouldDisplay()) {
            return $result;
        }

        $html = $result instanceof DBHTMLText ? $result->getValue() : $result->getBody();
        $navigatorHTML = $this->generateNavigator()->getValue();

        // Inject the NavigatorHTML before the closing </body> tag
        $html = preg_replace(
            '/(<\/body[^>]*>)/i',
            $navigatorHTML . '\\1',
            $html
        );
        if ($result instanceof DBHTMLText) {
            $result->setValue($html);
        } else {
            $result->setBody($html);
        }

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
        $viewingTitle = '';
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
                if ($name == 'LiveLink') {
                    $viewing = 'Live';
                    $viewingTitle = _t('JonoM\BetterNavigator.VIEWING_LIVE', 'Live');
                }
                else if ($name == 'StageLink') {
                    $viewing = 'Draft';
                    $viewingTitle = _t('JonoM\BetterNavigator.VIEWING_DRAFT', 'Draft');
                }
                else if ($name == 'ArchiveLink') {
                    $viewing = 'Archived';
                    $viewingTitle = _t('JonoM\BetterNavigator.VIEWING_ARCHIVED', 'Archived');
                }
            }
        }
        
        // Only show edit link if user has CMS access
        $editLink = null;
        if($isDev || Permission::check('CMS_ACCESS_CMSMain')) {
            // Check for edit link override, e.g. for a DataObject
            if(method_exists($this->owner, 'BetterNavigatorEditLink')) {
                $editLink = $this->owner->BetterNavigatorEditLink();
            } else {
                // Only show edit link if user has permission to edit this page
                $editLink = array_key_exists('CMSLink', $nav)
                && ($isDev || $this->owner->dataRecord->canEdit())
                ? $nav['CMSLink']['Link'] : false;
            }
        }

        // Is the logged in member nominated as a developer?
        $member = Member::currentUser();
        $devs = Config::inst()->get('BetterNavigator', 'developers');
        $identifierField = Member::config()->unique_identifier_field;
        $isDeveloper = $member && is_array($devs) ? in_array($member->{$identifierField}, $devs) : false;

        // Add other data for template
        $backURL = '?BackURL=' . urlencode($this->owner->Link());
        $logoutForm = LogoutForm::create($this->owner)->setName('BetterNavigatorLogoutForm');
        $logoutForm->Fields()->fieldByName('BackURL')->setValue($this->owner->Link());
        $bnModule = ModuleLoader::getModule('jonom/silverstripe-betternavigator');
        $bNData = array_merge($nav, [
            'Member' => $member,
            'Stage' => Versioned::get_stage(),
            'Viewing' => $viewing, // What we're viewing doesn't necessarily align with the active Stage
            'CssClass' => $this->BetterNavigatorCssClass(),
            'ViewingTitle' => $viewingTitle,
            'LoginLink' => Controller::join_links(Director::absoluteBaseURL(), Security::config()->login_url, $backURL),
            'LogoutLink' => Controller::join_links(Director::absoluteBaseURL() . Security::config()->logout_url, $backURL),
            'LogoutForm' => $logoutForm,
            'EditLink' => $editLink,
            'Mode' => Director::get_environment_type(),
            'IsDeveloper' => $isDeveloper,
            'ScriptUrl' => $bnModule->getResource('javascript/betternavigator.js')->getURL(),
            'CssUrl' => $bnModule->getResource('css/betternavigator.css')->getURL(),
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


    /**
     * @return string
     */
    private function BetterNavigatorCssClass() {
        $classes = '';

        $position = Config::inst()->get('BetterNavigator', 'position');
        $translucent = Config::inst()->get('BetterNavigator', 'translucent');

        if ($position === 'left-top' || $position === 'left-bottom' || $position === 'right-bottom') {
            $classes .= ' ' . $position;
        } else {
            $classes .= ' right-top';
        }

        if ($translucent && $translucent == 'true') {
            $classes .= ' translucent';
        }

        return $classes;
    }
}
