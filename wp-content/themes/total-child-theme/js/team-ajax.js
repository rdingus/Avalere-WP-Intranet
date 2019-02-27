(function($) { 
				
        var loaderWrapper = $("#team-member-scroller");
		var loaderWrapper2 = $(".teamMembersWrapper .teamOverlay");
        var teamlistWrapper = $("#team-member-list");
		//var stafffilterWrapper = $("#stafffilter");
		var servicefilterWrapper = $("#servicefilter");
		var memberfilter = $("#memberfilter");
        var canBeLoaded = true;
		var canBeLoaded2 = true;		
        var minlength = 2;
		
		
		var timer = null;
		memberfilter.keydown(function(){
			   clearTimeout(timer); 
			   timer = setTimeout(filterMember, 1000)
		});		
		
		function filterMember(){
			value = memberfilter.val();
			
				loaderWrapper2.hide();				
				var data = {
					'action': 'teamloadmore',
					'query': team_loadmore_params.posts, // that's how we get params from wp_localize_script() function
					'page': 0,
					//'staff':stafffilterWrapper.val(),
					'title':value,
					'service':servicefilterWrapper.val()
				};
				$.ajax({
                    url: team_loadmore_params.ajaxurl,
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
						canBeLoaded2 = true;
	                    loaderWrapper2.hide();
						teamlistWrapper.removeClass("disabled");

                        if (data.total) {							
							
							teamlistWrapper.attr("data-pagination","1");
							teamlistWrapper.attr("data-pages",data.pages);
							teamlistWrapper.attr("data-total",data.total);
                            teamlistWrapper.html("").append(data.items); // where to insert posts                    								

                        }else{
							teamlistWrapper.empty().append("<div class='teamerror'>No Results Found</div>");
							teamlistWrapper.addClass("disabled");
						}
						
						if(data.pages == 1){
							teamlistWrapper.addClass("disabled");
						}
                    }
                });
			
		};
		
		/*stafffilterWrapper.on('change',function(){
			var selectedStaff = $(this).val();
			loaderWrapper2.hide();	
					

            var data = {
                'action': 'teamloadmore',
                'query': team_loadmore_params.posts, // that's how we get params from wp_localize_script() function
                'page': 0,
				//'staff':selectedStaff,
				'service':servicefilterWrapper.val()
            };

			$.ajax({
                    url: team_loadmore_params.ajaxurl,
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
						canBeLoaded2 = true;
	                    loaderWrapper2.hide();
						teamlistWrapper.removeClass("disabled");

                        if (data.total) {							
							
							teamlistWrapper.attr("data-pagination","1");
							teamlistWrapper.attr("data-pages",data.pages);
							teamlistWrapper.attr("data-total",data.total);
                            teamlistWrapper.html("").append(data.items); // where to insert posts                    								

                        }
						
						if(data.pages == 1){
							teamlistWrapper.addClass("disabled");
						}
                    }
                });
		}); 
		*/
		servicefilterWrapper.on('change',function(){
			var selectedService = $(this).val();
			loaderWrapper2.hide();


            var data = {
                'action': 'teamloadmore',
                'query': team_loadmore_params.posts, // that's how we get params from wp_localize_script() function
                'page': 0,
				//'staff':stafffilterWrapper.val(),
				'title':memberfilter.val(),
				'service':selectedService
            };

			$.ajax({
                    url: team_loadmore_params.ajaxurl,
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
						
						canBeLoaded2 = true;
	                    loaderWrapper2.hide();
						teamlistWrapper.removeClass("disabled");
                        if (data.total) {														
							teamlistWrapper.attr("data-pagination","1");

							teamlistWrapper.attr("data-pages",data.pages);
							teamlistWrapper.attr("data-total",data.total);
                            teamlistWrapper.empty().append(data.items); // where to insert posts                    								
							if(data.pages == 1){
								teamlistWrapper.addClass("disabled");
							}
                        }else{
							
							teamlistWrapper.empty().append("<div class='teamerror'>No Results Found</div>");
							teamlistWrapper.addClass("disabled");
						}
						
                    }
                });
		});       


    

})(jQuery);