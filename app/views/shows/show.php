<div class="show">
	
	<div class="title">
		<h2>
			<?= $show->title ?> <small><?= $show->year ?></small>
		</h2>
	</div>
	
	<div class="seasons view-grid">
		<ul class="thumbnails">
		<?php foreach ($show->seasons as $season): ?>
			<li class="span2">
				<div class="ellipsis">
					<?= Html::anchor(To::season($season), Html::thumb($season, array('width' => 200, 'class' => 'thumbnail'))) ?>
					<h5><?= $season->index_title() ?></h5>
					<p><?= Html::badge(count($season->episodes), 'info').' '.__('app.episodes') ?></p>
				</div>
			</li>
		<?php endforeach ?>
		</ul>
	</div>
		
	<div class="summary">
		<?php if($show->summary): ?>
			<div><?= $show->summary() ?></div>
		<?php endif ?>
	</div>
	
	<div class="row clearfix">
		<div class="span6">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td><strong><?= __('app.year') ?></strong></td>
						<td><?= $show->year ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.studio') ?></strong></td>
						<td><?= $show->studio ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.genre') ?></strong></td>
						<td><?= $show->tags('genre') ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.content_rating') ?></strong></td>
						<td><?= $show->content_rating ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.rating') ?></strong></td>
						<td><?= Html::rating($show->rating) ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>