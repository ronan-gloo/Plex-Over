<div class="row-fluid artist">
	<div class="well">
		<div class="clearfix">
			<div class="pull-left span3">
				<?= Html::thumb($artist, array('class' => 'thumbnail')) ?>
			</div>
			<div class="pull-left span9">
				<h1><?= $artist->title ?> <small><?= count($artist->albums).' '.__('app.albums') ?></small></h1>
				<div><?= Text::summarize($artist->summary) ?></div>
			</div>
		</div>
	</div>
</div>

<div class="view-grid albums">
	<?php if($artist->albums): ?>
		<ul class="thumbnails">
			<?php foreach ($artist->albums as $album): ?>
				<li class="span2">
					<div class="ellipsis">
						<?= Html::anchor(To::album($album), Html::thumb($album, array('width' => 140, 'height' => 140)),
							array('class'	=> 'thumbnail')
						) ?>
						<h5><?= $album->title ?></h5>
						<p><?= Text::concat($album->year, count($album->tracks).' '.__('app.tracks')) ?></p>
					</div>
				</li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
</div>

<hr />

<nav>
	<?= $pager ?>
</nav>





