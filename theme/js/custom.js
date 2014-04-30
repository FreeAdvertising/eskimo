/**
 * Document is ready
 * @return void
 */
window.addEventListener("DOMContentLoaded", function(){
	if(document.querySelector("#wpadminbar")){
		document.body.classList.add("logged-in");
	}else {
		document.body.classList.remove("logged-in");
	}
	
	var quoterequests = document.querySelectorAll(".request-quote"),
		request_quote_form = document.querySelector(".request-quote-form");
	
	for(var i = 0; i < quoterequests.length; i++){
		quoterequests[i].addEventListener("click", function(evt){
			/*evt.preventDefault();*/
			window.location.href = "#";
	
			if(false == jQuery(request_quote_form).is(":visible")){
				jQuery(request_quote_form).slideDown();
			}else {
				jQuery(request_quote_form).slideUp();
			}
		});
	}

	jQuery(".request-quote-form .send-btn").click(function(evt){
		evt.preventDefault();
		
		var url = this.parentElement.parentElement.parentElement.action;

		jQuery("span.sending").fadeIn();

		jQuery.post(url, jQuery(".request-quote-form form").serialize(), function(data){
			jQuery("p.form-response").text(data.message);
		}).fail(function(data){
			jQuery("p.form-response").text(data.message);
		}).always(function(){
			jQuery("span.sending").fadeOut().remove();
		});
	});

	//because you can't actually add classes to contact-form-7 input elements
	//(bug???)
	jQuery("body.contact form.wpcf7-form input[type='text'], body.contact form.wpcf7-form input[type='email'], body.contact form.wpcf7-form textarea").addClass("form-control");

	//remove weird break tags from some pages
	jQuery("body.gallery br").each(function(){
		jQuery(this).remove();
	});

	//initialize detect.js
	var info = new Detect({format: false});
});