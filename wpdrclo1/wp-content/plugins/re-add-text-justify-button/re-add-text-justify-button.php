<?php
/*
 * Plugin Name: Re-Add Text Justify Button
 * Plugin URI: https://github.com/salvatorecordiano/re-add-text-justify-button/
 * Description: The most simple plugin to re-add text justify button in the WYSIWYG on WordPress 4.7+
 * Author: Salvatore Cordiano
 * Version: 0.1.4
 * Author URI: https://www.salvatorecordiano.it/
 */

function parallel_admin_head()
{
    add_filter('mce_buttons', 'parallel_mce_buttons', 5);
}

function parallel_mce_buttons($buttons)
{
    $position = array_search('alignright', $buttons);

    if (! is_int($position)) {

        return array_merge($buttons, ['alignjustify']);
    }

    return array_merge(
        array_slice($buttons, 0, $position + 1),
        ['alignjustify'],
        array_slice($buttons, $position + 1)
    );
}

add_action('admin_head', 'parallel_admin_head', 5);
