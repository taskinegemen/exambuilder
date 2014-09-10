<?php

class DeleteController extends Controller
{
	
	public function actionIndex()
	{
		$this->render('index');
	}
	function init() {
		$this->layout=false;
	}
	public function actionCourse(){

		$course_id=Yii::app()->request->getPost("course_id",NULL);
		if($course_id!=NULL)
		{
				$course=Course::model()->find('course_id=:course_id',array(':course_id'=>$course_id));
				if($course){
					$course->delete();
				}
		}
	}
	public function actionExam(){

		$exam_id=Yii::app()->request->getPost("exam_id",NULL);
		if($exam_id!=NULL)
		{
				$exam=Exam::model()->find('exam_id=:exam_id',array(':exam_id'=>$exam_id));
				if($exam){
					$exam->delete();
				}
		}
	}
	public function actionQuestion(){

		$question_id=Yii::app()->request->getPost("question_id",NULL);
		if($question_id!=NULL)
		{
				$question=Question::model()->find('question_id=:question_id',array(':question_id'=>$question_id));
				if($question){
					$question->delete();
				}
		}
	}
	/*
	public function addWarning($warnings,$property_name,$property_value){
		if($property==NULL || $property==""){
			$warnings[$property_name]=$property_value;
			return false;
		}
	}*/
	public function renderJSON($data)
	{
	    header('Content-type: application/json');
	    echo CJSON::encode($data);

	    foreach (Yii::app()->log->routes as $route) {
	        if($route instanceof CWebLogRoute) {
	            $route->enabled = false; // disable any weblogroutes
	        }
	    }
	    Yii::app()->end();
	}
	protected function beforeAction()
    {
    	if(!Yii::app()->user->isGuest)
    	{
		        if(Yii::app()->user->checkAccess(ucfirst($this->getId()) . ucfirst($this->getAction()->getId())))
		        {
		            return true;
		        } else {
		        	throw new CHttpException(401,'You are not authorized to perform this operation');
		            //Yii::app()->request->redirect(Yii::app()->user->returnUrl);
		        }
		}
		else
		{
			$this->redirect(Yii::app()->createUrl('site/login'));
		}
   }
}