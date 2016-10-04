<div id="comGallery">
<link rel="stylesheet" href="/engine/tpl/css/gallery.css" type="text/css" media="all" />
<ul class="comGallery" data-role="foreach" form="{{form}}" item="{{id}}" from="images" >
	<li class="thumbnail"><a href="{{_SESS[prj_path]}}/uploads/{{%form}}/{{%id}}/{{img}}">
		<img data-role="thumbnail" visible="{{visible}}" title="{{title}}" alt="{{alt}}" size="160px;120px" src="/uploads/{{%form}}/{{%id}}/{{img}}" />
	</a></li>
</ul>
</div>
