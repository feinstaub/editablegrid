<?php

class EditableGrid {
	
	protected $columns;
	protected $translate;
	
	function __construct() 
	{
		$this->translate = Zend_Registry::get('translate');
		$this->columns = array();
	}
	
	public function addColumn($name, $label, $type, $values = NULL, $editable = true, $field = NULL) 
	{
		$this->columns[$name] = array( "field" => $field ? $field : $name, "label" => $label, "type" => $type, "editable" => $editable, "values" => $values );
	}
	
	private function _getRowField($row, $field) 
	{
		return is_array($row) ? $row[$field] : $row->$field;
	}
	
	public function renderXML($rows, $customRowAttributes=array()) 
	{
		header('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="utf-8"?>';
		
		echo "<table><metadata>\n";

		foreach ($this->columns as $name => $info) {
			echo "<column name='$name' label='". $this->translate->_($info['label']) . "' datatype='{$info['type']}' editable='". ($info['editable'] ? "true" : "false") . "'>\n";
			if (is_array($info['values'])) {
				echo "<values>\n";
				foreach ($info['values'] as $key => $value) echo "<value value='{$key}'><![CDATA[{$value}]]></value>\n"; 
				echo "</values>\n";
			}
			echo "</column>\n";
		}  

  
		echo "</metadata><data>\n";
	
		foreach ($rows as $row) { 
	
			echo "<row id='" . $this->_getRowField($row, 'id') . "'";
			foreach ($customRowAttributes as $name => $field) echo " {$name}='" . $this->_getRowField($row, $field) . "'";
			echo ">\n";
			
			foreach ($this->columns as $name => $info) {
				$field = $info['field'];
				echo "<column name='{$name}'><![CDATA[" . $this->_getRowField($row, $field) . "]]></column>\n";
			}
			echo "</row>\n";
		}
		
		echo "</data></table>\n";
	} 
}