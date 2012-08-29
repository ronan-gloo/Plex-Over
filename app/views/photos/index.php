<script type="text/javascript">
	$(function() {
		$('.thumbnail').fancybox({
			padding			: 0,
			closeBtn		: false,
			preload			: 0
		});
	});
</script>

<div class="photos">
	
	<div class="title clearfix">
		<h2 class="pull-left">
			<?= $section->name ?> <small><?= $total.' '.__('app.photos') ?></small>
		</h2>
	</div>	
	
	<div class="view-grid">
		<?php if($photos): ?>
			<ul class="thumbnails">
				<?php foreach ($photos as $photo): ?>
				<li class="photo">
					<div class="ellipsis">
						<?= Html::anchor(
							To::thumb($photo, array('width' => 700, 'height' => 700)),
							Html::thumb($photo, array('height' => 120)),
							array(
								'rel'		=> 'pgal1',
								'class' => 'thumbnail',
								'title'	=> $photo->title,
							)
						)?>
						<h5><?= $photo->title ?></h5>
						<p><small><?= $photo->year ?></small></p>
					</div>
				</li>
				<?php endforeach ?>
			</ul>
		<?php endif ?>
	</div>
</div>