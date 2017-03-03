moduleSitemapGenerator();

function moduleSitemapGenerator() {
	if ($("#sitemapGenerator").length) {
	$("#sitemapGenerator .alert-success").hide().removeClass("hidden");
	$("#sitemapGenerator .alert-danger").hide().removeClass("hidden");
	$("#sitemapGenerator .progress").hide().removeClass("hidden");

	$("#sitemapGenerator .btn").on("click",function(event){
		$("#sitemapGenerator a.btn").hide();
		$("body").addClass("cursor-wait");
		moduleSitemapGeneratorAjax("/");

	});
	}
}

function moduleSitemapGeneratorAjax(url) {
		$("#sitemapGenerator .progress").show("fade");
		$.ajax({
			async: 		true,
			type:		'POST',
			data:		{link:url},
			url: "/engine/ajax.php?mode=module&src=sitemap&ajax=generate",
			success: function(data){
				$("#sitemapGenerator .alert-success").show("fade");
				setTimeout(function(){
					$("#sitemapGenerator .alert-success").hide();
					$("#sitemapGenerator .progress").hide();
					$("#sitemapGenerator  a.btn").show();
				},3000);
				$("body").removeClass("cursor-wait");
				return data;
			},
			error: function(){
				$("#sitemapGenerator .alert-danger").show("fade");
				setTimeout(function(){
					$("#sitemapGenerator .alert-danger").hide();
					$("#sitemapGenerator .progress").hide();
					$("#contactForm .sendbutton a.btn").show();
				},3000);				
				$("body").removeClass("cursor-wait");
				return false;
			}
		});	
}
