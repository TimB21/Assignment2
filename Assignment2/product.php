<HTML>
<BODY>

<CENTER>
<H1>Product Page</H1>
(One person's garbage is another person's treasure!)<BR>
<A href="assignment2.php">Search</A>
<BR><BR>

Fill out information about the product you intend to sell<BR>
<TABLE border="1">
<FORM name="newproductform" action="product.php" method="POST"><TR>
	<TH colspan="2" align="center">Your Information</TH>
</TR>
<TR>
	<TD align="right">
				Your E-mail Address
			</TD>
	<TD align="left"><INPUT type="text" size="20" name="emailaddressusername" id="emailaddressusername" value="" maxlength="255"></TD>
</TR>
<TR>
	<TH colspan="2" align="center">The Product's Information</TH>
</TR>
<TR>
	<TD align="right">Category</TD>
	<TD align="left">
		<SELECT name="productcategoryid" id="productcategoryid"><OPTION value="1" >
			   PlayerCard</OPTION><OPTION value="2" >
			   CardBundle</OPTION><OPTION value="3" >
			   AutographedCard</OPTION><OPTION value="4" >
			   Collectable<OPTION><OPTION value="4" >	</TD>
</TR>
<TR>
	<TD align="right">Name</TD>
	<TD align="left"><INPUT type="text" size="20" name="name" id="name" value="" maxlength="255"></TD>
</TR>
<TR>
	<TD align="right" valign="top">Description</TD>
	<TD align="left">
		<TEXTAREA name="description" id="description" cols="20" rows="10"></TEXTAREA>	</TD>
</TR>
<TR>
	<TH colspan="2" align="center">Details of Your Offer</TH>
</TR>
<TR>
	<TD align="right">Number Offered</TD>
	<TD align="left"><INPUT type="text" size="10" name="numberavailable" id="numberavailable" value="" maxlength="10"></TD>
</TR>
<TR>
	<TD align="right">Selling Price</TD>
	<TD align="left">$<INPUT type="text" size="10" name="sellingprice" id="sellingprice" value="" maxlength="10"></TD>
</TR>
<TR>
	<TD align="right" valign="top">Details</TD>
	<TD align="left"><TEXTAREA name="details" id="details" cols="20" rows="10"></TEXTAREA></TD>
</TR>
<TR>
	<TD colspan="2" align="center"><INPUT type="submit" name="submitnew" id="submitnew" value="Submit"></TD>
</TR>
</FORM></TABLE>

</BODY>
</HTML>

<?php
// Useful functions written by me
require_once("helper.php");

// Check if the form is submitted
if (isset($_POST['submitnew'])) {
    // Get the user's email address or username from the form
    $userInput = $_POST['emailaddressusername'];

    // Validate user input (you can use the verifyEmailAddress function)
    if (verifyEmailAddress($userInput)) {
        // User input is a valid email address

        // Retrieve product information from the form 
		$email = $_POST['emailaddressusername'];
        $productCategoryID = $_POST['productcategoryid'];
        $productName = $_POST['name'];
        $productDescription = $_POST['description'];
        $numberAvailable = $_POST['numberavailable'];
        $sellingPrice = $_POST['sellingprice'];
        $details = $_POST['details'];
		
		// Perform input validation
        if (empty($productName)) {
            echo "Product name cannot be empty.";
        } elseif (!verifyInteger($numberAvailable)) {
            echo "Number offered must be an integer.";
        } elseif (!verifyMoney($sellingPrice)) {
            echo "Selling price must be a valid dollar amount.";  
		}  
		else {  
			// Display the product information for review
            echo "Confirm All Information about your Offer:<br>"; 
			echo "Email Address: $email<br>";
            echo "Product Name: $productName<br>";
            echo "Category: $productCategoryID<br>";
            echo "Description: $productDescription<br>";
            echo "Number Available: $numberAvailable<br>";
            echo "Selling Price: $sellingPrice<br>";
            echo "Details: $details<br>"; 

			// Add a button to check for matching products and open a new window
			echo "<form method='post' target='_blank' action='product.php'>";
			echo "<input type='hidden' name='emailaddressusername' value='$userInput'>";
			echo "<input type='hidden' name='productcategoryid' value='$productCategoryID'>";
			echo "<input type='hidden' name='name' value='$productName'>";
			echo "<input type='hidden' name='description' value='$productDescription'>";
			echo "<input type='hidden' name='numberavailable' value='$numberAvailable'>";
			echo "<input type='hidden' name='sellingprice' value='$sellingPrice'>";
			echo "<input type='hidden' name='details' value='$details'>";
			echo "<input type='submit' name='checkMatchingProducts' value='Check for Matching Products' onClick='window.open(\"\", \"_blank\");'>";
			echo "</form>";
		}
		
		$searchtext = getRequestData("name"); 
		$category = getRequestData("productcategoryid"); 

	if($searchtext !== "") { // If the search interface has been filled out
		// Complicated helper function that splits up user input into separate search terms
		$searchComponents = getSearchStringComponents($searchtext);	 

		echo "These were the search terms<BR>\n";
		var_dump($searchComponents); 

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

        // Insert the product information into the database
        // You should write SQL statements for inserting into your database
        $insertProductQuery = "INSERT INTO Product (productName, description, categoryID) VALUES (?, ?, ?)";
        $stmt = $mysql->prepare($insertProductQuery);

        if ($stmt === false) {
            echo "Error preparing the statement for product insertion";
        } else {
            // Bind the parameters
            $stmt->bind_param("ssi", $productName, $productDescription, $productCategoryID);

            // Execute the insert statement
            $stmt->execute();

            // Close the statement
            $stmt->close();

            // Display a success message
            echo "Product information has been successfully stored.";

            // Optionally, you can display the product information here
            echo "Product Name: $productName<br>";
            echo "Category: $productCategoryID<br>";
            echo "Description: $productDescription<br>";
            echo "Number Available: $numberAvailable<br>";
            echo "Selling Price: $sellingPrice<br>";
            echo "Details: $details<br>";
        }
    } else {
        // User input is not a valid email address, show an error message
        echo "Invalid email address format. Please enter a valid email address.";
    }
	} 
}
?>
