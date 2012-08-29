<div class="navbar-inner">
  <div class="container-fluid">
    
    <?= Html::anchor(Uri::base(), $title, array('class' => 'brand')) ?>
        
    <?= $right_items ?>
    
    <?php if(isset($search_action)): ?>
	    <?= Form::open(array('method' => 'get', 'action' => $search_action, 'class' => 'navbar-search pull-right')) ?>
		   	<?= Form::input('q', Input::get('q'), array(
		   		'type'				=> 'search',
		   		'placeholder' => __('app.search'),
		   		'class'				=> 'search-query span2'
		   	)) ?>
		  <?= Form::close() ?>
		<?php endif ?>

  </div>
</div>
