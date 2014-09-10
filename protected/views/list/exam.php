

<div class="container">
    <div class="btn-group">
        <?php if($chosenYear!="Year"){?>
            <a class="btn btn-default" type="button" href="<?php echo Yii::app()->createUrl('list/exam',array('chosenTerm'=>$chosenTerm,'chosenCourse'=>$chosenCourse)); ?>"><span class="glyphicon glyphicon-off"></span></a>
        <?php } ?>
        <button class="btn btn-default"><?php echo $chosenYear;?></button>
        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></button>
        <ul class="dropdown-menu">
            <?php for ($year=$maxYear; $year >= $minYear ; $year--) { 
            ?>
            <li><a href="<?php echo Yii::app()->createUrl('list/exam',array('chosenYear'=>$year,'chosenTerm'=>$chosenTerm,'chosenCourse'=>$chosenCourse)); ?>"><?php echo $year?></a></li>
            <?php }?>
        </ul>
    </div>

    <div class="btn-group">
        <?php if($chosenTerm!="Term"){?>
            <a class="btn <?php echo $this->getTermFormat($chosenTerm,'btn');?>" type="button" href="<?php echo Yii::app()->createUrl('list/exam',array('chosenYear'=>$chosenYear,'chosenCourse'=>$chosenCourse)); ?>"><span class="glyphicon glyphicon-off"></span></a>
        <?php } ?>
        <button class="btn <?php echo $this->getTermFormat($chosenTerm,'btn');?>" ><?php $retrievedTerms=CJSON::decode(CJSON::encode($terms)); if(is_numeric($chosenTerm)){echo $retrievedTerms[$chosenTerm-1]['term_name'];} else {echo $chosenTerm;}?></button>
        <button data-toggle="dropdown" class="btn <?php echo $this->getTermFormat($chosenTerm,'btn');?> dropdown-toggle"><span class="caret"></span></button>
        <ul class="dropdown-menu">
            <?php foreach ($terms as $term) {
            ?>
            <li><a href="<?php echo Yii::app()->createUrl('list/exam',array('chosenYear'=>$chosenYear,'chosenTerm'=>$term->term_id,'chosenCourse'=>$chosenCourse)); ?>"><?php echo $term->term_name;?></a></li>
            <?php }?>
        </ul>
    </div>
    <div class="btn-group">
        <?php if($chosenCourse!="Course"){?>
            <a class="btn btn-default" type="button" href="<?php echo Yii::app()->createUrl('list/exam',array('chosenYear'=>$chosenYear,'chosenTerm'=>$chosenTerm)); ?>"><span class="glyphicon glyphicon-off"></span></a>
        <?php } ?>
        <button class="btn btn-default"><?php echo $this->getCourseCode($chosenCourse);?></button>
        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></button>
        <ul class="dropdown-menu">
            <?php foreach ($courses as $course) {
            ?>
            <li><a href="<?php echo Yii::app()->createUrl('list/exam',array('chosenYear'=>$chosenYear,'chosenTerm'=>$chosenTerm,'chosenCourse'=>$course->course_id)); ?>"><?php echo $course->course_name;?></a></li>
            <?php }?>
        </ul>
    </div>
</div>
<br>

    <?php
    $year='';
    $i=0;
    foreach ($exams as $exam) {
        if($year!=$exam->exam_year)
        {
    ?>

    <div class="container">
            <div class="row">
                <h3 style="margin-bottom:-20px;color:#D0D0D0"><?php echo $exam->exam_year; ?></h3>
                <hr>
    <?php
            $year=$exam->exam_year;
        }
    ?>
        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-4">
            <div class="panel <?php echo $this->getTermFormat($exam->exam_term_id,'panel');?>">
                <div class="panel-heading"><h3 class="panel-title"><?php echo $exam->exam_title."/".$exam->exam_year."-".($exam->exam_year+1);?></h3></div>
                <div class="panel-body"><?php echo $exam->exam_definition;?></div> 
                <div class="panel-footer clearfix">
                    <div class="pull-left">
                        <a href="<?php echo Yii::app()->createUrl('open/exam',array('examId'=>$exam->exam_id)); ?>" class="btn btn-primary open_exam"><span class="glyphicon glyphicon-eye-open"></span> Open</a>
                        <a href="<?php echo Yii::app()->createUrl('export/pdf',array('exam_id'=>$exam->exam_id)); ?>" class="btn btn-success download_exam"><span class="glyphicon glyphicon-download-alt"></span></a>
                        <a href="#" class="btn btn-default edit_exam" 
                        data-exam_id="<?php echo $exam->exam_id;?>"
                        data-exam_title="<?php echo $exam->exam_title;?>"
                        data-exam_definition="<?php echo $exam->exam_definition;?>"
                        data-exam_course_id="<?php echo $exam->exam_course_id;?>"
                        data-exam_term_id="<?php echo $exam->exam_term_id;?>"
                        data-exam_year="<?php echo $exam->exam_year;?>"
                        ><span class="glyphicon glyphicon-edit"></span></a>
                        <a href="#" data-exam_id="<?php echo $exam->exam_id;?>" class="btn btn-default delete_exam"><span class="glyphicon glyphicon-trash"></span></a>
                        <a href="#" data-exam_id="<?php echo $exam->exam_id;?>" class="btn btn-default clone_exam"><span class="glyphicon glyphicon-file"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <?php $i++;
                if(isset($exams[$i])){
                    if($exams[$i]->exam_year!=$year)
                    {
                        ?>
                            </div>
                        </div>
                        <?php

                    }
                }
         } ?>


