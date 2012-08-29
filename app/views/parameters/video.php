<fieldset>
	<?= Form::control_open() ?>
		<?= Form::label(__('video.subs_color')) ?>
		<?= Form::select('main[subtitles_color]', $subtitles_color, array('white' => 'white', 'yellow' => 'yellow')) ?>
	<?= Form::control_close() ?>

	<?= Form::control_open() ?>
		<?= Form::label(__('video.m3u8_quality')) ?>
		<?= Form::input('main[m3u8_quality]', $m3u8_quality, array('type' => 'number', 'min' => 1, 'max' => 10)) ?>
		<?= Form::help(Html::label(__('video.m3u8_quality_label'), 'warning').' '.__('video.m3u8_quality_help')) ?>
	<?= Form::control_close() ?>
</fieldset>