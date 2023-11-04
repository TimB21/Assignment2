

<HTML>
<BODY>

<CENTER> 
<!--Display for main page with the option to search, sell product, and account -->
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
require_once("helper.php"); 

// retrieves search term and category id
$searchtext = getRequestData("search"); 
$category = getRequestData("productcategoryid"); 

if($searchtext !== "") { // If the search interface has been filled out
	// Helper function that splits up user input into separate search terms
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

	// Product information query
	$query = "SELECT productid, singular, productName, description
              FROM Product
              JOIN Category ON Product.categoryID = Category.categoryID
              WHERE " . implode(" AND ", $searchChecks). " $categoryCondition"; 
	// Display product information
    showQueryResultInHTML($query, "productid", array("singular" => "Category", "productName" => "Product Name", "description" => "Description"), FALSE, "product.php", "productName");
}	 
?>  
</CENTER>

</CENTER>

</BODY>
</HTML>