<?php foreach ($data as $key => $row): ?>

	<?php if(! empty($row['items'])): ?>
		<div class="home-section <?= $key ?>">
			<h3 class="title"><?= __('app.last_'.$key.'s') ?></h3>
			<ul class="thumbnails">
				<?php foreach ($row['items'] as $item): ?>
					<li class="span2">
						<?= Html::anchor(To::$key($item), Html::thumb($item, array('height' => $row['height'])), array('class' => 'thumbnail')) ?>
					</li>
				<?php endforeach ?>	
			</ul>
		</div>
	<?php endif ?>

<?php endforeach ?>
