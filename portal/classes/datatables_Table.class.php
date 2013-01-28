<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class datatables_Table {

	private $headings = array();
	private $rows = NULL;


	public function __construct($headings=array()) {
		if(count($headings) > 0)
			$this->headings = $headings;
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


	public function addRow($row=array()) {
		if(!is_array($row) || count($row)==0)
			return;
//TODO: should this throw an exception? this means it's in 
//		if(!is_array($this->rows))
//			$this->rows = array();
		$this->rows[] = $row;
	}


	public function Render() {
		$this->Render_JS();
		return '
<table border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered" id="mainTable" style="width: 100%;">
<thead>
	<tr>
'.$this->Render_Headings().'
	</tr>
</thead>
<tbody>
'.$this->Render_Rows().'
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


	private function Render_Headings() {
		$data = '';
		foreach($this->headings as $heading) {
			$data .= TAB.TAB.'<th>'.$heading.'</th>'.NEWLINE;
		}
		return $data;
	}


	private function Render_Rows() {
		$data = '';
		foreach($this->rows as $row)
			$data .= $this->Render_Row($row);
		return $data;
	}


	private function Render_Row($row) {
		$data = TAB.'<tr class="odd gradeU">'.NEWLINE;
		foreach($row as $r)
			$data .= TAB.TAB.'<td>'.$r.'</td>'.NEWLINE;
		$data .= TAB.'</tr>'.NEWLINE;
		return $data;
	}


}
?>