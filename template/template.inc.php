<?php
/*
    Copyright 2014 Edward L. Platt <ed@elplatt.com>
    
    This file is part of the Seltzer CRM Project
    template.inc.php - Template for contributed modules

    Seltzer is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    Seltzer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Seltzer.  If not, see <http://www.gnu.org/licenses/>.
*/

// Installation functions //////////////////////////////////////////////////////

/**
 * @return This module's revision number.  Each new release should increment
 * this number.
 */
function template_revision () {
    return 1;
}

/**
 * @return An array of the permissions provided by this module.
 */
function template_permissions () {
    return array(
    );
}

/**
 * Install or upgrade this module.
 * @param $old_revision The last installed revision of this module, or 0 if the
 *   module has never been installed.
 */
function template_install($old_revision = 0) {
    if ($old_revision < 1) {
        // Create databases here
    }
}

// Utility functions ///////////////////////////////////////////////////////////

$template_first = array(
    'Smarmy', 'Blinky', 'Waffle', 'WiFi', 'Giga', 'Major'
);
$template_middle = array(
    'Chops', 'Punk', 'Face', 'Major', 'Newt', 'Style'
);
$template_last = array(
    'III', 'Major', 'The Meow', 'Scoundrel', 'Unit', 'Willie'
);

function template_nickname ($contact) {
    global $template_first;
    global $template_last;
    global $template_middle;
    $a = sha1($contact['firstName'] . $contact['middleName'] . $contact['lastName']);
    $first = $template_first[intval($a{0}, 16) % 6];
    $middle = $template_middle[intval($a{1}, 16) % 6];
    $last = $template_last[intval($a{2}, 16) % 6];
    return "$first $middle $last";
}

// DB to Object mapping ////////////////////////////////////////////////////////

/**
 * Implementation of hook_data_alter().
 * @param $type The type of the data being altered.
 * @param $data An array of structures of the given $type.
 * @param $opts An associative array of options.
 * @return An array of modified structures.
 */
function template_data_alter ($type, $data = array(), $opts = array()) {
    switch ($type) {
        case 'contact':
            foreach ($data as $i => $contact) {
                $data[$i]['nickname'] = template_nickname($data[$i]);
            }
            break;
    }
    return $data;
}

// Tables //////////////////////////////////////////////////////////////////////
// Put table generators here

// Forms ///////////////////////////////////////////////////////////////////////
// Put form generators here

// Themeing ////////////////////////////////////////////////////////////////////

/**
 * Return themed html for a nickname.
 */
function theme_template_nickname ($cid) {
    $contact = crm_get_one('contact', array('cid'=>$cid));
    return '<h3>Nickname</h3><p>' . template_nickname($contact) . '</p>';
}

// Pages ///////////////////////////////////////////////////////////////////////

/**
 * @return An array of pages provided by this module.
 */
function template_page_list () {
    $pages = array();
    // Add page names here
    return $pages;
}

/**
 * Page hook.  Adds module content to a page before it is rendered.
 *
 * @param &$page_data Reference to data about the page being rendered.
 * @param $page_name The name of the page being rendered.
 * @param $options The array of options passed to theme('page').
*/
function template_page (&$page_data, $page_name, $options) {
    switch ($page_name) {
        case 'contact':
            // Capture contact cid
            $cid = $options['cid'];
            if (empty($cid)) {
                return;
            }
            // Add nickname tab
            $nickname = theme('template_nickname', $cid);
            page_add_content_bottom($page_data, $nickname, 'View');
            break;
    }
}

// Request Handlers ////////////////////////////////////////////////////////////
// Put request handlers here

