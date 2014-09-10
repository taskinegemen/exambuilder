<?php

/**
 * This is the model class for table "exam".
 *
 * The followings are the available columns in table 'exam':
 * @property integer $exam_id
 * @property integer $exam_term_id
 * @property integer $exam_course_id
 * @property string $exam_title
 * @property string $exam_definition
 * @property string $exam_year
 *
 * The followings are the available model relations:
 * @property Course $examCourse
 * @property Term $examTerm
 * @property ExamQuestion[] $examQuestions
 */
class Exam extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exam';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exam_term_id, exam_course_id, exam_title, exam_definition, exam_year', 'required'),
			array('exam_term_id, exam_course_id', 'numerical', 'integerOnly'=>true),
			array('exam_title', 'length', 'max'=>150),
			array('exam_definition', 'length', 'max'=>500),
			array('exam_year', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('exam_id, exam_term_id, exam_course_id, exam_title, exam_definition, exam_year', 'safe', 'on'=>'search'),
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
			'examCourse' => array(self::BELONGS_TO, 'Course', 'exam_course_id'),
			'examTerm' => array(self::BELONGS_TO, 'Term', 'exam_term_id'),
			'examQuestions' => array(self::HAS_MANY, 'ExamQuestion', 'exam_id'),
			'questions'=>array(self::MANY_MANY, 'Question','exam_question(exam_id, question_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'exam_id' => 'Exam',
			'exam_term_id' => 'Exam Term',
			'exam_course_id' => 'Exam Course',
			'exam_title' => 'Exam Title',
			'exam_definition' => 'Exam Definition',
			'exam_year' => 'Exam Year',
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

		$criteria->compare('exam_id',$this->exam_id);
		$criteria->compare('exam_term_id',$this->exam_term_id);
		$criteria->compare('exam_course_id',$this->exam_course_id);
		$criteria->compare('exam_title',$this->exam_title,true);
		$criteria->compare('exam_definition',$this->exam_definition,true);
		$criteria->compare('exam_year',$this->exam_year,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Exam the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
