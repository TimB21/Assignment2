# Assignment2
Assignment 2: Online Trading Post

Instructions

This is a pair programming assignment. You may choose to work with a partner, but must inform me of your choice before beginning to work together. I will then assign you to a group on Moodle so that your submission will be linked. You cannot work with the same partner you worked with on assignment 1!

You may want your submission to consist of more than one PHP file, but this is optional. Regardless, the main start page for your project must be named assignment2.php. You must also submit a MySQL schema as a text file called assignment2DB.sql. These files and any other PHP files that are part of your submission must be downloaded from buzz using FTP and submitted to Moodle. Additionally, one member of your group must put your webpage up on their personal buzz server space and provide me with a link to the page in the comment box on Moodle. Your code must be functional and well-documented (use comments!).

Online Trading Post

This project builds heavily on problems from previous homework assignments. You are encouraged to make use of the schema work done in your previous homework submissions, but may need to revise your old solutions to meet the needs of this project.

This is a pair programming assignment. You have the option of working with one other student in the class on this assignment, but if you had a partner on Assignment 1, you cannot work with that person again. Once you have started working with another student, you must either finish the assignment with that student, or both of you must work independently from then on. Inform me of your partner choice in an email, and I will put you in a group on Moodle so that your submission will be linked. This means that either of you can submit the assignment. However, one of you must host the page on your buzz server account, and mention this link in the Moodle submission comment box. You should work together on the assignment side-by-side, not separately. If you start working with someone, but then "break up" due to personal differences, then you must inform me so that I can unlink you on Moodle, and discuss how each of you will complete the assignment.

One of the most important uses of the internet is for eCommerce, hence the success of sites like Amazon and eBay. Your overall goal for this project is to make a simple online trading post similar to the one here (here's a list of all products in the database: products).

When using and designing the site, keep in mind that it is much simpler than a real site would need to be. For example, there are no passwords for users, so if you know anyone's username (which must be formatted like a valid e-mail address), then you can access their account information and modify it. Also, the site doesn't have any mechanism for completing transactions. All that a user needs to do to buy an item is click the buy button and confirm the purchase. Despite these simplifications, the site does contain many of the features that would be found on sites of this sort, and should therefore be a very useful project.

This assignment is essentially composed of 3 parts:

Design the database
Go through the site and test its functionality. Become familiar with its features and think about the types of tables that need to be in the database to support the site. You can use as many tables as you want as long as they make sense.
Your previous answer to Problem 4 on HW2 might work as a schema for this assignment, but be sure to thoroughly test the site and see what features it has to determine if your schema can support all needed features.
Obviously there needs to be some method for representing products, but be careful what you put into this table. Notice that different people can sell the same product at different prices, and that different details can be associated with each offer.
There also needs to be some method for representing users. Notice that for this site a user is uniquely identified by an e-mail address that doubles as a username. Therefore, you can assume everyone has exactly one e-mail address.
Any user can be a buyer and/or a seller. Information of each type will appear in their account page. This means that in tables that associate with a user, there are different potential roles that the user may be filling. After all, any transaction requires both a buyer and a seller.
Notice that the search field has a drop down box next to it filled with product categories. These need to be stored in the database as well, and each product needs to be associated with one.
Make sure that your schema is properly normalized. Specifically, your schema is expected to be in at least third normal form (3NF).

Create the database and fill it with test data
Create a file called assignment2DB.sql and fill it with all of the CREATE TABLE statements necessary to create the tables in your schema. You will turn this file in on Moodle, but it should not be accessible in any way through the internet.
ALL tables should have a unique, automatically generated primary key column. You should name the column as the name of the table followed by "id". For example, to give a primary key column to a table named "product", you would define the column within your CREATE TABLE statement using:
productid BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY(productid)
Make use of the SQL DATETIME type to store when a purchase was made.
Use the SQL TEXT type to store text fields of unbounded length (from HTML TEXTAREAs).
The file assignment2DB.sql should also contain all of the INSERT statements necessary to fill your database with test data. You must have at least two products of each type of category (Books, Music CDs, and Games). At least one product in each category must have an active seller (there are still copies available), and at least one product in each category must have been purchased by someone.
Once your assignment2DB.sql file is complete, you should actually create the tables and data in MySQL. I will create a database for each student to use on this assignment called <username>_trading, where the <username> is your SU username. From buzz, you can log directly into your database with this console command: 
mysql -p -u <username>_trading "<username>_trading" 
Execute the commands from your assignment2DB.sql inside of MySQL to create the tables and data.

Make the actual website
Helper Functions
When interacting with a website you only see the front end aspects of the page. However, it is helpful to have a library of helper functions, both to make the front-end code simpler, and in case you have any functions that will be used on multiple pages. You could have a file called helper.php that only contains PHP code, and displays a blank white page if it is directly viewed in a browser. However, you can put the PHP command require_once('helper.php'); at the top of any page that you want to make use of these helper functions.
The focus of this class is on databases, so in order to allow you to focus more on the database aspects of the assignment, I will also provide you with some helpful libraries for creating HTML forms and doing form input verification and processing. These libraries are formModel.lib and inputTags.lib. They have a .lib extension so that you can view them through a browser, but you'll want to change the extension to .php so that they will display as blank when loaded on your buzz server. Use require_once to import the code into your pages. You are not required to use these functions, but doing so will save you lots of time and effort. The guidelines below refer to functions in these libraries and tell you where it makes sense to use them. If you use these files (or renamed/modified versions of these files), then you must turn them in as part of your submission to Moodle. I want all files for your project in one place.

Search Page/Home Page
Allows for searching, and gives access to the sell and account information pages.
This page submits back to itself when the search feature is used. You can tokenize the search string using the function getSearchStringComponents() in formModel.lib. You might want to var_dump the return value of this function to see how you can make use of it.
Be sure to filter the search results by product category, unless the user leaves the select box set to "All Categories".
Clicking the product name of any of the search results leads to the product info page for that product. Notice that my site passes information about the product to be viewed directly via the link. For example, the product named "Downward Spiral" has a product id in my database of 6, so the link from this product on the search results page is:
product.php?buymode=yes&productid=6
These variables are being sent using the GET method. The question mark indicates that values are being passed with the link, and each ampersand delimited element is an assignment of a variable to a value. These values are accessible on the receiving page through $_REQUEST, and through the getRequestData() method defined in formModel.lib.

Product Information Page
My implementation overloads this page to handle many different operations. You may want to spread its functionality across multiple pages.
If a user clicks the "Sell Your Product" button on the search page, they are redirected to this page, which in this case will contain a blank form for the user to insert information about the product.
Input validation must be performed on the fields of this page. The e-mail address must be valid (use verifyEmailAddress() in formModel.lib). The product name cannot be empty. The number offered must be an integer (use verifyInteger() in formModel.lib). The price must be a dollar amount (use verifyMoney() in formModel.lib).
After submitting valid product information, the application should search the database using the same function from the search page to see if any products already in the database have a similar name. The user may have inadvertently tried to create a new product when an existing product could be used instead. The user should have the option of selecting from existing products or creating an entirely new product.
After creating a new offer on a product, the user should see the product information page for the given product. This is the exact same page that any user would see if they searched for the item and clicked on its link on the results page. This page contains basic information about the product, as well as a list of all offers to sell the product from various users. These offers should be sorted in increasing order of price. Notice that each offer has a "Buy" button next to it. This button redirects to the account management page.
Notice that the product information page has a "Sell Yours" button that allows the current user to sell a copy of the product being viewed. This redirects to a page identical to the page for creating a new product to sell, except that all product specific information is already filled in and can't be changed.

Account Information Page
Clicking "Your Account" on the search page leads here. The user will first have to specify an e-mail address and indicate whether or not they are a new user with the check box. Use verifyEmailAddress() in formModel.lib to assure the e-mail address is valid, and if the user is a new user, go ahead and add them to the database. Otherwise, retrieve the user's information from the database.
Once this page knows which user it is displaying information for, it should retrieve all purchases made by the user, all sales made by the user and all outstanding offers made by the user. This data is output to the screen in tables.
This page also serves as the purchase confirmation page. When a user clicks a "Buy" button on the product information page, the user should be sent to this page, which asks for an e-mail address to log in with (or create a new account with) so that the purchase can be confirmed. Once the purchase is confirmed, it should be inserted into the database, and the user should be redirected to the basic account information page which will include information on the user's new purchase.
Coding Advice

Get started early, since this is a very complex project. Hopefully, you have a good starting point for your schema from previous homework, but you will need to get to work even earlier if this is not the case. See me for help in office hours if you need to catch up or you had points deducted from your previous HW that you do not understand.

You are strongly encouraged to use my provided formModel.lib and inputTags.lib, and to make an additional helper.php file. Some examples of things you might want to have in this helper file include: a way to get search results that either do or do not fit a certain product category (Use LIKE with % to check for similarity to the search string), a way to select all open offers of a particular product id, a way to select all purchases made by a particular user, and a way to select all sales made by a particular user. You can also make functions that are given a set of results, and then return a string of HTML that puts all of those results into a table. Whatever you do, try to avoid duplicating code whenever possible.

