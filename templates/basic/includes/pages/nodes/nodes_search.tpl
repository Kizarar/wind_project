{*
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
 *}

{include assign=help file=generic/help.tpl help=nodes_search }
{include file=generic/page-title.tpl title="`$lang.all_nodes`" right="$help"}
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="table-page">
{if $skip_map!='yes'}
	<tr>
		<td class="table-page-split">
		<div id="map" class="map" style="height: 350px;" > </div>
		<div style="text-align: right"> {include file=generic/link.tpl link="$link_gearth" content="Google Earth"}</div>
		</td>
	</tr>
{/if}
	<tr>
		<td class="table-page-split">
			{include file=generic/title1.tpl title="`$lang.nodes_search`" content=$form_search_nodes}
		</td>
	</tr>
	<tr>
		<td class="table-page-pad">
			{include file=generic/title2.tpl title="`$lang.nodes_found`" content=$table_nodes}
		</td>
	</tr>
</table>
