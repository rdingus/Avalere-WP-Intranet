function printElem(divId) {
	
    var content = document.getElementById(divId).innerHTML;
	var head = document.getElementsByTagName("head");
	alert(head);
    var mywindow = window.open('', 'Print', 'height=600,width=800');

    mywindow.document.write('<html>');
	mywindow.document.write(head);
    mywindow.document.write('<body >');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');

    mywindow.document.close();
    mywindow.focus()
    mywindow.print();
    mywindow.close();
    return true;
}
(function($) {
   $(".fancybox-share").fancybox({
	   'href'   : '#singleJobShare',
        'titleShow'  : false,
        'transitionIn'  : 'elastic',
        'transitionOut' : 'elastic'});  
	})(jQuery);