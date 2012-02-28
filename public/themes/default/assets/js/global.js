$(document).ready(function(){

	// Fadein
	$('body').fadeIn(1000);

	// Image fade
	$("#calltos li").hover(
		function() {
			$(this).stop().animate({"opacity": "0.7"}, "slow");
		},
		function() {
			$(this).stop().animate({"opacity": "1"}, "slow");
	});

	// Notices
	$(function () {
		var alert = $('.success-box, .notice-box, .error-box, .no-box');
		if (alert.length > 0)
		{
			alert.show()

			window.setTimeout(function() {
			  alert.toggle(500);
			}, 3000);
		}
	});

	// Tooltip
	$('.tooltip').tipsy({
		gravity: $.fn.tipsy.autoNS,
		fade: true,
		html: true
	});
	$('.tooltip-e').tipsy({
		gravity: 'e',
		fade: true,
		html: true
	});
	$('.tooltip-s').tipsy({
		gravity: 's',
		fade: true,
		html: true
	});

	// Accordion
	$(document).ready(function(){
		$(".accordion").accordion();
	});


	// Trees
	$(document).ready(function() {
		// tree toggle functions
		setTimeout(function() {
			$('.menutree > li > a.expanded + ul').show('normal');
			$('.menutree > li > a').click(function() {
				$(this).toggleClass('expanded').toggleClass('collapsed').parent().find('> ul').toggle(500);
			});
			$('.menutree .expand_all').click(function() {
				$('.menutree > li > a.collapsed').addClass('expanded').removeClass('collapsed').parent().find('> ul').show('normal');
			});
			$('.menutree .collapse_all').click(function() {
				$('.menutree > li > a.expanded').addClass('collapsed').removeClass('expanded').parent().find('> ul').hide('normal');
			});
		}, 250);
		// open the trees with selected items
		$('a.current').parents().filter('li').find('a:first').addClass('expanded').removeClass('collapsed').parent().parent().show();
	});

	// Toggler
	$(document).ready(function() {
		// choose text for the show/hide link - can contain HTML (e.g. an image)
		var showText='More...';
		var hideText='Less...';

		// initialise the visibility check
		var is_visible = false;

		// append show/hide links to the element directly preceding the element with a class of "toggle"
		$('.toggle').prev().append(' (<a href="#" class="toggleLink">'+showText+'</a>)');

		// hide all of the elements with a class of 'toggle'
		$('.toggle').hide();

		// capture clicks on the toggle links
		$('a.toggleLink').click(function() {

		// switch visibility
		is_visible = !is_visible;

		// change the link depending on whether the element is shown or hidden
		$(this).html( (!is_visible) ? showText : hideText);

		// toggle the display - uncomment the next line for a basic "accordion" style
		//$('.toggle').hide();$('a.toggleLink').html(showText);
		$(this).parent().next('.toggle').toggle('slow');

		// return false so any link destination is not followed
		return false;

		});
	});

	// Scroll
	$(document).ready(function(){
		jQuery.localScroll();
	});

	jQuery.fn.nudge = function(params) {
		//set default parameters
		params = jQuery.extend({
		amount: 10,				//amount of pixels to pad / marginize
		duration: 300,			//amount of milliseconds to take
		property: 'padding', 	//the property to animate (could also use margin)
		direction: 'left',		//direction to animate (could also use right)
		toCallback: function() {},	//function to execute when MO animation completes
		fromCallback: function() {}	//function to execute when MOut animation completes
		}, params);

		//For every element meant to nudge...
		this.each(function() {

		//variables
		var jQueryt = jQuery(this);
		var jQueryp = params;
		var dir = jQueryp.direction;
		var prop = jQueryp.property + dir.substring(0,1).toUpperCase() + dir.substring(1,dir.length);
		var initialValue = jQueryt.css(prop);

		/* fx */
		var go = {}; go[prop] = parseInt(jQueryp.amount) + parseInt(initialValue);
		var bk = {}; bk[prop] = initialValue;

		//Proceed to nudge on hover
		jQueryt.hover(function() {
		jQueryt.stop().animate(go, jQueryp.duration, '', jQueryp.toCallback);
		}, function() {
		jQueryt.stop().animate(bk, jQueryp.duration, '', jQueryp.fromCallback);
		});
		});
		return this;
	};

	// Colorbox
	$(document).ready(function(){
		$(".zoom").colorbox({maxWidth:"80%", maxHeight:"80%"});
	});

	$(document).ready(function(){
		$(".iframe").colorbox({width:"90%", height:"90%", iframe:true});
	});

});
