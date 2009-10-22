<?php

/**
 * mgDynamicEmbedded actions.
 *
 * @package    newplugin
 * @subpackage mgDynamicEmbedded
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class mgDynamicEmbeddedActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeDynamicEmbeddedFormAdd(sfWebRequest $request)
  {
    $this->forward404unless($request->isXmlHttpRequest());
    $delete_button_name=$request->getParameter('delete_button_name');
    $format_name=$request->getParameter('format_name');
    $name=$request->getParameter('name');
    $this->title=$request->getParameter('title');
    $embedded_model_name=$request->getParameter('embedded_model_name');
    $key_name=sfInflector::underscore($embedded_model_name).'_'.time().rand(0,100);
    $embedded_format_name= sfInflector::underscore($format_name).'['.sfInflector::underscore($name).']['.$key_name.'][%s]';
    $this->embedded_format_key= sfInflector::underscore($format_name).'_'.sfInflector::underscore($name).'_'.$key_name;
    $object = new $embedded_model_name();
    $embedded_form_name=$embedded_model_name .'Form';
    $this->form=new $embedded_form_name($object);
    $this->form->getWidgetSchema()->setNameFormat($embedded_format_name);
    $this->form->setWidget('delete',new mgWidgetFormDeleteButton(array('label'=> $delete_button_name , 'function'=> "$('".$this->embedded_format_key."_delete').value= true; $('".$this->embedded_format_key."_div').hide()")));
    $this->form->setValidator('delete',new sfValidatorPass());
    unset($this->form['_csrf_token']);
    return $this->renderPartial('embedded_form');
  }
}
