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

Silverstripe 4.0 (3.1+ through previous releases)

## Installation

**Composer / Packagist ([best practice](http://doc.silverstripe.org/framework/en/trunk/installation/composer))**
Add "jonom/silverstripe-betternavigator" to your requirements.

**Manually**
Download, place the folder in your project root, rename it to 'betternavigator' (if applicable) and run a dev/build?flush=1.

## How to use

The navigator is auto-injected into your template, and no code changes are needed.

If your website uses caching, make sure BetterNavigator's output is excluded.

## Disabling the navigator

You can disable the navigator using your own custom logic by defining a `showBetterNavigator(): bool`
method in any controller with the extension applied.

```php
public function showBetterNavigator()
{
    // A user-defined setting
    return $this->ShowDebugTools;
}
```
**Access developer tools on a live website**
You can mark certain CMS users as developers in your site's config, so they can access developer tools when logged in. Example YAML:

```
BetterNavigator:
developers:
    - 'dev@yoursite.com'
    - 'otherdev@yoursite.com'
```

## Customisation

BetterNavigator can be made translucent when collapsed by adding the following config setting:

```
BetterNavigator:
translucent: true
```

BetterNavigator's default position is 'right-top', but can be changed to 'right-bottom', 'left-top' or 'left-bottom'. Example:

```
BetterNavigator:
position: 'right-bottom'
```

BetterNavigator's output is controlled by templates so it can be [easily overridden](http://doc.silverstripe.org/framework/en/topics/theme-development#overriding).

Some empty `<% include %>` placeholders are included to let you easily add more content (new buttons for instance). Just create any of these templates in your theme or app directory and add your content:

* *templates/JonoM/BetterNavigator/Includes/* BetterNavigatorExtraContent.ss
* *templates/JonoM/BetterNavigator/Includes/* BetterNavigatorExtraDebugging.ss
* *templates/JonoM/BetterNavigator/Includes/* BetterNavigatorExtraDevTools.ss

The BetterNavigator.ss template's scope is set to the page that is being viewed, so any methods available in your page controller will be available in the BetterNavigator.ss template. This should allow you to add custom links by page type and introduce complex logic if you want to.

## Bonus: better debugging tools

This module provide quick access to Silverstripe's built in [URL Variable Tools](http://doc.silverstripe.org/framework/en/reference/urlvariabletools) but reading their output isn't much fun. You can peek under Silverstripe's hood much more conveniently using lekoala's [Silverstripe DebugBar](https://github.com/lekoala/silverstripe-debugbar)

## Maintainer contact

[Jono Menz](https://jonomenz.com)

## Sponsorship

If you want to boost morale of the maintainer you're welcome to make a small monthly donation through [**GitHub**](https://github.com/sponsors/jonom), or a one time donation through [**PayPal**](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z5HEZREZSKA6A). ❤️ Thank you!

Please also feel free to [get in touch](https://jonomenz.com) if you want to hire the maintainer to develop a new feature, or discuss another opportunity.
