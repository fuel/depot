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
		var alert = $('.success-box, .info-box, .error-box, .warning-box, .no-box');
		if (alert.length > 0)
		{
			alert.show()

			window.setTimeout(function() {
			  alert.toggle(750);
			}, 3000);
		}
	});

	// Accordion
	$(document).ready(function(){
		$(".accordion").accordion();
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

	// menu tree functions
	$(document).ready(function() {

		var $cookie = '';
		var $item_list = $("#menu_list>ul");

		var update_cookie = function() {
			var items = [];

			// get all of the open parents
			$item_list.find('li.minus:visible').each(function(){ items.push('#' + this.id) });

			var pathArray = window.location.pathname.split( '/' );
			var cookiepath = '/' + pathArray[1] + '/' + pathArray[2];

			// save open parents in the cookie
			$.cookie($cookie, items.join(', '), { expires: 1, path: cookiepath });
		}

		// this gets ran again after drop
		var refresh_tree = function() {

			// add the minus icon to all parent items that now have visible children
			$item_list.parent().find('ul li:has(li:visible)').removeClass('plus').addClass('minus');

			// add the plus icon to all parent items with hidden children
			$item_list.parent().find('ul li:has(li:hidden)').removeClass('minus').addClass('plus');

			// remove the class if the child was removed
			$item_list.parent().find('ul li:not(:has(ul))').removeClass('plus minus');
		}

		// tree toggle functions
		setTimeout(function() {
			$('.sidebar .expand_all').click(function() {
				$item_list.find('ul').children().show();
				refresh_tree();
				update_cookie();
			});
			$('.sidebar .collapse_all').click(function() {
				$item_list.find('ul').children().hide();
				refresh_tree();
				update_cookie();
			});
		}, 0);

		if ($item_list.length > 0)
		{
			// make the divs with embedded a's clickable
			$item_list.find('li').clickable(true,  true);

			// collapse all ordered lists but the top level
			$item_list.find('ul').children().hide();

			refresh_tree();

			// determine the cookie name based on the id prefix
			$cookie = $item_list.find('li').attr('id');
			$cookie = $cookie.substr(0, $cookie.indexOf("_")) + '_menustate';

			// set the icons properly on parents restored from cookie
			$($.cookie($cookie)).has('ul').toggleClass('minus plus');

			// show the parents that were open on last visit
			$($.cookie($cookie)).children('ul').children().show();

			// show/hide the children when clicking on an <li>
			$item_list.find('li').on('click', function(event)
			{
				if ($(this).children('ul').length > 0)
				{
					$(this).children('ul').children().slideToggle('fast');

					$(this).has('ul').toggleClass('minus plus');

					update_cookie();
				}

				event.stopImmediatePropagation();
			});

			$item_list.nestedSortable({
				delay: 100,
				revert: 250,
				disableNesting: 'no-nest',
				errorClass: 'ui-nestedSortable-error',
				forcePlaceholderSize: true,
				handle: 'div',
				helper:	'clone',
				items: 'li',
				maxLevels: 4,
				opacity: .7,
				placeholder: 'placeholder',
				tabSize: 25,
				listType: 'ul',
				tolerance: 'pointer',
				toleranceElement: '> div',
			});

		}

	});

});
