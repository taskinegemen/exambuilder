<?php

class ListController extends Controller
{
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}
	public function actionExam(){

		$chosenYear=Yii::app()->request->getParam('chosenYear','Year');
		$chosenTerm=Yii::app()->request->getParam('chosenTerm','Term');
		$chosenCourse=Yii::app()->request->getParam('chosenCourse','Course');
		$queryArray=array();

		$Criteria = new CDbCriteria();
		if($chosenYear!="Year"){$Criteria->addCondition("exam_year=".$chosenYear);}
		if($chosenTerm!="Term"){$Criteria->addCondition("exam_term_id=".$chosenTerm);}
		if($chosenCourse!="Course"){$Criteria->addCondition("exam_course_id='".$chosenCourse."'");}
		$Criteria->order = 'exam_year DESC, exam_term_id DESC';
		$exams = Exam::model()->findAll($Criteria);

		$terms=Term::model()->findAll();
		$courses=Course::model()->findAll();
		$minmax= Yii::app()->db->createCommand('SELECT MAX( exam_year ) AS maxYear, MIN(exam_year) AS minYear FROM  exam')->queryRow();


		$this->render('exam',array(
									'exams'=>$exams,
									'terms'=>$terms,
									'courses'=>$courses,
									'minYear'=>$minmax['minYear'],
									'maxYear'=>$minmax['maxYear'],
									'chosenYear'=>$chosenYear,
									'chosenTerm'=>$chosenTerm,
									'chosenCourse'=>$chosenCourse
									));

	}
	public function actionQuestion(){
		$Criteria = new CDbCriteria();
		$chosenCourse=Yii::app()->request->getParam('chosenCourse','Course');
		$chosenType=Yii::app()->request->getParam('chosenType','Type');
		$Criteria = new CDbCriteria();
		$Criteria->addCondition("question_template=1");//if it is template then list
		if($chosenCourse!="Course"){$Criteria->addCondition("question_course_id='".$chosenCourse."'");}
		if($chosenType!="Type"){$Criteria->addCondition("question_type='".$chosenType."'");}

		$Criteria->order = 'question_course_id DESC';
		$questions=Question::model()->findAll($Criteria);
		$courses=Course::model()->findAll();
		$this->render('question',array(
			'questions'=>$questions,
			'courses'=>$courses,
			'chosenCourse'=>$chosenCourse,
			'chosenType'=>$chosenType
			));
	}
	public function actionCourse(){
		$Criteria = new CDbCriteria();
		$Criteria->order = 'course_id ASC';
		$courses=Course::model()->findAll($Criteria);
		$this->render('course',array('courses'=>$courses));
	}
	public function actionCourseAndTerm(){
				$courses=Course::model()->findAll();
				$terms=Term::model()->findAll();
				$this->renderJSON(array("courses"=>$courses,"terms"=>$terms));
	}
	public function actionBasket()
	{
		$basket_base=base64_decode(Yii::app()->request->getParam('basket'));
		$basket_json=json_decode($basket_base);
		if(!empty($basket_json)){
			$Criteria = new CDbCriteria();
			foreach ($basket_json as $item) {
				$Criteria->addCondition("question_id=".$item,"OR");
			}
			$questions=Question::model()->findAll($Criteria);
		}
		else
		{
			$questions=array();
		}
		$this->renderJSON($questions);


	}
	public function getCourseCode($course_id)
	{
		if(is_numeric($course_id))
			return Course::model()->find('course_id=:course_id',array('course_id'=>$course_id))->course_code;
		else
			return $course_id;
	}
	public function getSpaceName($space_number)
	{
		return array("No Space","Short","Long","Whole Page")[$space_number];

	}
	public function getCourse($course_id)
	{
		return Course::model()->find('course_id=:course_id',array('course_id'=>$course_id));
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

	public function filters()
    {
        return array(
            'accessControl',
        );
    }
	public function accessRules()
    {
        return array(
            array('allow',
                'users'=>array('@'),
            ),

            array('deny'),
        );
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