(function($){

	"use strict";
	
	$(document).ready(function () {
		search.init();
	});
	
	var search = {
	
		init: function () {
			
			// LIST AND GRID VIEW TOGGLE
			$('.view-type li:first-child').addClass('active');
				
			$('.grid-view').click(function() {
				$('.three-fourth article').attr("class", "one-third");
				$('.view-type li').removeClass("active");
				$(this).addClass("active");
			});
			
			$('.list-view').click(function() {
				$('.three-fourth article').attr("class", "full-width");
				$('.view-type li').removeClass("active");
				$(this).addClass("active");
			});
			
			
			//STAR RATING
			$('#star').raty({
				score    : 3,
				path     : 'images/ico/',
				starOff  : 'star-rating-off.png',
				starOn  : 'star-rating-on.png'
			});
		}
	}

})(jQuery);