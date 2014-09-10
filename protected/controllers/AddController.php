<?php

class AddController extends CController
{
	
	public function actionIndex()
	{
		$this->render('index');
	}
	function init() {
		$this->layout=false;
	}
	public function actionMoveUpQuestion(){
		$question_id=Yii::app()->request->getPost("question_id",NULL);
		$exam_id=Yii::app()->request->getPost("exam_id",NULL);
		$question=Question::model()->find('question_id=:question_id',array(':question_id'=>$question_id));
		$max_order=$question->order;
		$Criteria = new CDbCriteria();
		$Criteria->addCondition("`t`.exam_id=".$exam_id);
		$Criteria->addCondition('questions.order<'.$max_order);
		$Criteria->order = 'questions.order DESC';
		$exam=Exam::model()->with('questions')->find($Criteria);

		if($exam)
		{
					$previous=$exam->questions[0];
					$previous_temp=$previous->order;
					$previous->order=$question->order;
					$question->order=$previous_temp;
					$question->save();
					$previous->save();			
		}
	}
	public function actionMoveDownQuestion(){
		$question_id=Yii::app()->request->getPost("question_id",NULL);
		$exam_id=Yii::app()->request->getPost("exam_id",NULL);
		$question=Question::model()->find('question_id=:question_id',array(':question_id'=>$question_id));
		$max_order=$question->order;
		$Criteria = new CDbCriteria();
		$Criteria->addCondition("`t`.exam_id=".$exam_id);
		$Criteria->addCondition('questions.order>'.$max_order);
		$Criteria->order = 'questions.order ASC';
		$exam=Exam::model()->with('questions')->find($Criteria);
		if($exam)
		{
					$previous=$exam->questions[0];
					$previous_temp=$previous->order;
					$previous->order=$question->order;
					$question->order=$previous_temp;
					$question->save();
					$previous->save();
		}
	}	
	public function actionQuestion(){

		$question_id=Yii::app()->request->getPost("question_id",NULL);
		$question_type=Yii::app()->request->getPost("question_type",NULL);
		$question_course_id=Yii::app()->request->getPost("question_course_id",NULL);
		$question_content=Yii::app()->request->getPost("question_content",NULL);
		//echo $question_type."-".$question_course_id."-".$question_content;
		//$this->renderJSON(array("1"=>$question_course_id,"2"=>$question_content));
		//die();
		if($question_id!=NULL || $question_id!="")
		{
			$question=Question::model()->find('question_id=:question_id',array('question_id'=>$question_id));
			$question->question_type=$question_type;
			$question->question_course_id=$question_course_id;
			$question->question_content=$question_content;
			if(!$question->save()){
					$this->renderJSON($question->getErrors());
			}
		}
		else
		{

				$question=new Question();
				$question->question_type=$question_type;
				$question->question_course_id=$question_course_id;
				$question->question_content=$question_content;
				$question->order=0;
				if(!$question->save()){

					$this->renderJSON($question->getErrors());
				}
				else
				{

					$question->order=$question->question_id;
					$question->save();
				}
		}
	}
	public function actionCourse(){

		$course_id=Yii::app()->request->getPost("course_id",NULL);
		$course_code=Yii::app()->request->getPost("course_code",NULL);
		$course_name=Yii::app()->request->getPost("course_name",NULL);

		if($course_id!=NULL || $course_id!="")
		{
			$retrievedCourse=Course::model()->find('course_id=:course_id',array('course_id'=>$course_id));
			$retrievedCourse->course_code=$course_code;
			$retrievedCourse->course_name=$course_name;
			if(!$retrievedCourse->save()){
					$this->renderJSON($retrievedCourse->getErrors());
				}
		}
		else
		{
			$retrievedCourse=Course::model()->find('course_code=:course_code',array('course_code'=>$course_code));
			if(!$retrievedCourse)
			{
				$course=new Course();
				$course->course_code=$course_code;
				$course->course_name=$course_name;
				if(!$course->save()){
					$this->renderJSON($course->getErrors());
				}
			}
			else
			{
				$this->renderJSON(array("course_code"=>"Course is already available"));
			}
		}
	}

	public function actionExam(){


		$exam_id=Yii::app()->request->getPost("exam_id",NULL);
		$exam_title=Yii::app()->request->getPost("exam_title",NULL);
		$exam_definition=Yii::app()->request->getPost("exam_definition",NULL);
		$exam_course_id=Yii::app()->request->getPost("exam_course_id",NULL);
		$exam_term_id=Yii::app()->request->getPost("exam_term_id",NULL);
		$exam_year=Yii::app()->request->getPost("exam_year",NULL);

		if($exam_id!=NULL || $exam_id!="")
		{
			$retrievedExam=Exam::model()->find('exam_id=:exam_id',array('exam_id'=>$exam_id));
			$retrievedExam->exam_title=$exam_title;
			$retrievedExam->exam_definition=$exam_definition;
			$retrievedExam->exam_course_id=$exam_course_id;
			$retrievedExam->exam_term_id=$exam_term_id;
			$retrievedExam->exam_year=$exam_year;
			if(!$retrievedExam->save()){
				$this->renderJSON($retrievedExam->getErrors());
			}
			/*
			$retrievedCourse=Course::model()->find('course_id=:course_id',array('course_id'=>$course_id));
			$retrievedCourse->course_code=$course_code;
			$retrievedCourse->course_name=$course_name;
			if(!$retrievedCourse->save()){
					$this->renderJSON($retrievedCourse->getErrors());
				}
			*/
		}
		else
		{
			$exam=new Exam();
			$exam->exam_title=$exam_title;
			$exam->exam_definition=$exam_definition;
			$exam->exam_course_id=$exam_course_id;
			$exam->exam_term_id=$exam_term_id;
			$exam->exam_year=$exam_year;

			if(!$exam->save()){
				$this->renderJSON($exam->getErrors());
			}

			/*$retrievedExam=Exam::model()->find('exam_term_id=:exam_term_id AND exam_course_id=:exam_course_id AND exam_title=:exam_title AND exam_year=:exam_year',array('exam_course_id'=>$exam_course_id,'exam_term_id'=>$exam_term_id,'exam_title'=>$exam_title,'exam_year'=>$exam_year));
			if(!$retrievedExam)
			{
				$exam=new Exam();
				$exam->exam_title=$exam_title;
				$exam->exam_definition=$exam_definition;
				$exam->exam_course_id=$exam_course_id;
				$exam->exam_term_id=$exam_term_id;
				$exam->exam_year=$exam_year;

				if(!$exam->save()){
					$this->renderJSON($exam->getErrors());
				}
			}
			else
			{
				$commonMessage="title,course,term and year are already available";
				$this->renderJSON(
					array(
						"exam_title"=>$commonMessage,
						"exam_year"=>$commonMessage,
						"exam_term_id"=>$commonMessage,
						"exam_course_id"=>$commonMessage
					));
			}*/
		}
	}

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