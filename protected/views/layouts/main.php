<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Linden Question Builder</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->getBaseUrl(true);?>/js/library/base64.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
    $("#listExam").tooltip({placement : 'bottom', title : 'List exams!'});
    $("#add").tooltip({placement : 'bottom', title : 'Add course,exam,question,category'});
    $("#lindenBrand").tooltip({placement : 'bottom', title : 'Linden Question Builder is a trademark of Linden Digital Publishing Co.'});
    $(".download_exam").tooltip({placement : 'bottom', title : 'Download the exam as pdf!'});
    $(".edit_exam").tooltip({placement : 'bottom', title : 'Update the exam information!'});
    $(".delete_exam").tooltip({placement : 'bottom', title : 'Delete the exam!'});
    $(".clone_exam").tooltip({placement : 'bottom', title : 'Clone the exam!'});
    $(".open_exam").tooltip({placement : 'bottom', title : 'Open the exam!'});
    function addError(element,data){

			$("#"+element).parent().append("<span class='help-block'>"+print_array(data)+"</span>")
	}
	function print_array(data){
		console.log("ege",data)
		var result="";
		for(var i=0;i<data.length;i++){
			result+=data[i];
		}
		return result;
	}
	$('.delete_course').click(function(event){
		var course_id=$(this).data("course_id");
		var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/delete/course",{course_id:course_id})
			  .done(function(data) {
			  	console.log('Response:',data);
			  	window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/course";
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("deleting course finished!");
			});
		

		}
	);
	$('#profile_save').click(function(){
		var profile_username=$('#profile_username').val();
		var profile_password=$('#profile_password').val();
		var profile_password_again=$('#profile_password_again').val();
		var that=this;
		if(profile_password==profile_password_again){
				var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/profile/save",{username:profile_username,password:profile_password})
					  .done(function(data) {
					  	console.log('Response:',data);
					  	if($(that).hasClass('for_exam'))
						{
							window.for_exam=true;

							window.location.reload();
						}
						else
						{
							show_modal('success','Your username and password have been successfully changed!');
					  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/profile/open";
					  	}
					  })
					  .fail(function() {
					    alert( "error" );
					  })
					  .always(function() {
					    console.log("deleting question finished!");
					});			
		}
		else
		{
			show_modal('warning','Entered passwords do not match!');
		}

	});

	$('.edit_course').click(function(event){
		$('#addCourseTitle').html('Update Course');
		var course_id=$(this).data('course_id');
		var course_code=$(this).data('course_code');
		var course_name=$(this).data('course_name');

		$('#add_course_id').val(course_id);
		$('#add_course_code').val(course_code);
		$('#add_course_name').val(course_name);
		$("#addCourse").modal('show');

	});
	$('#add_course').click(function(event){
		$('.help-block').remove();
		$('#addCourseTitle').html('Add Course');
		$('#add_course_code').val('');
		$('#add_course_name').val('');
		$("#addCourse").modal('show');

	});
	var option='<div class="input-group" style="margin-bottom:20px;"> \
            <input type="text" class="form-control" id="add_multiplechoice_question_answer" name="question_answer" style="margin-left:15px;padding-right:20px;" placeholder="Write an option.">\
            <span class="input-group-btn" style="z-index:999">\
                <button type="button" class="btn btn-default add_option_up" ><span class="glyphicon glyphicon-arrow-up"></span></button>\
                <button type="button" class="btn btn-default add_option_down"><span class="glyphicon glyphicon-arrow-down"></span></button>\
                <button type="button" class="btn btn-default remove_option"><span class="glyphicon glyphicon-trash"></span></button>\
            </span>\
        </div>';
    function AddOptionDown(){
    	$('.add_option_down').unbind( "click" );
    	$('.add_option_down').click(
			function(event){
	        	$(this).parent().parent().after(option);
	        	AddOptionDown();
	        	AddOptionUp();
	        	RemoveOption();
				});
    }
	function AddOptionUp(){
	    $('.add_option_up').unbind( "click" );
		$('.add_option_up').click(
			function(event){
	        	$(this).parent().parent().before(option);
	        	AddOptionDown();
	        	AddOptionUp();
	        	RemoveOption();
				});
	}
	function RemoveOption(){
		$('.remove_option').unbind( "click" );
		$('.remove_option').click(
			function(event){
				if($('.remove_option').length>1){
			    	$(this).parent().parent().remove();
			    	RemoveOption();
			    	}
				});
	}
	$('.delete_question').click(function(event){
		var question_id=$(this).data("question_id");
		var that=this;
		var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/delete/question",{question_id:question_id})
			  .done(function(data) {
			  	console.log('Response:',data);
			  	if($(that).hasClass('for_exam'))
				{
					window.for_exam=true;
					window.location.reload();
				}
				else
				{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";
			  	}
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("deleting question finished!");
			});

	});
	$('.clone_question').click(function(event){
		var question_id=$(this).data("question_id");
		var parameters=new Object();
		if($(this).hasClass('for_exam'))
		{
			for_exam=true;
			parameters.exam_id=$(this).data("exam_id");
		}
		parameters.question_id=question_id;
		var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/clone/question",parameters)
			  .done(function(data) {
			  	console.log('Response:',data);
			  	if(window.for_exam)
			  	{
			  		window.location.reload();
			  	}
			  	else
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";
			  	}
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("clonning question finished!");
			});		
	});
	$('.move_up_question').click(function(event){
		var question_id=$(this).data("question_id");
		var parameters=new Object();
		if($(this).hasClass('for_exam'))
		{
			for_exam=true;
			parameters.exam_id=$(this).data("exam_id");
		}
		parameters.question_id=question_id;
		console.log(parameters);
		var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/add/moveupquestion",parameters)
			  .done(function(data) {
			  	console.log('Response:',data);
			  	if(window.for_exam)
			  	{
			  		window.location.reload();
			  	}
			  	else
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";
			  	}
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("clonning question finished!");
			});		
	});
	$('.move_down_question').click(function(event){
		var question_id=$(this).data("question_id");
		var parameters=new Object();
		if($(this).hasClass('for_exam'))
		{
			for_exam=true;
			parameters.exam_id=$(this).data("exam_id");
		}
		parameters.question_id=question_id;
		console.log(parameters);
		var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/add/movedownquestion",parameters)
			  .done(function(data) {
			  	console.log('Response:',data);
			  	if(window.for_exam)
			  	{
			  		window.location.reload();
			  	}
			  	else
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";
			  	}
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("clonning question finished!");
			});		
	});	

	if(typeof sessionStorage.basket == 'undefined')
		{
			$('#basket_length').html("0");
			sessionStorage.basket=JSON.stringify(new Array());
		}
	else
		{
			$('#basket_length').html(JSON.parse(sessionStorage.basket).length);
		}
	$('#empty_basket').click(function(event){
		sessionStorage.basket=JSON.stringify(new Array());
		$('#basket_length').html("0");
		show_modal('success','The basket has been emptied successfully!');

	});
	$('#open_basket').click(function(event){
		$('#openBasketBody').empty();
		var modal_visibility=true;
		var basket=JSON.parse(sessionStorage.basket);
		console.log("basket",basket);
				var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/list/basket",{basket:base64_encode(sessionStorage.basket)})
			  .done(function(data) {
			  	console.log('Response:',data);
			  	if(data=="")
			  	{
			  		//window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";
			  		show_modal('warning','There is no item left in the basket, so you cannot list it');
			  		modal_visibility=false;
			  	}
			  	else
			  	{
				  	for(var item in data)
				  	{
				  		console.log("item",item);
				  		var question_object;
				  		var question_content=JSON.parse(base64_decode(data[item].question_content));
				  		if(data[item].question_type=='OpenEnded')
				  		{	
				  			var question_object=$('<div style="margin-bottom:20px;" class="alert alert-info"><div class="panel-body"><strong>Question:</strong>'+question_content.question_body+'</div><div class="panel-footer clearfix"><div class="pull-left"><a data-question_id="'+data[item].question_id+'" class="btn btn-default delete_basket_question"><span class="glyphicon glyphicon-trash"></span></a></div></div></div>');
				  		}
				  		else if(data[item].question_type=='MultipleChoice')
				  		{

				  			console.log(question_content);
				  			var question_object=$('<div style="margin-bottom:20px;" class="alert alert-success"><div class="panel-body"><strong>Question:</strong>'+question_content.question_body+'</div><div class="panel-footer clearfix"><div class="pull-left"><a data-question_id="'+data[item].question_id+'" class="btn btn-default delete_basket_question"><span class="glyphicon glyphicon-trash"></span></a></div></div></div>');
				  			var ul=$("<ul></ul>");
				  			for(var option in question_content.question_options)
				  			{
				  				var answer=(question_content.question_options)[option];
				  				ul.append("<li>"+answer+"</li>");
				  			}
				  			question_object.find('.panel-body').append(ul);

				  		}
				  		$('#openBasketBody').append(question_object);


				  	}
				  	/*begin update basket*/
				  	$('.delete_basket_question').click(function(event){
						var question_id=$(this).data('question_id');
						var basket_array=JSON.parse(sessionStorage.basket);
						var found_result=basket_array.indexOf(question_id);
						if(found_result>=0)
						{
							basket_array.splice(found_result,1);
							$('#basket_length').html(basket_array.length);
							sessionStorage.basket=JSON.stringify(basket_array);
						}
						$(this).parent().parent().parent().remove();
						if(basket_array.length==0){
							$('#openBasket').modal('hide');
						}

					});
				  	/*end update basket*/

					  	


			    }
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("openning basket!");
			    if(modal_visibility)
			    $('#openBasket').modal('show');
			});



	});
	$('.move_question').click(function(event){
		var question_id=$(this).data("question_id");
		if(typeof sessionStorage.basket == 'undefined')
		{
			sessionStorage.basket=JSON.stringify(new Array());
		}
		var basket_array=JSON.parse(sessionStorage.basket);
		console.log(basket_array);
		if(basket_array.indexOf(question_id)==-1)
		{
			basket_array.push(question_id);
			$('#basket_length').html(basket_array.length);
			sessionStorage.basket=JSON.stringify(basket_array);
			show_modal('success','This question has been added to the basket!');
		}
		else
		{
			show_modal('warning','This question is already available in the basket!');
			//show_modal('success','Already Available!');
		}
		//sessionStorage.basket=JSON.stringify(JSON.parse(sessionStorage.basket).push($(this).data("question_id")));

	});

	$('.import_from_basket').click(function(event){
		var exam_id=$(this).data('exam_id');

		var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/clone/basket",{exam_id:exam_id,basket:base64_encode(sessionStorage.basket)})
			  .done(function(data) {
			  	console.log(data);
			  	sessionStorage.basket=JSON.stringify(new Array());
				$('#basket_length').html("0");
			  	window.location.reload();
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("transferring basket!");
			});




	});

	function show_modal(type,message){
		$('#status_modal_status').removeClass();
		$('#status_modal_status').addClass("modal-content panel-"+type);
		$('#status_message').html(message);
		$('#status_title').html(type);
		$('#status_modal').modal('show');
	}

	window.for_exam=false;
	$('.edit_question').click(function(event){

		$("#add_openended_question_course_id").empty();
		$("#add_multiplechoice_question_course_id").empty();
		$('#add_multiplechoice_option').empty();
		var question_type=$(this).data('question_type');
		$('#addQuestion .nav li').removeClass('active');
		$.each($('#addQuestion .nav li'),function(id,item){$(item).show();});//show all tabs

		
		if($(this).hasClass('for_exam'))
		{
			window.for_exam=true;
			$('#add_openended_question_course_id').prop( "disabled", true );//disable course selection
			$('#add_multiplechoice_question_course_id').prop( "disabled", true );
			$.each($('#addQuestion .nav li'),function(id,item){$(item).hide();})//hide all tabs
			$('#addQuestion .nav li[data-type="'+question_type+'"]').show();//show just associated one;
		}
		$('#addQuestion .nav li[data-type="'+question_type+'"] a').tab('show');
		var that=this;

			  var request = $.get( "<?php echo Yii::app()->getBaseUrl(true);?>/list/courseAndTerm")
			  .done(function(data) {

			  	console.log('Response:',data);
			  	if(data=="")
			  	{
					//window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";
			  	}
			  	else
			  	{
				  	if(data.courses)
				  	{
				  		addCourseOption("add_openended_question_course_id",data.courses);
					  	addCourseOption("add_multiplechoice_question_course_id",data.courses);
						var question_content=$.parseJSON(base64_decode($(that).data('question_content')));
				  		if(question_type=='OpenEnded')
						{
							$('#add_openended_question_body').val(question_content.question_body);
							$('#add_openended_question_space').val(question_content.question_space);
							$('#add_openended_question_course_id').val($(that).data('question_course_id'));
							$('#add_openended_question_id').val($(that).data('question_id'));
						}
						else if(question_type=='MultipleChoice')
						{
							$('#add_multiplechoice_question_body').val(question_content.question_body);
							$('#add_multiplechoice_question_course_id').val($(that).data('question_course_id'));
							$('#add_multiplechoice_question_id').val($(that).data('question_id'));
							for(var option_item in question_content.question_options){
								console.log($(option).find('input').val('egemen'));
								var option_add=$(option);
								option_add.find('input').val(question_content.question_options[option_item]);
								$('#add_multiplechoice_option').append(option_add);
							}
							AddOptionUp();
							AddOptionDown();
							RemoveOption();

						}
				  	}
			    }
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			  	$("#addQuestion").modal('show');
			    console.log("adding finished!");
			});


		
		$('#addQuestion').modal('show');

	});
	$('#add_question').click(function(event){
		$('.help-block').remove();
		$.each($('#addQuestion .nav li'),function(id,item){$(item).show();});//show all tabs
		$('#add_openended_question_course_id').prop( "disabled", false );//enable course selection
		$('#add_multiplechoice_question_course_id').prop( "disabled", false );//enable course selection

		$('#addQuestionTitle').html('Add Question');
		$("#add_multiplechoice_question_id").val('');
		$("#add_openended_question_id").val('');

		$('#add_openended_question_body').val('');
		$('#add_openended_question_space').val('0');
		$("#add_openended_question_course_id").empty();
		$("#add_multiplechoice_question_course_id").empty();

		$('#add_multiplechoice_option').empty();
        $('#add_multiplechoice_option').append(option);
 		AddOptionUp();
		AddOptionDown();
		RemoveOption();



		var request = $.get( "<?php echo Yii::app()->getBaseUrl(true);?>/list/courseAndTerm")
			  .done(function(data) {

			  	console.log('Response:',data);
			  	if(data=="")
			  	{
			  		if(window.for_exam)
			  		{
			  			window.location.reload();
			  		}
			  		else
			  		{
			  			window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";	
			  		}
			  	}
			  	else
			  	{
				  	if(data.courses)
				  	{
				  		addCourseOption("add_openended_question_course_id",data.courses);
				  		addCourseOption("add_multiplechoice_question_course_id",data.courses);
				  	}
			    }
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			  	$("#addQuestion").modal('show');
			    console.log("adding finished!");
			});





	});
//egemen
	$('#add_question_save').click(function(event){
		var question_type=$($('#addQuestion li[class=active]').get(0)).data('type');
		if(question_type=="OpenEnded"){
			var question_body=$("#add_openended_question_body").val();
			var question_space=$("#add_openended_question_space").val();
			var question_id=$("#add_openended_question_id").val();
			var question_content=base64_encode(JSON.stringify({question_body:question_body,question_space:question_space}));
			var question_course_id=$("#add_openended_question_course_id").val();

			var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/add/question",{question_id:question_id,question_type:question_type,question_course_id:question_course_id,question_content:question_content})
			  .done(function(data) {
			  	console.log('Response:',data);

			  	if(window.for_exam)
			  	{
			  		window.location.reload();
			  	}
			  	else
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";
			  	}	
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("adding question finished!");
			});

		}
		else if(question_type="MultipleChoice"){
			var question_options=new Array();
			$('[name=question_answer]').each(function(number,object){
				question_options.push($(object).val());
			});
			var question_body=$("#add_multiplechoice_question_body").val();
			var question_id=$("#add_multiplechoice_question_id").val();
			var question_content=base64_encode(JSON.stringify({question_body:question_body,question_options:question_options}));
			var question_course_id=$("#add_multiplechoice_question_course_id").val();
			var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/add/question",{question_id:question_id,question_type:question_type,question_course_id:question_course_id,question_content:question_content})
			  .done(function(data) {
			  	console.log('Response:',data);
			  	if(window.for_exam)
			  	{
			  		window.location.reload();
			  	}
			  	else
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/question";
			  	}
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("adding question finished!");
			});

		}


	});

	$('.delete_exam').click(function(event){
		var exam_id=$(this).data("exam_id");
		var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/delete/exam",{exam_id:exam_id})
			  .done(function(data) {
			  	console.log('Response:',data);
			  	window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/exam";
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("deleting exam finished!");
			});
		

		}
	);
	$('.clone_exam').click(function(event){
		var exam_id=$(this).data("exam_id");
		var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/clone/exam",{exam_id:exam_id})
			  .done(function(data) {
			  	console.log('Response:',data);
			  	window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/exam";
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("deleting exam finished!");
			});
		

		}
	);
	$('.edit_exam').click(function(event){

		var that=this;
		var request = $.get( "<?php echo Yii::app()->getBaseUrl(true);?>/list/courseAndTerm")
			  .done(function(data) {

			  	console.log('Response:',data);
			  	if(data=="")
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/course";
			  	}
			  	else
			  	{
			  		$('#add_exam_course_id').empty();
			  		$('#add_exam_term_id').empty();
				  	if(data.courses)
				  	{
				  		addCourseOption("add_exam_course_id",data.courses);
				  	}
				  	if(data.terms)
				  	{
				  		addTermOption("add_exam_term_id",data.terms);
				  	}
				  	console.log($(this).data());
				  			$('#addExamTitle').html('Update Exam');
							$('#add_exam_id').val($(that).data('exam_id'));
							$('#add_exam_title').val($(that).data('exam_title'));
							$('#add_exam_definition').val($(that).data('exam_definition'));
							$('#add_exam_course_id').val($(that).data('exam_course_id'));
							$('#add_exam_term_id').val($(that).data('exam_term_id'));
							$('#add_exam_year').val($(that).data('exam_year'));
							$("#addExam").modal('show');
			    }
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("adding finished!");
			});		
	});
	$('#add_exam').click(function(event){
		$('#add_exam_course_id').empty();
		$('#add_exam_term_id').empty();
		$('#add_exam_id').val('');
		$('#add_exam_title').val('');
		$('#add_exam_definition').val('');
		$('#add_exam_year').val('');

		$('.help-block').remove();
		$('#addExamTitle').html('Add Exam');
		    	var request = $.get( "<?php echo Yii::app()->getBaseUrl(true);?>/list/courseAndTerm")
			  .done(function(data) {

			  	console.log('Response:',data);
			  	if(data=="")
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/course";
			  	}
			  	else
			  	{
				  	if(data.courses)
				  	{
				  		addCourseOption("add_exam_course_id",data.courses);
				  	}
				  	if(data.terms)
				  	{
				  		addTermOption("add_exam_term_id",data.terms);
				  	}
			    }
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("adding finished!");
			});
    	/*ajax end*/
		$("#addExam").modal('show');

	});	
	function addCourseOption(id,data)
	{
		for(key in data){
			$("#"+id).append("<option value="+data[key].course_id+">"+data[key].course_code+"-"+data[key].course_name+"</option>");
		}
	}
	function addTermOption(id,data)
	{
		for(key in data){
			$("#"+id).append("<option value="+data[key].term_id+">"+data[key].term_name+"</option>");
		}
	}
	$('#add_exam_save').click(function(event){
		var add_exam_title=$("#add_exam_title").val();
		var add_exam_definition=$("#add_exam_definition").val();
		var add_exam_course_id=$("#add_exam_course_id").val();
		var add_exam_term_id=$("#add_exam_term_id").val();
		var add_exam_year=$("#add_exam_year").val();
		var add_exam_id=$("#add_exam_id").val();
		//console.log(add_exam_title,add_exam_definition,add_exam_course_id,add_exam_term_id,add_exam_year);
		/*ajax begin*/
    	var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/add/exam",{
    		exam_id:add_exam_id,
    		exam_title:add_exam_title,
    		exam_definition:add_exam_definition,
    		exam_course_id:add_exam_course_id,
    		exam_term_id:add_exam_term_id,
    		exam_year:add_exam_year
    	})
			  .done(function(data) {

			  	console.log('Response:',data);
			  	if(data=="")
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/exam";
			  	}
			  	else
			  	{
				  	if(data.exam_title)
				  	{
				  		addError("add_exam_title",data.exam_title);
				  	}
				  	if(data.exam_definition)
				  	{
				  		addError("add_exam_definition",data.exam_definition);
				  	}
				  	if(data.exam_year)
				  	{
				  		addError("add_exam_year",data.exam_year);
				  	}
			    }
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("adding finished!");
			});
    	/*ajax end*/

	});
    $('#add_course_save').click(function(event){
    	var add_course_id=$('#add_course_id').val();
    	var add_course_code=$('#add_course_code').val();
    	var add_course_name=$('#add_course_name').val();

    	/*ajax begin*/
    	var request = $.post( "<?php echo Yii::app()->getBaseUrl(true);?>/add/course",{course_id:add_course_id,course_code:add_course_code,course_name:add_course_name})
			  .done(function(data) {

			  	console.log('Response:',data);
			  	if(data=="")
			  	{
			  		window.location="<?php echo Yii::app()->getBaseUrl(true);?>/list/course";
			  	}
			  	else
			  	{
				  	if(data.course_code)
				  	{
				  		addError("add_course_code",data.course_code);
				  	}
				  	if(data.course_name)
				  	{
				  		addError("add_course_name",data.course_name);
				  	}
			    }
			  })
			  .fail(function() {
			    alert( "error" );
			  })
			  .always(function() {
			    console.log("adding finished!");
			});
    	/*ajax end*/
    });

});	
</script>
<style type="text/css">
	.lindenLogo{
		height:180%;
		margin-top: -16px;
	}
</style>
</head> 
<body>
<nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a href="<?php echo Yii::app()->request->getBaseUrl(true);?>" class="navbar-brand" id="lindenBrand"><img class="lindenLogo" src="<?php echo Yii::app()->getBaseUrl(true);?>/images/logo/question_builder.png"/><!--Question Builder--></a>
        </div>
        <!-- Collection of nav links and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <!--<ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Messages</a></li>
            </ul>-->
            <?php if(!Yii::app()->user->isGuest) {?>
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" id="basket"><span class="glyphicon glyphicon-shopping-cart"></span> <span id="basket_length" class="badge"></span></a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="#" data-toggle="modal" id="open_basket">Open Basket</a></li>
                        <li><a href="#" data-toggle="modal" id="empty_basket">Empty Basket</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" id="add"><span class="glyphicon glyphicon-plus"></span></a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="#" data-toggle="modal" id="add_course">Add Course</a></li>
                         <li><a href="#" data-toggle="modal" id="add_exam">Add Exam</a></li>
                         <li><a href="#" data-toggle="modal" id="add_question">Add Question Template</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" id="list"><span class="glyphicon glyphicon-th"></span></a>
                    <ul role="menu" class="dropdown-menu">
                         <li><a href="<?php echo Yii::app()->getBaseUrl(true);?>/list/exam">List Exams</a></li>
                         <li><a href="<?php echo Yii::app()->getBaseUrl(true);?>/list/course">List Courses</a></li>
                         <li><a href="<?php echo Yii::app()->getBaseUrl(true);?>/list/question">List Question Templates</a></li>
                         <!--<li><a href="<?php echo Yii::app()->getBaseUrl(true);?>/list/category">List Categories</a></li>-->
                    </ul>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <?php 
                    echo Yii::app()->user->name."-";
                    $roles=Yii::app()->authManager->getRoles(Yii::app()->user->id);
                    foreach ($roles as $role)
					{
					    echo "(".$role->name.")";
					}
					?>
<b class="caret"></b></a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="<?php echo Yii::app()->getBaseUrl(true);?>/profile/open">Profile</a></li>
                        <?php if(Yii::app()->user->checkAccess('admin')) {?>
                         <li><a href="#">Account Management</a></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li><a href="<?php echo Yii::app()->createUrl('site/logout',array()); ?>">Logout</a></li>
                    </ul>
                </li>

            </ul>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- addCourse HTML -->
    <div id="addCourse" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="addCourseTitle">Add Course</h4>
                </div>
                <div class="modal-body">
                    <!--form begin-->
                    	<form class="form-horizontal">
					        <div class="form-group">
					            <label for="add_course_code" class="control-label col-xs-2">Course Code</label>
					            <div class="col-xs-10">
					                <input type="text" class="form-control" id="add_course_code" placeholder="Course Code, such as MATH 101">
					            </div>
					        </div>

					        <div class="form-group">
					            <label for="add_course_name" class="control-label col-xs-2">Course Name</label>
					            <div class="col-xs-10">
					                <input type="text" class="form-control" id="add_course_name" placeholder="Course Name, such as Calculus I">
					            </div>
					        </div>
					        <input type="hidden" class="form-control" id="add_course_id">
					       
					    </form>
                    <!--form end-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add_course_save">Save</button>
                </div>
            </div>
        </div>
    </div>

<!-- addExam HTML -->
    <div id="addExam" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="addExamTitle">Add Exam</h4>
                </div>
                <div class="modal-body">
                    <!--form begin-->
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="add_exam_title" class="control-label col-xs-2">Exam Title</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="add_exam_title" placeholder="Exam Title, such as Midterm I, Final etc.">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add_exam_definition" class="control-label col-xs-2">Exam Definition</label>
                                <div class="col-xs-10">
                                    <textarea class="form-control" id="add_exam_definition" placeholder="Write some word about the exa."></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="add_exam_course_id" class="control-label col-xs-2">Course</label>
                                <div class="col-xs-10">
                                    <select class="form-control" id="add_exam_course_id">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add_exam_term_id" class="control-label col-xs-2">Term</label>
                                <div class="col-xs-10">
                                    <select class="form-control" id="add_exam_term_id">
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="add_exam_year" class="control-label col-xs-2">Year</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="add_exam_year" placeholder="Exam Year, 2009,2010 etc.">                                
                                </div>
                            </div>                             
                            <input type="hidden" class="form-control" id="add_exam_id">
                           
                        </form>
                    <!--form end-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add_exam_save">Save</button>
                </div>
            </div>
        </div>
    </div>


<!-- addQuestion HTML -->
    <div id="addQuestion" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="addQuestionTitle">Add Question</h4>
                </div>
                <div class="modal-body">

                	<!--tab begins-->
					<div class="tabbable tabs-left">
		              <ul class="nav nav-tabs">
		                <li class="active" data-type="OpenEnded"><a href="#OpenEnded" data-toggle="tab">Open Ended</a></li>
		                <li data-type="MultipleChoice"><a href="#MultipleChoice" data-toggle="tab">Multiple Choice</a></li>
		              </ul>
		              <div class="tab-content">
		                <div class="tab-pane active" id="OpenEnded">
		                  <p>
		                  	<div class="alert alert-info">
						        <strong>Open ended question </strong> is a question format that limits respondents with a list of answer choices from which they must choose to answer the question.
						    </div>
		                  	<!--OpenEnded form begins-->
                        	<form class="form-horizontal">
		                        <div class="form-group">
	                                <label for="add_openended_question_body" class="control-label col-xs-2">Question</label>
	                                <div class="col-xs-10">
	                                    <textarea class="form-control" id="add_openended_question_body" placeholder="Write the question."></textarea>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <label for="add_openended_question_space" class="control-label col-xs-2">Answer Space</label>
	                                <div class="col-xs-10">
	                                    <select class="form-control" id="add_openended_question_space">
	                                    	<option value="0">No Space</option>
	                                    	<option value="1">Short</option>
	                                    	<option value="2">Long</option>
	                                    	<option value="3">Whole page</option>
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <label for="add_openended_question_course_id" class="control-label col-xs-2">Course</label>
	                                <div class="col-xs-10">
	                                    <select class="form-control" id="add_openended_question_course_id">
	                                    </select>
	                                </div>
                            	</div>
                            	<input type="hidden" id="add_openended_question_id"/>
                            </form>
                            <!--OpenEnded form ends-->
		                  </p>
		                </div>
		                <div class="tab-pane" id="MultipleChoice">
		                  <p>
		                  	<div class="alert alert-info">
						        <strong>Multiple choice question</strong> is a form of assessment in which respondents are asked to select the best possible answer (or answers) out of the choices from a list.
						    </div>
		                  	<!--MultipleChoice form begins-->
		                  	<form class="form-horizontal">
		                        <div class="form-group">
	                                <label for="add_multiplechoice_question_body" class="control-label col-xs-2">Question</label>
	                                <div class="col-xs-10">
	                                    <textarea class="form-control" id="add_multiplechoice_question_body" placeholder="Write the question."></textarea>
	                                </div>
	                            </div>
	                            <label class="control-label col-xs-2" style="margin-left:-10px">Options</label><br><br>
	                            <div id="add_multiplechoice_option">


				                </div>

	                            <div class="form-group">
	                                <label for="add_multiplechoice_question_course_id" class="control-label col-xs-2">Course</label>
	                                <div class="col-xs-10">
	                                    <select class="form-control" id="add_multiplechoice_question_course_id">
	                                    </select>
	                                </div>
                            	</div>
                            	<input type="hidden" id="add_multiplechoice_question_id"/>
                            </form>
		                  	<!--MultipleChoice form ends-->

		                  </p>
		                </div>

		              </div>
		            </div>
		            <!--tab ends-->
       
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add_question_save">Save</button>
                </div>
            </div>
        </div>
    </div>

<!--warning,success, etc. modal -->
<div class="modal fade" style="z-index:99999999" id="status_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div id="status_modal_status" class="">
        <div class="modal-header panel-heading">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="status_title"></h4>
        </div>
        <div class="modal-body" id="status_message">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


<!-- openBasket HTML -->
    <div id="openBasket" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="openBasketTitle">Basket</h4>
                </div>
                <div class="modal-body" id="openBasketBody">
                    <!--form begin-->
                       
                    <!--form end-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


<br><br><br><br>
<?php echo $content; ?>


</body>
</html>                                  		
