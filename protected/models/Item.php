<?php

/**
 * This is the model class for table "item".
 *
 * The followings are the available columns in table 'item':
 * @property integer $id
 * @property integer $item_category_id
 * @property string $item_name
 * @property string $item_create_time
 * @property string $item_submit_time
 * @property float $item_amount
 * @property float $item_amount_left
 * @property float $item_total
 * @property string $item_type
 * @property string $item_end_time
 * @property integer $item_client_id
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property Client $client
 */
class Item extends CActiveRecord
{
	const TYPE_BASIC = 'basic';
	const TYPE_NORMAL = 'normal';
	const TYPE_ADVANCED = 'advanced';

    public $item_amount = 0;

	/**
	 * fake field to store client name. it will be converted to model if ID is empty
	 * @var string
	 */
	public $item_client_name;
	
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->item_create_time = time();
        $this->item_submit_time = $this->item_create_time;
        $this->item_end_time = $this->item_create_time + 60 * 60 * 24 * 7;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item_name, item_create_time, item_submit_time, item_end_time, item_amount, item_amount_left, item_total, item_type', 'required'),
            array('item_category_id', 'numerical', 'integerOnly'=>true),
            array('item_amount, item_amount_left, item_total', 'numerical'),
            array('item_name, item_type', 'length', 'max'=>255),
            
			array('item_end_time', 'compareDates', 'moreThan' => 'item_create_time'),
            array('item_end_time', 'compareDates', 'moreThan' => 'item_submit_time'),
			
			// fake field is required
			array('item_client_id', 'numerical', 'integerOnly'=>true),
			array('item_client_name', 'required'),
			//array('item_client_name', 'length', 'max'=>255),
			
			// The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, item_category_id, item_name, item_create_time, item_submit_time, item_amount, item_amount_left, item_total, item_type', 'safe', 'on'=>'search'),
        );
    }
	
	/**
	 * internal validator, compare dates (bigger or less)
	 * @param string $attribute		model attribute name
	 * @param array $params
	 */
	public function compareDates($attribute, $params = array())
	{
		if( !empty($params['moreThan']) )
		{
			$compare_attribute = $params['moreThan'];
			$compare_value = Yii::app()->format->format($this->$compare_attribute, 'timestamp');
			$current_value = Yii::app()->format->format($this->$attribute, 'timestamp');
			
			if( $current_value <= $compare_value )
			{
				$this->addError($attribute, Yii::t('app', '{attr} should be later than {compare_attr}', array(
								'{attr}' => $this->getAttributeLabel($attribute),
								'{compare_attr}' => $this->getAttributeLabel($compare_attribute),
							)
						));
			}
		}
	}

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'item_category_id'),
            'client' => array(self::BELONGS_TO, 'Client', 'item_client_id'),
            'additional_data' => array(self::HAS_MANY, 'ItemAdditional', 'item_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'item_category_id' => 'Category',
            'item_name' => 'Kontrahent',
            'item_create_time' => 'Wystawiono',
            'item_submit_time' => 'Sprzedano',
			'item_end_time' => 'Płatność',
            'item_amount' => 'Zapłacono',
            'item_amount_left' => 'Pozostało',
            'item_total' => 'Tazem',
            'item_type' => 'Metoda',
        );
    }
	
	/**
	 * get key/value pairs of available types
	 * @return array
	 */
	public static function getTypesList()
	{
		return array(
			self::TYPE_BASIC => 'Basic type',
			self::TYPE_NORMAL => 'Normal type',
			self::TYPE_ADVANCED => 'Advanced',
		);
	}
	
	/**
	 * getter for $this->typeAlias
	 * @return string
	 */
	public function getTypeAlias()
	{
		$types = self::getTypesList();
		if( !empty($types[ $this->item_type ]) )
		{
			return $types[ $this->item_type ];
		}

		return $this->item_type;
	}
	
	protected function beforeValidate()
	{
		if( parent::beforeValidate() )
		{
			// this is autocompleted value and contain ID (numerical)
			if( is_numeric($this->item_client_name) )
			{
				$this->item_client_id = $this->item_client_name;
				$this->item_name = $this->client->client_name;
			}
			// this is new value, we need to reset old id and old name
			else
			{
				$this->item_client_id = null;
				$this->item_name = $this->item_client_name;
			}
			
			// if ID is still here - we can get client name
			if( !empty($this->item_client_id) )
			{
				$this->item_name = $this->client->client_name;
			}
			return true;
		}
		return false;
	}
	
	/**
	 * normalize date fields
	 */
	protected function beforeSave()
	{
		$this->item_create_time = Yii::app()->format->format($this->item_create_time, 'datetimeSQL');
		$this->item_submit_time = Yii::app()->format->format($this->item_submit_time, 'datetimeSQL');
		$this->item_end_time = Yii::app()->format->format($this->item_end_time, 'datetimeSQL');

		return parent::beforeSave();
	}
	
	/**
	 * get client model based on POST input. if we have old client - then get his data
	 * if we have new client - then generate new model and assign post attributes
	 * @return Client
	 */
	public function getClientByInput( $post )
	{
		// this is autocompleted value and contain ID (numerical)
		if( is_numeric($this->item_client_name) )
		{
			$client = Client::model()->findByPk($this->item_client_name);
		}
		
		if( empty($client) )
		{
			$client = new Client();
			$client->client_name = $this->item_client_name;
		}
		
		if( !empty($post['Client']) )
		{
			$client->attributes = $post['Client'];
		}
		
		return $client;
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
        $criteria = new CDbCriteria;
        $criteria->with = array('client' => array('select' => false));
        $criteria->compare('client.client_user_id', Yii::app()->user->id);
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.item_category_id', $this->item_category_id);
        $criteria->compare('t.item_name', $this->item_name,true);
        $criteria->compare('t.item_create_time', $this->item_create_time,true);
        $criteria->compare('t.item_submit_time', $this->item_submit_time,true);
        $criteria->compare('t.item_amount', $this->item_amount);
        $criteria->compare('t.item_amount_left', $this->item_amount_left);
        $criteria->compare('t.item_total', $this->item_total);
        $criteria->compare('t.item_type', $this->item_type,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Item the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
