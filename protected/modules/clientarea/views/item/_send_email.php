
<div class="form-wrap form-horizontal">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <?php echo $form->labelEx($model,'from', array('class' => 'control-label')); ?>
                <?php echo $form->textField($model,'from',array('readOnly'=>'readOnly','size'=>30)); ?>
                <?php echo $form->error($model,'from'); ?>
            </div>
            <div class="form-group">
                    <?php echo $form->labelEx($model,'to', array('class' => 'control-label')); ?>
                    <?php echo $form->textField($model,'to',array('placeholder'=>"Comma seperated To address",'type'=>'text','size'=>30)); ?>                             
                    <?php echo $form->error($model,'to'); ?>
            </div>
            <div class="form-group">
                    <?php echo $form->labelEx($model,'cc', array('class' => 'control-label')); ?>
                    <?php echo $form->textField($model,'cc',array('placeholder'=>"Comma seperated CC address",'type'=>'text','size'=>30)); ?>         
                    <?php echo $form->error($model,'cc'); ?>
            </div>
            <div class="form-group">
                    <?php echo $form->labelEx($model,'subject', array('class' => 'control-label')); ?>
                    <?php echo $form->textField($model,'subject',array('maxlength'=>128,'readOnly'=>'readOnly','size'=>30)); ?>
                    <?php echo $form->error($model,'subject'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'body', array('class' => 'control-label')); ?>
                <?php 
                $editMe = $this->widget('ext.editMe.widgets.ExtEditMe', array(
                    'name'  =>'SendMailForm[body]',
                    'value' => $model->body,
                    'toolbar'=>
                        array(
                            array(
                               'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates',
                           ),
                           array(
                               'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo',
                           ),
                           array(
                               'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'
                           ),
                           array(
                               'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'
                           ),
                           '/',
                           array(
                               'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat',
                           ),
                           array(
                               'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl',
                           ),
                           array(
                               'Link', 'Unlink', 'Anchor',
                           ),
                           array(
                               'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'
                           ),
                           '/',
                           array(
                               'Styles', 'Format', 'Font', 'FontSize',
                           ),
                           array(
                               'TextColor', 'BGColor',
                           ),
                           array(
                               'Maximize', 'ShowBlocks',
                           ),
                           array(
                               'About',
                           ),
                    )
                ));
                ?>
                <?php echo $form->error($model,'body'); ?>     
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <?php echo $form->labelEx($model,'attach_customer_statement'); ?>                    
                </div>
                <div class="col-md-6">
                    <?php echo $form->checkBox($model,'attach_customer_statement',array('style'=>'margin-top:50px;')); ?>                    
                </div>      
                    <?php echo $form->error($model,'attach_customer_statement'); ?>  
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <?php echo $form->labelEx($model,'attach_pdf'); ?>                
                </div>
                <div class="col-md-6">
                    <?php echo $form->checkBox($model,'attach_pdf'); ?>         
                </div>      
                    <?php echo $form->error($model,'attach_pdf'); ?>                
                </div> 
            <div class="form-group">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model,'attach_files'); ?>                    
                </div>
                <div class="col-md-8">
                    <?php $this->widget('CMultiFileUpload', array(
                        'name' => 'attached_files',
                        'accept' => 'pdf',
                        'duplicate' => 'Duplicate file!',
                        'denied' => 'Invalid file type', 
                    )); ?>                    
                </div>      
                <?php echo $form->error($model,'attach_files'); ?>                
            </div>
            <div class="form-group">
                <div class="buttons">
                    <?php   echo $form->hiddenField($model,'selected_items');  ?>
                    <?php   echo CHtml::submitButton('Send', array('class' => 'btn btn-primary')); ?>            
                    <?php   echo Html::link('Cancel', 
                                        array( ItemController::LIST_ACTION_ROUTE ), 
                                        array('data-target' => '#item-content', 'class' => 'btn btn-default')
                                ); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>