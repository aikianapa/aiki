<div id="formDesigner" class="container">
	<header class="navbar navbar-inverse navbar-fixed-bottom">
                        <!-- Left Header Navigation -->
                        <ul class="nav navbar-nav-custom">
                            <!-- Main Sidebar Toggle Button -->
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar-alt');this.blur();">
                                    <i class="fa fa-gear fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
                                    <i class="fa fa-gear fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>
                                </a>
                            </li>
                            <!-- END Main Sidebar Toggle Button -->

                            <!-- Header Link -->
                            <li class="animation-fadeInQuick toParent"></li>
                            <li class="animation-fadeInQuick">
                                <a href=""><strong class="currentInfo"></strong></a>
                            </li>
                            <!-- END Header Link -->
                        </ul>
	</header>

                <div id="sidebar-alt" tabindex="-1" aria-hidden="false">
                    <!-- Toggle Alternative Sidebar Button (visible only in static layout) -->
                    <a href="javascript:void(0)" id="sidebar-alt-close" onclick="App.sidebar('toggle-sidebar-alt');"><i class="fa fa-times"></i></a>

                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-scroll-alt">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Profile -->
                            <div class="sidebar-section">
                                <h2 class="text-light">Profile</h2>
<ul>
	<li><a href="#snippet" data="container">container</a></li>
	<li><a href="#snippet" data="row">row</a></li>
	<li><a href="#snippet" data="col">col</a></li>
	<li><a href="#snippet" data="inputgroup1">formgroup input</a></li>
	<li><a href="#snippet" data="selectgroup1">formgroup select</a></li>
	<li><a href="#snippet" data="button">button</a></li>
	<li><a href="#snippet" data="panel">панель</a></li>
</ul>
                            </div>
                            <!-- END Profile -->

                            <!-- Settings -->
                            <div class="sidebar-section">
                                <h2 class="text-light">Settings</h2>

                            </div>
                            <!-- END Settings -->
                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->
                </div>
<div class="row">
	<div class="panel col-xs-8">
		<form id="formDesignerEditor" class="form-horizontal" >
				<div id="formDesignerToolBtn" class="btn btn-sm">
					<i class="fa fa-gear"></i>
					<i class="fa fa-copy"></i>
					<i class="fa fa-code"></i>
					<i class="fa fa-trash"></i>
				</div>
		</form>
	</div>
</div>
</div>

<script language="javascript">
	$("#formDesignerEditor #formDesignerToolBtn").hide();
	$("#formDesignerEditor").delegate("*","mouseover",function(event){
		$("#formDesignerEditor [data-hovered]").removeAttr("data-hovered");
		if (!$(event.target).is("#formDesignerToolBtn") && !$(event.target).parents("#formDesignerToolBtn").length) {
			$(event.target).attr("data-hovered",true);
		}
	});

	$("#formDesignerEditor #formDesignerToolBtn").delegate("i.fa","click",function(){
		if ($(this).hasClass("fa-trash")) {
			var that=$("#formDesignerEditor [data-current]");
			var parent=that.parent();
			$("#formDesignerEditor #formDesignerToolBtn").hide();
			//$("#formDesigner header .toParent").trigger("click");
			$(that).remove();
			if ($(parent).html()=="") {$(parent).html("&nbsp;");}
			parent.trigger("click");
		}
		if ($(this).hasClass("fa-copy")) {
			var that=$("#formDesignerEditor [data-current]");
			var copy=$(that).clone();
			$(that).after(copy);
			copy.trigger("click");
		}
		return false;
	});

	$("#formDesignerEditor").delegate("*","mouseleave",function(event){
		$(event.target).removeAttr("data-hovered");
	});
	
	
	$("#formDesigner header .toParent").delegate("a[href=#parent]","click",function(){
		if ($("#formDesignerEditor [data-current]").parent().attr("id")!=="formDesignerEditor") {
			$("#formDesignerEditor [data-current]").parent().trigger("click");
		} else {
			$("#formDesignerEditor [data-current]").removeAttr("data-current");
			$("#formDesigner header .toParent").html("");
			$("#formDesigner header .currentInfo").html("");
		}
	});
	
	$("#formDesignerEditor").delegate("*","click",function(event){
		formDesigner_clickElement(event.target);
	});
	
	$("#formDesigner a[href=#snippet]").on("click",function(){
		var snippet=$(this).attr("data");
		var load=$("<meta>");
		var target="#formDesignerEditor";
		if ($("#formDesignerEditor [data-current]").length) {target="#formDesignerEditor [data-current]";}
		$.get("/engine/ajax.php?mode=snippet&form=form&snippet="+snippet,function(data){
			if ($(target).html()=="&nbsp;") {$(target).html("");}
			var data=$(data);
			$(target).append(data);
			if (!$("#formDesignerEditor [data-current]").length) {$(data).trigger("click");}
		});
	});
	
	function formDesigner_clickElement(that) {
		$("#formDesignerEditor [data-current]").removeAttr("data-current");
		$("#formDesignerEditor #formDesignerToolBtn").hide();
		if (!$(that).is("#formDesignerToolBtn") && !$(that).parents("#formDesignerToolBtn").length) {
			
			$(that).attr("data-current",true);
			var x=$(that).offset().left;
			var y=$(that).offset().top-24;
			var tool=$("#formDesignerEditor #formDesignerToolBtn");
			$(tool).css("left",x+"px").css("top",y+"px");
			var tagName=that.tagName;
			if ($(that).attr("id")>"") {tagName+="#"+$(that).attr("id");}
			var className=that.className;
				className=trim(str_replace(" ",".",className));
			if (className>"") {className="."+className;}
			var parent="";
			parent='<a href="#parent"><i class="fa fa-arrow-left"></i></a>';
			$("#formDesigner header .toParent").html(parent);
			$("#formDesigner header .currentInfo").html(tagName+className);
			$(tool).show();
		}
	}
	
</script>

<style>
#formDesignerEditor {min-height:10px; background:#fff;}
#formDesignerEditor {padding:5px;}
#formDesignerEditor [data-current] {border:1px #FFA500 dashed; background: rgba(98, 122, 173, 0.15);cursor:move;}
#formDesignerEditor [data-hovered] { background: rgba(98, 122, 173, 0.25);}
#formDesignerToolBtn {position:fixed; display:inline-block; width:auto; background:#FFA500; z-index:100; padding:2px;
	border-radius: 4px 4px 0px 0px; color:#fff;}
#formDesignerToolBtn i.fa {margin-left:4px; margin-right:4px;}
#formDesignerToolBtn i.fa:hover  {color:#90EE90;}
#formDesigner header .currentInfo a {color:#fff;}
</style>
