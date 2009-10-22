<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mgWidgetFormDeleteLinkclass
 *
 * @author gramirez
 */
class mgWidgetFormDeleteLink extends sfWidgetForm{
    //put your code here

  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes );
    $this->addRequiredOption('label');
    $this->addRequiredOption('function');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Javascript', 'I18N'));
    $label= $this->getOption('label');
    $function= $this->getOption('function');
    $html= link_to_function(__($label), $function);
    $input_hidden=new sfWidgetFormInputHidden();
    $html.=$input_hidden->render($name,false);
    return $html;
  }
}
?>
