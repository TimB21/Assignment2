<HTML>
<BODY>
<CENTER> 
<H1>Product Page</H1>
    (One person's garbage is another person's treasure!)<BR>
    <A href="assignment2.php">Search</A>
    <BR><BR> 
	
<!--Centered header for the Product page -->
<CENTER>

<?php
// Useful functions written by me
require_once("helper.php"); 

if (isset($_GET['productid'])) {
    // Product ID is in the URL, so display the product details
    $productID = $_GET['productid'];

    // Query the database to retrieve product details based on the product ID
    // Replace this with your own SQL query
    $query = "SELECT productid, singular, productName, description
              FROM Product
              JOIN Category ON Product.categoryID = Category.categoryID
			  AND productid = $productID"; 

	showQueryResultInHTML($query, "productid", array("singular" => "Category", "productName" => "Product Name", "description" => "Description"), NULL, NULL, NULL);  

	$selectOffersQuery = "SELECT o.username, p.productid, o.details, o.numberOfUnits
             FROM Product p
             JOIN Category c ON p.categoryID = c.categoryID
             JOIN Offer o ON p.productID = o.productID
             WHERE o.productid = $productID";

        
    echo "<h2>Outstanding Offers</h2>"; 
    showQueryResultInHTML($selectOffersQuery, "productid", array("username" => "Username", "numberOfUnits" => "# Available", "details" => "Details"), FALSE, NULL, NULL);  
	
	// Assuming $productID contains the specific product ID
	$selectOfferIdQuery = "SELECT offerid FROM Offer WHERE productID = $productID";

	// Execute the query
	$result = $mysql->query($selectOfferIdQuery);

	$offerid = '';

	if ($result) {
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$offerid = $row['offerid']; 
			?>  
		<FORM name="buy" action="account.php" method="POST">
		<label for="numberpurchased">Quantity:</label> 
		<input type="text" size="1" name="numberpurchased" id="numberpurchased" value="1" maxlength="5"> 
		<input type="hidden" name="productid" value="<?php echo $productID; ?>">
		<input type="hidden" name="offerid" value="<?php echo $offerid; ?>">
		<button type="submit" name="buy" id="buy">Buy</button>
		</FORM> 
		<?php
		 } 
	} 
} 

if(!isset($_POST['submitnew']) && !isset($_GET['productid'])) {   
	?>
	<CENTER>
    Fill out information about the product you intend to sell<BR>
    <TABLE border="1">
    <FORM name="newproductform" action="product.php" method="POST">
        <TR>
            <TH colspan="2" align="center">Your Information</TH>
        </TR>
        <TR>
            <TD align="right">
                Your E-mail Address
            </TD>
            <TD align="left"><INPUT type="text" size="20" name="emailaddressusername" id="emailaddressusername" value="" maxlength="255"></TD>
        </TR>
		<TR>
		<TD align="right">Category</TD>
		<TD align="left">
			<SELECT name="productcategoryid" id="productcategoryid"><OPTION value="1" >
				Player Card</OPTION><OPTION value="2" >
				Card Bundle</OPTION><OPTION value="3" > 
				Autographed Bundle</OPTION><OPTION value="4" >
				Collectable</OPTION></SELECT>	</TD>
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
		</FORM> 
		</TABLE> 

    </CENTER>
	<?php
}
?> 

<?php 
// Check if the form is submitted
if (isset($_POST['submitnew'])) {
    // Get the user's username from the form
    $userInput = $_POST['emailaddressusername'];

    // Validate user input 
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
			?>
			<table border="1">
				<tr>
					<th colspan="2" class="centered">Product Information</th>
				</tr>
				<tr>
					<td align="right">Email Address:</td>
					<td align="left"><?php echo $email; ?></td>
				</tr>
				<tr>
					<td align="right">Product Name:</td>
					<td align="left"><?php echo $productName; ?></td>
				</tr>
				<tr>
					<td align="right">Category:</td>
					<td align="left"><?php echo $productCategoryID; ?></td>
				</tr>
				<tr>
					<td align="right">Description:</td>
					<td align="left"><?php echo $productDescription; ?></td>
				</tr>
				<tr>
					<td align="right">Number Available:</td>
					<td align="left"><?php echo $numberAvailable; ?></td>
				</tr>
				<tr>
					<td align="right">Selling Price:</td>
					<td align="left"><?php echo $sellingPrice; ?></td>
				</tr>
				<tr>
					<td align="right">Details:</td>
					<td align="left"><?php echo $details; ?></td>
				</tr>
			</table>
			<form method="post" action="product.php">
				<input type="submit" name="confirm" id="confirm" value="All Information is Accurate. Offer Product"> 
				<input type='hidden' name='emailaddressusername' value='<?php echo $email; ?>'>
				<input type='hidden' name='productcategoryid' value='<?php echo $productCategoryID; ?>'>
				<input type='hidden' name='name' value='<?php echo $productName; ?>'>
				<input type='hidden' name='description' value='<?php echo $productDescription; ?>'>
				<input type='hidden' name='numberavailable' value='<?php echo $numberAvailable; ?>'>
				<input type='hidden' name='sellingprice' value='<?php echo $sellingPrice; ?>'>
        		<input type='hidden' name='details' value='<?php echo $details; ?>'>
			</form>
		<?php
		} 
		
		$searchtext = getRequestData("name"); 

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
		

		// Same query as above, but HAVING clause will narrow the search.
		// HAVING clause is required because I am referring to the aliases
		// I defined using the AS keyword
		$query = "SELECT productid, singular, productName, description
				FROM Product
				JOIN Category ON Product.categoryID = Category.categoryID
				WHERE " . implode(" AND ", $searchChecks); 

		showQueryResultInHTML($query, "productid", array("singular" => "Category", "productName" => "Product Name", "description" => "Description"), TRUE, "product.php", "productName"); 
    } else {
        // User input is not a valid email address, show an error message
        echo "Invalid email address format. Please enter a valid email address.";
    } 
	} 
} 

if (isset($_POST['confirm'])) { 
		
	// Retrieve product information from the form 
	$email = $_POST['emailaddressusername'];
	$productCategoryID = $_POST['productcategoryid'];  
	$productName = $_POST['name']; 
	$productDescription = $_POST['description']; 
	$numberAvailable = $_POST['numberavailable'];
	$sellingPrice = $_POST['sellingprice'];
	$details = $_POST['details']; 

	// Construct the SQL query to select the category singular
	$selectCategoryQuery = "SELECT singular FROM Category WHERE categoryID = $productCategoryID";

	// Execute the query
	$result = $mysql->query($selectCategoryQuery);

	$category = '';  

	if ($result) {
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$categorySingular = $row['singular']; 
			$category = $categorySingular;
			echo "Category Singular: " . $categorySingular;
		} else {
			echo "Category not found.";
		}  
	}  

	$productID = $_POST['productid'];
	
	if (productid === 'NEW') {
	// Insert the product information into the database
	$insertNewProductQuery = "INSERT INTO Product (productName, description, categoryID) VALUES (?, ?, ?)";  
	
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
		}
	
		$insertOfferQuery = "INSERT INTO Offer (username, productID, numberOfUnits, unitPrice, details, category) VALUES (?, ?, ?, ?, ?, ?)"; 
		
		$offerstmt = $mysql->prepare($insertOfferQuery);  

		$getLastInsertedIDQuery = "SELECT LAST_INSERT_ID() as last_id";  

		$result = $mysql->query($getLastInsertedIDQuery);
		
		if ($result) {
		$row = $result->fetch_assoc();
        $lastInsertedProductID = $row['last_id']; 

		// Bind the parameters
		$offerstmt->bind_param("siidss", $email, $lastInsertedProductID, $numberAvailable, $sellingPrice, $details, $category); 

		// Execute the insert statement
		$offerstmt->execute(); 

		// Close the statement
		$offerstmt->close();
		}

        // Redirect to the product page using the retrieved product ID
        $productPageURL = "product.php?productid=" . $lastInsertedProductID;
        header("Location: $productPageURL");
        exit;   
		}   
		else {
			// Replace this with your actual database insert query
			$selectedProductID = $_POST['productid'];
			$insertOfferQuery = "INSERT INTO Offer (username, productID, numberOfUnits, unitPrice, details, category) 
								VALUES (?, ?, ?, ?, ?, ?)";
			
			// Prepare the statement
			$stmt = $mysql->prepare($insertOfferQuery);
	
			if ($stmt === false) {
				echo "Error preparing the statement for offer insertion";
			} else {
				$numberOfUnits = 1; // You can adjust this as needed
	
				// Bind the parameters
				$stmt->bind_param("siiiss", $email, $selectedProductID, $numberOfUnits, $sellingPrice, $details, $productCategoryID);
	
				// Execute the insert statement
				$stmt->execute();
	
				// Close the statement
				$stmt->close();
	
				// Display a success message
				echo "Offer has been successfully recorded for the selected product.";
			}
		}
}


?> 
 </CENTER>
</BODY>
</HTML>