<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

//Проверка изображения
if ($this->checkPosition('media')){

    preg_match('/<img[^>]+>/i', $this->renderPosition('media'), $img);
    preg_match("/\<img.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/", $img[0], $matches);

    $background = 'style="background-image: url(\'' . $matches[1] . '\');"';

} else {
    $background = '';
}

//Проверка ссылки на материал
if ($this->checkPosition('link')){
    $url = preg_match('/<a href="(.+)">/', $this->renderPosition('link'), $match);
    $link = $match[1];
} else {
    $link = '#';
}
?>

<a class="uk-latest_news-block uk-cover uk-inline-block uk-position-relative uk-text-contrast uk-width-1-1" href="<?php echo $link; ?>">
    <span class="uk-latest_news-block-image uk-display-inline-block uk-width-1-1 uk-height-1-1 uk-position-relative" <?php echo $background; ?>></span>

    <?php if ($this->checkPosition('date')){ ?>
        <span class="uk-latest_news-publish_up uk-position-absolute uk-text-small"><?php echo $this->renderPosition('date'); ?></span>
    <?php } ?>

    <?php if ($this->checkPosition('hits')){ ?>
        <span class="uk-latest_news-hits uk-position-absolute">
            <i class="icon-eye uk-text-top"></i>
            <span class="uk-text-top uk-text-small uk-text-middle uk-margin-small-left"><?php echo $this->renderPosition('hits'); ?></span>
		</span>
    <?php } ?>

    <?php if ($this->checkPosition('title')){ ?>
        <span class="uk-latest_news-title uk-position-absolute"><?php echo $this->renderPosition('title'); ?></span>
    <?php } ?>
</a>
