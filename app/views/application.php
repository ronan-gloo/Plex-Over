<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?= $title ?></title>
		<?= $assets->render('main') ?>
		<?= $assets->render('local') ?>
	</head>
	<body id="page-<?= $style->page_id ?>" class="page-<?= $style->page_class ?>">
			
		<header id="header" class="navbar navbar-fixed-top">
			<?= $header ?>
		</header>
		
		<nav id="sidebar">
			<?= $sidebar ?>
		</nav>
		
		<section id="content-wrapper"> 
			<header id="navigation" class="ellipsis">
				<?= $breadcrumb ?>
			</header>
			
			<div id="content">
				<div id="content-inner">
					<?= $content ?>
				</div>
			
				<div id="content-footer" class="breadcrumb">
					<?= $pagination ?>
				</div> 
			</div>
			
		</section>
		<?= Text::get_modal() ?>
	</body>
</html>
