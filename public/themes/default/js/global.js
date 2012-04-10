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

	// Trees
	$(document).ready(function() {
		// open trees that contain selected items
		$('a.current').parents('li').children('a.collapsed').toggleClass('expanded').toggleClass('collapsed').next('ul').show();

		// tree toggle functions
		setTimeout(function() {
			$('.menutree li > a').click(function() {
				$(this).parent().find('> ul').toggle(100).parent().find('> a').toggleClass('expanded').toggleClass('collapsed');
			});
			$('.menutree .expand_all').click(function() {
				$('.menutree').find('li').children('a.collapsed').addClass('expanded').removeClass('collapsed').next('ul').show();
			});
			$('.menutree .collapse_all').click(function() {
				$('.menutree').find('li').children('a.expanded').addClass('collapsed').removeClass('expanded').next('ul').hide();
			});
		}, 0);
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

	$(document).ready(function() {

		$cookie = 'menucookie';
		$item_list = $("#menu_list>ul");

		if ($item_list.length > 0)
		{
			// collapse all ordered lists but the top level
			$item_list.find('ul').children().hide();

			// this gets ran again after drop
			var refresh_tree = function() {

				// add the minus icon to all parent items that now have visible children
				$item_list.parent().find('ul li:has(li:visible)').removeClass('plus').addClass('minus');

				// add the plus icon to all parent items with hidden children
				$item_list.parent().find('ul li:has(li:hidden)').removeClass('minus').addClass('plus');

				// remove the class if the child was removed
				$item_list.parent().find('ul li:not(:has(ul))').removeClass('plus minus');
			}
			refresh_tree();

			// set the icons properly on parents restored from cookie
			$($.cookie($cookie)).has('ul').toggleClass('minus plus');

			// show the parents that were open on last visit
			$($.cookie($cookie)).children('ul').children().show();

			// show/hide the children when clicking on an <li>
			$item_list.find('li').live('click', function()
			{
				$(this).children('ul').children().slideToggle('fast');

				$(this).has('ul').toggleClass('minus plus');

				var items = [];

				// get all of the open parents
				$item_list.find('li.minus:visible').each(function(){ items.push('#' + this.id) });

				// save open parents in the cookie
				$.cookie($cookie, items.join(', '), { expires: 1 });

				 return false;
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

			$("#menu_list").show();
		}

	});

});
