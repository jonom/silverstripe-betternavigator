//Hide the navigator when we're in the CMS
//ToDo - should be easier to use afterIframeAdjustedForPreview() but I couldn't work out how to do this
(function($) {
    $('.cms-preview').entwine({
        onadd: function() {
            var iframe = this.find('iframe');
            if (iframe){
                iframe.bind('load', function() {
                    var doc = this.contentDocument;
                    if(!doc) {return;}
                    var navi = doc.getElementById('BetterNavigator');
                    if(navi) {navi.style.display = 'none';}
                });
            }
        }
    });
}(jQuery));
