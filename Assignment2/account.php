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
                echo "User not found. If you are a new user, please click the I'm a new user box."
            }  

            $selectPurchasesQuery = "SELECT * FROM Purchase WHERE username = ?";  

            $purchasestmt = $mysql->prepare($selectPurchasesQuery);
	
            // Ensures that the statement is properly prepared
            if ($purchasestmt === false) {
                echo "Error preparing the statement";
            } else { 
                // Bind the songid parameter
                $purchasestmt->bind_param("s", $userInput); 
                // Executes select statement 
                $purchasestmt->execute(); 
            } 

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

            $selectOffersQuery = "SELECT * FROM Offer WHERE username = ?";
            $offersstmt = $mysql->prepare($selectOffersQuery);

            // Ensure the statement is properly prepared
            if ($offersstmt === false) {
                echo "Error preparing the statement for offers";
            } else {
                // Bind the username parameter
                $offersstmt->bind_param("s", $userInput);
                // Execute the select statement
                $offersstmt->execute();
            } 

            // Fetch and display the results for purchases
            echo "<h2>Purchases:</h2>";
            $result = $purchasestmt->get_result();
            if ($result->num_rows === 0) {
                echo "No purchases found.";
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo "Purchase ID: " . $row['purchaseid'] . "<br>";
                    // Check if any field is empty
                    if (empty($row['field1']) || empty($row['field2']) || empty($row['field3'])) {
                        echo "This purchase has missing information.<br>";
                    } else {
                        // Display other purchase details as needed
                    }
                }
            }

            // Fetch and display the results for sales
            echo "<h2>Sales:</h2>";
            $result = $salesstmt->get_result();
            if ($result->num_rows === 0) {
                echo "No sales found.";
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo "Offer ID: " . $row['offerid'] . "<br>";
                    // Check if any field is empty
                    if (empty($row['field1']) || empty($row['field2']) || empty($row['field3'])) {
                        echo "This sale has missing information.<br>";
                    } else {
                        // Display other sales details as needed
                    }
                }
            }

            // Fetch and display the results for offers
            echo "<h2>Outstanding Offers:</h2>";
            $result = $offersstmt->get_result();
            if ($result->num_rows === 0) {
                echo "No outstanding offers found.";
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo "Offer ID: " . $row['offerid'] . "<br>";
                    // Check if any field is empty
                    if (empty($row['field1']) || empty($row['field2']) || empty($row['field3'])) {
                        echo "This offer has missing information.<br>";
                    } else {
                        // Display other offer details as needed
                    }
                }
            }

            // Close the statement
            $stmt->close(); 
            $purchasestmt->close();
            $salesstmt->close();
            $offersstmt->close(); 
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
            $connection->close();
        }
    } else {
        // User input is not a valid email address, show an error message
        echo "Invalid email address format. Please enter a valid email address.";
    }
}
?>



</BODY>
</HTML> 
