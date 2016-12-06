<?php
/**
 * @package   com_zoo
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//Проверка изображения
if ($this->checkPosition('media')){

    preg_match('/<img[^>]+>/i', $this->renderPosition('media'), $img);
    preg_match("/\<img.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/", $img[0], $matches);

    $href = $matches[1];

} else {
    $href = '#';
}
?>

<?php if ($this->checkPosition('related')) { ?>

    <div class="uk-grid">
        <div class="uk-width-2-3">
            <?php if ($this->checkPosition('media')) : ?>
                <a class="uk-article-full-image uk-align-left" href="<?php echo $href; ?>" data-uk-lightbox>
                    <?php echo $this->renderPosition('media'); ?>
                </a>
            <?php endif; ?>

            <?php if ($this->checkPosition('title')) : ?>
                <div class="uk-article-title">
                    <?php echo $this->renderPosition('title'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->checkPosition('content')) : ?>
                <?php echo $this->renderPosition('content'); ?>
            <?php endif; ?>
            <div class="uk-margin-large-top">
                <a class="uk-latest_news-all_news_link js-back-button uk-text-contrast uk-text-small uk-text-uppercase uk-display-inline-block uk-position-relative" href="#">
                    <?php echo JText::_('COM_ZOO_FULL_BACK_BUTTON'); ?>		
                    <span class="uk-latest_news-all_news_link-icon uk-position-absolute"></span>
                </a>
            </div>
        </div>
        <div class="uk-width-1-3">
            <div class="uk-related-articles">
                <div class="uk-related-articles-title uk-article-title"><?php echo JText::_('COM_ZOO_RELATED_ARTICLES_TITLE'); ?></div>
                <?php echo $this->renderPosition('related'); ?>
            </div>
        </div>
    </div>
<?php } else {?>
    <?php if ($this->checkPosition('media')) : ?>
        <a class="uk-article-full-image uk-align-left" href="<?php echo $href; ?>" data-uk-lightbox>
            <?php echo $this->renderPosition('media'); ?>
        </a>
    <?php endif; ?>

    <?php if ($this->checkPosition('title')) : ?>
        <div class="uk-article-title">
            <?php echo $this->renderPosition('title'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->checkPosition('content')) : ?>
        <?php echo $this->renderPosition('content'); ?>
    <?php endif; ?>

    <div class="uk-margin-large-top">
        <a class="uk-latest_news-all_news_link js-back-button uk-text-contrast uk-text-small uk-text-uppercase uk-display-inline-block uk-position-relative" href="#">
            <?php echo JText::_('COM_ZOO_FULL_BACK_BUTTON'); ?>
            <span class="uk-latest_news-all_news_link-icon uk-position-absolute"></span>
        </a>
    </div>
    
<?php } ?>
