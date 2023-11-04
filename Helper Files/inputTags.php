<?php
/*
 * Functions to produce HTML input tags
 */


/** Function: buildHidden()
 * Build a hidden HTML form INPUT tag. 
 *
 * @param       string  $name   name and id of the INPUT element
 * @param	any	$value	value of the INPUT element
 * @return      string          HTML string representation of the INPUT tag
 */
function buildHidden($name,$value){
        $result = '<INPUT type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
        return $result;
}

/** Function: buildSubmit()
 * Build an HTML form submit button INPUT tag. 
 *
 * @param       string  $name   name and id of the INPUT element
 * @param	any	$value	value of the INPUT element
 * @return      string          HTML string representation of the INPUT tag
 */
function buildSubmit($name,$value){
        $result = '<INPUT type="submit" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
        return $result;
}

/** Function: buildText()
 * Build an HTML form text field INPUT tag. 
 *
 * @param       string  $name   	name and id of the INPUT element
 * @param	any	$value		value displayed in the text field
 * @param	int	$size		display size/width of the text field
 * @param	int	$maxlength	maximum number of characters allowed as input
 * @return      string          HTML string representation of the INPUT tag
 */
function buildText($name,$value = "",$size = 20,$maxlength = 255){
        $result = '<INPUT type="text" size="'.$size.'" name="'.$name.'" id="'.$name.'" value="'.$value.'" maxlength="'.$maxlength.'">';
        return $result;
}

/** Function: buildPassword()
 * Build an HTML form password text field INPUT tag (input characters obscurred). 
 *
 * @param       string  $name   name and id of the INPUT element
 * @param	int	$size	display size/width of the text field
 * @return      string          HTML string representation of the INPUT tag
 */
function buildPassword($name,$size = 20){
        $result = '<INPUT type="password" size="'.$size.'" name="'.$name.'" id="'.$name.'" value="">';
        return $result;
}

/** Function: buildRadio()
 * Build an HTML form radio button INPUT tag.
 * Radio buttons that are part of the same group,
 * where only one within the group is allowed to be
 * selected, should all have the same name/id. 
 *
 * @param       string  $name   	name and id of the INPUT element
 * @param	any	$value		value transmitted by the form if this button is selected
 * @param	boolean	$checked	whether this radio button is pre-selected. 
 *					Should only be true of one button in the group.
 * @return      string          HTML string representation of the INPUT tag
 */
function buildRadio($name,$value,$checked = FALSE){
	$result = '<INPUT type="radio" name="'.$name.'" id="'.$value.'" value="'.$value.'" '.($checked ? 'checked' : '').'>';
	return $result;
}

/** Function: buildSelect()
 * Builds a select drop down box filled with options from the array $options
 *
 * @param	string	$name			Name and ID of SELECT tag
 * @param	array	$options		Each index holds an associative array where one of the fields holds
 *						values for each OPTION tag and another field holds the corresponding
 *						display values.
 * @param	string	$valueField		Name of the field in each element of $options that contains the value
 * @param	string	$descriptionField	Name of the field in each element of $options that contains the display value
 * @param	mixed	$value			Value of default selected OPTION tag
 * @return	string				HTML string of the created SELECT drop down box
 */
function buildSelect($name,$options,$valueField = 'value',$descriptionField = 'description',$value = NULL){
	$result = '';
	$result .= '<SELECT name="'.$name.'" id="'.$name.'">';
	foreach($options as $option){
		$result .= '<OPTION value="'.$option[$valueField].'" '.($option[$valueField] == $value ? 'selected' : '').'>
			   '.$option[$descriptionField].
			   '</OPTION>';
	}
	$result .= '</SELECT>';
	return $result;
}

/** Function: buildCheckbox()
 * Build an HTML form checkbox INPUT tag. 
 *
 * @param       string  $name   	name and id of the INPUT element
 * @param	any	$value		value to transmit if box is checked (could just be "on" or "yes")
 * @param	boolean	$checked	whether the box is pre-checked
 * @param	string	$extra		additional HTML tag attributes for the element (can ignore)
 * @return      string          HTML string representation of the INPUT tag
 */
function buildCheckbox($name,$value,$checked = FALSE,$extra = ''){
	$result = '<INPUT type="checkbox" name="'.$name.'" id="'.$name.'" value="'.$value.'" '.($checked ? 'checked' : '').' '.$extra.'>';
	return $result;
}

/** Function: buildTextarea()
 * Build an HTML form TEXTAREA tag. 
 *
 * @param       string  $name   name and id of the TEXTAREA element
 * @param	string	$value	initially displayed content within the text area
 * @param	int	$cols	number of columns wide of text area	
 * @param	int	$rows	number of rows tall of the text area
 * @return      string          HTML string representation of the TEXTAREA tag
 */
function buildTextarea($name,$value = '',$cols = 20,$rows = 10){
	$result = '<TEXTAREA name="'.$name.'" id="'.$name.'" cols="'.$cols.'" rows="'.$rows.'">'.$value.'</TEXTAREA>';
	return $result;
}

?>