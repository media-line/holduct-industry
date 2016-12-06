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

$this->app->jbdebug->mark('layout::frontpage::start');


// set vars
$category   = $vars['object'];
$title      = $this->app->string->trim($vars['params']->get('content.category_title',    ''));
$subTitle   = $this->app->string->trim($vars['params']->get('content.category_subtitle', ''));
$image      = $this->app->jbimage->get('category_image', $vars['params']);

if (!$title) {
    $title = $category->name;
}
/*
function categoriesRender($category, $level = 0, $flat = false, $attribs = null, $expanded = false, $max_number, $more_text, $hide_text) {
    // init vars
    $menu_item = 0;
    $max_depth = 10;


    $result = array("<ul $attribs>");
    $count = 0;
    foreach ($category->getChildren($flat ? true : false) as $category) {

        $path = array_reverse($category->getPath());
        $depth = count(array_slice($path, array_search($params->get('category', 0), $path))) - 1;
        if ($max_depth && $max_depth < $depth) {
            continue;
        }

        $current = $current_id == $category->id;
        $active = $current || in_array($current_id, array_keys($category->getChildren(true)));
        $parent = $category->hasChildren() && !($max_depth && $max_depth < $depth + 1);
        $url = $this->app->route->category($category, true, $menu_item);
        $class = ' class="'.($flat ? '' : 'level'.$level).($parent ? ' parent' : '').($current ? ' current' : '').($active ? ' active' : '');
        if($count >= $max_number){
            $class .= ' uk-hidden-category';
        }
        $class .= '"';
        $result[] = "<li$class>";
        if ($params->get('add_count', 0)) {
            $result[] = "<a href=\"$url\"$class><span>{$category->name} ({$category->itemCount()})</span></a>";
        } else {
            $result[] = "<a href=\"$url\"$class><span>{$category->name}</span></a>";
        }
        if (!$flat && ($active || $expanded) && $parent) {
            $result[] = $this->render($category, $params, $level+1, $flat, 'class="level'.($level+1).'"', $expanded);
        }
        $result[] = '</li>';
        $count++;
    }
    $result[] = '</ul>';
    $more_text_arr = explode('#N', $more_text);
    if (($count - $max_number) > 0){
        $result[] = '<div class="uk-more-categories uk-text-small"><span class="uk-more-text">'.$more_text_arr[0].''. ($count - $max_number) .''.$more_text_arr[1].'</span><span class="uk-hide-text">'.$hide_text.'</span></div>';
    }

    return implode("\n", $result);
}

    $application = $zoo->table->application->get($params->get('application', 0));

    $categories = $application->getCategoryTree(true, null, false, false);

    echo '<pre>';
        print_r(categoriesRender());
    echo '</pre>';

*/

?>
<?php if ((int)$vars['params']->get('template.category_show', 1)) : ?>
    <div class="frontpage rborder alias-<?php echo $category->alias;?>">
    
        <?php if ((int)$vars['params']->get('template.category_show', 1)) : ?>
            
            <?php if ((int)$vars['params']->get('template.category_subtitle', 1) && !empty($subTitle)) : ?>
                <h2 class="subtitle"><?php echo $subTitle; ?></h2>
            <?php endif; ?>
        
            
            <?php if ((int)$vars['params']->get('template.category_image', 1) && $image['src']) : ?>
                <div class="image-full align-<?php echo $vars['params']->get('template.category_image_align', 'left');?>">
                    <img src="<?php echo $image['src']; ?>" <?php echo $image['width_height']; ?>
                         title="<?php echo $category->name; ?>" alt="<?php echo $category->name; ?>" />
                </div>
            <?php endif; ?>
        
            
            <?php if ((int)$vars['params']->get('template.category_teaser_text', 1)
                    && $vars['params']->get('content.category_teaser_text', '')) : 
                ?>
                <div class="description-teaser">
                    <?php echo $vars['params']->get('content.category_teaser_text', ''); ?>
                </div>
            <?php endif; ?>
        
            
            <?php if ((int)$vars['params']->get('template.category_text', 1) && $category->description) : ?>
                <div class="description-full"><?php echo $category->getText($category->description); ?></div>
            <?php endif; ?>
        
            
            <div class="clr clear"></div>
        <?php endif; ?>
    </div>
    
<?php else: ?>

<?php endif; ?>

<?php
$this->app->jbdebug->mark('layout::frontpage::finish');