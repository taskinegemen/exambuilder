<?php

class ProfileController extends Controller
{

	public function actionOpen(){

		$user=User::model()->find('user_id=:user_id',array(':user_id'=>Yii::app()->user->user_id));
		$this->render('open',array(
									'user'=>$user,
									));

	}
	public function actionSave(){

		$username=Yii::app()->request->getPost("username",NULL);
		$password=Yii::app()->request->getPost("password",NULL);
		$password=hash('sha512', $password.Yii::app()->params['salt']);
		$user=User::model()->find('user_id=:user_id',array(':user_id'=>Yii::app()->user->user_id));
		if($user){
			$user->username=$username;
			$user->password=$password;
			if(!$user->save())
			{
				$this->renderJSON($user->getErrors());
			}
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