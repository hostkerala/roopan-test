<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <div class="col-lg-12">
                <?php echo $form->labelEx($model,'from'); ?>
                <?php echo $form->textField($model,'from',array('readOnly'=>'readOnly','size'=>'50')); ?>
                <?php echo $form->error($model,'from'); ?>
            </div>
        </div>
        <div class="form-group">
             <div class="col-lg-12">
                <?php echo $form->labelEx($model,'to'); ?>
                <?php echo $form->textField($model,'to',array('size'=>'50','placeholder'=>"Comma seperated To address",'type'=>'text')); ?>                             
                <?php echo $form->error($model,'to'); ?>
             </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <?php echo $form->labelEx($model,'cc'); ?>
                <?php echo $form->textField($model,'cc',array('size'=>'50','placeholder'=>"Comma seperated CC address",'type'=>'text')); ?>         
                <?php echo $form->error($model,'cc'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <?php echo $form->labelEx($model,'subject'); ?>
                <?php echo $form->textField($model,'subject',array('size'=>50,'maxlength'=>128,'readOnly'=>'readOnly')); ?>
                <?php echo $form->error($model,'subject'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
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
        </div>
        <hr />
        <div class="form-group">
            <div class="col-lg-12">
                <div class="col-md-6">
                    <?php echo $form->labelEx($model,'attach_customer_statement'); ?>                    
                </div>
                <div class="col-md-6">
                    <?php echo $form->checkBox($model,'attach_customer_statement',array('style'=>'margin-top:50px;')); ?>                    
                </div>      
                <div class="col-lg-12">
                    <?php echo $form->error($model,'attach_customer_statement'); ?>                
                </div>                   
            </div>
        </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <hr />
                <div class="col-md-6">
                    <?php echo $form->labelEx($model,'attach_pdf'); ?>                
                </div>
                <div class="col-md-6">
                    <?php echo $form->checkBox($model,'attach_pdf'); ?>         
                </div>      
                <div class="col-lg-12">
                    <?php echo $form->error($model,'attach_pdf'); ?>                
                </div>                   
            </div>
        </div>        
        <div class="form-group">
            <div class="col-lg-12">
                <hr />
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
                <div class="col-lg-12">
                    <?php echo $form->error($model,'attach_files'); ?>                
                </div>                   
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">    
                <hr />  
                <?php echo $form->hiddenField($model,'selected_items');  ?>
                <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>            
                <?php echo CHtml::submitButton('Cancel', array('class' => 'btn btn-danger')); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
//$(document).ready(function() {
//tinymce.init({
//    selector: "textarea",
//    theme: "modern",
//    plugins: [
//        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
//        "searchreplace wordcount visualblocks visualchars code fullscreen",
//        "insertdatetime media nonbreaking save table contextmenu directionality",
//        "emoticons template paste textcolor colorpicker textpattern"
//    ],
//    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
//    toolbar2: "print preview media | forecolor backcolor emoticons",
//    image_advtab: true,
//    templates: [
//        {title: 'Test template 1', content: 'Test 1'},
//        {title: 'Test template 2', content: 'Test 2'}
//    ]
//});
//})
</script>