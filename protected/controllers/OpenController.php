<?php

class OpenController extends Controller
{
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}
	public function actionExam(){

		$exam_id=Yii::app()->request->getParam('examId');
		echo $exam_id;

		$Criteria = new CDbCriteria();
		$Criteria->addCondition("exam_id=".$exam_id);
		//$Criteria->order = 'exam_year DESC, exam_term_id DESC';
		//$exams = Exam::model()->with('examQuestions')->findAll('exam_question_exam_id=:exam_question_exam_id',array('exam_question_exam_id'=>$exam_id));//->
		//$examQuestions=ExamQuestion::model()->findAll($Criteria);
		$exam=Exam::model()->find($Criteria);
		//$questions=$exam->questions;
		$questions=$exam->questions?Util::sortArrayofObjectByProperty($exam->questions,'order'):array();


		$this->render('exam',array(
									'questions'=>$questions,
									'exam'=>$exam,
									));

	}
	public function getTermFormat($term,$type){
                $class="";

                if($term==1){
                    $class=$type."-warning";
                }
                else if($term==2){
                    $class=$type."-success";
                }
                else if($term==3){
                    $class=$type."-danger";
                }
                else if($term==4){
                    $class=$type."-info";
                }
                else
                {
                	$class=$type."-default";
                }
                return $class;
	}
	public function getQuestionType($type){
		return array("OpenEnded"=>4,"MultipleChoice"=>2,"Type"=>NULL)[$type];
	}
	public function getSpaceName($space_number)
	{
		return array("No Space","Short","Long","Whole Page")[$space_number];

	}
	/*
	protected function beforeAction()
    {
        if(Yii::app()->user->checkAccess(ucfirst($this->getId()) . ucfirst($this->getAction()->getId())))
        {
            return true;
        } else {
        	throw new CHttpException(401,'You are not authorized to perform this operation');
            //Yii::app()->request->redirect(Yii::app()->user->returnUrl);
        }
   }*/
}