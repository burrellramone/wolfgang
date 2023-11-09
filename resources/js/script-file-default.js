$(document).ready(function(){
	$(document.body).append("<div class='no-script-file'>You Have Not Created A Script File For This Page</div>");
	$('.no-script-file').css({
		"position": "absolute",
    	"background-color": "orange",
    	"color": "#FFF",
		"width": "400px",
		"top": "50%",
    	"z-index": "1000000",
    	"padding": "8px",
    	"font-weight": "bold",
	});
});