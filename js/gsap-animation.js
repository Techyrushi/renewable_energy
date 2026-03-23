(function ($) {
	"use strict";

	gsap.registerPlugin(ScrollTrigger, SplitText);
	gsap.config({
		nullTargetWarn: false,
		trialWarn: false
	});

	/*----  Functions  ----*/
	function getpercentage(x, y, elm) { 
		elm.find('.pbmit-fid-inner').html(y + '/' + x);
		var cal = Math.round((y * 100) / x);
		return cal;
	}

	var pbmit_video_popup = function() {
		jQuery('.pbmit-popup').on('click', function(event) {
			event.preventDefault();
			var href = jQuery(this).attr('href');
			var title = jQuery(this).attr('title');
			window.open(href, title, "width=600,height=500");
		});
	}

	var pbmit_search_btn = function() {
		jQuery(function() {
			jQuery('.pbmit-header-search-btn').on("click", function(event) {
				event.preventDefault();
				jQuery(".pbmit-header-search-form-wrapper").addClass("open");
				jQuery('.pbmit-header-search-form-wrapper input[type="search"]').focus();
			});
			jQuery(".pbmit-search-close").on("click keyup", function(event) {
				jQuery(".pbmit-header-search-form-wrapper").removeClass("open");
			});
		});
	}

	var pbmit_sticky_header = function() {
		if (jQuery('.pbmit-header-sticky-yes').length > 0) {
			var header_html = jQuery('#masthead .pbmit-main-header-area').html();
			jQuery('.pbmit-sticky-header').append(header_html);
			jQuery('.pbmit-sticky-header #menu-toggle').attr('id', 'menu-toggle2');
			jQuery('#menu-toggle2').on('click', function() {
				jQuery("#menu-toggle").trigger("click");
			});
			jQuery('.pbmit-sticky-header .main-navigation ul, .pbmit-sticky-header .main-navigation ul li, .pbmit-sticky-header .main-navigation ul li a').removeAttr('id');
			jQuery('.pbmit-sticky-header h1').each(function() {
				var thisele = jQuery(this);
				var thisele_class = jQuery(this).attr('class');
				thisele.replaceWith('<span class="' + thisele_class + '">' + jQuery(thisele).html() + '</span>');
			});
			// For infostak header
			if (jQuery('.pbmit-main-header-area').hasClass('pbmit-infostack-header')) { // check if infostack header
				// for header style 2
				jQuery(".pbmit-sticky-header .pbmit-header-menu-area").insertAfter(".pbmit-sticky-header .site-branding");
				jQuery('.pbmit-sticky-header .pbmit-header-info, .pbmit-sticky-header .pbmit-mobile-search').remove();
			}
		}
		pbmit_flotingbar();
	}

	var pbmit_sticky_header_class = function() {
		// Add sticky class
		if (jQuery('#wpadminbar').length > 0) {
			jQuery('#masthead').addClass('pbmit-adminbar-exists');
		}
		var offset_px = 300;
		if (jQuery('.pbmit-main-header-area').length > 0) {
			offset_px = jQuery('.pbmit-main-header-area').height() + offset_px;
		}
		// apply on document ready
		if (jQuery(window).scrollTop() > offset_px) {
			jQuery('#masthead').addClass('pbmit-fixed-header');
			jQuery('.pbmit-sticky-header .mega-menu.max-mega-menu.mega-menu-horizontal').attr("id", "mega-menu-pbminfotech-top");
		} else {
			jQuery('#masthead').removeClass('pbmit-fixed-header');
		}
		jQuery(window).scroll(function() {
			if (jQuery(window).scrollTop() > offset_px) {
				jQuery('#masthead').addClass('pbmit-fixed-header');
				jQuery('.pbmit-sticky-header .mega-menu.max-mega-menu.mega-menu-horizontal').attr("id", "mega-menu-pbminfotech-top");
			} else {
				jQuery('#masthead').removeClass('pbmit-fixed-header');
			}
		});
	}

	var pbmit_toggleSidebar = function() {
		jQuery('#menu-toggle').on('click', function() {
			jQuery("body:not(.mega-menu-pbminfotech-top) .pbmit-navbar > div, body:not(.mega-menu-pbminfotech-top)").toggleClass("active");
		})
		if (jQuery('.pbmit-navbar > div > .closepanel').length == 0) {
			jQuery('.pbmit-navbar > div').append('<span class="closepanel"><svg class="qodef-svg--close qodef-m" xmlns="http://www.w3.org/2000/svg" width="20.163" height="20.163" viewBox="0 0 26.163 26.163"><rect width="36" height="1" transform="translate(0.707) rotate(45)"></rect><rect width="36" height="1" transform="translate(0 25.456) rotate(-45)"></rect></svg></span>');
			jQuery('.pbmit-navbar > div > .closepanel, .mega-menu-pbminfotech-top .nav-menu-toggle').on('click', function() {
				jQuery(".pbmit-navbar > div, body, .mega-menu-wrap").toggleClass("active");
			});
			return false;
		}
	}

	var pbmit_flotingbar = function() {
		jQuery('.pbmit-nav-menu-toggle').on('click', function() {
			jQuery("body .floting-bar-wrap").toggleClass("active");
		})
		if (jQuery('.floting-bar-wrap .closepanel').length == 0) {
			jQuery('.floting-bar-wrap').append('<span class="closepanel"><svg class="qodef-svg--close qodef-m" xmlns="http://www.w3.org/2000/svg" width="26.163" height="26.163" viewBox="0 0 26.163 26.163"><rect width="36" height="1" transform="translate(0.707) rotate(45)"></rect><rect width="36" height="1" transform="translate(0 25.456) rotate(-45)"></rect></svg></span>');
			jQuery('.floting-bar-wrap .closepanel').on('click', function() {
				jQuery(".floting-bar-wrap").toggleClass("active");
			});
			return false;
		}
	}

	var pbmit_img_animation = function() {
		const pbmit_img_class = jQuery('.pbmit-animation-style1, .pbmit-animation-style2, .pbmit-animation-style3, .pbmit-animation-style4, .pbmit-animation-style5, .pbmit-animation-style6, .pbmit-animation-style7');

		pbmit_img_class.each(function() {
		const each_box = jQuery(this);

		new Waypoint({
			element: this, 
			handler: function(direction) {
			if (direction === 'down') {
				each_box.addClass('active');
			}
			},
			offset: '70%',
		});
		});
	}

	// ** Hover Image Effect ** \\
	function pbmit_hover_img() {
		const $targets = jQuery(".pbmit-team-style-4 .pbminfotech-box-content-inner");
	
		$targets.each(function () {
			const $target = jQuery(this);
			const $img = $target.find(".pbmit-hover-img");
	
			// Set initial CSS
			$img.css({
				opacity: 0,
				transform: "scale(0.95)",
				transition: "all 0.4s ease-out",
				position: "absolute",
				pointerEvents: "none"
			});
	
			$target.on("mouseenter", function () {
				$img.css({
					opacity: 1,
					transform: "scale(1)"
				});
			});
	
			$target.on("mouseleave", function () {
				$img.css({
					opacity: 0,
					transform: "scale(0.95)"
				});
			});
	
			$target.on("mousemove", function (e) {
				// Calculate mouse position relative to the target
				const offset = $target.offset();
				const xpos = e.pageX - offset.left;
				const ypos = e.pageY - offset.top;
	
				$img.css({
					left: xpos + "px",
					top: ypos + "px",
					transform: "translate(-50%, -50%) scale(1)" // Keep it centered
				});
			});
		});
	}

	function pbmit_tween_effect() {
		if (jQuery(window).width() < 768) return;

		jQuery(window).on('scroll resize', function () {
			jQuery('.pbmit-tween-effect').each(function () {
			let $el = jQuery(this),
				rect = this.getBoundingClientRect(),
				inView = rect.top < window.innerHeight && rect.bottom > 0;

			if (!inView) return;

			let progress = 1 - (rect.top / window.innerHeight);
			progress = Math.max(0, Math.min(1, progress)); // Clamp 0–1

			const getVal = (attr) => parseFloat($el.data(attr)) || 0;

			let tx = getVal('x-start') + (getVal('x-end') - getVal('x-start')) * progress,
				ty = getVal('y-start') + (getVal('y-end') - getVal('y-start')) * progress,
				scale = getVal('scale-x-start') + (getVal('scale-x-end') - getVal('scale-x-start')) * progress,
				skewX = getVal('skew-x-start') + (getVal('skew-x-end') - getVal('skew-x-start')) * progress,
				skewY = getVal('skew-y-start') + (getVal('skew-y-end') - getVal('skew-y-start')) * progress,
				rotate = getVal('rotate-x-start') + (getVal('rotate-x-end') - getVal('rotate-x-start')) * progress;

			$el.css('transform', `translate(${tx}%, ${ty}%) scale(${scale}) skew(${skewX}deg, ${skewY}deg) rotate(${rotate}deg)`);
			});
		}).trigger('scroll');
	}

	// on load 
	jQuery(window).on('load', function(){
		pbmit_video_popup();
		pbmit_search_btn();
		pbmit_sticky_header();
		pbmit_sticky_header_class();
		pbmit_toggleSidebar();
		pbmit_img_animation();
		pbmit_hover_img();
		pbmit_tween_effect();
		
		gsap.delayedCall(1, () =>
			ScrollTrigger.getAll().forEach((t) => {
				t.refresh();
			})
		);	
	});	
})($);

