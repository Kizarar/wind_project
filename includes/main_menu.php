<?php
/*
 * WiND - Wireless Nodes Database
 *
 * Copyright (C) 2005-2013 	by WiND Contributors (see AUTHORS.txt)
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

class menu {
	
	var $tpl;
	var $hide=FALSE;
	

	function calculate_menu_stats() {
		global $db, $config;
		$stats_tmp = "/tmp/wind-stats-".md5(__FILE__).".tmp";
		if (file_exists($stats_tmp)) {
			$stats = unserialize(file_get_contents($stats_tmp));
			if ($stats['last_calc'] + 3600 > time()) {
				unset($stats['last_calc']);
				$this->tpl = array_merge($this->tpl, $stats);
				return;
			}
		}
		$stats['stats_nodes_active'] =
				$db->cnt('',
						'nodes ' .
						'INNER JOIN links AS l1 ON l1.node_id = nodes.id ' .
						'LEFT JOIN links AS p2p ON (l1.type = "p2p" AND p2p.type = "p2p" AND l1.node_id = p2p.peer_node_id AND p2p.node_id = l1.peer_node_id) ' .
						'LEFT JOIN links AS clients ON (l1.type = "client" AND l1.peer_ap_id = clients.id) ' .
						'INNER JOIN users_nodes ON nodes.id = users_nodes.node_id ' .
						'LEFT JOIN users ON users.id = users_nodes.user_id',
						'users.status = "activated" AND l1.status = "active" AND (p2p.status = "active" OR clients.status = "active")',
						'nodes.id'
						);
		$stats['stats_nodes_total'] =
				$db->cnt('',
						'nodes ' .
						'INNER JOIN users_nodes ON nodes.id = users_nodes.node_id ' .
						'LEFT JOIN users ON users.id = users_nodes.user_id',
						'users.status = "activated"',
						'nodes.id'
						);
		$stats['stats_backbone'] =
				$db->cnt('',
						'nodes ' .
						'INNER JOIN links AS l1 ON l1.node_id = nodes.id ' .
						'INNER JOIN links AS l2 ON (l1.type = "p2p" AND l2.type = "p2p" AND l1.node_id = l2.peer_node_id AND l2.node_id = l1.peer_node_id) ' .
						'INNER JOIN users_nodes ON nodes.id = users_nodes.node_id ' .
						'LEFT JOIN users ON users.id = users_nodes.user_id',
						'users.status = "activated" AND l1.status = "active" AND l2.status = "active"',
						'nodes.id'
						);
		$stats['stats_links'] =
				$db->cnt('',
						'nodes ' .
						'INNER JOIN links AS l1 ON l1.node_id = nodes.id ' .
						'LEFT JOIN links AS p2p ON (l1.id < p2p.id AND l1.type = "p2p" AND p2p.type = "p2p" AND l1.node_id = p2p.peer_node_id AND p2p.node_id = l1.peer_node_id) ' .
						'LEFT JOIN links AS clients ON (l1.type = "client" AND l1.peer_ap_id = clients.id) ' .
						'INNER JOIN users_nodes ON nodes.id = users_nodes.node_id ' .
						'LEFT JOIN users ON users.id = users_nodes.user_id',
						'users.status = "activated" AND l1.status = "active" AND (p2p.status = "active" OR clients.status = "active")',
						'l1.id'
						);
		$stats['stats_aps'] =
				$db->cnt('',
						'nodes ' .
						'INNER JOIN links ON links.node_id = nodes.id AND links.type = "ap" AND links.status = "active" ' .
						'INNER JOIN users_nodes ON nodes.id = users_nodes.node_id ' .
						'LEFT JOIN users ON users.id = users_nodes.user_id',
						'users.status = "activated"',
						'links.id'
						);
		$stats['stats_services_active'] =
				$db->cnt('',
						'nodes_services',
						'nodes_services.status = "active"'
						);
		$stats['stats_services_total'] =
				$db->cnt('',
						'nodes_services',
						''
						);
		$this->tpl = array_merge($this->tpl, $stats);
		$stats['last_calc'] = time();
		if (is_writable($stats_tmp) || !file_exists($stats_tmp)) {
			$h = @fopen($stats_tmp, "w");
			@fwrite($h, serialize($stats));
			@fclose($h);
		}
	}
	
	function output() {
		if ($this->hide) return;
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && method_exists($this, 'output_onpost_'.$_POST['form_name'])) call_user_func(array($this, 'output_onpost_'.$_POST['form_name']));
		global $construct, $main, $db, $vars, $lang;
		
		$this->tpl['logged'] = $main->userdata->logged;
		if ($main->userdata->logged) {
			$this->tpl = array_merge($this->tpl, $main->userdata->info);
			$this->tpl['mynodes'] = $db->get('nodes.id, nodes.name', 'nodes INNER JOIN users_nodes ON nodes.id = users_nodes.node_id', "users_nodes.user_id = '".$main->userdata->user."'", 'nodes.id');
			foreach( (array) $this->tpl['mynodes'] as $key => $value) {
				$this->tpl['mynodes'][$key]['url'] = makelink(array("page" => "mynodes", "node" => $this->tpl['mynodes'][$key]['id']));
				$this->tpl['mynodes'][$key]['url_view'] = makelink(array("page" => "nodes", "node" => $this->tpl['mynodes'][$key]['id']));
			}
			$this->tpl['link_addnode'] = makelink(array("page" => "mynodes", "node" => "add"));
			$this->tpl['link_edit_profile'] = makelink(array("page" => "users", "user" => $main->userdata->user));
			if ($main->userdata->privileges['admin'] === TRUE) {
				$this->tpl['is_admin'] = TRUE;
				$this->tpl['link_admin_nodes'] = makelink(array("page" => "admin", "subpage" => "nodes"));
				$this->tpl['link_admin_users'] = makelink(array("page" => "admin", "subpage" => "users"));
				$this->tpl['link_admin_nodes_services'] = makelink(array("page" => "admin", "subpage" => "nodes_services"));
				$this->tpl['link_admin_services'] = makelink(array('page' => 'admin', 'subpage' => 'services'));
				$this->tpl['link_admin_regions'] = makelink(array('page' => 'admin', 'subpage' => 'regions'));
				$this->tpl['link_admin_areas'] = makelink(array('page' => 'admin', 'subpage' => 'areas'));
			}
			if ($main->userdata->privileges['admin'] === TRUE || $main->userdata->privileges['hostmaster'] === TRUE) {
				$this->tpl['is_hostmaster'] = TRUE;

				$this->tpl['link_dnsnameservers'] = makelink(array("page" => "hostmaster", "subpage" => "dnsnameservers"));
				$this->tpl['link_dnsnameservers_waiting'] = makelink(array("page" => "hostmaster", "subpage" => "dnsnameservers", "form_search_nameservers_search" => serialize(array("dns_nameservers__status" => "waiting"))));
				$this->tpl['dnsnameservers_waiting'] = $db->cnt('', "dns_nameservers", "status = 'waiting'");

				$this->tpl['link_dnszones'] = makelink(array("page" => "hostmaster", "subpage" => "dnszones"));
				$this->tpl['link_dnszones_waiting'] = makelink(array("page" => "hostmaster", "subpage" => "dnszones", "form_search_dns_search" => serialize(array("dns_zones__status" => "waiting"))));
				$this->tpl['dnszones_waiting'] = $db->cnt('', "dns_zones", "status = 'waiting'");

				$this->tpl['link_ranges'] = makelink(array("page" => "hostmaster", "subpage" => "ranges"));
				$this->tpl['link_ranges_waiting'] = makelink(array("page" => "hostmaster", "subpage" => "ranges", "form_search_ranges_search" => serialize(array("ip_ranges__status" => "waiting", "ip_ranges__delete_req" => "N"))));
				$this->tpl['ranges_waiting'] = $db->cnt('', "ip_ranges", "status = 'waiting' AND delete_req = 'N'");
				$this->tpl['link_ranges_req_del'] = makelink(array("page" => "hostmaster", "subpage" => "ranges", "form_search_ranges_search" => serialize(array("ip_ranges__delete_req" => "Y"))));
				$this->tpl['ranges_req_del'] = $db->cnt('', "ip_ranges", "delete_req = 'Y'");
			}
		}
		$this->tpl['link_home'] = makelink(array());
		$this->tpl['link_allnodes'] = makelink(array("page" => "nodes"));
		$this->tpl['link_allranges'] = makelink(array("page" => "ranges", "subpage" => "search"));
		$this->tpl['link_allservices'] = makelink(array("page" => "services"));
		$this->tpl['link_alldnszones'] = makelink(array("page" => "dnszones"));
		parse_str(substr(makelink(array("page" => "search"), FALSE, TRUE, FALSE), 1), $this->tpl['query_string']);
		$this->calculate_menu_stats();
		$main->html->head->add_script("text/javascript", makelink(array("page" => "search", "subpage" => "suggest_js")));
		return template($this->tpl, __FILE__);
	}
}

?>
