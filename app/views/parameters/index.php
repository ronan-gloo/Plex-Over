<div class="parameters">

<div class="content">
	<?= $msg ?>
	<?= Form::open(array('type' => 'horizontal')) ?>
		
		<?= $tabs ?>
		
		<?= Form::action_open() ?>
			<?= Form::submit(null, 'Save parameters', array('status' => 'primary', 'size' => 'large')) ?>
		<?= Form::action_close() ?>
		
	<?= Form::close() ?>
</div>

</div>