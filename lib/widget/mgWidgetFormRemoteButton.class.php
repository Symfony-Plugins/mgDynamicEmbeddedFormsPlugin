<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mgWidgetFormRemoteButtonclass
 *
 * @author gramirez
 */
class mgWidgetFormRemoteButton extends sfWidgetForm
{
  protected $label;
  protected $url;

  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes );
    $this->addRequiredOption('label');
    $this->addRequiredOption('url');
    $this->addOption('update','update_div');
    $this->addOption('position','after');
    $this->addOption('with');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {

    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Javascript'));
    $label= $this->getOption('label');
    $url= $this->getOption('url');
    $update= $this->getOption('update');
    $position= $this->getOption('position');
    $with= $this->getOption('with');
    $div_name= substr($name,0,-8);
    $button=button_to_remote($label,
          array('url'=> $url, 'update'=>$update, 'position'=> $position, 'script'=> true, (is_null($with))?'':'with'=> $with), array());
    $button.='<div id="'.$update.'"></div>';
    return $button;
  }
}
?>
