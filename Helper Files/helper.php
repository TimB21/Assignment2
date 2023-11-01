<?php

require_once("inputTags.php");
require_once("formModel.php"); 

$mysql = new mysqli('localhost','berlangat_trading','qLw6gZ','berlangat_trading');		// Put your actual username here (same as SU/buzz username)

/** Function: getDBResultArray()
 * Get MySQL results in an array indexed
 * by specified unique id. 
 *
 * @param       string  $query             valid MySQL SELECT query 
 * @param       string  $pid               identifying column of each row, should be 
 *                                         primary key of one of the joined tables 
 */
function getDBResultArray($query, $pid) {
	global $mysql;
	$result = $mysql->query($query);	
	if(!$result) {
		echo "DB Error, could not query the database";
		exit();
	}
	return associateRowsWithKey($result, $pid);
}

/** Function: associateRowsWithKey()
 * Get MySQL results in an array indexed
 * by specified unique id. 
 *
 * @param       string  $result            SELECT result returned by MySQL
 * @param       string  $pid               identifying column of each row, should be 
 *                                         primary key of one of the joined tables 
 */
function associateRowsWithKey($result, $pid) {
	$data = array();
	while($row = $result->fetch_assoc()) { 
		$data[$row[$pid]] = $row; 
	}
	$result->close(); 
	return $data;
}

/** Function: showQueryResultInHTML()
 * Echo an HTML table presenting the results of 
 * a MySQL database SELECT query. This is an easy
 * way to present the data from any SQL SELECT
 * query.
 *
 * @param       string  $query             valid MySQL SELECT query 
 * @param       string  $pid               identifying column of each row, should be 
 *                                         primary key of one of the joined tables 
 * @param       array   $columnsAndHeaders maps the name of each database attribute to 
 *                                         be selected to the HTML header name to be echoed
 * @param       bool    $radioSelect       There are radio buttons to select one row 
 * @param       string  $link              URL that row links to with data sent along
 * @param       string  $linkField         Specific column of the table that has the URL 
 */
function showQueryResultInHTML($query, $pid, $columnsAndHeaders, $radioSelect = FALSE, $link = NULL, $linkField = NULL) {
	$data = getDBResultArray($query, $pid);
	showDBResultInHTML($data, $pid, $columnsAndHeaders,$radioSelect,$link,$linkField);
}

/** Function: showDBResultInHTML()
 * Echo an HTML table presenting the results of 
 * a MySQL database SELECT query. This is an easy
 * way to present the data from any SQL SELECT
 * query.
 *
 * @param       string  $data              Data from SELECT result returned by MySQL
 * @param       string  $pid               identifying column of each row, should be 
 *                                         primary key of one of the joined tables 
 * @param       array   $columnsAndHeaders maps the name of each database attribute to 
 *                                         be selected to the HTML header name to be echoed
 * @param       bool    $radioSelect       There are radio buttons to select one row 
 * @param       string  $link              URL that row links to with data sent along
 * @param       string  $linkField         Specific column of the table that has the URL 
 */
function showDBResultInHTML($data, $pid, $columnsAndHeaders, $radioSelect = FALSE, $link = NULL, $linkField = NULL) {
	echo "<TABLE border=1>\n";
	echo "  <TR>\n";
	if($radioSelect) {
		echo "    <TH>&nbsp;</TH>\n"; // Just an empty space to make table look nice
	}
	foreach($columnsAndHeaders as $col => $head) {
		echo "    <TH>$head</TH>\n";
	}
	echo "  </TR>\n";

	if(empty($data) && !$radioSelect){ // Only show "No data" message if not selecting an option
		echo "  <TR>\n";
		echo "    <TD colspan=".sizeof($columnsAndHeaders)."><I>No data</I></TD>\n";
		echo "  </TR>\n";
	} else {
		$first = TRUE;
  		foreach($data as $id => $datum){
	  		echo "  <TR>\n";
			if($radioSelect) {
	  			echo "    <TD>\n";
				echo buildRadio($pid,$id,$first)."\n";
				$first = FALSE; // The first radio button will be selected
	  			echo "    </TD>\n";
			}
			foreach($columnsAndHeaders as $col => $head) {
	  			echo "    <TD>\n";
				if($link !== NULL && $linkField === $col) {
					echo "<A href=\"$link?$pid=$id\">\n";
				}
	  			echo "      ".$datum[$col]."\n";
				if($link !== NULL && $linkField === $col) {
					echo "</A>\n";
				}
	  			echo "    </TD>\n";
			}
	  		echo "</TR>\n";
		}
		if($radioSelect) {
			echo "  <TR>\n";
	  		echo "    <TD>\n";
			echo buildRadio($pid,"NEW",$first)."\n";
	  		echo "    </TD>\n";
			echo "    <TD colspan=".sizeof($columnsAndHeaders).">No previous option</TD>\n";
			echo "  </TR>\n";
		}
	}
	echo "</TABLE>\n";
}

/** Function: showInputTableInHTML()
 * Echo an HTML table that contains 
 * input fields of various sorts to accept
 * data for submission to a form. 
 *
 * @param       array   $inputTypes	Each key is an input field name, and the value clarifies the type:
 *                                      An int indicates the length of a text input field, and an array
 *                                      with "value" and "description" subarrays describes contents of
 *                                      a SELECT dropdown box. 
 * @param       array   $displayNames   Each key is an input field name (matches $inputTypes) but the
 *                                      values are the actual text displayed next to each input telling
 *                                      the user what to input.
 * @param       bool    $verifyData     Whether previous data exists, and if problems with the previously
 *                                      entered data should be pointed out (comes from $_REQUEST).
 * @param       array   $defaultData    Data to fill in the input fields with to start
 */
function showInputTableInHTML($inputTypes,$displayNames,$verifyData=FALSE,$defaultData = NULL) {
	echo "<TABLE border=1>\n";
	foreach($displayNames as $field => $label) {
		echo "  <TR>\n";
		echo "    <TD align=\"right\">";
		if($verifyData && getRequestData($field) === "") echo "<FONT color=\"red\">"; // Bad data 
		echo $label;
		if($verifyData && getRequestData($field) === "") echo "</FONT>"; // Bad data 
		echo "    </TD>\n";
		echo "    <TD>\n";
		$value = ($verifyData ? getRequestData($field) : NULL); // in verify mode, look at previously submitted data
		if(!$verifyData && $defaultData !== NULL && !empty($defaultData[$field])) {
			$value = $defaultData[$field]; // Use the default data if available and not verifying
		}
		if(verifyInteger($inputTypes[$field])) { // Corresponds to text input
			echo buildText($field,$value,20,$inputTypes[$field])."\n";
		} else { // Corresponds to dropdown box (could there be other types?)
			echo buildSelect($field,$inputTypes[$field][0],$inputTypes[$field][1],$inputTypes[$field][2],$value)."\n"; 
		}
		echo "    </TD>\n";
		echo "  </TR>\n";
	}
	echo "</TABLE>\n";
}

/** Function: hiddenInputsForAllPreviousData()
 * Takes all previously entered $_REQUEST data and
 * creates hidden HTML input fields to resend all of it.
 */
function hiddenInputsForAllPreviousData() {
	foreach($_REQUEST as $key => $value) {
		echo buildHidden($key,$value);
	}
}

?>