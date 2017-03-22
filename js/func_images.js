$(document).ready(function(){
commonImageUpload();
$(document).on("data-ajax-done",function(event,target,ajax){
	if ($(target).find("[data-role=imageloader]").length) {commonImageUpload();}
});

});


function commonImageUpload() {
		$("div.imageloader .moxie-shim").remove();
		$("#comImagesUpl #uploadfiles").hide();
		var  store=$("div.imageloader input[data-role=imagestore]");
		var path=$("div.imageloader").attr("path");
		var max=store.attr("data-max"); if (max==undefined) {max=10;}
		var ext=store.attr("data-ext");
		if (ext>"") {
			var ext=explode(" ",ext);
			var types=[];
			for(i=0; i<ext.length; i++) {types.push({title : ext[i], extensions : ext[i]});}
		} else {
			var types= [
					{title : "Image files", extensions : "jpg,gif,png"},
					{title : "Zip files", extensions : "zip"},
					{title : "Pdf files", extensions : "pdf"}
				]			
		}
		var uploader = new plupload.Uploader({
		runtimes : 'html5,html4',
		browse_button : 'pickfiles', // you can pass in id...
		container: document.getElementById('uploader'), // ... or DOM Element itself
		url : '/engine/js/uploader/upload.php?path='+path,
		dragdrop: true,
		chunk_size : '1mb',
		unique_names : true,
		//resize : {width : 320, height : 240, quality : 90},
		filters : {
			max_file_size : max+'mb',
			mime_types: types
		},
		init: {
			PostInit: function() {
				document.getElementById('filelist').innerHTML = '';
				document.getElementById('uploadfiles').onclick = function() {
					uploader.start();
					return false;
				};
				comImagesList(path);
			},
			FilesAdded: function(up, files) {
				plupload.each(files, function(file) {
					$("#filelist").append('<div class="list-group-item" id="' + file.id + '"><span class="glyphicon glyphicon-upload"></span>&nbsp;<b>' + file.name + '</b>  <span class="badge">' + plupload.formatSize(file.size) + '</span></div>');
				});
				uploader.start();
			},
			FileUploaded: function(up, file, res) {
				var res=$.parseJSON(res.response);
				$("#filelist #"+file.id).remove();
				var name=res.id.toLowerCase();
				comImagesAddToList(name);
				$("#comImagesAll ul.gallery li:last").trigger("click");
			},
			UploadProgress: function(up, file) {
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			},
			Error: function(up, err) {
				document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
			}
		}
		});
		uploader.init();
		comImagesEvents();
		var images=store.val();
		if (images>"") {
			var gal=JSON.parse(store.val());
		} else {var gal=new Array();}
		$(".plupload_button").addClass("btn btn-default").removeClass("plupload_button");
}

function comImagesEvents() {
		$("#comImagesAll").off("mouseenter","ul li");
		$("#comImagesAll").on ("mouseenter","ul li",function(){
			$(this).find(".dropdown-menu").remove();
			$(this).find("a.delete").after('<ul class="dropdown-menu" role="menu"><li class="bg-danger"><a href="#" class="delete-confirm"><span class="glyphicon glyphicon-trash"></span> Удалить</a></li><li><a href="#"><span class="glyphicon glyphicon-ban-circle"></span> Отмена</a></li></ul>');
			$(this).tooltip({title:$(this).attr("data-name")});
			$(this).tooltip("toggle");
			
			
		});
		$("#comImagesAll").off("mouseleave","ul li");
		$("#comImagesAll").on ("mouseleave","ul li",function(){
			if ($(this).find(".dropdown-menu:visible").length) {
				//$(this).find("a.delete").trigger("click");
				$(this).find(".dropdown-menu").remove();
			}
		});

		$("#comImagesAll").off("click","ul:first li a.delete-confirm");
		$("#comImagesAll").on ("click","ul:first li a.delete-confirm",function(){
			var name=$(this).parents("li.thumbnail").attr("data-name");
			var path=$("div.imageloader").attr("path");
			var that=$(this);
			$.get("/engine/ajax.php?mode=ajax_deletefile&path="+path+"&file="+name,function(data){
				$(that).parents("li").tooltip("destroy");
				var data=JSON.parse(data);
				var error=data.error;
				if (error==0) {
					that.parents("li.thumbnail").remove();
				} else {
					if (confirm("Ошибка удаления! Убрать превью?")) {
					  that.parents("li.thumbnail").remove();
					}
				}
				comImagesToField();
			});
		});

		$("#comImagesAll").delegate("ul li input, ul li textarea","click",function(){
			return false;
		});
		$("#comImagesAll").delegate("ul li input, ul li textarea","keyup",function(){
			return false;
		});

		$("#comImagesAll").delegate(".imagesAttr .close","click",function(){
			$(this).parents("#comImagesAll").prepend($(this).parents(".imagesAttr"));
		});

		$("#comImagesAll").off("click","ul li a.info");
		$("#comImagesAll").on("click","ul li a.info",function(){
			if ($(this).parents("li").next("li").is(".imagesAttr")) {
				$("#comImagesAll .imagesAttr .close").trigger("click");
			} else {
				$(this).parent("li").after($(this).parents("#comImagesAll").find(".imagesAttr"));
				var imginfo=$(this).parents("#comImagesAll").find(".imagesAttr");
				var imgnum=$(this).parent("li").index();
				var imgname=$(this).parent("li").attr("data-name");
				var imgpath=$(this).parents(".imageloader").attr("path")+imgname;

				$("#comImagesAll").data("imgnum",imgnum);
				imginfo.find("textarea,input").val("");
				imginfo.find(".attr-link").val(imgpath);
				imginfo.find(".attr-alt").val($(this).parent("li").attr("alt"));
				imginfo.find(".attr-title").val($(this).parent("li").attr("title"));
				//var formname=$(this).parents("#comImagesAll").parents("form[role=form]").attr("name");
				//var itemname=$(this).parents("#comImagesAll").parents("form[role=form]").attr("item");
			}
			return false;
		});

		$("#comImagesAll").delegate(".imagesAttr .attr-alt, .imagesAttr .attr-title","focusout",function(){
			var imgnum=$("#comImagesAll").data("imgnum");
			if ($(this).is(".attr-title")) {
				$("#comImagesAll ul.gallery li.thumbnail:eq("+imgnum+")").attr("title",$(this).val());
			}
			if ($(this).is(".attr-alt")) {
				$("#comImagesAll ul.gallery li.thumbnail:eq("+imgnum+")").attr("alt",$(this).val());
			}
			comImagesToField();
		});


		$("#comImagesAll").off("click","ul.gallery li.thumbnail");
		$("#comImagesAll").on ("click","ul.gallery li.thumbnail",function(){
			if ($(this).hasClass("selected")) {$(this).removeClass("selected");} else {$(this).addClass("selected");}
			comImagesToField();
		});

}


function comImagesList(path) {
	$.get("/engine/ajax.php?mode=ajax_listfiles&path="+path,function(data){
		var  store=$("div.imageloader input[data-role=imagestore]");
		var gallery=JSON.parse(data);
		var images=store.val();
		var ext=store.attr("data-ext");
		if (ext!==undefined) {
			var exts=explode(" ",ext);
		} else {
			var exts=new Array("jpg","png","gif","pdf");
		}
		$("#comImagesAll").data("images",images);
		if (images!=="") {
			images=JSON.parse(images);
			$(images).each(function(i){
				var ext=images[i]["img"].split('.');
				var ext=ext[ext.length-1].toLowerCase();
				if (in_array(images[i]["img"],gallery)) {gallery.splice(array_search(images[i]["img"],gallery),1);}
				if (in_array(ext,exts)) {comImagesAddToList(images[i]["img"],true);}
			});
		}
		$(gallery).each(function(i){
			var ext=gallery[i].split('.');
			var ext=ext[ext.length-1].toLowerCase();
			if (in_array(ext,exts)) {comImagesAddToList(gallery[i],false);}
		});
		comImagesSort();
		$("#comImagesAll ul").sortable({	update: function() { comImagesToField(); }	});
	});
}
 
function comImagesSort() {
	var  store=$("div.imageloader input[data-role=imagestore]");
	var images=store.val();
	if (images=="") {var images=[];} else {var images=JSON.parse(images);}
	$("#comImagesAll ul.gallery").after("<ul class='tmp' style='display:none;'></ul>");
	$(images).each(function(i,img){
		var name=img["img"];
		that=$("#comImagesAll ul.gallery > li[data-name='"+name+"']");
		that.attr("alt",img["alt"]);
		that.attr("title",img["title"]);
		if (that.length) {
			if (img.visible==1 || img.visible==undefined) {that.addClass("selected");}
			$("#comImagesAll ul.tmp").append(that);
		}
	});
	$("#comImagesAll ul.gallery > li").each(function(){
		$(this).addClass("selected");
		$("#comImagesAll ul.tmp").append($(this));
	});
	$("#comImagesAll ul.gallery").html($("#comImagesAll ul.tmp").html());
	$("#comImagesAll ul.tmp").remove();
}

function comImagesToField() {
	var images = new Array();
	var  store=$("div.imageloader input[data-role=imagestore]");
	$("#comImagesAll ul li.thumbnail").each(function(i){
		if ($(this).hasClass("selected")) {var sel=1;} else {var sel=0;}
		var img = {
			img: $(this).attr("data-name"),
			title: $(this).attr("title"),
			alt: $(this).attr("alt"),
			visible: sel
		}
		images.push(img);
	});
	store.val(JSON.stringify(images));
}

function comImagesAddToList(name,vis) {
	var path="/engine/phpThumb/phpThumb.php?w=250&src="+$("div.imageloader").attr("path");
	var title=""; var alt=""; var visible=1;
	var images=$("#comImagesAll").data("images");
	if (images=="") {var images=[];} else {var images=JSON.parse(images);}
	$(images).each(function(i,img){
		if (img["img"]==name) {title=img["title"]; alt=img["alt"]; visible=img["visible"];}
	});
	var path=$("div.imageloader").attr("path");
	var thumb="<img data-role='thumbnail' size='250px;130px;bkg' src='"+path+name+"'/>";
	var url="/engine/ajax.php?mode=content_set_data";
	$.ajax({
		async: 		true,
		type:		'POST',
		data:		{html:thumb},
		url: 		url,
		success: 	function(data){
			var thumbnail=$(data).html();
			if (!$("#comImagesAll ul.gallery li[data-name='"+name+"']").length) {
				$("#comImagesAll ul.gallery").append('<li class="thumbnail" data-name="'+name+'" title="'+title+'" alt="'+alt+'" ></li>');
				$("#comImagesAll ul.gallery li:last").append(thumbnail);
				$("#comImagesAll ul.gallery li:last").append('<a href="#" class="btn btn-default btn-xs delete dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-remove-sign"></span></a>');
				$("#comImagesAll ul.gallery li:last").append('<a href="#" class="btn btn-default btn-xs info"><span class="glyphicon glyphicon-info-sign"></span></a>');			
			}
			//$("#comImagesAll ul.gallery li:last").trigger("click");
		}
	});
}
