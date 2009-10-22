<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mgWidgetFormButton
 *
 * @author gramirez
 */
class mgWidgetFormButton extends sfWidgetForm
{
  protected $label;
  protected $function;

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
    $button= button_to_function(__($label), $function);
    return $button;
  }
}
?>
