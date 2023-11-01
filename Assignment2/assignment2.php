

<HTML>
<BODY>

<CENTER>
<H1>Sport Fanatic Online Trading Post</H1>
(Get the collectables your friends will be jealous about)

<BR><BR>
<B>Product Search</B><BR>
<FORM name="searchform" action="assignment2.php" method="POST"><INPUT type="text" size="55" name="search" id="search" value="" maxlength="255">
<SELECT name="productcategoryid" id="productcategoryid"><OPTION value="all" selected>
			   All Categories</OPTION><OPTION value="1" >
			   Player Cards</OPTION><OPTION value="2" >
			   Card Bundles</OPTION><OPTION value="3" > 
			   Autographed Cards</OPTION><OPTION value="4" >
			   Collectables</OPTION></SELECT><BR>
<TABLE>
	<TR>
<TD><INPUT type="submit" name="submit" id="submit" value="Search"></TD>
</FORM><FORM name="newproductform" action="product.php" method="POST"><TD><INPUT type="submit" name="newproduct" id="newproduct" value="Sell Your Product"></TD>
</FORM><FORM name="accountinfo" action="account.php" method="POST"><TD><INPUT type="submit" name="gotoaccount" id="gotoaccount" value="Your Account"></TD>
</FORM>	</TR>
</TABLE>
<BR><BR>

<CENTER>
<?php
// Useful functions written 
require_once("helper.php"); 

$searchtext = getRequestData("search"); 
$category = getRequestData("productcategoryid"); 

if($searchtext !== "") { // If the search interface has been filled out
	// Complicated helper function that splits up user input into separate search terms
	$searchComponents = getSearchStringComponents($searchtext);	 

	// Debugging tool
	// echo "These were the search terms<BR>\n";
	// var_dump($searchComponents); 

	// Incorporate these search terms into LIKE checks of a SQL statement
	$searchChecks = array(); // Empty to start
	// All of these columns will be searched using LIKE
	$searchColumns = array("Product.description", "Product.productName");
	
	foreach($searchComponents as $target) {
		// Prevent SQL injection
		$target = $mysql->real_escape_string($target); 
		foreach($searchColumns as $col) {
			// Add extra component to array of search checks
			$searchChecks[] = " $col LIKE '%$target%' ";
		}
	} 
    
	// Include category in the query if it's selected
    $categoryCondition = "";
    if ($category !== "all") {
        $categoryCondition = "AND Category.categoryID = $category";
    }

	// Same query as above, but HAVING clause will narrow the search.
	// HAVING clause is required because I am referring to the aliases
	// I defined using the AS keyword
	$query = "SELECT productid, singular, productName, description
              FROM Product
              JOIN Category ON Product.categoryID = Category.categoryID
              WHERE " . implode(" AND ", $searchChecks). " $categoryCondition";
    showQueryResultInHTML($query, "productid", array("singular" => "Category", "productName" => "Product Name", "description" => "Description"), FALSE, "product_info.php", "productName");
}	
?>  
</CENTER>

</CENTER>

</BODY>
</HTML>