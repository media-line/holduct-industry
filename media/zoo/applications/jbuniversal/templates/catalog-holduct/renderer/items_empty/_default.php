<?php
/**
 * JBZoo is universal CCK, application for YooTheme Zoo component
 * @package     JBZoo
 * @author      JBZoo App http://jbzoo.com
 * @copyright   Copyright (C) JBZoo.com
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<div class="uk-grid">
    <div class="uk-width-1-4">
        <div class="uk-catalog-sidebar">
            <?php
            $document = JFactory::getDocument();
            $renderer = $document->loadRenderer('modules');
            $options = array('style' => 'xhtml');
            $position = 'in-template-sidebar';
            echo $renderer->render($position, $options, null);
            ?>
        </div>
    </div>

    <div class="uk-width-3-4">
        <!-- empty -->
    </div>
</div>

