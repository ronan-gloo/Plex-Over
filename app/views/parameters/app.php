<fieldset>
	<?= Form::control_open() ?>
		<?= Form::label(__('global.appname')) ?>
		<?= Form::input('main[appname]', $appname) ?>
	<?= Form::control_close() ?>

	<?= Form::control_open() ?>
		<?= Form::label(__('global.per_page')) ?>
		<?= Form::input('main[per_page]', $per_page, array('type' => 'number')) ?>
	<?= Form::control_close() ?>

	<?= Form::control_open() ?>
		<?= Form::label(__('global.language')) ?>
		<?= Form::select('main[language]', Config::get('language'), $languages) ?>
	<?= Form::control_close() ?>	
</fieldset>