<div class="container">
    <div class="row">
    <?php
    foreach ($courses as $course) {

    ?>
        <div class="col-xs-4 col-sm-3 col-md-2 col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title"><?php echo $course->course_code;?></h3></div>
                <div class="panel-body"><?php echo $course->course_name;?></div> 
                <div class="panel-footer clearfix">
                    <div class="pull-left">
                        <a href="#" data-course_code="<?php echo $course->course_code;?>" data-course_id="<?php echo $course->course_id;?>" data-course_name="<?php echo $course->course_name;?>" class="btn btn-default edit_course"><span class="glyphicon glyphicon-edit"></span></a>
                        <a href="#" data-course_id="<?php echo $course->course_id;?>" class="btn btn-default delete_course"><span class="glyphicon glyphicon-trash"></span></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>