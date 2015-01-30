<?php

/**
 * SendMAi class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class SendMailForm extends CFormModel
{
	public $from;
	public $to;
	public $cc;
	public $subject;
	public $body;
        public $selected_items;
        public $attach_customer_statement;
        public $attach_pdf;
        public $attach_files;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// subject and body are required
			array('from,to,subject', 'required'),
                       // from,to,cc has to be a valid email address
                        array('from', 'email'),
			array('to,cc', 'validateEmail'),
                        array('body,selected_items,attach_customer_statement,attach_pdf,attach_files', 'safe'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'from'=>'From',
                        'to'=>'Send To',
                        'cc'=>'CC',
                        'subject'=>'Subject',
                        'attach_customer_statement'=>'Attach Customer Statement',
                        'attach_pdf'=>'Attach PDF',
                        'attach_files'=>'Attach Files',
		);
	}
        
        
        /**
        * Created By Roopan v v <yiioverflow@gmail.com>
        * Date : 26-01-2015
        * Time 04:00 PM
        * Function to Validate email arrays
        */
        
        public function validateEmail($attribute,$params)
        {
            if($this->$attribute)
            {
                $emailArray =  explode( ',', $this->$attribute);    
                foreach($emailArray as $email)
                {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                    {
                        $this->addError($attribute,'Incorrect Email Entered');                    
                    }     
                }
            }
        }
}