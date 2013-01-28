<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class datatables_Table {


	public function __construct() {
		html_File_Main::addFileCSS(
			'{path=static}jquery-ui/redmond/jquery.ui.theme.css',
			'{path=static}jquery/datatables_bootstrap.css'
//			'{path=theme}table_jui.css'
		);
		html_File_Main::addFileJS(
			'{path=static}jquery/jquery.dataTables-1.9.4.min.js',
			'{path=static}jquery/datatables_bootstrap.js'
		);
	}


	public static function Validate($clss) {
		html_File::Validate($clss);
		if(!($clss instanceof self))
			die('<p>Not instance of html_File_Main!</p>');
		//TODO: throw exception
	}


	public function Render() {
		$this->Render_JS();
		return '
<table border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered" id="mainTable" style="width: 100%;">
	<thead>
		<tr>
			<th>Item</th>
			<th>Seller</th>
			<th>Expires</th>
			<th>Price (Each)</th>
			<th>Price (Total)</th>
			<th>Market Value</th>
			<th>Qty</th>
			<th>Buy</th>
		</tr>
	</thead>
	<tbody>
		<tr class="odd gradeU">
			<td>i</td>
			<td>s</td>
			<td>e</td>
			<td>p</td>
			<td>p</td>
			<td>m</td>
			<td>q</td>
			<td>
				<a href="">More Options</a>
			</td>
		</tr>
	</tbody>
</table>
';
	}
	private function Render_JS() {
		html_Engine::addHeader('
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
	oTable = $(\'#mainTable\').dataTable({
		"oLanguage": {
			"sEmptyTable"     : "&nbsp;<br />Nothing to display<br />&nbsp;",
			"sZeroRecords"    : "&nbsp;<br />Nothing to display<br />&nbsp;",
		},
		"bJQueryUI"         : true,
		"sPagePrevEnabled"  : true,
		"sPageNextEnabled"  : true,
	});
} );
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper form-inline"
} );
</script>
');
	}

}
?>