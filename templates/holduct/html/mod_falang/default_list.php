<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_falang
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>



<div class="uk-language-select uk-text-uppercase uk-grid uk-grid-collapse uk-margin-top uk-position-relative">
    <?php foreach($list as $language):?>

        
        <!-- >>> [FREE] >>> -->
        <?php if ($params->get('show_active', 0) || !$language->active):?>
            <div class="<?php echo $language->active ? 'lang-active' : '';?> uk-language-text uk-width-1-3" dir="<?php echo JLanguage::getInstance($language->lang_code)->isRTL() ? 'rtl' : 'ltr' ?>">
                <?php if ($language->display) { ?>
                    <a href="<?php echo $language->link;?>">
                        <?php if ($params->get('image', 1)):?>
                            <?php echo JHtml::_('image', 'mod_falang/'.$language->image.'.gif', $language->title_native, array('title'=>$language->title_native), true);?>
                        <?php endif; ?>
                        <?php if ($params->get('show_name', 1)):?>
                            <?php echo $params->get('full_name', 1) ? $language->title_native : strtoupper($language->sef);?>
                        <?php endif; ?>
                    </a>
                <?php } else { ?>
                    <?php if ($params->get('image', 1)):?>
                        <?php echo JHtml::_('image', 'mod_falang/'.$language->image.'.gif', $language->title_native, array('title'=>$language->title_native,'style'=>'opacity:0.5'), true);?>
                    <?php endif; ?>
                    <?php if ($params->get('show_name', 1)):?>
                        <?php echo $params->get('full_name', 1) ? $language->title_native : strtoupper($language->sef);?>
                    <?php endif; ?>
                <?php } ?>
            </div>
        <?php endif;?>
        <!-- <<< [FREE] <<< -->
    <?php endforeach;?>
</div>
