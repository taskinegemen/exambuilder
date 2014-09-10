<div class="container">
    <div class="btn-group">
        <?php if($chosenCourse!="Course"){?>
            <a class="btn btn-default" type="button" href="<?php echo Yii::app()->createUrl('list/question',array('chosenType'=>$chosenType)); ?>"><span class="glyphicon glyphicon-off"></span></a>
        <?php } ?>
        <button class="btn btn-default"><?php echo $this->getCourseCode($chosenCourse);?></button>
        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></button>
        <ul class="dropdown-menu">
            <?php foreach ($courses as $course) {
            ?>
            <li><a href="<?php echo Yii::app()->createUrl('list/question',array('chosenType'=>$chosenType,'chosenCourse'=>$course->course_id)); ?>"><?php echo $course->course_name;?></a></li>
            <?php }?>
        </ul>
    </div>
    <div class="btn-group">
        <?php if($chosenType!="Type"){?>
            <a class="btn <?php echo $this->getTermFormat($this->getQuestionType($chosenType),'btn');?>" type="button" href="<?php echo Yii::app()->createUrl('list/question',array('chosenCourse'=>$chosenCourse)); ?>"><span class="glyphicon glyphicon-off"></span></a>
        <?php } ?>
        <button class="btn <?php echo $this->getTermFormat($this->getQuestionType($chosenType),'btn');?>"><?php echo $chosenType;?></button>
        <button data-toggle="dropdown" class="btn <?php echo $this->getTermFormat($this->getQuestionType($chosenType),'btn');?> dropdown-toggle"><span class="caret"></span></button>
        <ul class="dropdown-menu">

            <li><a href="<?php echo Yii::app()->createUrl('list/question',array('chosenType'=>'OpenEnded','chosenCourse'=>$chosenCourse)); ?>">Open Ended</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('list/question',array('chosenType'=>'MultipleChoice','chosenCourse'=>$chosenCourse)); ?>">Multiple Choice</a></li>
        </ul>
    </div>
 </div>

<?php
    $question_course_id='';
    $course=NULL;
    $i=0;
    foreach ($questions as $question) {
        if($question_course_id!=$question->question_course_id)
        {
        	$course=$this->getCourse($question->question_course_id);
    ?>

    <div class="container">
            
                <h3 style="margin-bottom:-20px;color:#D0D0D0"><?php echo $course->course_code."-".$course->course_name; ?></h3>
                <hr>
    <?php
            $question_course_id=$question->question_course_id;

        }
    ?>
    <div class="row">
        <div>
            <div class="panel <?php echo $this->getTermFormat($this->getQuestionType($question->question_type),'panel');?>">
                <!--<div class="panel-heading"><h3 class="panel-title"></h3></div>-->
                <div class="panel-body">
                	<?php

                	$question_content=json_decode(base64_decode($question->question_content));
                	$fields='data-question_id='.$question->question_id.
                			' data-question_course_id='.$question_course_id.
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
                        <a <?php echo $fields;?> class="btn btn-default edit_question"><span class="glyphicon glyphicon-edit"></span></a>
                        <a data-question_id="<?php echo $question->question_id;?>" class="btn btn-default delete_question"><span class="glyphicon glyphicon-trash"></span></a>
                        <a data-question_id="<?php echo $question->question_id;?>" class="btn btn-default clone_question"><span class="glyphicon glyphicon-file"></span></a>
                        <a data-question_id="<?php echo $question->question_id;?>" class="btn btn-default move_question"><span class="glyphicon glyphicon-shopping-cart"></span></a>

                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php $i++;
                if(isset($questions[$i])){
                    if($questions[$i]->question_course_id!=$question_course_id)
                    {
                        ?>
                            
                        </div>
                        <?php

                    }
                }
 } ?>