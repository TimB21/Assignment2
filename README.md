# README for Sport Fanatic Online Trading Post

## Project Overview

The **Sport Fanatic Online Trading Post** is a dynamic PHP-based web application designed to facilitate the buying and selling of sports memorabilia. Leveraging advanced SQL queries and robust database management techniques, the platform allows users to interact with a variety of collectible items such as player cards, autographed items, and other memorabilia. This project is a demonstration of proficiency in building relational databases, crafting optimized SQL queries, and integrating these queries into a functional web application.

Key features of the platform include:

- **Product Search**: Users can search for items using complex filtering criteria.
- **Product Listing**: Sellers can list their products with detailed attributes.
- **Offers and Transactions**: Buyers can view active offers, select products, and make purchases.
- **User Management**: Users can track their purchases and product listings.

---

## Skills Highlighted

### Database Management
- Designed and implemented a normalized MySQL database schema for handling products, offers, and categories.
- Applied advanced SQL queries, including **JOINs**, **subqueries**, and **aggregate functions** to retrieve and manipulate data efficiently.
- Optimized queries to ensure fast performance and scalability, handling complex relationships between multiple tables.
- Ensured data integrity and consistency through the use of foreign keys and data validation rules.

### SQL Query Writing
- Created dynamic and reusable SQL query builders for common operations, such as searching products by keywords and category, managing offers, and retrieving detailed product information.
- Used advanced filtering techniques to enable users to search products based on specific criteria such as category, price range, and product details.
- Designed complex queries for transaction handling and calculating offer totals.

---

## Project Files

### 1. `assignment2.php` - Home Page
The home page serves as the entry point to the application and is the main interface for users:
- **Product Search**: Users can search for products by entering keywords and selecting a category from a dropdown menu.
- **Sell Product**: A link directs users to the `product.php` page to list a new product.

### 2. `helper.php` - Utility Functions
This file includes various helper functions:
- **`showQueryResultInHTML`**: Displays SQL query results in an HTML table, facilitating data presentation.
- **SQL Query Builders**: Modular functions that dynamically construct SQL queries, including searches, insertions, and transactions.

### 3. `product.php` - Product Page
Handles the product details and offer management:
- **Product Information**: Displays detailed product information based on `productid`.
- **Outstanding Offers**: Lists offers and allows buyers to view and select their preferred offers.
- **Product Listing**: Sellers can submit new listings with category, name, description, price, and availability.
- **Data Validation**: Ensures accurate user input and prevents SQL injection and other security vulnerabilities.

---

## Features and Workflow

### Product Search
1. Users enter search keywords and select a category to find relevant products.
2. SQL queries fetch matching products, leveraging **JOINs** to display category names and product details.

### Product Listing
1. Sellers fill out a form to list their items, which includes category selection, description, price, and quantity.
2. Input validation ensures that data adheres to expected formats, preventing errors during insertion.

### Offers and Transactions
1. Buyers view available offers for products and select quantities to purchase.
2. Complex SQL queries track transaction history and inventory updates, ensuring accurate pricing and stock management.

---

# Database Schema for Sports Memorabilia Trading

This schema is designed to support a sports memorabilia trading platform where users can buy and sell various types of memorabilia, such as player cards and autographed items.

## Tables

### 1. **datastate**
Stores the states of records (e.g., ACTIVE, DELETED).
- **Columns:**
  - `datastateid` (TINYINT, Primary Key) - Unique identifier for each state.
  - `name` (VARCHAR(100)) - Name of the state (e.g., "ACTIVE", "DELETED").
  - `ordinal` (REAL) - Arbitrary sorting order for states.

### 2. **User**
Stores user information, including usernames and their current data state.
- **Columns:**
  - `userid` (BIGINT, Primary Key) - Unique identifier for each user.
  - `username` (VARCHAR(255), Unique) - Username of the user.
  - `datastateid` (TINYINT, Foreign Key) - References `datastate(datastateid)`. Indicates the status of the user.

### 3. **Category**
Stores categories of products (e.g., player cards, autographed memorabilia).
- **Columns:**
  - `categoryid` (BIGINT, Primary Key) - Unique identifier for each category.
  - `singular` (VARCHAR(255)) - Singular form of the category (e.g., "Player Card").
  - `plural` (VARCHAR(255)) - Plural form of the category (e.g., "Player Cards").
  - `datastateid` (TINYINT, Foreign Key) - References `datastate(datastateid)`. Indicates the status of the category.

### 4. **Product**
Stores product details, such as name, description, and associated category.
- **Columns:**
  - `productid` (BIGINT, Primary Key) - Unique identifier for each product.
  - `productName` (VARCHAR(255)) - Name of the product (e.g., "Michael Jordan Player Card").
  - `description` (TEXT) - Description of the product.
  - `categoryID` (BIGINT, Foreign Key) - References `Category(categoryid)`. Indicates which category the product belongs to.
  - `datastateid` (TINYINT, Foreign Key) - References `datastate(datastateid)`. Indicates the status of the product.

### 5. **Offer**
Stores offers made by sellers for specific products, including price and availability.
- **Columns:**
  - `offerid` (BIGINT, Primary Key) - Unique identifier for each offer.
  - `username` (VARCHAR(255)) - Username of the seller making the offer.
  - `productID` (BIGINT, Foreign Key) - References `Product(productid)`. Indicates which product the offer is for.
  - `numberOfUnits` (INT) - Number of units available for sale.
  - `unitPrice` (DECIMAL(10, 2)) - Price per unit for the product.
  - `details` (TEXT) - Additional details about the offer.
  - `category` (VARCHAR(255)) - Category of the product being offered.
  - `datastateid` (TINYINT, Foreign Key) - References `datastate(datastateid)`. Indicates the status of the offer.

## Relationships

- A **datastate** can have multiple **Users**, **Categories**, **Products**, and **Offers**.
- A **User** can have multiple **Offers**.
- A **Category** can have multiple **Products**.
- A **Product** can have multiple **Offers**.
- A **Buyer** (identified by `username` in **Offer**) can purchase **Products** through **Offers**.

## Notes
- All tables maintain the `datastateid` column, which references the `datastate` table to track the status (e.g., "ACTIVE", "DELETED") of records.
- The schema supports a sports memorabilia trading platform where users can list products, make offers, and track transactions.


## Setup Instructions

1. **Database Configuration**:
   - Import the schema and data into MySQL.
   - Update PHP files with the correct database connection credentials.

2. **Hosting**:
   - Host the PHP files on a server supporting PHP and MySQL (e.g., Apache or Nginx).
   - Ensure the web server is properly configured to handle requests to `assignment2.php`.

3. **Usage**:
   - Access the platform through the home page (`assignment2.php`) and start exploring available products or listing your own items for sale.

---

## Future Enhancements

1. **User Authentication**: Implement user registration, login, and authentication to secure user data and transactions.
2. **Advanced Search Filters**: Add additional filtering options such as price range, product condition, and date added.
3. **Rating System**: Implement a system for buyers to rate sellers based on transaction quality.

---
