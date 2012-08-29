<div class="music">
	
	<div class="title clearfix">
		<h2 class="pull-left">
			<?= $section->name ?> <small><?= $total.' '.__('app.artists') ?></small>
		</h2>
	</div>
	
	<div class="albums view-grid">
		<?php if($artists): ?>
			<ul class="thumbnails">
				<?php foreach ($artists as $id => $artist): ?>
				<li class="span2">
					<div class="ellipsis">
						<?= Html::anchor(To::artist($artist), Html::thumb(reset($artist->albums), array(
								'height'=> 140,
								'width'	=> 140)),
							array(
								'class' => 'thumbnail'
						)) ?>
						<h5><?= $artist->title ?></h5>
						<p><?= count($artist->albums).' '.__('app.albums') ?></p>
					</div>
				</li>
				<?php endforeach ?>
			</ul>
		<?php endif ?>
	</div>
	
</div>