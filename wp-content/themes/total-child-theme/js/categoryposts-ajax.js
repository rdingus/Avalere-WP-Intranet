(function($) { 
				
        var loaderWrapper = $("#categoryposts-scroller"); //scroller div
		var loaderWrapper2 = $("#blog-entries .teamOverlay"); 
        var teamlistWrapper = $("#blog-entries");
		var postfilter = $("#postfilter");
        var canBeLoaded = true;
        var canBeLoaded2 = true;
		
		
		var timer = null;
		postfilter.keydown(function(){
			   clearTimeout(timer); 
			   timer = setTimeout(filterPost, 1000)
		});	
		function filterPost(){
			var searchtext = postfilter.val();
			//var pagination = teamlistWrapper.attr("data-pagination");	
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
						//var currentpage = parseInt(pagination) + 1;
						canBeLoaded2 = true;
	                    loaderWrapper2.hide();
						teamlistWrapper.removeClass("disabled");
                        if (data.total) {	
                            teamlistWrapper.attr("data-pagination","1");
							teamlistWrapper.attr("data-pages",data.pages);
							teamlistWrapper.attr("data-total",data.total);
							teamlistWrapper.find(".categorypostswrapper").empty().append(data.items);
							teamlistWrapper.find(".wpex-pagination").empty().append(data.pagination);
                        }
						if(data.pages == 1){
							teamlistWrapper.addClass("disabled");
						}
                    }
                });
		};	
		

       


    

})(jQuery);