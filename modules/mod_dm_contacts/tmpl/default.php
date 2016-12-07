<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_custom
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$contactList = json_decode($params->get('contact'));
$contactsCount = count($contactList->contact_title);
?>

<h1 class="tm-title uk-h1 uk-text-center"><?php	if ($params->get('page_title')) echo $params->get('page_title');?></h1>
<div class="uk-mod-contacts uk-mod-contacts<?php echo $moduleclass_sfx ?>">
	<div class="uk-grid">
	<?php for ($i = 0; $i < $contactsCount; $i++){?>
		<div class="uk-width-1-<?php echo $params->get('fields_number',4); ?> uk-contact <?php if ($i < $params->get('fields_number',4)) echo 'uk-first-row'; ?>">
			<div class="uk-text-bold"><?php if ($contactList->contact_title[$i] != '') echo $contactList->contact_title[$i]; ?></div>
			<div><?php if ($contactList->position[$i] != '') echo $contactList->position[$i]; ?></div>
			<div>
				<?php if ($contactList->phone1[$i] != '') echo 'Тел.'; ?>
				<?php if ($contactList->phone1[$i] != '') echo ' '.$contactList->phone1[$i]; ?>
			</div>
			<div><?php if ($contactList->phone2[$i] != '') echo $contactList->phone2[$i]; ?></div>
			<div>
				<?php if ($contactList->fax[$i] != '') echo 'Факс:'; ?>
				<?php if ($contactList->fax[$i] != '') echo ' '.$contactList->fax[$i]; ?>
			</div>
			<div>
				<?php if ($contactList->site[$i] != '') echo 'Сайт:' ?>
				<?php if ($contactList->site[$i] != '') echo ' '.$contactList->site[$i]; ?>
			</div>
			<div>
				<?php if ($contactList->email1[$i] != '') {
						echo 'e-mail:'; 
						echo ' '.$contactList->email1[$i]; 
					}
				?>
			</div>
			<div><?php if ($contactList->email2[$i] != '') echo $contactList->email2[$i]; ?></div>
			<div><?php if ($contactList->email3[$i] != '') echo $contactList->email3[$i]; ?></div>
		</div>
	<?php } ?>
	

</div>
