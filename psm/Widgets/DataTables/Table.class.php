<?php namespace psm\Widgets\DataTables;
global $ClassCount; $ClassCount++;
class Table extends \psm\Widgets\Widget {

	const HARD_LIMIT_ROWS = 1000;

	private $headings = array();
	private $queryClass = NULL;

	private $tableName      = 'mainTable';
	private $usingAjax      = FALSE;
	private $saveState      = TRUE;
	private $defaultSort    = -1;
	private $paginate       = TRUE;
	private $scrollInfinite = FALSE;


	/**
	 * Wrapper to generate DataTables html.
	 *
	 * @param string[] $headings
	 * @param datatables_Query $queryClass
	 */
	public function __construct($headings, $queryClass, $usingAjax=FALSE) {
		$this->headings = $headings;
		// validate Query class type
		\psm\Utils\FuncArgs::classEquals('psm\\Widgets\\DataTables\\Query', $queryClass);
		$this->queryClass = $queryClass;
		$this->usingAjax = $usingAjax;
		\psm\html\tplFile_Main::addFileCSS(
			'{path=static}jquery-ui'.DIR_SEP.'redmond'.DIR_SEP.'jquery.ui.theme.css',
			'{path=static}jquery'.DIR_SEP.'datatables_bootstrap.css'
		);
		\psm\html\tplFile_Main::addFileJS(
			'{path=static}jquery'.DIR_SEP.'jquery.dataTables-1.9.4.min.js',
			'{path=static}jquery'.DIR_SEP.'datatables_bootstrap.js'
		);
	}


//	public function addRow($row=array()) {
//		if(!is_array($row) || count($row)==0)
//			return;
//		if(!is_array($this->rows))
//			$this->rows = array();
//		$this->rows[] = $row;
//	}


	public function Render() {
		if(!$this->usingAjax) {
			if(!$this->queryClass->runQuery()) {
\psm\Portal::Error('Failed to query db.');
			}
		}
		$this->Render_JS();
		return '
<a class="button" href="javascript:(function()%20{var%20url%20=%20\'http://debug.datatables.net/bookmarklet/DT_Debug.js\';if(%20typeof%20DT_Debug!=\'undefined\'%20)%20{if%20(%20DT_Debug.instance%20!==%20null%20)%20{DT_Debug.close();}else%20{new%20DT_Debug();}}else%20{var%20n=document.createElement(\'script\');n.setAttribute(\'language\',\'JavaScript\');n.setAttribute(\'src\',url+\'?rand=\'+new%20Date().getTime());document.body.appendChild(n);}})();">Debug</a>
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
$data = '
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
	oTable = $(\'#'.$this->tableName.'\').dataTable({
		"oLanguage": {
			"sEmptyTable"       : "&nbsp;<br />Nothing to display<br />&nbsp;",
			"sZeroRecords"      : "&nbsp;<br />Nothing to display<br />&nbsp;",
		},
		"bJQueryUI"         : true,
		"bProcessing"       : false,
';
if($this->defaultSort >= 0)
	$data .= '
		"aaSorting"         : [[ '.((int) $this->defaultSort).', "asc" ]],
';
if($this->paginate)
	$data .= '
		"bPaginate"         : true,
		"iDisplayLength"    : 10,
		"aLengthMenu"       : [[5, 10, 30, 100, -1], [5, 10, 30, 100, "All"]],
		"sPaginationType"   : "full_numbers",
';
//		"bStateSave"        : '.($this->saveState ? 'true' : 'false').',
if(!empty($this->scrollInfinite))
	$data .= '
		"bScrollInfinite"   : true,
		"bScrollCollapse"   : true,
		"sScrollY"          : "450px",
';
//$data .= '
//		"bDeferRender"      : true,
//';
if($this->usingAjax)
	$data .= '
		"bServerSide"       : true,
		"sAjaxSource"       : "./?page={page}&ajax=true",
';
$data .= '
	});
} );
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper form-inline"
} );
</script>
';
		\psm\html\Engine::addHeader($data);
	}


	private function Render_Headings() {
		$data = '';
		foreach($this->headings as $heading) {
			$data .= TAB.TAB.'<th>'.$heading.'</th>'.NEWLINE;
		}
		return $data;
	}


	private function Render_Rows() {
		if($this->usingAjax)
			return;
		$data = '';
		for($i = 0; $i < self::HARD_LIMIT_ROWS; $i++) {
			$row = $this->queryClass->getRow();
			if($row == FALSE) break;
//echo '<pre>';print_r($row);echo '</pre>';
			$data .= $this->Render_Row($row);
		}
		return $data;
	}


	private function Render_Row($row) {
		$data = TAB.'<tr class="odd gradeU">'.NEWLINE;
		foreach($row as $r)
			$data .= TAB.TAB.'<td>'.$r.'</td>'.NEWLINE;
		$data .= TAB.'</tr>'.NEWLINE;
		return $data;
	}


	public function setTableName($tableName='mainTable') {
		if(empty($tableName))
			return;
		$this->tableName = $tableName;
	}


	public function setSaveState($saveState=TRUE) {
		$this->saveState = $saveState;
	}


	// enable default sort column (starting at 0)
	public function setDefaultSort($col=-1) {
		if($col === NULL) $col = -1;
		$col = (int) $col;
		if($col < -1) $col = -1;
		$this->defaultSort = $col;
	}
	// enable pagination
	public function setPagination($paginate=TRUE) {
		$this->paginate = \psm\Utils\Vars::toBoolean($paginate);
		// disable infinite scroll
		if($this->paginate)
			$this->scrollInfinite = FALSE;
	}
	// enables infinite scroll
	public function setScrollInfinite($tableHeight=TRUE) {
		if($tableHeight === TRUE)
			$this->scrollInfinite = '450px';
		elseif($tableHeight === FALSE)
			$this->scrollInfinite = FALSE;
		$this->scrollInfinite = $tableHeight;
		// disable pagination
		if(!empty($this->scrollInfinite))
			$this->paginate = FALSE;
	}


}
?>