<?php
/* @var $this ItemController */
/* @var $model Item */
/* @var $client Client */
/* @var $category Category */
?>
<div class="add-form">
	<div class="form-title">
		<h3>Add Item to <?php echo Html::encode($category->category_title); ?></h3>
	</div>

	<?php
	/* @var $form ClientActiveForm */
	$form = $this->beginWidget('application.modules.clientarea.widgets.ClientActiveForm', array(
		'id' => 'add-item-form',
		'isAjax' => true,
		'ajaxTarget' => '#item-content',
	));
	?>
		<?php $this->renderPartial('_form_fields', array('form' => $form, 'model' => $model, 'client' => $client, 'controls' => true, 'additional_data' => $additional_data, 'category'=> $category)); ?>
	<?php $this->endWidget(); ?>
</div>