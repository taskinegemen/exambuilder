<?php

/**
 * This is the model class for table "question".
 *
 * The followings are the available columns in table 'question':
 * @property integer $question_id
 * @property integer $question_course_id
 * @property integer $question_template
 * @property integer $question_parent
 * @property string $question_type
 * @property string $question_content
 * @property double $order
 *
 * The followings are the available model relations:
 * @property ExamQuestion[] $examQuestions
 * @property Course $questionCourse
 */
class Question extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question_course_id, question_content, order', 'required'),
			array('question_course_id, question_template, question_parent', 'numerical', 'integerOnly'=>true),
			array('order', 'numerical'),
			array('question_type', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('question_id, question_course_id, question_template, question_parent, question_type, question_content, order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'examQuestions' => array(self::HAS_MANY, 'ExamQuestion', 'question_id'),
			'questionCourse' => array(self::BELONGS_TO, 'Course', 'question_course_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'question_id' => 'Question',
			'question_course_id' => 'Question Course',
			'question_template' => 'Question Template',
			'question_parent' => 'Question Parent',
			'question_type' => 'Question Type',
			'question_content' => 'Question Content',
			'order' => 'Order',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('question_course_id',$this->question_course_id);
		$criteria->compare('question_template',$this->question_template);
		$criteria->compare('question_parent',$this->question_parent);
		$criteria->compare('question_type',$this->question_type,true);
		$criteria->compare('question_content',$this->question_content,true);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Question the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
