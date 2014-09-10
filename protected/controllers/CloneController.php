<?php

class CloneController extends Controller
{
	
	public function actionIndex()
	{
		$this->render('index');
	}
	function init() {
		$this->layout=false;
	}

	public function actionQuestion(){

		$question_id=Yii::app()->request->getPost("question_id",NULL);
		$exam_id=Yii::app()->request->getPost("exam_id",NULL);
		if($question_id!=NULL)
		{
				$question=Question::model()->find('question_id=:question_id',array(':question_id'=>$question_id));
				if($question){
					$question_instance=new Question();
					$question_instance->attributes = $question->attributes;
					$question_instance->question_id = null;
					$question_content=json_decode(base64_decode($question->question_content));
					$question_content->question_body="<strong>CLONE</strong>".$question_content->question_body;
					$question_instance->question_content=base64_encode(json_encode($question_content));
					if($question_instance->save() && $exam_id!=NULL){
						$exam_question=new ExamQuestion();
						$exam_question->exam_id=$exam_id;
						$exam_question->question_id=$question_instance->question_id;
						$exam_question->save();
					}
				}
		}
	}
	public function actionBasket(){

		$questions=json_decode(base64_decode(Yii::app()->request->getPost("basket",NULL)));
		$exam_id=Yii::app()->request->getPost("exam_id",NULL);
		if (!is_null($questions))
		{
			foreach ($questions as $question_id) 
			{
				$question=Question::model()->find('question_id=:question_id',array(':question_id'=>$question_id));

					if($question)
					{
						$question_instance=new Question();
						$question_instance->attributes = $question->attributes;
						$question_instance->question_id = null;
						$question_instance->question_template=0;
						$question_content=json_decode(base64_decode($question->question_content));
						$question_content->question_body=$question_content->question_body;
						$question_instance->question_content=base64_encode(json_encode($question_content));
						if($question_instance->save() && $exam_id!=NULL){
							$exam_question=new ExamQuestion();
							$exam_question->exam_id=$exam_id;
							$exam_question->question_id=$question_instance->question_id;
							$exam_question->save();
						}
					}
			}
		}
	}
	public function actionExam()
	{

		$exam_id=Yii::app()->request->getPost("exam_id",NULL);
		$exam=Exam::model()->find('exam_id=:exam_id',array(':exam_id'=>$exam_id));
		$exam_clone=new Exam();
		$exam_clone->attributes=$exam->attributes;
		$exam_clone->exam_id=null;
		$exam_clone->exam_title="Copy(".$exam->exam_title.")";

		$questions=$exam->questions;
		$exam_clone->save();

		foreach ($questions as $question) 
		{


					$question_instance=new Question();
					$question_instance->attributes = $question->attributes;
					$question_instance->question_id = null;
					$question_instance->question_template=0;
					$question_content=json_decode(base64_decode($question->question_content));
					$question_content->question_body=$question_content->question_body;
					$question_instance->question_content=base64_encode(json_encode($question_content));
					if($question_instance->save() && $exam_clone->exam_id!=NULL){
						$exam_question=new ExamQuestion();
						$exam_question->exam_id=$exam_clone->exam_id;
						$exam_question->question_id=$question_instance->question_id;
						$exam_question->save();
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
        if(Yii::app()->user->checkAccess(ucfirst($this->getId()) . ucfirst($this->getAction()->getId())))
        {
            return true;
        } else {
        	throw new CHttpException(401,'You are not authorized to perform this operation');
            //Yii::app()->request->redirect(Yii::app()->user->returnUrl);
        }
   }
}