(function($) {

	$.fn.modal = function(method) {

		var defaults = defaults || {
            width: '30%',
			div:   ''
        };

		var methods = methods || {
			init: function(opts) {
				opts = $.extend({}, defaults, opts);

                $.each(this[0], function(a, b){
                    if (/innerHTML/.test(a)) {
                        opts.div = b;
                    }
                });

				_modal.__setup(opts);
			}
		}

		var _modal = _modal || {

			/* create the dynamic elements */
			__setup: function(opts) {

				/* overlay, modal & content elements */
				var _window = '<div id="overlay"></div>';
				_window += '<div id="modal">';
				_window += '<div id="content" style="text-align: left">';
				_window += '<div style="text-align: right">';
				_window += '<a href="" id="close">[X]</a></div>';

				/* load the content method */
				_window += opts.div;
				_window += '</div></div>';

				/* load the elements */
				$('body').prepend(_window);

				/* call __styles to apply CSS to new elements */
				_modal.__styles(opts);
			},

			/* Everyone likes pretty things, do work */
			__styles: function(opts) {
				/* assign our steezy styles */
				$('body').css({
				    'overflow':'hidden'
				});

				/* darken background of modal window */
				$('#overlay').css({
				    'position':'fixed',
					'z-index':999,
				    'top':0,
				    'left':0,
				    'width':'100%',
				    'height':'100%',
				    'background':'#000',
				    'opacity':0.5,
				    'filter':'alpha(opacity=50)'
				});

				/* bring focus to modal using transparent border */
				$('#modal').css({
				    'position':'absolute',
					'z-index':1000,
				    'background':'rgba(0,0,0,0.2)',
				    'border-radius':'14px',
				    'padding':'8px',
				    'width':opts.width
				});

				/* pad & assign some styles to content element */
				$('#content').css({
				    'border-radius':'8px',
				    'background':'#fff',
				    'padding':'20px',
					'position':'relative',
					'z-index':1001
				});
			}
		}

		/* the magic method & argument parser */
		if (methods[method]){
		    return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if ((typeof method==='object')||(!method)){
		    return methods.init.apply(this, arguments);
		} else {
		    console.log('Method '+method+' does not exist');
		}
	};
})(jQuery);
