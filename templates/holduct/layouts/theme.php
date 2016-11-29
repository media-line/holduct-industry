<?php
// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>"  data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>

<head>
<?php echo $this['template']->render('head'); ?>
</head>

<body class="<?php echo $this['config']->get('body_classes'); ?>">

		<?php if ($this['widgets']->count('toolbar-l + toolbar-r')) : ?>
			<div class="uk-container uk-container-center">
				<div class="tm-toolbar uk-clearfix uk-hidden-small">

				<?php if ($this['widgets']->count('toolbar-l')) : ?>
				<div class="uk-float-left"><?php echo $this['widgets']->render('toolbar-l'); ?></div>
				<?php endif; ?>

				<?php if ($this['widgets']->count('toolbar-r')) : ?>
				<div class="uk-float-right"><?php echo $this['widgets']->render('toolbar-r'); ?></div>
				<?php endif; ?>

				</div>
			</div>
		<?php endif; ?>

		<?php if ($this['widgets']->count('logo + headerbar + menu + search')) : ?>
			<div class="uk-container uk-container-center">
				<div class="tm-headerbar uk-clearfix uk-hidden-small uk-grid">

					<?php if ($this['widgets']->count('logo')) : ?>
						<div class="uk-width-1-6">
							<a class="tm-logo" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo'); ?></a>
						</div>
					<?php endif; ?>

					<?php if ($this['widgets']->count('menu + search')) : ?>
						<nav class="tm-navbar uk-navbar uk-width-4-6 uk-margin-top">

							<?php if ($this['widgets']->count('menu')) : ?>
								<?php echo $this['widgets']->render('menu'); ?>
							<?php endif; ?>

							<?php if ($this['widgets']->count('offcanvas')) : ?>
								<a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
							<?php endif; ?>

							<?php if ($this['widgets']->count('search')) : ?>
								<div class="uk-navbar-flip">
									<div class="uk-navbar-content uk-hidden-small"><?php echo $this['widgets']->render('search'); ?></div>
								</div>
							<?php endif; ?>

							<?php if ($this['widgets']->count('logo-small')) : ?>
								<div class="uk-navbar-content uk-navbar-center uk-visible-small"><a class="tm-logo-small" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo-small'); ?></a></div>
							<?php endif; ?>

						</nav>
					<?php endif; ?>

					<div class="uk-width-1-6">
						<?php echo $this['widgets']->render('headerbar'); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($this['widgets']->count('slider')) : ?>
			<div class="uk-pos-slider">
				<?php echo $this['widgets']->render('slider'); ?>
			</div>
		<?php endif; ?>


		<div class="uk-clients uk-container uk-container-center uk-grid">

			<div class="uk-clients-main_text uk-flex uk-flex-middle uk-width-1-4">
				<div class="uk-h1 uk-gotham-light">Наши клиенты:</div>
			</div>
			<div class="uk-clients-slider uk-position-relative uk-width-3-4">
				<div class="" data-uk-slider="{center:true, autoplay: true, autoplayInterval: 4000}">
					<div class="uk-slider-container">
						<ul class="uk-slider uk-grid-width-medium-1-5 uk-text-center">
							<li><img src="/images/clients/clients-logo-1.jpg" alt=""/></li>
							<li><img src="/images/clients/clients-logo-2.jpg" alt=""/></li>
							<li><img src="/images/clients/clients-logo-3.jpg" alt=""/></li>
							<li><img src="/images/clients/clients-logo-4.jpg" alt=""/></li>
							<li>
								<img src="/images/clients/clients-logo-1.jpg" alt=""/>
							</li>
							<li><img src="/images/clients/clients-logo-2.jpg" alt=""/></li>
							<li><img src="/images/clients/clients-logo-3.jpg" alt=""/></li>
							<li><img src="/images/clients/clients-logo-4.jpg" alt=""/></li>
						</ul>
					</div>
				</div>
			</div>

		</div>


		<?php if ($this['widgets']->count('top-a')) : ?>
		<div class="uk-container uk-container-center">
			<section id="tm-top-a" class="<?php echo $grid_classes['top-a']; echo $display_classes['top-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?></section>
		</div>
		<?php endif; ?>

		<?php if ($this['widgets']->count('top-b')) : ?>
		<section id="tm-top-b" class="<?php echo $grid_classes['top-b']; echo $display_classes['top-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?></section>
		<?php endif; ?>

		<?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
		<div class="uk-container uk-container-center">
			<div id="tm-middle" class="tm-middle uk-grid" data-uk-grid-match data-uk-grid-margin>

				<?php if ($this['widgets']->count('main-top + main-bottom') || $this['config']->get('system_output', true)) : ?>
				<div class="<?php echo $columns['main']['class'] ?>">

					<?php if ($this['widgets']->count('main-top')) : ?>
					<section id="tm-main-top" class="<?php echo $grid_classes['main-top']; echo $display_classes['main-top']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?></section>
					<?php endif; ?>

					<?php if ($this['config']->get('system_output', true)) : ?>
					<main id="tm-content" class="tm-content">

						<?php if ($this['widgets']->count('breadcrumbs')) : ?>
						<?php echo $this['widgets']->render('breadcrumbs'); ?>
						<?php endif; ?>

						<?php echo $this['template']->render('content'); ?>

					</main>
					<?php endif; ?>

					<?php if ($this['widgets']->count('main-bottom')) : ?>
					<section id="tm-main-bottom" class="<?php echo $grid_classes['main-bottom']; echo $display_classes['main-bottom']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?></section>
					<?php endif; ?>

				</div>
				<?php endif; ?>

				<?php foreach($columns as $name => &$column) : ?>
				<?php if ($name != 'main' && $this['widgets']->count($name)) : ?>
				<aside class="<?php echo $column['class'] ?>"><?php echo $this['widgets']->render($name) ?></aside>
				<?php endif ?>
				<?php endforeach ?>

			</div>
		</div>
		<?php endif; ?>

		<?php if ($this['widgets']->count('bottom-a')) : ?>
			<div class="uk-container uk-container-center">
				<section id="tm-bottom-a" class="<?php echo $grid_classes['bottom-a']; echo $display_classes['bottom-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?></section>
			</div>
		<?php endif; ?>

		<?php if ($this['widgets']->count('bottom-b')) : ?>
			<section id="tm-bottom-b" class="<?php echo $grid_classes['bottom-b']; echo $display_classes['bottom-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
		<?php endif; ?>

		<?php if ($this['widgets']->count('footer + footer-left + footer-left + debug') || $this['config']->get('warp_branding', true) || $this['config']->get('totop_scroller', true)) : ?>
		<footer id="tm-footer" class="tm-footer uk-text-left">
			<div class="uk-container uk-container-center">
			<?php if ($this['config']->get('totop_scroller', true)) : ?>
			<a class="tm-totop-scroller" data-uk-smooth-scroll href="#"></a>
			<?php endif; ?>

			<div class="uk-grid">
				<div class="uk-width-1-4">
					<?php
						echo $this['widgets']->render('footer-left');
					?>

					<?php if($this['widgets']->render('site-copyrate')); ?>
						<div class="uk-site-copyrate">
							&copy;2009-<?php echo $this['widgets']->render('site-copyrate'); ?>
							<?php echo $this['widgets']->render('site-copyrate'); ?>
						</div>
					<?php } ?>
				</div>

				<div class="uk-width-1-2">
					<?php
						echo $this['widgets']->render('footer');
					?>
				</div>

				<div class="uk-width-1-4">
					<?php
						echo $this['widgets']->render('footer-right');
					?>
				</div>
			</div>

			<?php
				$this->output('warp_branding');
				echo $this['widgets']->render('debug');
			?>
			</div>
		</footer>
		<?php endif; ?>



	<?php echo $this->render('footer'); ?>

	<?php if ($this['widgets']->count('offcanvas')) : ?>
	<div id="offcanvas" class="uk-offcanvas">
		<div class="uk-offcanvas-bar"><?php echo $this['widgets']->render('offcanvas'); ?></div>
	</div>
	<?php endif; ?>

</body>
</html>