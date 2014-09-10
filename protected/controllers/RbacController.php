<?php
class RbacController extends CController
{
	/* public function filters()
	 {
		 return array(
		 'accessControl',
		 );
	 }
 
 public function accessRules()
 {
 	return array(
				 array(
				 'allow',
				 'actions' => array('deletePost'),
				 'roles' => array('deletePost'),
				 ),

				 array(
				 'allow',
				 'actions' => array('init', 'test'),
				 ),
 				
 				array('deny'),
 			);
 }*/

 public function actionInit()
 {
	 $auth=Yii::app()->authManager;
	 $auth->createOperation('AddQuestion','add exam');
	 $auth->createOperation('AddCourse','add course');
	 $auth->createOperation('AddExam','add exam');

	 $auth->createOperation('CloneQuestion','add exam');
	 $auth->createOperation('CloneBasket','add course');
	 $auth->createOperation('CloneExam','add course');

	 $auth->createOperation('DeleteExam','delete exam');
	 $auth->createOperation('DeleteQuestion','delete question');
	 $auth->createOperation('DeleteCourse','delete course');

	 $auth->createOperation('ExportPdf','export');


	 $auth->createOperation('ListExam','list exam');
	 $auth->createOperation('ListQuestion','list question');
	 $auth->createOperation('ListCourse','list course');
	 $auth->createOperation('ListCourseAndTerm','list course and term');
	 $auth->createOperation('ListBasket','list basket');
	 
	 $auth->createOperation('OpenExam','open the exam');


	 $edit_task=$auth->createTask('edit','edit everything');
	 $edit_task->addChild('AddQuestion');
	 $edit_task->addChild('AddCourse');
	 $edit_task->addChild('AddExam');

	 $edit_task->addChild('CloneQuestion');
	 $edit_task->addChild('CloneBasket');
	 $edit_task->addChild('CloneExam');

	 $edit_task->addChild('DeleteExam');
	 $edit_task->addChild('DeleteQuestion');
	 $edit_task->addChild('DeleteCourse');

	 $list_task=$auth->createTask('list','list everything');
	 $list_task->addChild('ListExam');
	 $list_task->addChild('ListQuestion');
	 $list_task->addChild('ListCourse');
	 $list_task->addChild('ListCourseAndTerm');
	 $list_task->addChild('ListBasket');
	 $list_task->addChild('OpenExam');
	 $list_task->addChild('ExportPdf');

	 $role_admin=$auth->createRole('admin');
	 $role_admin->addChild('edit');
	 $role_admin->addChild('list');

	 $role_viewer=$auth->createRole('viewer');
	 $role_viewer->addChild('list');

	 $auth->assign('admin','admin');
	 $auth->assign('viewer','viewer');
 	echo "Done.";
 }

 public function actionDeletePost()
 {
 	echo "Post deleted.";
 }

 public function actionTest()
 {
 	$post = new stdClass();
 	//$post->authID = 'admin';

 	echo "Current permissions:<br />";
 	echo "<ul>";
 	echo "<li>Check admin: ".Yii::app()->user->checkAccess('admin')."</li>";
 	echo "<li>Create post: ".Yii::app()->user->checkAccess('edit')."</li>";
  	echo "<li>Read post: ".Yii::app()->user->checkAccess('list')."</li>";
	 //echo "<li>Update post: ".Yii::app()->user->checkAccess('updatePost', array('post' => $post))."</li>";
	 echo "<li>Delete post: ".Yii::app()->user->checkAccess('deleteExam')."</li>";
	 echo "</ul>";
 }
   protected function beforeAction()
    {
    	echo ucfirst($this->getId()).ucfirst($this->getAction()->getId());
        /*if(Yii::app()->user->checkAccess(ucfirst($this->getId()) . ucfirst($this->getAction()->getId())))
        {
            return true;
        } else {
            Yii::app()->request->redirect(Yii::app()->user->returnUrl);
        }*/
   }

}