<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mgWidgetFormDeleteButtonclass
 *
 * @author gramirez
 */
class mgWidgetFormDeleteButton extends mgWidgetFormButton{
    //put your code here
  protected $delete_name;

  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes );
    $this->addRequiredOption('label');
    $this->addRequiredOption('function');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Javascript'));
    $label= $this->getOption('label');
    $function= $this->getOption('function');
    $div_name= substr($name,0,-8);
    $html= button_to_function(__($label), $function);
    $input_hidden=new sfWidgetFormInputHidden();
    $html.=$input_hidden->render($name,false);
    return $html;
  }
}
?>
