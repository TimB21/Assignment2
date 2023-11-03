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
            WHERE o.username = '$userInput'";

            echo "<h2>Purchases</h2>";
            showQueryResultInHTML($selectPurchasesQuery, "productid", array("productName" => "Product Name", "singular" => "Category", "numberOfUnits" => "# Available", "unitPrice" => "Unit Price", "username" => "Seller", "purchaseTime" => "Purchase Time"), FALSE, NULL, NULL); 

            $selectSalesQuery = "SELECT * FROM Offer WHERE username = ? AND Offer.offerID IN (SELECT offerID FROM Purchase WHERE username = ?)";
            $salesstmt = $mysql->prepare($selectSalesQuery);

            // Ensure the statement is properly prepared
            if ($salesstmt === false) {
                echo "Error preparing the statement for sales";
            } else {
                // Bind the username parameter
                $salesstmt->bind_param("ss", $userInput, $userInput);
                // Execute the select statement
                $salesstmt->execute(); 
            } 

             // Fetch and display the results for sales
             echo "<h2>Sales</h2>";
             $result = $salesstmt->get_result();
             if ($result->num_rows === 0) {
                 echo "No sales found.";
             } else {
                 "<TABLE border='1'>";
                 "<TR>
                 <TH>Name</TH>
                 <TH>Category</TH>
                 <TH>Quantity</TH>
                 <TH>Unit Cost</TH>
                 <TH>Buyer</TH>
                 <TH>Date</TH>
                 </TR>";
                 while ($row = $result->fetch_assoc()) {
                 echo "<TR>";
                 echo "<TD>" . $row['productName'] . "</TD>";
                 echo "<TD>" . $row['singular'] . "</TD>";
                 echo "<TD>" . $row['quantity'] . "</TD>";
                 echo "<TD>" . $row['unitprice'] . "</TD>";
                 echo "<TD>" . $row['username'] . "</TD>";
                 echo "<TD>" . $row['purchaseTime'] . "</TD>";
                 echo "</TR>";
             }
             echo "</TABLE>";
             } 

             $salesstmt->close(); 
 
             $selectOffersQuery = "SELECT p.productid, c.singular, p.productName, p.description, o.numberOfUnits
             FROM Product p
             JOIN Category c ON p.categoryID = c.categoryID
             JOIN Offer o ON p.productID = o.productID
             WHERE o.username = '$userInput'";

        
            echo "<h2>Outstanding Offers</h2>"; 
            showQueryResultInHTML($selectOffersQuery, "productid", array("productName" => "Product Name", "singular" => "Category", "numberOfUnits" => "# Available", "description" => "Details"), FALSE, "product.php", "productName"); 
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
?>




</BODY>
</HTML> 
