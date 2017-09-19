<?php echo head(); ?>

<?php
if (isset($_GET['document_id'])){
    $identifier = $_GET['document_id'];
}
if (isset($_GET['document_id'])){
    $title = $_GET['document_title'];
}
?>

<h1><?php echo html_escape(get_option('simple_contact_form_contact_page_title')); ?></h1>
<div id="primary">
  <div id="simple-contact">
    <div id="form-instructions">
        <?php echo get_option('simple_contact_form_contact_page_instructions'); // HTML ?>
    </div>
    <?php echo flash(); ?>
    <form name="contact_form" id="contact-form"  method="post" enctype="multipart/form-data" accept-charset="utf-8">
      <fieldset>
        <div class="field">
            <h3>Name</h3>
            <div class='inputs'>
            <?php echo $this->formText('name', $name, array('class'=>'textinput')); ?>
            </div>
        </div>

        <div class="field">
            <h3>Email</h3>
            <div class='inputs'>
                <?php echo $this->formText('email', $email, array('class'=>'textinput'));  ?>
            </div>
        </div>

        <div class="field">
          <h3>Message</h3>
          <div class='inputs'>
            <?php if (isset($title)){
              $document_data = "Document Title: ".$title."\rDocument URL: discovery.civilwargovernors.org/document/".$identifier."\r";
              $message = $document_data . $message;
            } ?>
            <?php echo $this->formTextarea('message', $message , array('class'=>'textinput', 'rows' => '10')); ?>
          </div>
        </div>

      </fieldset>


        <fieldset>
        <?php if ($captcha): ?>
        <div class="field">
            <?php echo $captcha; ?>
        </div>
        <?php endif; ?>

        <div class="field">
          <?php echo $this->formSubmit('send', 'Send'); ?>
        </div>

        </fieldset>
    </form>

</div>

</div>
<?php echo foot();
