(function($) {
	var loaderWrapper = $("#team-member-scroller");
	var loaderWrapper2 = $(".teamMembersWrapper .teamOverlay");
    var teamlistWrapper = $("#team-member-list");
	var area = $("#area");
	var location = $("#location");
    var canBeLoaded = true;
	var canBeLoaded2 = true;

	$(window).scroll(function(e) {
		e.preventDefault();
		var pagination = teamlistWrapper.attr("data-pagination");	
		var pages = teamlistWrapper.attr("data-pages");
		var total = teamlistWrapper.attr("data-total");
        var data = {
            'action': 'careerloadmore',
            'query': career_loadmore_params.posts, // that's how we get params from wp_localize_script() function
            'page': pagination,
			'area':area.val(),
			'location':location.val()
        };
        /*console.log(pagination);
        console.log(pages);
        console.log(total);
        console.log(data);
        console.log($(document).scrollTop());
        console.log(loaderWrapper.position().top);
        console.log(canBeLoaded);*/
        if ($(document).scrollTop() > loaderWrapper.position().top && canBeLoaded == true) {
			if(!teamlistWrapper.hasClass("disabled")){
				console.log($(document).scrollTop());
        console.log(loaderWrapper.position().top);
        console.log(canBeLoaded);
	            $.ajax({
	                url: career_loadmore_params.ajaxurl,
	                data: data,
	                type: 'POST',
					dataType: 'json',
	                beforeSend: function(xhr) {
	                    // you can also add your own preloader here
	                    // you see, the AJAX call is in process, we shouldn't run it again until complete
	                    canBeLoaded = false;
	                    loaderWrapper.find('.team-member-loader').show();
	                },
	                success: function(data) {
	                    if (data.total) {	

							var currentpage = parseInt(pagination) + 1;
	                        teamlistWrapper.find('.job-box:last-of-type').after(data.items); // where to insert posts
	                        canBeLoaded = true; // the ajax is completed, now we can run it again
	                        teamlistWrapper.attr("data-pagination",currentpage);
							teamlistWrapper.attr("data-pages",data.pages);
							teamlistWrapper.attr("data-total",data.total);
	                        loaderWrapper.find('.team-member-loader').hide();
	                        if (currentpage == data.pages) {
	                            loaderWrapper.find('.team-member-loader').remove();
	                        }
	                    }
	                }
	            });
			}
        }
    });


    area.on('change',function(){
		var areaquery = $(this).val();
		loaderWrapper2.hide();

        var data = {
            'action': 'careerloadmore',
            'query': career_loadmore_params.posts, // that's how we get params from wp_localize_script() function
            'page': 0,
			'area':areaquery,
			'location':location.val()
        };

		$.ajax({
            url: career_loadmore_params.ajaxurl,
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

	location.on('change',function(){
		var locationquery = $(this).val();
		loaderWrapper2.hide();


        var data = {
            'action': 'careerloadmore',
            'query': career_loadmore_params.posts, // that's how we get params from wp_localize_script() function
            'page': 0,
			'area':area.val(),
			'location':locationquery
        };

		$.ajax({
            url: career_loadmore_params.ajaxurl,
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
					teamlistWrapper.empty().append("<div class='teamerror'>No result</div>");
					teamlistWrapper.addClass("disabled");
				}	
			}
		});
	});
})(jQuery);