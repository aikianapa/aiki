sendComments();

function sendComments() {
	if ($("#contactForm").length) {
	$("#content .alert-success").hide().removeClass("hidden");
	$("#content .alert-danger").hide().removeClass("hidden");
	$("#contactForm .norobot input").click(function(){
		$("#contactForm .norobot input").attr("disabled",true);
		$("#contactForm .sendbutton a.btn").removeAttr("disabled");
	});
	$(document).on("check_required_success",function(event){
		//$("#contactForm .sendbutton a.btn").hide("fade");
		$(document).on("comments_after_formsave",function(event,name,item,form,ret){
		$("#contactForm").hide();
		if (ret==true) {
			$("#content .alert-success").show("fade");
		} else {
			$("#content .alert-danger").show("fade");
			setTimeout(function(){
				$("#content .alert-danger").hide();
				$("#contactForm").show("fade");
				$("#contactForm .sendbutton a.btn").show();
			},3000);
		}
		});
	});
	}
}
