/*
 *  Document   : compTodo.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in To do list page
 */

var CompTodo = function() {

    return {
        init: function() {
            var taskList        = $('.task-list');
            var taskInput       = $('#add-task');
            var taskInputVal    = '';
            var taskCategory	= $('#tasksCatalog li.active').attr("data-id");

            /* On page load, check the checkbox if the class 'task-done' was added to a task */
            $('.task-done input:checkbox').prop('checked', true);

            /* Toggle task state */
            taskList.on('click', 'input:checkbox', function(){
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
            taskList.on('click', '.task-close', function(){
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


            taskList.on('click', '.task-menu', function(){
				App.sidebar('open-sidebar-alt');
                var id=$(this).parents("li").attr("data-id");
				data={};
				data.task=$(this).parents("li").find("label span").text();
				data.id=$(this).parents("li").attr("data-id");
				data.category=$(this).parents("li").attr("data-category");
				data.time=$(this).parents("li").attr("data-time");
				data.done=$(this).parents("li").find("input:checkbox").prop('checked');
				var res=content_set_data("script#task-tpl",data,true);
                $("#task-form section").html($(res.responseText).find("#task-tpl").html());
                $("#task-form section form").attr("item",data.id);
                $("#task-form section form").attr("parent-template",taskList.attr("data-template")).attr("data-add","true");
                $("#sidebar-alt").trigger('focus');
                if (data.done==true) {$("#task-form [name=done]").trigger("click");}
                active_plugins();
                return false;
            });
            
            taskList.on('click', '[contenteditable]', function(){
				return false;
            });
            
            taskList.on('keydown', '[contenteditable]', function(e){
				var code = e.which;
				if(code==13) {
					$(this).parents('label').trigger('focusout');
					return false;
				}
            });            

            taskList.on('click', '.task-edit', function(){
					$(this).parents("li").find("label span").text($(this).parents("li").find("label span").text());
					$(this).parents("li").find("label span").attr("contenteditable",true).focus();
                    var data={};
                    data.id=$(this).parents("li").attr("data-id");
                    
            });
            
			taskList.on('focusout', 'label', function(){
					$(this).find("span[contenteditable=true]").removeAttr("contenteditable");
					var data={};
					data.id=$(this).parents("li").attr("data-id");
					data.task=$(this).text();
					updTodo(data);
					return false;
            });
            

            /* Add a new task to the list */
            $('#add-task-form').on('submit', function(){
                taskInputVal = taskInput.prop('value');
                if ( taskInputVal ) {
                    var data={};
                    data.task=taskInputVal;
                    data.category=taskCategory;
                    data.done="";
                    var id=addTodo(data);
                    if (id!==false) {
						var data=getTodo(id);
						var ret=template_set_data(".task-list",data,true);
						console.log(ret);
						taskList
							.prepend('<li class="animation-slideDown" data-id="'+id+'">' +
								'<a href="javascript:void(0)" class="task-close text-danger"><i class="fa fa-times"></i></a>' +
								'<label class="checkbox-inline">' +
								'<input type="checkbox">' +
								$('<span />').text(taskInputVal).html() +
								'</label>' +
								'</li>');
						taskInput.prop('value', '').focus();
					}

                }
                return false;
            });
        }
    };
    
    function addTodo(data) {
		var res=false;
		$.ajax({
			url: "/engine/ajax.php?mode=ajax&form=tasks&action=add",
			async:false, method: "post", data: data,
			success: function(data){
				data=JSON.parse(data);
				if (data.id!==undefined) {res=data.id; console.log(res);}
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
    
    
}();

