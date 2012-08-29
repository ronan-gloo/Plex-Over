<div class="episode">

	<h2 class="title">
		<?= $episode->index_title() ?>
		<small> - <?= $episode->title ?></small>
	</h2>
	
	<div class="row-fluid">
			<div class="video-player">
				<?= Widget_Video::forge($episode, array('poster' => To::thumb($episode, array('width' => 500)))) ?>
			</div>
	</div>
	
	<div class="summary">
		<?= $episode->summary() ?>
	</div>
	<br />
	<div class="row clearfix">
		<div class="span6">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td><strong><?= __('app.duration') ?></strong></td>
						<td><?= Time::duration($episode->media->duration) ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.director') ?></strong></td>
						<td><?= $episode->tags('director') ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.writer') ?></strong></td>
						<td><?= $episode->tags('writer') ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.rating') ?></strong></td>
						<td><?= Html::rating($episode->rating) ?></td>
					</tr>
				</tbody>
			</table>
			<?= Widget_Download::forge($episode) ?>
		</div>
	</div>
	
	<hr />
	
	<nav><?= $pager ?></nav>
	
</div>