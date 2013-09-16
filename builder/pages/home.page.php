<?php namespace builder\Pages;
global $ClassCount; $ClassCount++;
class page_home extends \psm\Portal\Page {

	const dbName = 'main';


	public function Render() {
		// list projects
		$html = '
<table class="table table-striped">
<thead>
	<tr>
		<th>State</th>
		<th>Project</th>
		<th>Latest Version</th>
		<th>Latest Build</th>
	</tr>
</thead>
<tbody>
';
		$db = \psm\pxdb\dbPool::getDB(self::dbName);
		$sql = 'SELECT ';
		$sql .= "`project_id` AS `id`, `enabled`, `build_count`, `name`, `title`, ".
				"UNIX_TIMESTAMP(`last_build`) as `last_build`, ".
				"UNIX_TIMESTAMP(`last_successful`) as `last_successful` ".
				"FROM `builder_projects` LIMIT 0, 1";
		while($db->hasNext()) {
			$html .= '
	<tr>
		<td>Pending</td>
		<td>WebAuctionPlus</td>
		<td>3.0.16</td>
		<td>NA</td>
	</tr>
	<tr>
		<td>Pending</td>
		<td>WeBook</td>
		<td>3.0.1</td>
		<td>NA</td>
	</tr>
	<tr>
		<td>Successful</td>
		<td>GrowControl</td>
		<td>3.0.11</td>
		<td>1</td>
	</tr>
';
		}
		$html .= '
</tbody>
</table>
';
		



		return $html;
		$headings = array(
			'Item',
			'Seller',
			'Expires',
			'Price (Each)',
			'Price (Total)',
			'Market Value',
			'Qty',
			'Buy',
		);
		$table = \psm\Widgets\Widget_DataTables::factory(
			$headings,
			new home_Query(),
			FALSE
		);
		return $table->Render();
	}


	public function Action($action) {
	}


}
?>