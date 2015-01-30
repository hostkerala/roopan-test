<?php
/* @var $this ItemController */
?>


<div class="add-form">
    <div class="form-title">
        <h3>Email To KOSTKA </h3>
    </div>
    <br />
    <div class="col-lg-12">
        <?php
            /* @var $form ClientActiveForm */
            $form = $this->beginWidget('application.modules.clientarea.widgets.ClientActiveForm', array(
                'id' => 'sendmail-form', 
                 'htmlOptions' => array('enctype' => 'multipart/form-data'), 
                'isAjax' => true,
                'ajaxTarget' => '#item-content',
            ));
            
            $this->renderPartial('_send_email', array(
                    'form' => $form,
                    'model' => $model,
            )); 
            
            $this->endWidget(); 
        ?>
    </div> 
</div>