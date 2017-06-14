$(document).ready(function(){
	$("#aside .nav a").on("click",function(){
		setcookie("mainmenu",$(this).parent().index());
	});

	if (getcookie("mainmenu")!==undefined && getcookie("mainmenu")>0) {
		var menu=getcookie("mainmenu")*1-1;
		setTimeout(function(){
			$("#aside .nav a:eq("+menu+")").focus().trigger("click");
		},100);
	}
	ajax_sess_kick();
});


function ajax_sess_kick() {
	setInterval(function(){
		$.get("/engine/ajax.php?mode=ajax_sess_kick");
	},120000);
}
