<?php
/* @var $this ItemController */
/* @var $categories_root Category[] */
?>

<div class="row clearfix" id="dashboard-content">
    <div class="col-md-2 column" id="item-sidebar">
        <div class="panel-group" id="panel-categories_list">
            <?php  foreach($categories_root as $category) : ?>
                <div class="panel panel-default">
					<div class="panel-heading">
						<a class="panel-title" data-toggle="collapse" data-parent="#panel-categories_list" href="#panel-element-<?php echo $category->id; ?>">
							<?php echo Html::encode($category->category_title); ?>
						</a>
					</div>
					<div id="panel-element-<?php echo $category->id; ?>" class="panel-collapse collapse">

						<?php foreach($category->descendants()->findAll() as $descendant) :?>
						<div class="panel-body">
							<?php echo Html::link(
									$descendant->category_title, 
									array('/clientarea/item/create', 'category_id' => $descendant->id),
									array('data-target' => '#item-content')
								); ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
            <?php endforeach; ?>
        </div>
		<div id="items-controls" class="sidebar-btns">
			<?php echo Html::button('Read', array('class' => 'btn btn-default', 
						'id' => 'read-item',
						'data-action' => Html::url( '/clientarea/item/bulkView' ),
						'data-target' => '#item-content',
				));?>
			<?php echo Html::button('Edit', array('class' => 'btn btn-default', 
						'id' => 'edit-item',
						'data-action' => Html::url( '/clientarea/item/bulkUpdate' ),
						'data-target' => '#item-content',
				));?>
			<?php echo Html::button('Delete', array('class' => 'btn btn-default', 
						'id' => 'delete-item',
						'data-action' => Html::url( '/clientarea/item/bulkDelete' ),
						'data-target' => '#item-content',
						'confirm' => "Are you sure you want to delete the selected items?",
				));?>
            <?php echo Html::button('Print', array('class' => 'btn btn-default',
                        'id' => 'print-item',
                        'data-action' => Html::url( '/clientarea/item/print' ),
                        'data-target' => '#item-content',
                ));?>
		</div>
    </div>
    <div class="col-md-10 column" id="item-content">
		loading...
		<script>
			ajax_load_block('#item-content', '<?php echo Html::url( array('item/list', 'categoryType' => $categoryType) ); ?>');
			//ajax_load_block('#item-content', '<?php echo Html::url( '/clientarea/item/create/category_id/6' ); ?>');
		</script>
    </div>
</div>

