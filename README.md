# BetterNavigator for Silverstripe

![Diagram of module](images/demo.png)

This module is intended to replicate and expand upon the functionality provided by Silverstripe's built-in SilverStripeNavigator class. It provides a handy front-end menu for CMS users which offers these features:

**For Content Authors**

 * Indicates to a user that they are logged in
 * Indicates whether they are viewing draft or live content
 * Quickly edit the page you're viewing

**For Developers**

 * When in Dev Mode links are included for accessing most of Silverstripe's [URL Variable Tools](http://doc.silverstripe.org/framework/en/reference/urlvariabletools)
 * Developers can access these tools on a live website by nominating themselves as a developer in the site config

## Requirements

Silverstripe 5.0 (4.0+ and 3.1+ through previous releases)

## Installation

Add "jonom/silverstripe-betternavigator" to your composer requirements.

```
composer require jonom/silverstripe-betternavigator
```

## Upgrading

* **6.0:** the namespace for this module's templates and configuration was changed in v6 to include a `JonoM` prefix. You may need to update your template directory structure and/or app configuration accordingly.

## How to use

The navigator is auto-injected into your template, and no code changes are needed.

If your website uses caching, make sure BetterNavigator's output is excluded.

### Access developer tools on a live website

You can mark certain CMS users as developers in your site's config, so they can access developer tools when logged in. Example YAML:

```
JonoM\BetterNavigator:
  developers:
    - 'dev@yoursite.com'
    - 'otherdev@yoursite.com'
```

## Customisation

### Navigator display

You can control whether the navigator is displayed by defining a `showBetterNavigator(): bool`
method in any controller with the extension applied. By default the navigator will only show on controllers that have a `dataRecord` property that is an instance of `SilverStripe\CMS\Model\SiteTree`.

```php
public function showBetterNavigator()
{
    // A user-defined setting
    return $this->ShowDebugTools;
}
```

### Layout options

BetterNavigator can be made translucent when collapsed by adding the following config setting:

```
JonoM\BetterNavigator:
  translucent: true
```

BetterNavigator's default position is 'right-top', but can be changed to 'right-bottom', 'left-top' or 'left-bottom'. Example:

```
JonoM\BetterNavigator:
  position: 'right-bottom'
```

### Template additions/overrides

BetterNavigator's output is controlled by templates so it can be [easily overridden](https://docs.silverstripe.org/en/5/developer_guides/templates/template_inheritance/#cascading-themes).

Some empty `<% include %>` placeholders are included to let you easily add more content (new buttons for instance). Just create any of these templates in your theme or app directory and add your content:

* *templates/JonoM/BetterNavigator/Includes/* BetterNavigatorExtraContent.ss
* *templates/JonoM/BetterNavigator/Includes/* BetterNavigatorExtraDebugging.ss
* *templates/JonoM/BetterNavigator/Includes/* BetterNavigatorExtraDevTools.ss

The BetterNavigator.ss template's scope is set to the page that is being viewed, so any methods available in your page controller will be available in the BetterNavigator.ss template. This should allow you to add custom links by page type and introduce complex logic if you want to.

### Overriding the "Edit in CMS" Link

There may be occasions when you wish to override the "Edit in CMS" link. For example to point to the edit form for a displayed DataObject, rather than for the Page itself. To do so, simply add a `BetterNavigatorEditLink()` method to your page's Controller, e.g.:

````php
// EventsPageController.php

/**
 * Return an alternative URL for the BetterNavigator Edit in CMS link.
 * @return string
 */
public function BetterNavigatorEditLink()
{
    $event = $this->displayedEvent();
    return $event->canEdit() ? CMSEditLinkAPI::find_edit_link_for_object($event) : false;
}
````

(This example uses [sunnysideup/cms_edit_link_field](https://github.com/sunnysideup/silverstripe-cms_edit_link_field) to automatically find an edit link for a specified DataObject, but you can return any URL.)

### Overriding the permissions required for the cms edit link

By default users are required to have at least the `CMS_ACCESS_CMSMain` permission in order to see the edit link in better navigator, you can override this by setting the `better_navigator_edit_permission` configuration option on your controller to another permission code or an array of permission codes, e.g.:

```yml
My\Namespace\EventController:
  better_navigator_edit_permission: "CUSTOM_PERMISSION_CODE"
  better_navigator_edit_permission_mode: "any" #Optional, but can be either "any" or "all" (defaults to "all")
```

## Recommended companions

### DebugBar for better debugging tools

This module provide quick access to Silverstripe's built in [URL Variable Tools](https://docs.silverstripe.org/en/developer_guides/debugging/url_variable_tools/#url-variable-tools) but reading their output isn't much fun. You can peek under Silverstripe's hood much more conveniently using lekoala's [Silverstripe DebugBar](https://github.com/lekoala/silverstripe-debugbar)

### Environment Awareness to save your sites from yourself

[Environment Awareness](https://github.com/jonom/silverstripe-environment-awareness) makes it obvious which environment you're in, to make it less likely that you nuke something in prod. You can display the current environment [right in the navigator](https://github.com/jonom/silverstripe-environment-awareness/blob/master/docs/en/how-to-use.md#include-front-end-environment-notice).

## Maintainer contact

[Jono Menz](https://jonomenz.com)

## Sponsorship

If you want to boost morale of the maintainer you're welcome to make a small monthly donation through [**GitHub**](https://github.com/sponsors/jonom), or a one time donation through [**PayPal**](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z5HEZREZSKA6A). ❤️ Thank you!

Please also feel free to [get in touch](https://jonomenz.com) if you want to hire the maintainer to develop a new feature, or discuss another opportunity.
