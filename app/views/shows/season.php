<div class="well">
	<h2><?= $season->show->title ?> <small> <?= $season->index_title() ?></small></h2>
</div>

<div class="row-fluid season">	
	<div class="cover span3">
		<?= Html::thumb($season, array('class' => 'thumbnail', 'width' => 300)) ?>
		<strong><?= $season->title ?></strong>
	</div>
	<div class="span9">
		<table class="table table-condensed table-striped">
			<tbody class="ellipsis">
				<?php foreach ($season->episodes as $episode): ?>
					<tr>
						<td><?= Html::label($episode->index, 'inverse') ?></td>
						<td><?= Html::anchor(To::episode($episode), $episode->title) ?></td>
						<td><?= Time::duration($episode->media->duration) ?></td>
						<td>
							<a href="<?= To::download(reset($episode->media->parts)) ?>">
								<i class="icon icon-download"></i> <small><?= Num::format_bytes($episode->media->size) ?></small>
							</a>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>

<hr/>

<nav>
	<?= $pager ?>
</nav>
