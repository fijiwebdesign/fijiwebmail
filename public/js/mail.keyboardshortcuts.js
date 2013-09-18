/**
 *	Keyboard shortcuts for mail app 
 * @param {Object} $ jQuery object
 */
(function($) {
	
// domReady
$(function() {
	
	// bind functionality to keyup events 
	$(window).bind('keyup', function(event) {
		
		// configuration of keyCodes mapped to urls to go to
		var keyMap = {
			'Inbox' : { keyCode: 73, url: '?app=mail' }, // i
			'Sent Mail' : { keyCode: 83, url: '?app=mail&folder=Sent Mail' }, // s
			'Drafts': { keyCode: 68, url: 'app=mail&folder=Drafts' }, // d
			'Archive': { keyCode: 65, url: 'app=mail&folder=Archive' }, // a
			'Trash': { keyCode: 84, url: 'app=mail&folder=Trash' }, // t
			'Spam': { keyCode: 88, url: 'app=mail&folder=Spam' } // x
		};

		if (event.ctrlKey) {
			$.each(keyMap, function(key, val) {
				if (event.keyCode == val.keyCode) {
					event.preventDefault();
					window.location = val.url;
				}
			})
		}
	});
});
	
})(window.jQuery);
