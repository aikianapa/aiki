$(document).ready(function(){
	$("#aside .nav a").on("click",function(){
		setcookie("mainmenu",$(this).parent().index());
	});
});

$(document).ready(function(){
	if (getcookie("mainmenu")!==undefined && getcookie("mainmenu")>0) {
		var menu=getcookie("mainmenu")*1-1;
		setTimeout(function(){
			$("#aside .nav a:eq("+menu+")").focus().trigger("click");
		},100);
	}
});
