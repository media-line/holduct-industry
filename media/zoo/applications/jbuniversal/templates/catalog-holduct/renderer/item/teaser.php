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
<div class="uk-product-teaser">
    <div class="uk-grid uk-grid-collapse">
        <?php if ($this->checkPosition('media')){ ?>

            <div class="uk-width-1-3">
                <a href="<?php echo $link; ?>" class="uk-product-teaser-image uk-display-inline-block" <?php echo $background; ?>></a>
            </div>

            <div class="uk-width-2-3 uk-flex uk-flex-middle">
                <div>
                    <?php if ($this->checkPosition('title')){ ?>
                        <div class="uk-margin-bottom">
                            <a href="<?php echo $link; ?>" class="uk-product-teaser-title uk-h5 uk-text-uppercase">
                                <?php echo $this->renderPosition('title'); ?>
                            </a>
                        </div>
                    <?php } ?>

                    <?php if ($this->checkPosition('content')){ ?>
                        <div class="uk-product-teaser-content">
                            <?php echo $this->renderPosition('content'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="uk-width-1-1">
                <?php if ($this->checkPosition('title')){ ?>
                    <div class="uk-margin-bottom">
                        <a href="<?php echo $link; ?>" class="uk-product-teaser-title uk-h5 uk-text-uppercase">
                            <?php echo $this->renderPosition('title'); ?>
                        </a>
                    </div>
                <?php } ?>

                <?php if ($this->checkPosition('content')){ ?>
                    <div class="uk-product-teaser-content">
                        <?php echo $this->renderPosition('content'); ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
