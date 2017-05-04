/*
 *  Document   : compTodo.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in To do list page
 */

var CompTodo = function() {
    return {
        init: function() {
            var taskInput       = $('#add-task');
            var taskOptions     = $('#todo-options');
            var taskAddCatry    = $('#add-category');
            var taskInputVal    = '';
            var taskCatryVal    = '';
            var taskCatalog		= $('#tasksCatalog');
            var taskForm		= $('#task-form');
			taskAddCatry.hide();
            /* On page load, check the checkbox if the class 'task-done' was added to a task */
            $('.task-done input:checkbox').prop('checked', true);
			countTodo();
            /* Toggle task state */
            $('.task-list').on('click', 'input:checkbox', function(){
				if ($(this).parents('li').find("[contenteditable=true]").length) {
					return false;
				} else {
					$(this).parents('li').toggleClass('task-done');
					var data={};
					data.id=$(this).parents("li").attr("data-id");
					data.done="";
					if ($(this).parents('li').hasClass('task-done')) {data.done=1;}
					updTodo(data);
				}
            });

            /* Remove a task from the list */
            $('.task-list').on('click', '.task-close', function(){
                $(this).parents('li').slideUp(200);
                    var data={};
                    data.id=$(this).parents("li").attr("data-id");
                    delTodo(data);
            });
            
            $("#sidebar-alt").on('focusout', function(e){
				if (!$(e.relatedTarget).hasClass("task-menu") && !$(e.relatedTarget).parents("#sidebar-alt").length && !$(e.relatedTarget).is("#sidebar-alt")) {
					App.sidebar('close-sidebar-alt');
				}
			});


			taskOptions.on('click','.fa-plus',function(){
				taskAddCatry.toggle().focus();
			});
			
			taskForm.on('click','button.btn[data]',function(){
				taskForm.find("input[name=status]").val($(this).attr("data"));
			});

            $('.task-list').on('click', '.task-menu', function(){
				App.sidebar('open-sidebar-alt');
                var id=$(this).parents("li").attr("data-id");
				data={};
				data.task=$(this).parents("li").find("label span").text();
				data.id=$(this).parents("li").attr("data-id");
				data.category=$(this).parents("li").attr("data-category");
				data.time=$(this).parents("li").attr("data-time");
				data.status=$(this).parents("li").attr("data-status");
				data.done=$(this).parents("li").hasClass("task-done");
				var res=content_set_data("script#task-tpl",data,true);
                $("#task-form section").html($(res.responseText).find("#task-tpl").html());
                $("#task-form section form").attr("item",data.id);
                $("#task-form section form").attr("parent-template",$('.task-list').attr("data-template")).attr("data-add","true");
                $("#sidebar-alt").trigger('focus');
                if (data.done==true) {$("#task-form [name=done]").trigger("click");}
                active_plugins();
                return false;
            });
            
            $('.task-list').on('click', '[contenteditable]', function(){
				return false;
            });
            
            $('.task-list').on('keydown', '[contenteditable]', function(e){
				var code = e.which;
				if(code==13) {
					$(this).parents('label').trigger('focusout');
					return false;
				}
            });            

            $('.task-list').on('click', '.task-edit', function(){
					$(this).parents("li").find("label span").text($(this).parents("li").find("label span").text());
					$(this).parents("li").find("label span").attr("contenteditable",true).focus();
                    var data={};
                    data.id=$(this).parents("li").attr("data-id");
                    
            });
            
			$('.task-list').on('focusout', 'label', function(){
					$(this).find("span[contenteditable=true]").removeAttr("contenteditable");
					var data={};
					data.id=$(this).parents("li").attr("data-id");
					data.task=$(this).find("span").text();
					updTodo(data);
					return false;
            });
            
            taskCatalog.on('click','li',function(){
				var cid=$(this).attr("data-id");
				$('.task-list').find("li:not([data-category="+cid+"])").slideUp(100);
				$('.task-list').find("li[data-category="+cid+"]").slideDown(100);
			});

			$(document).off("tasks_after_formsave");
			$(document).on("tasks_after_formsave",function(event,name,item,form){
				setTimeout(function(){
					//taskCatalog.find("li.active a").trigger("click");
					var cid=taskCatalog.find("li.active").attr("data-id");
					var tid=$('.task-list').find("li[data-id="+item+"]").attr("data-category");
					if (cid!==tid) {$('.task-list').find("li[data-id="+item+"]").slideUp(200);}
					countTodo();
				},300);
			});

            /* Add a new task category to the list */
            $('#add-category-form').on('submit', function(){
                taskCatryVal = taskAddCatry.prop('value');
                if ( taskCatryVal ) {
                    var data={};
                    data.category=taskCatryVal;
                    data.id=addTodoCategory(data);
                    if (data.id!==false) {
						var uns=$("#tasksCatalog li[data-id=unsorted]").clone();
						var arc=$("#tasksCatalog li[data-id=arc]").clone();
						var ret=content_set_data("#tasksCatalog",data,true);
						$("#tasksCatalog").html($(ret.responseText).find("#tasksCatalog").html());
						$("#tasksCatalog").prepend(uns).append(arc);
						taskAddCatry.prop('value', '').focus();
						taskAddCatry.hide();
					}

                }
                return false;
            });


            /* Add a new task to the list */
            $('#add-task-form').on('submit', function(){
                taskInputVal = taskInput.prop('value');
                if ( taskInputVal ) {
                    var data={};
                    data.task=taskInputVal;
                    data.category=$('#tasksCatalog li.active').attr("data-id");
                    data.done="";
                    data.status="default";
                    var id=addTodo(data);
                    if (id!==false) {
						var data=getTodo(id);
						var ret=template_set_data(".task-list",data,true);
						$('.task-list').prepend($(ret.responseText).html());
						taskInput.prop('value', '').focus();
						countTodo();
					}

                }
                return false;
            });
            
            
			function addTodo(data) {
				var res=false;
				$.ajax({
					url: "/engine/ajax.php?mode=ajax&form=tasks&action=add",
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
					url: "/engine/ajax.php?mode=ajax&form=tasks&action=addcategory",
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
					url: "/engine/ajax.php?mode=ajax&form=tasks&action=del",
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
				var ajax= "/engine/ajax.php?mode=ajax&form=tasks&action=getitem";
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
					url: "/engine/ajax.php?mode=ajax&form=tasks&action=upd",
					async: false, method: "post", data: data,
					success: function(data){
								data=JSON.parse(data);
								err=data;
					}
				});		
			}
			
			function countTodo() {
				
				$.ajax({
					url: "/engine/ajax.php?mode=ajax&form=tasks&action=counter",
					async: false, method: "post", 
					success: function(data){
								data=JSON.parse(data);
								$.each(data,function(c){
									taskCatalog.find("li[data-id="+c+"] .badge").html(data[c]);
								});
					}
				});	
			}           
        }
    };   
}();



