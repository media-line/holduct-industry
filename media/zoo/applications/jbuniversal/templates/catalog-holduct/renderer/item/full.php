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

<div class="uk-grid">
    <div class="uk-width-1-2">
        <?php if ($this->checkPosition('title')) : ?>
            <div class="uk-product-full-title uk-article-title uk-margin-large-bottom uk-text-bold">
                <?php echo $this->renderPosition('title'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->checkPosition('main')) : ?>
            <div class="uk-product-full-main">
                <?php echo $this->renderPosition('main'); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="uk-width-1-2">
        <?php if ($this->checkPosition('media')) : ?>
            <a class="uk-product-full-image uk-align-left" href="<?php echo $href; ?>" data-uk-lightbox>
                <?php echo $this->renderPosition('media'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($this->checkPosition('highlight')) : ?>
    <div class="uk-pruduct-full-highlight uk-text-contrast">
        <?php echo $this->renderPosition('highlight'); ?>
    </div>
<?php endif; ?>

<?php if ($this->checkPosition('additional')) : ?>
    <div class="uk-pruduct-full-additional">
        <?php echo $this->renderPosition('additional'); ?>
    </div>
<?php endif; ?>
<?php
    $document = JFactory::getDocument();
    $renderer = $document->loadRenderer('modules');
    $options = array('style' => 'xhtml');
    $position = 'in-template-product';
    echo $renderer->render($position, $options, null);
?>

