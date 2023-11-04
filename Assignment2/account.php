<HTML>
<BODY>

<?php
require_once("helper.php");
?>

<CENTER>
<H1>Account Manager</H1> 
<!-- Account Header -->
(So you can track exactly how much money you've spent on crap you don't need)<BR>
<A href="assignment2.php">Search</A>
<BR><BR> 

<?php 
// If the user presses complete purchase
if (isset($_POST['completePurchase'])) {
    // Retrieve the necessary information from the form
    $email = $_POST['emailaddressusername']; // User's email
    $quantity = $_POST['quantity']; // Quantity to purchase
    $productID = $_POST['productID']; // Product ID 
    $offerid = $_POST['offerID']; // Offer ID 
    $purchaseTime = date('Y-m-d H:i:s');

    // Additional query to retrieve the current quantity available for the offer
    $selectQuantityQuery = "SELECT numberOfUnits FROM Offer WHERE offerID = ?";
    
    // Prepare the statement to select the current quantity
    $stmtSelectQuantity = $mysql->prepare($selectQuantityQuery);
    
    if ($stmtSelectQuantity === false) {
        echo "Error preparing the statement for selecting quantity";
    } else {
        // Bind the parameters
        $stmtSelectQuantity->bind_param("i", $offerid);
        
        // Execute the select statement
        $stmtSelectQuantity->execute();
        
        // Bind the result
        $stmtSelectQuantity->bind_result($currentQuantity);
        
        // Close the select statement for quantity
        $stmtSelectQuantity->close();
        
        // Check if there's enough quantity available to purchase
        if ($currentQuantity >= $quantity) {
            // Calculate the updated quantity after purchase
            $newQuantity = $currentQuantity - $quantity;
            
            // Update query for the offer's quantity
            $updateQuantityQuery = "UPDATE Offer SET numberOfUnits = ? WHERE offerID = ?";
            
            // Prepare the statement to update the quantity
            $stmtUpdateQuantity = $mysql->prepare($updateQuantityQuery);
            
            if ($stmtUpdateQuantity === false) {
                echo "Error preparing the statement for updating quantity";
            } else {
                // Bind the parameters
                $stmtUpdateQuantity->bind_param("ii", $newQuantity, $offerid);
                
                // Execute the update statement
                $stmtUpdateQuantity->execute();
                
                // Close the update statement
                $stmtUpdateQuantity->close();
                
                // Insert query for purchase
                $insertPurchaseQuery = "INSERT INTO Purchase (username, offerID, purchaseTime, quantity) 
                                        VALUES (?, ?, ?, ?)";
                
                // Prepare the statement for purchase
                $stmt = $mysql->prepare($insertPurchaseQuery);
                
                if ($stmt === false) {
                    echo "Error preparing the statement for purchase insertion";               
                } else {
                    // Bind the parameters
                    $stmt->bind_param("sisi", $email, $offerid, $purchaseTime, $quantity);
                    
                    // Execute the insert statement for purchase
                    $stmt->execute();
                    
                    // Close the purchase statement
                    $stmt->close();
                    
                    // Display a success message
                    echo "Purchase has been successfully recorded.";
                }
            }
        } else {
            echo "Not enough quantity available to make the purchase.";
        }
    }
}
if (!isset($_POST['buy']) && !isset($_POST['completePurchase'])) { 
?> 
<!-- Main log in form -->
<FORM name="loginform" action="account.php" method="POST">You'll need to provide your e-mail address:<BR><INPUT type="text" size="20" name="emailaddressusername" id="emailaddressusername" value="" maxlength="255"><BR><INPUT type="checkbox" name="newuser" id="newuser" value="new"  > I'm a new user<BR><INPUT type="submit" name="login" id="login" value="Login"></FORM> 

<?php 
// Main form is submitted
if (isset($_POST['login'])) {
    // Get the username from the form
    $userInput = $_POST['emailaddressusername'];

    // Validate user input
    if (verifyEmailAddress($userInput)) { 

        // Retrieve user input (email/username)
        $userInput = $_POST['emailaddressusername'];

        // Condional statement for when the user is new
        if (isset($_POST['newuser']) && $_POST['newuser'] === 'new') {
            // Check if the "New User" radio button is selected      
            $username = $userInput;
        
            // Prepare a select statement to check if the username already exists
            $checkUserQuery = "SELECT username FROM User WHERE username = ?";
            $stmtCheck = $mysql->prepare($checkUserQuery);
        
            if ($stmtCheck === false) {
                echo "Error preparing the statement for checking the username";
            } else {
                // Bind the username parameter
                $stmtCheck->bind_param("s", $userInput);
        
                // Execute the select statement
                if ($stmtCheck->execute()) {
                    $result = $stmtCheck->get_result();

                    if ($result->num_rows === 0) {
                        // User input is a valid email address
                        // Prepare the insert statement  
                        $insertQuery = "INSERT INTO User (username, datastateid) VALUES (?, 1)"; 
                        $stmt = $mysql->prepare($insertQuery); 
                
                        if ($stmt === false) {
                            echo "Error preparing the statement";
                        } else {
                            // Bind the username parameter
                            $stmt->bind_param("s", $userInput);
                
                            // Execute the insert statement
                            if ($stmt->execute()) {
                                // New user created successfully
                                echo "New user created successfully!";
                            } else {
                                // Error occurred while creating a new user
                                echo "Error creating a new user. Please try again.";
                            }
                
                            // Close the statement 
                            $stmt->close();
                        }
                    } else { 
                        echo "You are already in the database silly. Here is your information";
                    }
                    
                    // Close the statement for checking the username
                    $stmtCheck->close();
                } 
            }
        }
        

        // Prepare the SQL statement
        $selectUserQuery = "SELECT * FROM User WHERE username = ?";
        $stmt = $mysql->prepare($selectUserQuery);

        if ($stmt === false) {
            echo "Error preparing the statement";
        } else {
            // Bind the username parameter
            $stmt->bind_param("s", $userInput);

            // Execute the select statement
            $stmt->execute();  

            $result = $stmt->get_result(); 

            if($result->num_rows === 1){ 
                $row = $result->fetch_assoc();
                $title = $row['username'];
            } 
            else { 
                echo "User not found. If you are a new user, please click the I'm a new user box.";
            }  
 
            // Query to retrieve all purchases from user
            $selectPurchasesQuery = "SELECT p.productid, p.productName, c.singular, o.numberOfUnits, o.unitPrice, o.username, Purchase.purchaseTime
            FROM Product p
            JOIN Category c ON p.categoryID = c.categoryID
            JOIN Offer o ON p.productID = o.productID 
            JOIN Purchase ON Purchase.offerID = o.offerID
            WHERE Purchase.username = '$userInput'";

            // Display all purchases
            echo "<h2>Purchases</h2>";
            showQueryResultInHTML($selectPurchasesQuery, "productid", array("productName" => "Product Name", "singular" => "Category", "numberOfUnits" => "# Available", "unitPrice" => "Unit Price", "username" => "Seller", "purchaseTime" => "Purchase Time"), FALSE, NULL, NULL); 
            
            // Query to retrieve all sales from user
             $selectSalesQuery = "SELECT Product.productid, Category.singular, Product.productName, Purchase.quantity, Offer.unitPrice, Purchase.username, Purchase.purchaseTime
             FROM Purchase
             JOIN Offer ON Purchase.offerID = Offer.offerID
             JOIN Product ON Offer.productID = Product.productID
             JOIN Category ON Product.categoryID = Category.categoryID 
             WHERE Offer.username = '$userInput'"; 

             // Display all sales
             echo "<h2>Sales</h2>"; 
             showQueryResultInHTML($selectSalesQuery, "productid", array("productName" => "Product Name", "singular" => "Category", "quantity" => "Quantity", "unitPrice" => "Unit Cost", "username" => "Buyer", "purchaseTime" => "Date"), FALSE, NULL, NULL); 
            
             // Query to retrieve all offers
             $selectOffersQuery = "SELECT p.productid, c.singular, p.productName, p.description, o.numberOfUnits
             FROM Product p
             JOIN Category c ON p.categoryID = c.categoryID
             JOIN Offer o ON p.productID = o.productID
             WHERE o.username = '$userInput'";

            // Display all offers
            echo "<h2>Outstanding Offers</h2>"; 
            showQueryResultInHTML($selectOffersQuery, "productid", array("productName" => "Product Name", "singular" => "Category", "numberOfUnits" => "# Available", "description" => "Details"), FALSE, NULL, NULL); 
        }
    } else {
        // User input is not a valid email address, show an error message
        echo "Invalid email address format. Please enter a valid email address.";
    }
    }  
}
?>

<?php

if (isset($_POST['buy'])) {   
    // Retrieve all product information
    $quantity = $_POST['numberpurchased']; 
    $productID = $_POST['productid'];
    $offerid = $_POST['offerid']; 

    // Query to select the name and price to be put into purchase
	$selectNameAndPriceQuery = "SELECT p.productName, o.unitPrice FROM Product p JOIN Offer o ON p.productID = o.productID WHERE o.productID = $productID;";

    // Execute the SQL query
    $result = $mysql->query($selectNameAndPriceQuery);

    if ($result) {
        // Check if there are rows returned
        if ($result->num_rows > 0) {
            // Fetch the result as an associative array
            $row = $result->fetch_assoc();

            // Access the product name and unit price
            $productName = $row['productName'];
            $unitPrice = $row['unitPrice'];
        } 

    // Free the result set
    $result->free(); 
    ?>
    <FORM name="completePurchase" action="account.php" method="POST">
    You'll need to provide your e-mail address to purchase an Offer:<BR>
    <INPUT type="text" size="20" name="emailaddressusername" id="emailaddressusername" value="" maxlength="255"><BR>
    <INPUT type="checkbox" name="newuser" id="newuser" value="new"  > I'm a new user<BR>

    <!-- Display a confirmation message -->
    Are you sure you want to purchase <?php echo $quantity; ?> of the <?php echo $productName; ?> for <?php echo $unitPrice; ?> per Unit?<BR>

    <INPUT type='hidden' name='quantity' value='<?php echo $quantity; ?>'> 
    <INPUT type='hidden' name='productID' value='<?php echo $productID; ?>'> 
    <INPUT type='hidden' name='offerID' value='<?php echo $offerid; ?>'>
    <INPUT type='submit' name='completePurchase' value='Complete Purchase'>
    </FORM>
<?php
    }   
}

?>

</BODY>
</HTML> 
