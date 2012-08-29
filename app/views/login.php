<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?= __('title') ?></title>
		<?= Asset::render('login') ?>
	</head>
	<body>
		<div id="login-form" class="span8">
			
			<?= ($error) ?: null ?>
			
			<?= Form::open(array('class' => 'form-horizontal well', 'action' => 'login/check')) ?>
				<h1>Plex Over <small> <?= __('connexion') ?></small></h1>
				
				<fieldset style="margin-top: 30px">
					<div class="control-group">
						<label class="control-label"><?= __('username') ?></label>
						<div class="controls">
							<?= Form::input('username', $username, array(
								'required'	=> 'required',
								'prepend'		=> 'icon-user'
							)) ?>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label"><?= __('password') ?></label>
						<div class="controls">
							<?= Form::input('password', '', array(
								'type'			=> 'password',
								'required'	=> 'required',
								'prepend'		=> 'icon-lock'
							)) ?>
						</div>
					</div>
					
					<div class="form-actions">
						<?= Form::button('', __('login'), array(
							'type'	=> 'submit',
							'status'=> 'primary',
							'size'	=> 'large',
							'icon'	=> 'chevron-right'
						)) ?>
					</div>
				
				</fieldset>
			
			<?= Form::close() ?>
		</div>
	</body>
</html>
