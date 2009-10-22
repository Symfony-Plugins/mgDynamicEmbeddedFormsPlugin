<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mgDynamicEmbeddedFormDecoratorclass
 *
 * @author gramirez
 */
class mgDynamicEmbeddedFormDecorator extends sfWidgetFormSchemaFormatter{

    //put your code here
 


  protected
    $rowFormat = '%field%%help%%error%%hidden_fields%',
    $helpFormat = '<span class="help">%help%</span>',
    $errorRowFormat = '<div class ="form_global_errors error">%errors%</div>',
    $errorListFormatInARow = '%first_error%',
    $errorRowFormatInARow = '<span class="error">%error%</span>',
    $namedErrorRowFormatInARow = '%name%: %error%<br />',
    $decoratorFormat = '<ul class="custom_list_form">%content%</ul>';

}
?>
