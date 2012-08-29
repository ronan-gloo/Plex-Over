<div class="movies">
	
	<div class="title clearfix">
		<h2 class="pull-left">
			<?= $section->name ?> <small><?= $total.' '.__('app.movies') ?></small>
		</h2>
	</div>	
	
	<div class="view-grid">
		<?php if($movies): ?>
			<ul class="thumbnails">
				<?php foreach ($movies as $movie): ?>
				<li class="span2">
					<div class="ellipsis">
						<?= Html::anchor(To::movie($movie), Html::thumb($movie, array('height' => 200)), array('class' => 'thumbnail')) ?>
						<h5><?= $movie->title ?></h5>
						<p><?= $movie->year ?></p>
					</div>
				</li>
				<?php endforeach ?>
			</ul>
		<?php endif ?>
	</div>
</div>