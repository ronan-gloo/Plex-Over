<div class="show">
	
	<div class="title">
		<h2 class="pull-left">
			<?= $section->name ?> <small><?= $total.' '.__('app.shows') ?></small>
		</h2>
	</div>
	
	<div class="shows view-grid">
		<?php if($shows): ?>
			<ul class="thumbnails">
				<?php foreach ($shows as $show): ?>
				<li class="span2">
					<div class="ellipsis">
						<?= Html::anchor(To::show($show), Html::thumb($show), array('class' => 'thumbnail')) ?>
						<h5><?= $show->title ?></h5>
						<p><?= Text::concat($show->year, count($show->seasons).' '.__('app.seasons')) ?></p>
					</div>
				</li>
				<?php endforeach ?>
			</ul>
		<?php endif ?>
	</div>
</div>