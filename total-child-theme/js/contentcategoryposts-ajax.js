(function($) { 
				
        var loaderWrapper = $("#categoryposts-scroller"); //scroller div
		var loaderWrapper2 = $("#blog-entries .teamOverlay"); 
        var teamlistWrapper = $("#blog-entries");
		var searchWrapper = $("#category-archive-searchform #search");
		var filterformWrapper = $("#category-archive-searchform #filterform");
        var canBeLoaded = true;
        var canBeLoaded2 = true;
        $(window).scroll(function(e) {
			e.preventDefault();
			var searchtext = searchWrapper.val();
			var pagination = teamlistWrapper.attr("data-pagination");
			var pages = teamlistWrapper.attr("data-pages");
			var total = teamlistWrapper.attr("data-total");
            var data = {
                'action': 'categorypostsloadmore',
                'query': categoryposts_loadmore_params.posts, // that's how we get params from wp_localize_script() function
                'page': pagination,
				'search':searchtext
            };
            if ($(document).scrollTop() > loaderWrapper.position().top - 500 && canBeLoaded == true) {
				if(!teamlistWrapper.hasClass("disabled")){
                $.ajax({
                    url: categoryposts_loadmore_params.ajaxurl,
                    data: data,
                    type: 'POST',
					dataType: 'json',
                    beforeSend: function(xhr) {
                        // you can also add your own preloader here
                        // you see, the AJAX call is in process, we shouldn't run it again until complete
                        canBeLoaded = false;
                        loaderWrapper.find('.categoryposts-loader').show();
                    },
                    success: function(data) {
						if (data.total) {
							var currentpage = parseInt(pagination) + 1;
                            teamlistWrapper.find('article:last-of-type').after(data.items); // where to insert posts
                            canBeLoaded = true; // the ajax is completed, now we can run it again
                            teamlistWrapper.attr("data-pagination",currentpage);
							teamlistWrapper.attr("data-pages",data.pages);
							teamlistWrapper.attr("data-total",data.total);
                            loaderWrapper.find('.categoryposts-loader').hide();
                            if (currentpage == data.pages) {
                                loaderWrapper.find('.categoryposts-loader').remove();
                            }


                        }
                    }
                });
				}
            }
        });
		
		filterformWrapper.keydown(function(e) {
			var key = e.which;
			if (key == 13) {
				event.preventDefault();
				var searchtext = searchWrapper.val();
				loaderWrapper2.hide();


				var data = {
					'action': 'categorypostsloadmore',
					'query': categoryposts_loadmore_params.posts, // that's how we get params from wp_localize_script() function
					'page': 0,
					'search':searchtext
				};
				$.ajax({
                    url: categoryposts_loadmore_params.ajaxurl,
                    data: data,
                    type: 'POST',
					dataType: 'json',
                    beforeSend: function(xhr) {
                        // you can also add your own preloader here
                        // you see, the AJAX call is in process, we shouldn't run it again until complete
                         canBeLoaded2 = false;
                        loaderWrapper2.show();
						teamlistWrapper.addClass("disabled");
                    },
                    success: function(data) {
						console.log(data);
						canBeLoaded2 = true;
	                    loaderWrapper2.hide();
						teamlistWrapper.removeClass("disabled");
                        if (data.total) {	
                            teamlistWrapper.attr("data-pagination","1");
							teamlistWrapper.attr("data-pages",data.pages);
							teamlistWrapper.attr("data-total",data.total);
							teamlistWrapper.find(".categorypostswrapper").empty().append(data.items);
                        }
						if(parseInt(data.total) < 10){
							teamlistWrapper.addClass("disabled");
						}
                    }
                });
				
			      return false;
			}
		});
		
		

       


    

})(jQuery);