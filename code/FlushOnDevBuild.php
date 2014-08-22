<?php

/**
 * via swaiba https://groups.google.com/d/msg/silverstripe-dev/RNWCiFAnRI0/bABhA5Otnl4J
 */

class FlushOnDevBuild extends Extension {
	function beforeCallActionHandler($request,$action){
		if(!$this->owner->response->isFinished() && $action=='build' && $request->getVar('flush')){
			SS_TemplateLoader::instance()->getManifest()->regenerate(true);
			SSViewer::flush_template_cache();
		}
	}
}