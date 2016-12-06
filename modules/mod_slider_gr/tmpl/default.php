<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_popular
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="mod-slider-gr uk-position-relative">
	<?php if($title != '') { ?>
	<div class="uk-slider-main_text uk-text-center uk-h1 uk-gotham-light uk-text-contrast uk-position-absolute uk-position-z-index">
		<div class="uk-container uk-container-center uk-text-center">
			<?php echo $title; ?>
		</div>
	</div>
	<?php } ?>
	<div class="slick-slider js-slick-slider">
		<?php foreach ($slides as $key => $slide) { ?>
			<div class="item">
				<img src="<?php echo $slide['img']; ?>" alt=""/>
				<div class="uk-container uk-container-center">
					<div class="text">
						<span><?php echo $slide['text']; ?></span>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php/*
	<div class="dots-container uk-position-absolute uk-position-bottom-left uk-position-bottom-right">
		<div class="uk-container uk-container-center">
			<ul class="dots uk-slider-dots uk-text-center">
				<?php for ($i=1; $i <= count($slides); $i++) { ?>
					<li class="dot js-dot <?php if ($i == 1) { echo 'active'; } ?>"></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	*/?>
</div>