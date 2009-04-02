<?php

class UserController extends CController
{
	const PAGE_SIZE=10;
	
	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';
	
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_user;
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.RbacFilter - list,show,captcha',
			),
		);
	}
	
	/**
	 * Login user.
	 */
	public function actionLogin() {
		$user = new User;
		if (Yii::app()->request->isPostRequest) {
			// collect user input data
			if (isset($_POST['User']))
				$user->setAttributes($_POST['User'], 'login');
			// validate user input and redirect to previous page if valid
			if ($user->validate('login'))// ;
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('user' => $user));
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/**
	 * Shows a particular user.
	 */
	public function actionShow()
	{
		$this->render('show',array('user'=>$this->loadUser()));
	}

	/**
	 * Creates a new user.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$user=new User;
		if(isset($_POST['User']))
		{
			$user->attributes=$_POST['User'];
			if($user->save())
				$this->redirect(array('show','id'=>$user->id));
		}
		$this->render('create',array('user'=>$user));
	}

	/**
	 * Updates a particular user.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$user=$this->loadUser();
		if(isset($_POST['User']))
		{
			$user->attributes=$_POST['User'];
			if($user->save())
				$this->redirect(array('show','id'=>$user->id));
		}
		$this->render('update',array('user'=>$user));
	}

	/**
	 * Deletes a particular user.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadUser()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(500,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all users.
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria;

		$pages=new CPagination(User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$userList=User::model()->findAll($criteria);

		$this->render('list',array(
			'userList'=>$userList,
			'pages'=>$pages,
		));
	}

	/**
	 * Manages all users.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;

		$pages=new CPagination(User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('User');
		$sort->applyOrder($criteria);

		$userList=User::model()->findAll($criteria);

		$this->render('admin',array(
			'userList'=>$userList,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser($id=null)
	{
		if($this->_user===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_user=User::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_user===null)
				throw new CHttpException(500,'The requested user does not exist.');
		}
		return $this->_user;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadUser($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
}