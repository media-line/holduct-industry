<?php
/**
 * @package   Warp Theme Framework
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;


?>

<div class="uk-latest_news">

	<div class="uk-grid">
	<?php if($params->get('block_title')){ ?>
		<div class="uk-width-2-3">
			<div class="uk-latest_news-block_title uk-h1 uk-gotham-light">
				<?php echo $params->get('block_title'); ?>
			</div>
		</div>
	<?php } ?>
	<?php if($menuItem != '' && ($params->get('all_news_text'))){ ?>
		<div class="uk-width-1-3 uk-text-right">
			<a class="uk-latest_news-all_news_link uk-text-contrast uk-text-small uk-text-uppercase uk-display-inline-block uk-position-relative" href="<?php echo $menuItem->route; ?>">
				<?php echo $params->get('all_news_text'); ?>
				<span class="uk-latest_news-all_news_link-icon uk-position-absolute"></span>
			</a>
		</div>
	<?php } ?>
	</div>
	<div class="uk-grid ">
	<?php foreach ($list as $item) : ?>
		<div class="uk-width-1-3">
			<?php
				$images = json_decode($item->images);
				$publishDate = new JDate($item->publish_up);
				$publishDate = $publishDate->format('d F y');
			?>

			<a class="uk-latest_news-block uk-cover uk-inline-block uk-position-relative uk-text-contrast uk-width-1-1" href="<?php echo $item->link; ?>">
				<span class="uk-latest_news-block-image uk-display-inline-block uk-width-1-1 uk-height-1-1 uk-position-relative" style="background-image: url('<?php echo $images->image_intro; ?>');"></span>

				<span class="uk-latest_news-publish_up uk-position-absolute uk-text-small"><?php echo $publishDate; ?></span>

				<span class="uk-latest_news-hits uk-position-absolute">
					<i class="icon-eye uk-text-top"></i>
					<span class="uk-text-top uk-text-small uk-text-middle uk-margin-small-left"><?php echo $item->hits; ?></span>
				</span>

				<span class="uk-latest_news-title uk-position-absolute"><?php echo $item->title; ?></span>

			</a>
		</div>
	<?php endforeach; ?>
	</div>
</div>