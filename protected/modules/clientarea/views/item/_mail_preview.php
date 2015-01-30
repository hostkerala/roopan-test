<div class="list-view">
    <h3> Following Items sent successfully. </h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => Item::model()->getSelectedItems($selected_items),
        'itemsCssClass' => 'table table-hover table-bordered',
        'columns' => array(
                'id',                           
                'item_name',
                'item_create_time' => array(
                        'name' => 'item_create_time',
                        'type' => 'date',
                        'htmlOptions' => array( 'width' => '120px' ),
                ),
                'item_submit_time' => array(
                        'name' => 'item_submit_time',
                        'type' => 'date',
                        'htmlOptions' => array( 'width' => '120px' ),
                ),
                'item_amount' => array(
                        'name' => 'item_amount',
                        'htmlOptions' => array( 'width' => '80px' ),
                ),
                'item_amount_left' => array(
                        'name' => 'item_amount_left',
                        'value' => function($data) {
                                return $data->item_category_id == Category::ID_SUB_IN_1_2 ? '-' : $data->item_amount_left;
                        },
                        'htmlOptions' => array( 'width' => '120px' ),
                ),
                'item_total' => array(
                        'name' => 'item_total',
                        'htmlOptions' => array( 'width' => '80px' ),
                ),
                'typeAlias' => array(
                        'name' => 'type',
                        'value' => '$data->typeAlias',
                        'htmlOptions' => array( 'width' => '200px' ),
                ),
        ),
));?>

        <div class="buttons">
        <?php echo Html::link('Back to list', 
                        array( ItemController::LIST_ACTION_ROUTE ), 
                        array('data-target' => '#item-content', 'class' => 'btn btn-default')
                ); ?>
        </div>
</div>
