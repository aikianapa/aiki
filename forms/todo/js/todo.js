/*
 *  Document   : compTodo.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in To do list page
 */

var CompTodo = function() {
    return {
        init: function() {
            var todoInput       = $('#add-todo');
            var todoOptions     = $('#todo-options');
            var todoAddCatry    = $('#add-category');
            var todoInputVal    = '';
            var todoCatryVal    = '';
            var todoCatalog		= $('#todoCatalog');
            var todoForm		= $('#todo-form');
            var todoEdit		= $('.item-title');
			todoAddCatry.hide();
            /* On page load, check the checkbox if the class 'todo-done' was added to a todo */
            $('.todo-done input:checkbox').prop('checked', true);
			countTodo();
            /* Toggle todo state */
            $('.todo-list').on('click', 'input:checkbox', function(){
				if ($(this).parents('li').find("[contenteditable=true]").length) {
					return false;
				} else {
					$(this).parents('li').toggleClass('todo-done');
					var data={};
					data.id=$(this).parents("li").attr("data-id");
					data.done="";
					if ($(this).parents('li').hasClass('todo-done')) {data.done=1;}
					updTodo(data);
				}
            });

			todoEdit.on('click',function(){
				$(this).attr("contenteditable",true);
				$(this).focus();
			});

            /* Remove a todo from the list */
            $('.todo-list').on('click', '.todo-close', function(){
                $(this).parents('li').slideUp(200);
                    var data={};
                    data.id=$(this).parents("li").attr("data-id");
                    delTodo(data);
            });
            
            $("#sidebar-alt").on('focusout', function(e){
				if (!$(e.relatedTarget).hasClass("todo-menu") && !$(e.relatedTarget).parents("#sidebar-alt").length && !$(e.relatedTarget).is("#sidebar-alt")) {
					App.sidebar('close-sidebar-alt');
				}
			});


			
			todoForm.on('click','button.btn[data]',function(){
				todoForm.find("input[name=status]").val($(this).attr("data"));
			});

            $('.todo-list').on('click', '.todo-menu', function(){
				App.sidebar('open-sidebar-alt');
                var id=$(this).parents("li").attr("data-id");
				data={};
				data.task=$(this).parents("li").find("label span").text();
				data.id=$(this).parents("li").attr("data-id");
				data.category=$(this).parents("li").attr("data-category");
				data.time=$(this).parents("li").attr("data-time");
				data.status=$(this).parents("li").attr("data-status");
				data.done=$(this).parents("li").hasClass("todo-done");
				var res=content_set_data("script#todo-tpl",data,true);
                $("#todo-form section").html($(res.responseText).find("#todo-tpl").html());
                $("#todo-form section form").attr("item",data.id);
                $("#todo-form section form").attr("parent-template",$('.todo-list').attr("data-template")).attr("data-add","true");
                $("#sidebar-alt").trigger('focus');
                if (data.done==true) {$("#todo-form [name=done]").trigger("click");}
                active_plugins();
                return false;
            });
            
            $('.todo-list').on('click', '[contenteditable]', function(){
				return false;
            });
            
            $('.todo-list').on('keydown', '[contenteditable]', function(e){
				var code = e.which;
				if(code==13) {
					$(this).parents('label').trigger('focusout');
					return false;
				}
            });            

            $('.todo-list').on('click', '.todo-edit', function(){
					$(this).parents("li").find("label span").text($(this).parents("li").find("label span").text());
					$(this).parents("li").find("label span").attr("contenteditable",true).focus();
                    var data={};
                    data.id=$(this).parents("li").attr("data-id");
                    
            });
            
			$('.todo-list').on('focusout', '.item-title', function(){
					$(this).find("span[contenteditable=true]").removeAttr("contenteditable");
					var data={};
					data.id=$(this).parents("li").attr("data-id");
					data.task=$(this).find("span").text();
					updTodo(data);
					return false;
            });
            
            todoCatalog.on('click','li',function(){
				var cid=$(this).attr("data-id");
				$('.todo-list').find("li:not([data-category="+cid+"])").slideUp(100);
				$('.todo-list').find("li[data-category="+cid+"]").slideDown(100);
			});

			$(document).off("todo_after_formsave");
			$(document).on("todo_after_formsave",function(event,name,item,form){
				setTimeout(function(){
					//todoCatalog.find("li.active a").trigger("click");
					var cid=todoCatalog.find("li.active").attr("data-id");
					var tid=$('.todo-list').find("li[data-id="+item+"]").attr("data-category");
					if (cid!==tid) {$('.todo-list').find("li[data-id="+item+"]").slideUp(200);}
					countTodo();
				},300);
			});

            /* Add a new todo category to the list */
            $('#add-category-form').on('submit', function(){
                todoCatryVal = todoAddCatry.prop('value');
                if ( todoCatryVal ) {
                    var data={};
                    data.category=todoCatryVal;
                    data.id=addTodoCategory(data);
                    if (data.id!==false) {
						var uns=$("#todoCatalog li[data-id=unsorted]").clone();
						var arc=$("#todoCatalog li[data-id=arc]").clone();
						var ret=content_set_data("#todoCatalog",data,true);
						$("#todoCatalog").html($(ret.responseText).find("#todoCatalog").html());
						$("#todoCatalog").prepend(uns).append(arc);
						todoAddCatry.prop('value', '').focus();
						todoAddCatry.hide();
					}

                }
                return false;
            });


            /* Add a new todo to the list */
            $('#add-todo-form').on('submit', function(){
                todoInputVal = todoInput.prop('value');
                if ( todoInputVal ) {
                    var data={};
                    data.task=todoInputVal;
                    data.category=$('#todoCatalog li.active').attr("data-id");
                    data.done="";
                    data.status="default";
                    var id=addTodo(data);
                    if (id!==false) {
						var data=getTodo(id);
						var ret=template_set_data(".todo-list",data,true);
						$('.todo-list').prepend($(ret.responseText).html());
						todoInput.prop('value', '').focus();
						countTodo();
					}

                }
                return false;
            });
            
            
			function addTodo(data) {
				var res=false;
				$.ajax({
					url: "/engine/ajax.php?mode=ajax&form=todo&action=add",
					async:false, method: "post", data: data,
					success: function(data){
						data=JSON.parse(data);
						if (data.id!==undefined) {res=data.id;}
					}
				});
				return res;
			}

			function addTodoCategory(data) {
				var res=false;
				$.ajax({
					url: "/engine/ajax.php?mode=ajax&form=todo&action=addcategory",
					async:false, method: "post", data: data,
					success: function(data){
						data=JSON.parse(data);
						if (data.id!==undefined) {res=data.id;}
					}
				});
				return res;
			}
			
			function delTodo(data) {
				var err=false
				if (data.category==undefined || data.category=="") {data.category="unsorted";}
				$.ajax({
					url: "/engine/ajax.php?mode=ajax&form=todo&action=del",
					async: false, method: "post", data: data,
					success: function(data){
								data=JSON.parse(data);
								err=data;
					}
				});
				return err;
			}
			
			function getTodo(id) {
				var res=false;
				var ajax= "/engine/ajax.php?mode=ajax&form=todo&action=getitem";
				$.ajax({
					url:ajax,
					async: false, method: "post", data: {id:id},
					success: function(data){
								data=JSON.parse(data);
								res=data;
					}
				});
				return res;
			}
			
			function updTodo(data) {
				$.ajax({
					url: "/engine/ajax.php?mode=ajax&form=todo&action=upd",
					async: false, method: "post", data: data,
					success: function(data){
								data=JSON.parse(data);
								err=data;
					}
				});		
			}
			
			function countTodo() {
				
				$.ajax({
					url: "/engine/ajax.php?mode=ajax&form=todo&action=counter",
					async: false, method: "post", 
					success: function(data){
								data=JSON.parse(data);
								$.each(data,function(c){
									todoCatalog.find("li[data-id="+c+"] .badge").html(data[c]);
								});
					}
				});	
			}           
        }
    };   
}();



