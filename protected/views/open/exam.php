

<div class="container">

<div class="btn-group">
    <a class="btn btn-success import_from_basket" type="button" data-exam_id="<?php echo $exam->exam_id;?>"><span class="glyphicon glyphicon-shopping-cart"></span> Import from the Basket</a>
    <a class="btn btn-info" type="button" href="<?php echo Yii::app()->createUrl('export/pdf',array('exam_id'=>$exam->exam_id)); ?>"><span class="glyphicon glyphicon-cloud-download"></span> Export as Pdf</a>
</div>

<br><br>

</div>    
<div class="container text-uppercase" style="padding:0px; font-size:18px;">
    <div class="col-lg-12" style="color:#D0D0D0;padding:0px; ">
        <div class="text-left col-lg-4"><?php echo $exam->exam_title; ?></div> <div class="text-center col-lg-4"><b><?php echo $exam->examCourse->course_code."-".$exam->examCourse->course_name;?></b></div><div class="text-right col-lg-4"><?php echo $exam->examTerm->term_name."/".$exam->exam_year."-".($exam->exam_year+1); ?></div>
    </div>
    <div class="col-lg-12" style="color:#D0D0D0;padding:0px; ">
        <hr>
    </div>       
</div>
<div class="container">

<?php
    foreach ($questions as $question) {
    ?>

    <div class="row">
        <div>
            <div class="panel <?php echo $this->getTermFormat($this->getQuestionType($question->question_type),'panel');?>">
                <!--<div class="panel-heading"><h3 class="panel-title"></h3></div>-->
                <div class="panel-body">
                	<?php

                	$question_content=json_decode(base64_decode($question->question_content));
                	$fields='data-question_id='.$question->question_id.
                            ' data-question_course_id='.$question->question_course_id.
                			' data-question_template='.$question->question_template.
                			' data-question_parent=\''.$question->question_parent.'\''.
                			' data-question_type='.$question->question_type.
                			' data-question_content='.$question->question_content;
                	if($question->question_type=="OpenEnded")
                		{
                			$format=$this->getTermFormat($this->getQuestionType($question->question_type),'label');
                			$format_alert=$this->getTermFormat($this->getQuestionType($question->question_type),'alert');
                			echo "<div style=\"margin-bottom:0px;\" class=\"alert ".$format_alert."\"><strong>Question:</strong>".$question_content->question_body."</div>";
                			?>
                			<br><span class="label <?php echo $format;?>">Answer space: <?php echo $this->getSpaceName($question_content->question_space); ?></span>
                			<?php
                		}
                	else if($question->question_type="MultipleChoice")
                		{
                			$format=$this->getTermFormat($this->getQuestionType($question->question_type),'alert');
                			if($question_content->question_body!=""){
                				echo "<div class=\"alert ".$format."\"><strong>Question:</strong>".$question_content->question_body."</div>";
                			}
                			else
                			{
                				echo '<div class="alert '.$format.'">Please enter the question!</div>';          				
                			}

                			if(count($question_content->question_options)>1 && $question_content->question_options[0]!=""){
								//echo "<ul class=\"list-group\">";
	                			foreach ($question_content->question_options as $option) {
	                				//echo "<li class=\"list-group-item\"> <div class=\"alert alert-info\">".$option."</div></li>";
	                				echo "<div style=\"margin-left:100px;margin-bottom:0px;\" class=\"alert ".$format."\"><strong>Option:</strong>".$option."</div>";
	                			}
	                			//echo "</ul>";
                			}
                			else
                			{
                				echo '<div class="alert '.$format.'">Please enter some option for the question!</div>';
                			}
                		}
                	?>
                </div> 
                <div class="panel-footer clearfix">
                    <div class="pull-left">
                        <a <?php echo $fields;?> class="btn btn-default edit_question for_exam"><span class="glyphicon glyphicon-edit"></span></a>
                        <a data-question_id="<?php echo $question->question_id;?>" class="btn btn-default delete_question for_exam"><span class="glyphicon glyphicon-trash"></span></a>
                        <a data-question_id="<?php echo $question->question_id;?>" data-exam_id="<?php echo $exam->exam_id;?>" class="btn btn-default clone_question for_exam"><span class="glyphicon glyphicon-file"></span></a>
                        <a data-question_id="<?php echo $question->question_id;?>" data-exam_id="<?php echo $exam->exam_id;?>" class="btn btn-default move_up_question for_exam"><span class="glyphicon glyphicon-arrow-up"></span></a>
                        <a data-question_id="<?php echo $question->question_id;?>" data-exam_id="<?php echo $exam->exam_id;?>" class="btn btn-default move_down_question for_exam"><span class="glyphicon glyphicon-arrow-down"></span></a>

                    </div>
                </div>
            </div>
        </div>
        </div>
<?php
}
?>
        </div>