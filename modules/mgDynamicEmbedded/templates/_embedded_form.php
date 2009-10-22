<?php use_helper('Javascript','I18N')?>
<?php $id=sfInflector::camelize(substr($form->getWidgetSchema()->getNameFormat(),0,-4))?>
<?php $name= sfInflector::underscore($id)?>
<div id="<?php echo $embedded_format_key?>_div">
  <div class="sf_admin_form_row">
    <label><strong><?php echo __($title) ?></strong></label><br/><br/>

    <?php include_stylesheets_for_form($form) ?>
    <?php include_javascripts_for_form($form) ?>

    <?php echo $form->renderHiddenFields() ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

          <table id="cualquier_cosa">
            <tbody>
              <?php echo $form ?>
            </tbody>
          </table>
  </div>
</div>