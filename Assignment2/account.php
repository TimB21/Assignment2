<HTML>
<BODY>

<?php
// Useful functions written by me
require_once("helper.php");
?>

<CENTER>
<H1>Account Manager</H1>
(So you can track exactly how much money you've spent on crap you don't need)<BR>
<A href="assignment2.php">Search</A>
<BR><BR> 

<?php
if (isset($_POST['completePurchase'])) {
        // Retrieve the necessary information from the form
        $email = $_POST['emailaddressusername']; // User's email
        $quantity = $_POST['quantity']; // Quantity to purchase
        $productID = $_POST['productID']; // Product ID 
        $offerid = $_POST['offerID']; // Offer ID 
        $purchaseTime = date('Y-m-d H:i:s');
    
        // At this point, you have the required information to record the purchase
        // You can write the SQL query to insert the purchase record into your database
        // Replace the following query with your actual query
    
        $insertPurchaseQuery = "INSERT INTO Purchase (username, offerID, purchaseTime, quantity) 
                                VALUES (?, ?, ?, ?)";
        
        // Prepare the statement
        $stmt = $mysql->prepare($insertPurchaseQuery);
    
        if ($stmt === false) {
            echo "Error preparing the statement for purchase insertion";
        } else {
            // Bind the parameters
            $stmt->bind_param("sisi", $email, $offerid, $purchaseTime, $quantity);
    
            // Execute the insert statement
            $stmt->execute();
    
            // Close the statement
            $stmt->close();
    
            // Display a success message
            echo "Purchase has been successfully recorded.";
        }
}
if (!isset($_POST['buy']) && !isset($_POST['completePurchase'])) { 
?>
<FORM name="loginform" action="account.php" method="POST">You'll need to provide your e-mail address:<BR><INPUT type="text" size="20" name="emailaddressusername" id="emailaddressusername" value="" maxlength="255"><BR><INPUT type="checkbox" name="newuser" id="newuser" value="new"  > I'm a new user<BR><INPUT type="submit" name="login" id="login" value="Login"></FORM> 

<?php
if (isset($_POST['login'])) {
    // Get the user's email address or username from the form
    $userInput = $_POST['emailaddressusername'];

    // Validate user input
    if (verifyEmailAddress($userInput)) {
        // User input is a valid email address  

        // Retrieve user input (email/username)
        $userInput = $_POST['emailaddressusername'];

        if (isset($_POST['newuser']) && $_POST['newuser'] === 'new') {
            // Check if the "New User" radio button is selected      
            $username = $userInput;
            
            // Validate user input
            if (verifyEmailAddress($userInput)) {
                // User input is a valid email address
                // Prepare the INSERT statement
                $insertQuery = "INSERT INTO User (username, datastateid) VALUES (?, 1)"; 
                $stmt = $mysql->prepare($insertQuery);
        
                if ($stmt === false) {
                    echo "Error preparing the statement";
                } else {
                    // Bind the username parameter
                    $stmt->bind_param("s", $userInput);
        
                    // Execute the INSERT statement
                    if ($stmt->execute()) {
                        // New user created successfully
                        echo "New user created successfully!";
                    } else {
                        // Error occurred while creating a new user
                        echo "Error creating a new user. Please try again.";
                    }
        
                    // Close the statement and the database connection
                    $stmt->close();
                }
            } else {
                // User input is not a valid email address, show an error message
                echo "Invalid email address format. Please enter a valid email address.";
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
 

            $selectPurchasesQuery = "SELECT p.productid, p.productName, c.singular, o.numberOfUnits, o.unitPrice, o.username, Purchase.purchaseTime
            FROM Product p
            JOIN Category c ON p.categoryID = c.categoryID
            JOIN Offer o ON p.productID = o.productID 
            JOIN Purchase ON Purchase.offerID = o.offerID
            WHERE Purchase.username = '$userInput'";

            echo "<h2>Purchases</h2>";
            showQueryResultInHTML($selectPurchasesQuery, "productid", array("productName" => "Product Name", "singular" => "Category", "numberOfUnits" => "# Available", "unitPrice" => "Unit Price", "username" => "Seller", "purchaseTime" => "Purchase Time"), FALSE, NULL, NULL); 

             $selectSalesQuery = "SELECT Product.productid, Category.singular, Product.productName, Purchase.quantity, Offer.unitPrice, Purchase.username, Purchase.purchaseTime
             FROM Purchase
             JOIN Offer ON Purchase.offerID = Offer.offerID
             JOIN Product ON Offer.productID = Product.productID
             JOIN Category ON Product.categoryID = Category.categoryID 
             WHERE Offer.username = '$userInput'"; 

             // Fetch and display the results for sales
             echo "<h2>Sales</h2>"; 
             showQueryResultInHTML($selectSalesQuery, "productid", array("productName" => "Product Name", "singular" => "Category", "quantity" => "Quantity", "unitPrice" => "Unit Cost", "username" => "Buyer", "purchaseTime" => "Date"), FALSE, NULL, NULL); 
 
             $selectOffersQuery = "SELECT p.productid, c.singular, p.productName, p.description, o.numberOfUnits
             FROM Product p
             JOIN Category c ON p.categoryID = c.categoryID
             JOIN Offer o ON p.productID = o.productID
             WHERE o.username = '$userInput'";

        
            echo "<h2>Outstanding Offers</h2>"; 
            showQueryResultInHTML($selectOffersQuery, "productid", array("productName" => "Product Name", "singular" => "Category", "numberOfUnits" => "# Available", "description" => "Details"), FALSE, NULL, NULL); 
        }
    } else {
        // User input is not a valid email address, show an error message
        echo "Invalid email address format. Please enter a valid email address.";
    }
} 
?>

<?php
if (isset($_POST['newuser'])) {
    // Check if the "New User" radio button is selected

    // Retrieve user input (email/username)
    $userInput = $_POST['emailaddressusername'];

    // Validate user input
    if (verifyEmailAddress($userInput)) {
        // User input is a valid email address

        // Prepare the INSERT statement
        $insertQuery = "INSERT INTO User (username, datastateid) VALUES (?, 1)";
        $stmt = $mysql->prepare($insertQuery);

        if ($stmt === false) {
            echo "Error preparing the statement";
        } else {
            // Bind the username parameter
            $stmt->bind_param("s", $userInput);

            // Execute the INSERT statement
            if ($stmt->execute()) {
                // New user created successfully
                echo "New user created successfully!";
            } else {
                // Error occurred while creating a new user
                echo "Error creating a new user. Please try again.";
            }

            // Close the statement and the database connection
            $stmt->close();
        }
    } else {
        // User input is not a valid email address, show an error message
        echo "Invalid email address format. Please enter a valid email address.";
    }
}
}

if (isset($_POST['buy'])) {  
    $quantity = $_POST['numberpurchased']; // Assuming this is the input field for quantity
    $productID = $_POST['productid']; // Assuming this is how you pass the product ID
    $offerid = $_POST['offerid']; // Assuming this is the input field for quantity

    // Construct the SQL query to select the category singular
	$selectCategoryQuery = "SELECT p.productName, o.unitPrice FROM Product p JOIN Offer o ON p.productID = o.productID WHERE o.productID = $productID;";

    // Execute the SQL query
    $result = $mysql->query($selectCategoryQuery);

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
