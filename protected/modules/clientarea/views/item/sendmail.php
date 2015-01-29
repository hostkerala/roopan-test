<?php
/* @var $this ItemController */
?>

<div class="add-form">
    <div class="form-title">
        <h3>Email To KOSTKA </h3>
    </div>

<div class="col-lg-12">
    <?php $this->renderPartial('_mail_preview', array(
        'selected_items'=>$model->selected_items,
    ));?>
</div>

<?php
/* @var $form ClientActiveForm */
$form = $this->beginWidget('application.modules.clientarea.widgets.ClientActiveForm', array(
    'id' => 'sendmail-form', 
     'htmlOptions' => array('enctype' => 'multipart/form-data'), 
    'isAjax' => true,
    'ajaxTarget' => '#item-content',
));
?>    

    <?php
        //$msgHTML = $this->renderInternal('_mail_template', array('model'=>$model), true);    
    ?>
    
    <div class="form-group">
    <?php $this->renderPartial('_send_email', array(
        'form' => $form,
        'model' => $model,
    )); ?>
    <?php $this->endWidget(); ?>
</div>
</div>