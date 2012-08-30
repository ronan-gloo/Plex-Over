<script type="text/javascript">
	$(function(){
		$('#watch').click(function(){
			if ($(this).hasClass('btn-primary')) {
				$('article').hide();
				$('.video-player').show();
				$(this).removeClass('btn-primary').text('<?= __('app.go_back') ?>');
			}
			else {
				$('article').show();
				$('.video-player').hide();
				$(this).addClass('btn-primary').text('<?= __('app.watch_now') ?>');
			}
			return false;
		});
	});
</script>

<div class="movie row-fluid">
	
	<div class="well">
		<h2 class="clearfix">
			<?= Html::button('#', __('app.watch_now'), array(
				'class'	=> 'pull-right',
				'id'		=> 'watch',
				'status'=> 'primary',
				'size' 	=> 'large'
			)) ?>
			<?= $movie->title ?>
		</h2>
	</div>
	
	<article class="clearix">
		<div class="cover span3">
			<?= Html::thumb($movie, array('class' => 'thumbnail')) ?>
			<?= Widget_Download::forge($movie) ?>
		</div>
		
		<div class="span9">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td><strong><?= __('app.releasedate') ?></strong></td>
						<td>
							<?= Date::forge(strtotime($movie->originally_available_at))->format('eu_named') ?>
						</td>
					</tr>
					<tr>
						<td><strong><?= __('app.duration') ?></strong></td>
						<td><?= Time::duration($movie->duration) ?></td>
					</tr>
					<?php if ($movie->tags_star ): ?>
						<tr>
							<td><strong><?= __('app.with') ?></strong></td>
							<td><?= $movie->tags('star') ?></td>
						</tr>
					<?php endif ?>
					<tr>
						<td><strong><?= __('app.director') ?></strong></td>
						<td><?= $movie->tags('director') ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.writer') ?></strong></td>
						<td><?= $movie->tags('writer') ?></td>
					</tr>
					<tr>
						<td><strong><?= __('app.rating') ?></strong></td>
						<td><?= Html::rating($movie->rating) ?></td>
					</tr>
				</tbody>
			</table>
			<div><?= Text::summarize($movie->summary) ?></div>
		</div>
		
	</article>
	
		<div class="video-player hide">
			<?= Widget_Video::forge($movie)->set('poster', To::art($movie, 500)) ?>
		</div>
				
</div>
