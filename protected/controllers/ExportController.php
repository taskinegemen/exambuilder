<?php

class ExportController extends Controller
{
	
	public function actionIndex()
	{
		$this->render('index');
	}
	function init() {
		$this->layout=false;
	}

	public function actionPdf()
	{
		$question_space_lookup=array(0=>0,1=>25,2=>200);
		$content="";
		$exam_id=Yii::app()->request->getParam("exam_id",NULL);
		if($exam_id!=NULL)
		{
			$Criteria = new CDbCriteria();
			$Criteria->addCondition("exam_id=".$exam_id);
			$exam=Exam::model()->find($Criteria);
			if($exam)
			{
					$content.="\\runningheadrule\\header{".$exam->examCourse->course_code."}{".$exam->exam_title."}{".$exam->examTerm->term_name.", ".$exam->exam_year."-".($exam->exam_year+1)."}\n";
					$content.="\lfoot{}\cfoot{}\\rfoot[]{Page \\thepage\ of \\numpages}";
					$content.="\begin{questions}"."\n";
				if($exam->questions)
				{

					$questions=Util::sortArrayofObjectByProperty($exam->questions,'order');
					foreach ($questions as $question) 
					{
						$question_content=json_decode(base64_decode($question->question_content));
						if($question->question_type=='OpenEnded')
						{
							$question_body=$question_content->question_body;
							$question_space=$question_content->question_space;

							$content.="\\question ".$question_body."\n".
							($question_space!=3?"\\vspace{".$question_space_lookup[$question_space]."mm}"."\n":"").
							($question_space=="3"?"\\newpage\n\\mbox{}\n\\newpage\n":"");
							
							
						}
						else if($question->question_type=='MultipleChoice')
						{
							$question_body=$question_content->question_body;
							$question_options=$question_content->question_options;

							$content.="\\question ".$question_body."\n".
									 "\\begin{checkboxes}"."\n";
							foreach ($question_options as $option)
							{
								$content.="\\choice ". $option."\n"; 
							}
							$content.="\\end{checkboxes}"."\n";
						}
					}
				}
			}
			$content.="\\end{questions}"."\n";
			$file_name=$exam->exam_id.".tex";
			$pdf_file_name=$exam->exam_id.".pdf";
			$content=$this->getHeader($exam->exam_definition).$content.$this->getBottom();
			file_put_contents(Yii::app()->params["storage_path"].$file_name, $content);
			$output=shell_exec("cd ".Yii::app()->params["storage_path"].";pdflatex -interaction nonstopmode ".Yii::app()->params["storage_path"].$file_name);
			error_log("PDFLATEX:".$output);
			header('Content-type: application/pdf');
			//header('Content-Disposition: inline; filename="'.$pdf_file_name. '"');
			header('Content-Disposition:attachment; filename="'.$pdf_file_name. '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize(Yii::app()->params["storage_path"].$pdf_file_name));
			header('Accept-Ranges: bytes');
			@readfile(Yii::app()->params["storage_path"].$pdf_file_name);

		}
	}
	public function getBottom(){
		return "\\end{document}";
	}
	public function getHeader($exam_definition){
		return "\\documentclass[addpoints]{exam}
				\\usepackage{amssymb}
				\\usepackage{amsmath}
				\\usepackage[utf8]{inputenc}
				\\usepackage{algorithm}
				\\usepackage{pseudocode}
				\\usepackage[noend]{algpseudocode}
				\\begin{document}
				 
				\\begin{center}
				\\fbox{\\fbox{\\parbox{5.5in}{\\centering
				$exam_definition}}}
				\\end{center}
				 
				\\vspace{5mm}
				 
				\\makebox[\\textwidth]{Name and section:\\enspace\\hrulefill}
				 
				\\vspace{5mm}
				 
				\\makebox[\\textwidth]{Instructor’s name:\\enspace\\hrulefill}

				\\vspace{5mm}"."\n";

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