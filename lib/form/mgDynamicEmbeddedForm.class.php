<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mgDinamicEmbeddedFormclass
 *
 * @author gramirez
 */
class mgDynamicEmbeddedForm extends sfFormPropel {
  
  public function configure()
  {
    $this->validatorSchema->setOption('allow_extra_fields', true);    
    $this->setOptions();
    $this->configureWidgets();
    $this->setupEmbeddedForms();
    $myDecorator = new mgDynamicEmbeddedFormDecorator($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('custom', $myDecorator);
    $this->widgetSchema->setFormFormatterName('custom');
  }


  public function __construct( $object, $options = array(), $CSRFSecret = null)
  {
    $this->object = $object;
    $this->isNew = $this->object->isNew();
    $this->options = $options;

    $this->validatorSchema = new sfValidatorSchema();
    $this->widgetSchema    = new sfWidgetFormSchema();
    $this->errorSchema     = new sfValidatorErrorSchema($this->validatorSchema);
    $this->setup();
    $this->configure();

    $this->addCSRFProtection($CSRFSecret);
    $this->resetFormFields();    
  }
  
  static public function configureEmbeddedForms($parent_form, $name, $options)
  {
    $format_name=$parent_form->getWidgetSchema()->getNameFormat();
    $options=array_merge($options, array('name'=>$name, 'format_name' => $format_name));
    $mgDinamicForm= new mgDynamicEmbeddedForm($parent_form->getObject(),$options);
    $parent_form->embedForm($name,$mgDinamicForm);
    $parent_form->getValidatorSchema()->setOption('allow_extra_fields', true);
  }


  public function getModelName(){
    return null;
  }

  public function setOptions()
  {
    $this->format_name=substr($this->getOption('format_name'),0,-4);
    $this->model=$this->getOption('model');
    $this->add_button_name=$this->getOption('add_button_name','add');
    $this->delete_button_name=$this->getOption('delete_button_name','delete');
    $this->name=$this->getOption('name');
    $this->embedded_model=$this->getOption('embedded_model');
    $this->title=$this->getOption('title',sfInflector::humanize(sfInflector::underscore($this->embedded_model)));
  }

  public function configureWidgets()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
    $this->widgetSchema['add_embedded']= new mgWidgetFormRemoteButton(array('url'=>url_for('@mgDynamicEmbeddedFormAdd'),
        'label' =>  $this->add_button_name,
        'with'  =>  "'?embedded_model_name=".$this->embedded_model."&format_name=".$this->format_name."&title=".$this->title."&name=".$this->name."&delete_button_name=".$this->delete_button_name."'",
        'update'=>  'dynamic_form_'.sfInflector::underscore($this->embedded_model).rand(0,10000)));
  }

  public function setupEmbeddedForms()
  {
    $embedded_forms = $this->getRequestEmbeddedForms();
    $embed_form_request = sfContext::getInstance()->getRequest()->getParameter(sfInflector::underscore($this->model), array());
    $embed_form_request[sfInflector::underscore($this->embedded_model)] = isset($embed_form_request[sfInflector::underscore($this->embedded_model)])? $embed_form_request[sfInflector::underscore($this->embedded_model)] : array();

    $embedded_objects=call_user_func(array($this->object,'get'.(sfInflector::camelize($this->embedded_model)).'s'));
    $embedded_form_name=$this->embedded_model.'Form';
    foreach ( $embedded_objects as $embedded)
    {
      $key=sfInflector::underscore($this->embedded_model).'_'.$embedded->getId();
      $form = new $embedded_form_name($embedded);
      $form->widgetSchema['delete']=new mgWidgetFormDeleteButton(array('label'=> $this->delete_button_name , 'function'=> "$('".$key."_div').hide();$('".$this->format_name."_".$this->name ."_".$key."_delete').value= true;"));
      $form->validatorSchema['delete']=new sfValidatorPass();
      $this->embedForm($key, $form, "<div id='".$key."_div'>".$form->getWidgetSchema()->getFormFormatter()->getDecoratorFormat()."</div>");
      //$delete_link = new mgWidgetFormDeleteLink(array('label'=> $this->delete_button_name , 'function'=> "$('".$key."_div').hide();$('".$this->format_name."_".$this->name ."_".$key."_delete').value= true;"));
      //$this->embedForm($key, $form, "<div id='".$key."_div'>".$form->getWidgetSchema()->getFormFormatter()->getDecoratorFormat()."<div>".$delete_link->render($this->delete_button_name)."</div></div>");
    }
    foreach ($embedded_forms as $key => $s)
    {
      if (empty($s['id']) )
      {
        $object = new $this->embedded_model();
        call_user_func(array($object, 'set'.$this->model),$this->object);
        $form = new $embedded_form_name($object);
        $div_id=sfInflector::underscore($key)."_div";
        $form->widgetSchema['delete']=new mgWidgetFormDeleteButton(array('label'=> $this->delete_button_name , 'function'=> "$('".$div_id."').hide();$('".$div_id."_delete').value= true;"));
        $form->validatorSchema['delete']=new sfValidatorPass();
        $this->embedForm($key, $form, "<div id='".$key."_div'>".$form->getWidgetSchema()->getFormFormatter()->getDecoratorFormat()."</div>");
        //$delete_link = new mgWidgetFormDeleteLink(array('label'=> $this->delete_button_name , 'function'=> "$('".$div_id."').hide();$('".$div_id."_delete').value= true;"));
        //$this->embedForm($key, $form, "<div id='".$key."_div'>".$form->getWidgetSchema()->getFormFormatter()->getDecoratorFormat()."<div>".$delete_link->render($this->delete_button_name)."</div></div>");
      }
    }
  }

  public function getRequestEmbeddedForms()
  {
    $embed_form_request = sfContext::getInstance()->getRequest()->getParameter(sfInflector::underscore($this->format_name).'['.$this->name.']', array());
    $embedded_forms = array();
    
    foreach ($embed_form_request as $key => $s)
    {
      if (strstr($key, sfInflector::underscore($this->embedded_model)))
      {
        if ($s['delete'] != "true")
          $embedded_forms[$key] = $s;
      }
    }
    return $embedded_forms;
  }
  public function getEmbeddedFormsDeleted()
  {
    $embed_form_request = sfContext::getInstance()->getRequest()->getParameter(sfInflector::underscore($this->format_name).'['.$this->name.']', array());
    $embedded_forms = array();
    foreach ($embed_form_request as $key => $s)
    {
      if (strstr($key, sfInflector::underscore($this->embedded_model)))
      {
        if ($s['delete'] == "true")
          $embedded_forms[$key] = $s;
      }
    }
    return $embedded_forms;
  }


  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (is_null($con))
    {
      $con = $this->getConnection();
    }
    if (is_null($forms))
    {
      $forms = $this->embeddedForms;
    }
    $embedded_forms_deleted=$this->getEmbeddedFormsDeleted();
    foreach ($forms as $form)
    {
      $key = sfInflector::underscore($this->embedded_model).'_'.$form->getObject()->getId();
      if(isset($embedded_forms_deleted[$key])){
        if($embedded_forms_deleted[$key]['delete'] != "true"){
          if ($form instanceof sfFormPropel)
          {
            $form->saveEmbeddedForms($con);            
            $form->getObject()->save($con);
          }
          else
          {
            $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
          }
        }
        else{
          $form->getObject()->delete();
        }
      }
    }
  }
}
?>
