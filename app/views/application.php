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
		</header> <!-- Header -->
		
		<nav id="sidebar">
			<?= $sidebar ?>
		</nav> <!-- sidebar principale -->
		
		<section id="content-wrapper"> 
			<header id="navigation" class="ellipsis">
				<?= $navigation ?>
			</header> <!-- navigation principale -->
			
			<div id="content">
				<?= $content ?>
			
				<div id="content-footer">
					<?= $pagination ?>
				</div> <!-- Footer pour les contenus -->
			</div> <!-- contenu des pages -->
			
			<footer id="footer">
				
			</footer>

		</section>
		<?= Text::get_modal() ?>
	</body>
</html>
