<ul class="pager">
	<?php if($prev = $item->prev($params)): ?>
		<li class="previous"><?= Html::anchor(To::$to($prev).$get, '&larr; '.$prev->index_title(), array(
			//'title'			=> $prev->title,
			'data-tip'	=> 'right',
		)) ?></li>
	<?php endif ?>
	<?php if($next = $item->next($params)): ?>
		<li class="next"><?= Html::anchor(To::$to($next).$get, $next->index_title().' &rarr;', array(
			//'title'			=> $next->title,
			'data-tip'	=> 'left',
		)) ?></li>
	<?php endif ?>
</ul>
