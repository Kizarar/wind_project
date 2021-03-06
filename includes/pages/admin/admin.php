<?php
/*
 * WiND - Wireless Nodes Database
 *
 * Copyright (C) 2005-2014 	by WiND Contributors (see AUTHORS.txt)
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (get('subpage') != '')
	include_once(ROOT_PATH."includes/pages/admin/admin_".get('subpage').".php");

class admin {

	var $tpl;
	var $page;
	
	function __construct() {
		if (get('subpage') != '') {
			$p = "admin_".get('subpage');
			$this->page = new $p;
		} else {
			redirect(make_ref('/admin/nodes'));
		}
	}
	
	function output() {
		global $main, $lang;
		$admin_entry = $main->menu->main_menu->getRootEntry()->getChild('admin');
		$admin_entry->createLink($lang['nodes'], make_ref('/admin/nodes'));
		$admin_entry->createLink($lang['users'], make_ref('/admin/users'));
		$admin_entry->createLink($lang['services'], make_ref('/admin/nodes_services'));
		$admin_entry->createLink($lang['services_categories'], make_ref('/admin/services'));
		$admin_entry->createLink($lang['regions'], make_ref('/admin/regions'));
		$admin_entry->createLink($lang['areas'], make_ref('/admin/areas'));
		
		return $this->page->output();
		
	}

}

?>
