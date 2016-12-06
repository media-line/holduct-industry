<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_custom
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>


<div class="uk-pagetitle uk-pagetitle<?php echo $moduleclass_sfx ?> uk-margin-large-bottom">
	<div class="uk-pagetitle-container uk-position-relative" <?php if ($params->get('image')){ echo 'style="background-image: url(\''.$params->get('image').'\');"'; }?>>
		<div class="uk-pagetitle-title uk-position-absolute uk-position-top-left uk-position-bottom-right">
			<div class="uk-flex uk-flex-middle uk-flex-center uk-height-1-1">
				<div class="uk-text-center">
					<h1 class="uk-h1 uk-text-contrast uk-margin-top uk-margin-bottom-remove"><?php echo $title;?></h1>

					<?php
						$document = JFactory::getDocument();
						$renderer = $document->loadRenderer('modules');
						$options = array('style' => 'xhtml');
						$position = 'in-page-title-template';
						echo $renderer->render($position, $options, null);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
