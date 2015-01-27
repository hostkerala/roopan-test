<?php

/**
 * This is the model class for table "email_history".
 *
 * The followings are the available columns in table 'email_history':
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $cc
 * @property string $sent_date_time
 * @property string $item_ids
 * @property integer $attach_pdf
 * @property integer $attach_customer_statement
 * @property string $attach_files
 * @property string $body
 */
class EmailHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'email_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('from, to, subject, cc, body', 'required'),
                        array('subject', 'length', 'max'=>254),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, from, to, subject, cc, sent_date_time, item_ids, attach_pdf, attach_customer_statement, attach_files, body', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'from' => 'From',
			'to' => 'To',
			'subject' => 'Subject',
			'cc' => 'Cc',
			'sent_date_time' => 'Sent Date Time',
			'item_ids' => 'Item Ids',
			'attach_pdf' => 'Attach Pdf',
			'attach_customer_statement' => 'Attach Customer Statement',
			'attach_files' => 'Attach Files',
			'body' => 'Body',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('to',$this->to,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('cc',$this->cc,true);
		$criteria->compare('sent_date_time',$this->sent_date_time,true);
		$criteria->compare('item_ids',$this->item_ids,true);
		$criteria->compare('attach_pdf',$this->attach_pdf);
		$criteria->compare('attach_customer_statement',$this->attach_customer_statement);
		$criteria->compare('attach_files',$this->attach_files,true);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmailHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
