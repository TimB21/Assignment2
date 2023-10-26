<?php

/** Function: getRequestData()
 * Cleans any requested data by converting HTML entities
 * (to avoid cross scripting attacks) and trims white space
 * around strings.
 *
 * @param       string  $name   request data label name
 * @return      string          request data value
 */
function getRequestData($name){
	$result = "";
	if(array_key_exists($name, $_REQUEST)) {
		$result = trim(htmlentities($_REQUEST[$name]));
	}
	return $result;
}

/** Function: buildFormStart()
 * Returns a string for an opening form tag with a given name, action and method (defaults to POST).
 *
 * @param       string  $name	Name of HTML form
 * @param       string  $action	Page that the form sends data to
 * @param       string  $method	Passing method of the form, should be either GET or POST (defaults to POST)
 * @return      string		HTML string for opening form tag
 */
function buildFormStart($name,$action,$method = 'POST'){
	$result = '<FORM name="'.$name.'" action="'.$action.'" method="'.$method.'">';
	return $result;
}

/** Function: buildFormEnd()
 * Returns a string for a closing form tag. 
 *
 * @return      string		HTML string for closing form tag
 */
function buildFormEnd(){
	return '</FORM>';
}

/** Function: getSearchStringComponents()
 * Takes an input string from a search field, and returns an array of string tokens
 * for the individual search terms in the string. Terms enclosed in double quotes are
 * preserved as whole tokens.
 *
 * @param	string	$search	String entered by a user into a search field
 * @return	array		Each element is a single separate search element/token
 *				of the input string
 */
function getSearchStringComponents($search){
	$groups = array();
	$keys = array();
	preg_match_all('/"([^"]+)"/',html_entity_decode($search), $groups);

	// $groups[1] contains each double-quote enclosed string
	foreach($groups[1] as $index => $grouping){
		$groups[$index] = trim($grouping);
		if(empty($groups[$index])) unset($groups[$index]);
		else $keys[] = $groups[$index]; // Add double-quoted groups to keys
	}

	$quotebarriers = explode('"',html_entity_decode($search));

	$indexes = array_keys($quotebarriers);
	for($i = 0; $i < count($indexes); $i += 2){ // Skip double quoted groups
		$spacebarriers = preg_split('/\s+/', $quotebarriers[$indexes[$i]]);
		foreach($spacebarriers as $word){
			if(!empty($word)) $keys[] = $word;
		}
	}
	
	return $keys;
}

/** Function: verifyEmailAddress()
 * Returns TRUE if the input string is a valid e-mail address 
 *
 * @param	string $emailaddress	A string that should contain a valid e-mail address
 * @return	bool			TRUE if the string is a valid e-mail address and FALSE otherwise
 */
function verifyEmailAddress($emailaddress){
	// Not perfect, but good enough
	return preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+\.[a-zA-Z0-9\._-]+$/",$emailaddress); 
}

/** Function: verifyInteger()
 * Returns TRUE if the input string contains nothing but a valid integer 
 *
 * @param	string $integer	A string representation of what should be an integer
 * @return	bool		TRUE if the string represents an integer and FALSE otherwise
 */
function verifyInteger($integer){
	return is_numeric($integer) && $integer == floor($integer);
}

/** Function: verifyMoney()
 * Returns TRUE if the input string contains a valid dollar amount 
 *
 * @param	string $money	String that should contain a valid dollar amount
 * @return	bool		TRUE if the string is an integer optionally followed by a single
 *				decimal and exactly two more digits and FALSE otherwise
 */
function verifyMoney($money){
	return preg_match("/^\d+(\.\d\d)?$/",$money);
}

?>
