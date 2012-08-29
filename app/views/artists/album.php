<div class="row-fluid album">
		<div class="cover span3">
			<?= Html::thumb($album, array('class' => 'thumbnail')) ?>
			<strong><?= $album->title ?></strong>
			<small><?= $album->artist->title ?></small>
			<i><small><?= $album->year ?></small></i>
		</div>
		<div class="span9">
			<table class="table table-condensed table-striped">
				<tbody>
					<?php foreach ($album->tracks as $track): ?>
						<tr>
							<td><?= $track->index ?></td>
							<td data-audio-url="<?= To::stream($track, 'audio') ?>"><i class="icon icon-play"></i></td>
							<td><?= $track->title ?></td>
							<td><?= Time::duration($track->media->duration, true) ?></td>
							<td>
								<a href="<?= To::download(reset($track->media->parts)) ?>">
									<i class="icon icon-download"></i> <small><?= Num::format_bytes($track->media->size, 1) ?></small>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
</div>

<div class="breadcrumb audio-player">
	<?= Html::audio_player(html_tag('i', array(), $album->artist->title)) ?>
</div>
