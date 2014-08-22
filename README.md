#BetterNavigator for SilverStripe

![Diagram of module](/images/demo.png?raw=true)

This module is intended to replicate and expand upon the functionality provided by SilverStripe's built-in SilverStripeNavigator class. It provides a handy front-end menu for CMS users which offers these features:

**For Content Authors**

 * Indicates to a user that they are logged in
 * Indicates whether they are viewing draft or live content
 * Quickly edit the page you're viewing
 
**For Developers**
 
 * When in Dev Mode links are included for accessing most of SilverStripe's [URL Variable Tools](http://doc.silverstripe.org/framework/en/reference/urlvariabletools)
 * Developers can access these tools on a live website by nominating themselves as a developer in the site config

##Requirements

SilverStripe 3.1

##Installation

**Composer / Packagist ([best practice](http://doc.silverstripe.org/framework/en/trunk/installation/composer))**  
Add "jonom/silverstripe-betternavigator" to your requirements.

**Manually**  
Download, place the folder in your project root, rename it to 'betternavigator' (if applicable) and run a dev/build.

##How to use

Just place **$BetterNavigator** somewhere in your template(s). If your website uses caching, make sure BetterNavigator's output is excluded.

**Access developer tools on a live website**  
You can mark certain CMS users as developers in your site's config, so they can acess developer tools when logged in. Example YAML:

```
  BetterNavigator:
    developers:
      - 'dev@yoursite.com'
      - 'otherdev@yoursite.com'
```

##Customisation

Scripts and CSS are included via the BetterNavigator.ss template, so you can completely customise the front-end code and included links by copying or creating your own BetterNavigator.ss template.

The BetterNavigator.ss template's scope is set to the page that is being viewed, so any methods available in your page controller will be available in the BetterNavigator.ss template. This should allow you to add custom links by page type if you wish.

##Known issues

 * Probably won't work in IE8 or lower.