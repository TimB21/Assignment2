CREATE TABLE IF NOT EXISTS datastate (
datastateid TINYINT NOT NULL AUTO_INCREMENT, PRIMARY KEY(datastateid),
name VARCHAR(100) NOT NULL UNIQUE,
ordinal REAL DEFAULT 0.0 -- Allows for an arbitrary sorting order
);

INSERT INTO datastate (name, ordinal) VALUES ('ACTIVE',1); -- id 1
INSERT INTO datastate (name, ordinal) VALUES ('DELETED',2); -- id 2 

-- Create the User table
CREATE TABLE User ( 
	userid BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY(userid),
    username VARCHAR(255) UNIQUE,
	datastateid TINYINT NOT NULL DEFAULT 1, FOREIGN KEY (datastateid) REFERENCES datastate(datastateid)
); 

INSERT INTO User (username, datastateid) VALUES ('Tim', 1); 
INSERT INTO User (username, datastateid) VALUES ('Kyle', 1); 


-- Create the Category table
CREATE TABLE Category (
    categoryid BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY(categoryid),
    singular VARCHAR(255) NOT NULL,
    plural VARCHAR(255) NOT NULL,
	datastateid TINYINT NOT NULL DEFAULT 1, FOREIGN KEY (datastateid) REFERENCES datastate(datastateid)
);  

INSERT INTO Category (singular, plural, datastateid)
VALUES ('Player Card', 'Player Cards', 1);   

INSERT INTO Category (singular, plural, datastateid)
VALUES ('Card Bundle', 'Card Bundles', 1);   

INSERT INTO Category (singular, plural, datastateid)
VALUES ('Autographed Card', 'Autographed Cards', 1);   

INSERT INTO Category (singular, plural, datastateid) 
VALUES('Collectable', 'Collectables', 1); 


-- Create the Product table with a foreign key reference to Category
CREATE TABLE Product (
    productid BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY(productid),
    productName VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    categoryID BIGINT NOT NULL,
    FOREIGN KEY (categoryID) REFERENCES Category(categoryID), 
	datastateid TINYINT NOT NULL DEFAULT 1, FOREIGN KEY (datastateid) REFERENCES datastate(datastateid)
);   

INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Vintage Micheal Jordan Finals Card', 'Vintage Micheal Jordan player card. Brand new in protective casing. Printed after 1996 season with MVP graphic.', 1, 1);

INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Vintage Micheal Jordan Finals Cards', 'Vintage Micheal Jordan player cards. All printed after 1996 season with MVP graphic. They are all brand new packages in individual protective cases', 1, 1);

-- Kobe Bryant Player Card
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Kobe Bryant Rookie Card', 'Kobe Bryant rookie trading card. Pristine condition, great investment for collectors.', 1, 1);

INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Kobe Bryant Rookie Cards', 'Kobe Bryant rookie trading cards. All in Pristine condition, great investment for collectors.', 1, 1);  

-- Create items within the "Card Bundle" category
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('2003 NBA Card Bundles',  'A Bundle of the entire NBA roster assorted in packs by teams. All boxes are unopened and sealed. Great for collectors and enthusiasts.', 2, 1);

-- Create items within the "Card Bundle" category
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('1999 NBA Card Bundles', 'A bundle of the entire NBA roster assorted in packs by teams. All boxes Unopened and sealed. Great for collectors and enthusiasts.', 2, 1);

-- Create items within the "Card Bundle" category
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('2003 NBA Card Bundle', 'A bundle of the entire NBA roster assorted in packs by teams. Unopened and sealed. Great for collectors and enthusiasts.', 2, 1);

-- Create items within the "Card Bundle" category
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('1999 NBA Card Bundle', 'A bundle of the entire NBA roster assorted in packs by teams. Unopened and sealed. Great for collectors and enthusiasts.', 2, 1); 

-- Create items within the "Autographed Card" category
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('LeBron James Autographed Card', 'An autographed trading card featuring LeBron James. A rare and valuable piece for basketball enthusiasts.', 3, 1);

INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Tom Brady Autographed Card', 'An autographed trading card featuring Tom Brady. A must-have for football fans and collectors.', 3, 1);

-- Create items within the "Collectable" category
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Michael Jordan Collectable Figure', 'A collectible action figure of Michael Jordan in his iconic pose. Perfect for basketball fans and collectors.', 4, 1);

INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Soccer Memorabilia Collection', 'A collection of various soccer memorabilia, including jerseys, scarves, and signed soccer balls. Ideal for soccer enthusiasts and collectors.', 4, 1);


-- Michael Irving Player Card
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Michael Irving Autographed Card', 'Michael Irving autographed trading card. A rare find for any football card enthusiast.', 3, 1);
-- Michael Irving Player Card
INSERT INTO Product (productName, description, categoryID, datastateid)
VALUES ('Michael Irving Autographed Cards', 'Michael Irving autographed trading cards. A rare find for any football card enthusiast.', 3, 1);

-- Create the Offer table with foreign key references to User and Product
CREATE TABLE Offer (
    offerid BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY(offerid),
    username VARCHAR(255),
    productID BIGINT NOT NULL, -- seller of purchase
    numberOfUnits INT NOT NULL,
    unitPrice DECIMAL(10, 2) NOT NULL,
    details TEXT NOT NULL,
    category VARCHAR(255) NOT NULL,
    FOREIGN KEY (username) REFERENCES User(username),
    FOREIGN KEY (productID) REFERENCES Product(productID),
	datastateid TINYINT NOT NULL DEFAULT 1, FOREIGN KEY (datastateid) REFERENCES datastate(datastateid)
); 


-- Create the Purchase table with a foreign key reference to Offer
CREATE TABLE Purchase (
    purchaseid BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY(purchaseid),
    offerID BIGINT NOT NULL,
    username VARCHAR(255) NOT NULL, -- buyer for sale
    purchaseTime DATETIME NOT NULL, 
    quantity INT NOT NULL, -- add to schema
    FOREIGN KEY (offerID) REFERENCES Offer(offerID),
	datastateid TINYINT NOT NULL DEFAULT 1, FOREIGN KEY (datastateid) REFERENCES datastate(datastateid)
);



