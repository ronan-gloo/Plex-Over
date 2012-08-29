<fieldset>
	<?= Form::control_open() ?>
		<?= Form::label(__('server.protocol')) ?>
		<?= Form::input('main[protocol]', $protocol) ?>
	<?= Form::control_close() ?>

	<?= Form::control_open() ?>
		<?= Form::label(__('server.host')) ?>
		<?= Form::input('main[host]', $host) ?>
	<?= Form::control_close() ?>

	<?= Form::control_open() ?>
		<?= Form::label(__('server.port')) ?>
		<?= Form::input('main[port]', $port, array('type' => 'number')) ?>
	<?= Form::control_close() ?>

	<?= Form::control_open() ?>
		<?= Form::label(__('server.identifier')) ?>
		<?= Form::input('main[identifier]', $identifier, array('class' => 'span4')) ?>
	<?= Form::control_close() ?>

</fieldset>
