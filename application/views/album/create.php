<?php $this->load->view('inc/header'); ?>

<h1>Create Album</h1>
<?php
echo form_open('album/add');

echo form_fieldset('Album Information');

echo form_error('album_name');
echo form_label('Album Name', 'album_name');
echo form_input('album_name');

echo form_fieldset_close(); 

echo form_button(array('id' => 'submit', 'value' => 'Add', 'name' => 'submit', 'type' => 'submit', 'content' => 'Add','class' => 'btn btn-primary'));

echo form_close();
?>

<?php $this->load->view('inc/footer'); ?>
