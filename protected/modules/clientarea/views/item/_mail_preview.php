<section class="round-border">
        <div>
                <button href="#collapse1" class="nav-toggle btn btn-primary">Show Preview</button>
        </div>
        <div id="collapse1" style="display:none">
            <div class="bulk-item-view">
             <hr />
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
        </div>
</section>


<script>
        $(document).ready(function() {
          $('.nav-toggle').click(function(){
                //get collapse content selector
                var collapse_content_selector = $(this).attr('href');					

                //make the collapse content to be shown or hide
                var toggle_switch = $(this);
                $(collapse_content_selector).toggle(function(){
                  if($(this).css('display')=='none'){
                        //change the button label to be 'Show'
                        toggle_switch.html('Show Preview');
                  }else{
                        //change the button label to be 'Hide'
                        toggle_switch.html('Hide Preview');
                  }
                });
          });

        });	
</script>
<style>	
.round-border {
        border: 1px solid #eee;
        border: 1px solid rgba(0, 0, 0, 0.05);
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 5px;
}
</style>
