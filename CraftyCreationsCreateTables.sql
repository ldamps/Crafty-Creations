/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS `CraftyCreationsDatabase`;

CREATE DATABASE `CraftyCreationsDatabase`;

USE `CraftyCreationsDatabase`;


-- Dump of table Customer
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `Customer`;

CREATE TABLE `Customer` (
  `CustomerID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(45) NOT NULL,
  `FirstName` varchar(45) NOT NULL,
  `LastName` varchar(45) NOT NULL,
  `PhoneNumber` int(11) NOT NULL,
  `EmailAddress` varchar(45) NOT NULL,
  `Password` varchar(60) NOT NULL,
  PRIMARY KEY (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `Customer` WRITE;
/*!40000 ALTER TABLE `Customer` DISABLE KEYS */;

INSERT INTO `Customer` (`Title`, `FirstName`, `LastName`, `PhoneNumber`, `EmailAddress`, `Password`)
VALUES
    ('Mr', 'Fred', 'Smith', 123, 'fred.smith@gmail.com', 'password'),
    ('Mr', 'John', 'Doe', 101, 'john.doe101@gmail.com', 'password'),
	('Ms', 'Jane', 'Doe', 102, 'jane.doe102@gmail.com', 'password'),
	('Mr', 'Tom', 'Jones', 103, 'tom.jones103@gmail.com', 'password'),
	('Ms', 'Anna', 'Brown', 104, 'anna.brown104@gmail.com', 'password'),
	('Mr', 'Peter', 'White', 105, 'peter.white105@gmail.com', 'password'),
	('Ms', 'Emily', 'Clark', 106, 'emily.clark106@gmail.com', 'password'),
	('Mr', 'James', 'Taylor', 107, 'james.taylor107@gmail.com', 'password'),
	('Ms', 'Sophia', 'Adams', 108, 'sophia.adams108@gmail.com', 'password'),
	('Mr', 'Chris', 'Evans', 109, 'chris.evans109@gmail.com', 'password'),
	('Ms', 'Olivia', 'Green', 110, 'olivia.green110@gmail.com', 'password'),
	('Mr', 'David', 'Harris', 111, 'david.harris111@gmail.com', 'password'),
	('Ms', 'Lily', 'Carter', 112, 'lily.carter112@gmail.com', 'password'),
	('Mr', 'Henry', 'Scott', 113, 'henry.scott113@gmail.com', 'password'),
	('Ms', 'Mia', 'Mitchell', 114, 'mia.mitchell114@gmail.com', 'password'),
	('Mr', 'William', 'Hall', 115, 'william.hall115@gmail.com', 'password'),
	('Ms', 'Ava', 'Turner', 116, 'ava.turner116@gmail.com', 'password'),
	('Mr', 'Luke', 'King', 117, 'luke.king117@gmail.com', 'password'),
	('Ms', 'Ella', 'Reed', 118, 'ella.reed118@gmail.com', 'password'),
	('Mr', 'Daniel', 'Wright', 119, 'daniel.wright119@gmail.com', 'password'),
	('Ms', 'Grace', 'Collins', 120, 'grace.collins120@gmail.com', 'password'),
	('Mr', 'Samuel', 'Cook', 121, 'samuel.cook121@gmail.com', 'password'),
	('Ms', 'Nora', 'Lewis', 122, 'nora.lewis122@gmail.com', 'password'),
	('Mr', 'Jack', 'Wilson', 123, 'jack.wilson123@gmail.com', 'password'),
	('Ms', 'Ruby', 'Morgan', 124, 'ruby.morgan124@gmail.com', 'password'),
	('Mr', 'Adam', 'Bell', 125, 'adam.bell125@gmail.com', 'password'),
	('Ms', 'Eva', 'Rivera', 126, 'eva.rivera126@gmail.com', 'password'),
	('Mr', 'Noah', 'Hughes', 127, 'noah.hughes127@gmail.com', 'password'),
	('Ms', 'Chloe', 'Ward', 128, 'chloe.ward128@gmail.com', 'password'),
	('Mr', 'Liam', 'Perry', 129, 'liam.perry129@gmail.com', 'password'),
	('Ms', 'Isabella', 'Cruz', 130, 'isabella.cruz130@gmail.com', 'password'),
	('Mr', 'Owen', 'Hayes', 131, 'owen.hayes131@gmail.com', 'password'),
	('Ms', 'Hannah', 'Foster', 132, 'hannah.foster132@gmail.com', 'password'),
	('Mr', 'Isaac', 'Bailey', 133, 'isaac.bailey133@gmail.com', 'password'),
	('Ms', 'Luna', 'Wells', 134, 'luna.wells134@gmail.com', 'password'),
	('Mr', 'Charles', 'Cole', 135, 'charles.cole135@gmail.com', 'password'),
	('Ms', 'Emma', 'Ramirez', 136, 'emma.ramirez136@gmail.com', 'password'),
	('Mr', 'Thomas', 'Reyes', 137, 'thomas.reyes137@gmail.com', 'password'),
	('Ms', 'Violet', 'Powell', 138, 'violet.powell138@gmail.com', 'password'),
	('Mr', 'George', 'Long', 139, 'george.long139@gmail.com', 'password'),
	('Ms', 'Sofia', 'Jenkins', 140, 'sofia.jenkins140@gmail.com', 'password'),
	('Mr', 'Oliver', 'Griffin', 141, 'oliver.griffin141@gmail.com', 'password'),
	('Ms', 'Camila', 'Kelly', 142, 'camila.kelly142@gmail.com', 'password'),
	('Mr', 'Jacob', 'Howard', 143, 'jacob.howard143@gmail.com', 'password'),
	('Ms', 'Madeline', 'Brooks', 144, 'madeline.brooks144@gmail.com', 'password'),
	('Mr', 'Nathan', 'Peterson', 145, 'nathan.peterson145@gmail.com', 'password'),
	('Ms', 'Zara', 'Fisher', 146, 'zara.fisher146@gmail.com', 'password'),
	('Mr', 'Ryan', 'Sanders', 147, 'ryan.sanders147@gmail.com', 'password'),
	('Ms', 'Abigail', 'Price', 148, 'abigail.price148@gmail.com', 'password'),
	('Mr', 'Andrew', 'Murray', 149, 'andrew.murray149@gmail.com', 'password'),
	('Ms', 'Leah', 'Rogers', 150, 'leah.rogers150@gmail.com', 'password'),
	('Mr', 'Kevin', 'Watson', 151, 'kevin.watson151@gmail.com', 'password'),
	('Ms', 'Scarlett', 'Barnes', 152, 'scarlett.barnes152@gmail.com', 'password'),
	('Mr', 'Sebastian', 'Reed', 153, 'sebastian.reed153@gmail.com', 'password'),
	('Ms', 'Zoe', 'Patterson', 154, 'zoe.patterson154@gmail.com', 'password'),
	('Mr', 'Aaron', 'Hunter', 155, 'aaron.hunter155@gmail.com', 'password'),
	('Ms', 'Victoria', 'Ramos', 156, 'victoria.ramos156@gmail.com', 'password'),
	('Mr', 'Ethan', 'Gutierrez', 157, 'ethan.gutierrez157@gmail.com', 'password'),
	('Ms', 'Molly', 'Owens', 158, 'molly.owens158@gmail.com', 'password'),
	('Mr', 'Connor', 'Chavez', 159, 'connor.chavez159@gmail.com', 'password'),
	('Ms', 'Lillian', 'Flores', 160, 'lillian.flores160@gmail.com', 'password'),
	('Mr', 'Eric', 'Austin', 161, 'eric.austin161@gmail.com', 'password'),
	('Ms', 'Nina', 'Mendoza', 162, 'nina.mendoza162@gmail.com', 'password'),
	('Mr', 'Patrick', 'Tucker', 163, 'patrick.tucker163@gmail.com', 'password'),
	('Ms', 'Amelia', 'Gonzalez', 164, 'amelia.gonzalez164@gmail.com', 'password'),
	('Mr', 'Richard', 'Porter', 165, 'richard.porter165@gmail.com', 'password'),
	('Ms', 'Layla', 'Simmons', 166, 'layla.simmons166@gmail.com', 'password'),
	('Mr', 'Arthur', 'Bryant', 167, 'arthur.bryant167@gmail.com', 'password'),
	('Ms', 'Elena', 'Jensen', 168, 'elena.jensen168@gmail.com', 'password'),
	('Mr', 'Victor', 'Myers', 169, 'victor.myers169@gmail.com', 'password'),
	('Ms', 'Clara', 'Lawson', 170, 'clara.lawson170@gmail.com', 'password'),
	('Mr', 'Martin', 'Page', 171, 'martin.page171@gmail.com', 'password');
    

/*!40000 ALTER TABLE `Customer` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table CustomerAddress
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `CustomerAddress`;

CREATE TABLE `CustomerAddress` (
  `Customer_CustomerID` int(11) NOT NULL,
  `HouseNumber` varchar(45) NOT NULL,
  `StreetName` varchar(45) NOT NULL,
  `City` varchar(45) NOT NULL,
  `Postcode` varchar(45) NOT NULL,
  PRIMARY KEY (`Customer_CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `CustomerAddress` WRITE;
/*!40000 ALTER TABLE `CustomerAddress` DISABLE KEYS */;

INSERT INTO `CustomerAddress` (`Customer_CustomerID`, `HouseNumber`, `StreetName`, `City`, `Postcode`)
VALUES
    (1, '123', 'Bond Street', 'Dundee', 'DD4 6XE');

/*!40000 ALTER TABLE `CustomerAddress` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table PaymentMethods
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `PaymentMethods`;

CREATE TABLE `PaymentMethods` (
  `Customer_CustomerID` int(11) NOT NULL,
  `CardNumber` int(11) NOT NULL,
  `CVV` int(3) NOT NULL,
  `ExpiryDate` date NOT NULL,
  PRIMARY KEY (`Customer_CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `PaymentMethods` WRITE;
/*!40000 ALTER TABLE `PaymentMethods` DISABLE KEYS */;

INSERT INTO `PaymentMethods` (`Customer_CustomerID`, `CardNumber`, `CVV`, `ExpiryDate`)
VALUES
    (1, 1234567812345678, 123, '2025-12-31');

/*!40000 ALTER TABLE `PaymentMethods` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table ShopPurchase
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `ShopPurchase`;

CREATE TABLE `ShopPurchase` (
  `PurchaseID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `Price` float NOT NULL,
  `PurchaseDate` date NOT NULL,
  `Customer_CustomerID` int(11) NOT NULL,
  `Shop_ShopID` int(11) NOT NULL,
  FOREIGN KEY (`Customer_CustomerID`) REFERENCES `Customer`(`CustomerID`),
  FOREIGN KEY (`Shop_ShopID`) REFERENCES `Shop`(`ShopID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `ShopPurchase` WRITE;
/*!40000 ALTER TABLE `ShopPurchase` DISABLE KEYS */;

INSERT INTO `ShopPurchase` (`Price`, `PurchaseDate`, `Customer_CustomerID`, `Shop_ShopID`)
VALUES
    (199.99, '2024-11-01', 1, 1);

/*!40000 ALTER TABLE `ShopPurchase` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table ShopPurchase_has_Product
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `ShopPurchase_has_Product`;

CREATE TABLE `ShopPurchase_has_Product` (
  `Purchase_PurachseID` int(11) NOT NULL,
  `Product_ProductID` int(11) NOT NULL,
  PRIMARY KEY (`Purchase_PurachseID`, `Product_ProductID`),
  FOREIGN KEY (`Purchase_PurachseID`) REFERENCES `ShopPurchase`(`PurchaseID`),
  FOREIGN KEY (`Product_ProductID`) REFERENCES `Product`(`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `ShopPurchase_has_Product` WRITE;
/*!40000 ALTER TABLE `ShopPurchase_has_Product` DISABLE KEYS */;

INSERT INTO `ShopPurchase_has_Product` (`Purchase_PurachseID`, `Product_ProductID`)
VALUES
    (1, 1);

/*!40000 ALTER TABLE `ShopPurchase_has_Product` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table ShopReturn
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `ShopReturn`;

CREATE TABLE `ShopReturn` (
  `ShopReturnID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `AmountToReturn` float NOT NULL,
  `Reason` varchar(45) NOT NULL,
  `ShopPurchase_PurchaseID` int(11) NOT NULL,
  FOREIGN KEY (`ShopPurchase_PurchaseID`) REFERENCES `ShopPurchase`(`PurchaseID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `ShopReturn` WRITE;
/*!40000 ALTER TABLE `ShopReturn` DISABLE KEYS */;

INSERT INTO `ShopReturn` (`AmountToReturn`, `Reason`, `ShopPurchase_PurchaseID`)
VALUES
    (50.00, 'Defective item', 1),
    (100.00, 'Incorrect size', 2),
    (20.00, 'Changed mind', 3);

/*!40000 ALTER TABLE `ShopReturn` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table Shop
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `Shop`;

CREATE TABLE `Shop` (
  `ShopID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `StreetName` varchar(45) NOT NULL,
  `City` varchar(45) NOT NULL,
  `Postcode` varchar(45) NOT NULL,
  `NumEmployees` int(11) NOT NULL,
  `TotalSales` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `Shop` WRITE;
/*!40000 ALTER TABLE `Shop` DISABLE KEYS */;

INSERT INTO `Shop` (`StreetName`, `City`, `Postcode`, `NumEmployees`, `TotalSales`)
VALUES
    ('High Street', 'London', 'W1A 1LA', 23, 1000.00);

/*!40000 ALTER TABLE `Shop` ENABLE KEYS */;

UNLOCK TABLES;


-- Dump of table Product
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `Product`;

CREATE TABLE `Product` (
  `ProductID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `Type` varchar(45) NOT NULL,
  `ProductName` varchar(45) NOT NULL,
  `Price` float NOT NULL,
  `ProductDescription` varchar(60),
  `Brand` varchar(45)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `Product` WRITE;
/*!40000 ALTER TABLE `Product` DISABLE KEYS */;

INSERT INTO `Product` (`Type`, `ProductName`, `Price`, `ProductDescription`, `Brand`)
VALUES
    ('Yarn', 'BlueYarn', 5.99, 'Best Quality Yearn', 'ArtWorld'),
     ('Yarn', 'RedYarn', 6.49, 'High-quality yarn for knitting', 'KnitterPro'),
    ('Yarn', 'GreenYarn', 5.79, 'Durable and soft yarn', 'ArtWorld'),
    ('Yarn', 'YellowYarn', 6.99, 'Vibrant color yarn for crochet', 'WeaverHub'),
    ('Yarn', 'SoftWhiteYarn', 7.29, 'Premium cotton yarn', 'FiberWorks'),
    ('Yarn', 'PinkYarn', 5.50, 'Smooth textured yarn', 'ArtCraft'),
    ('Yarn', 'BrownYarn', 5.99, 'Eco-friendly organic yarn', 'NatureSpin'),
    ('Yarn', 'BlackYarn', 6.20, 'Versatile dark yarn', 'CraftArt'),
    ('Yarn', 'PurpleYarn', 6.10, 'Rich color yarn', 'ColorThreads'),
    ('Yarn', 'GrayYarn', 6.50, 'High strength yarn', 'KnitterPro'),
    ('Yarn', 'OrangeYarn', 5.95, 'Perfect for weaving projects', 'WeaverHub'),
    
    ('Paint', 'AcrylicRed', 3.99, 'Bright red acrylic paint', 'PaintMaster'),
    ('Paint', 'OilGreen', 4.59, 'High-quality oil paint', 'ArtWorld'),
    ('Paint', 'WatercolorBlue', 2.99, 'Smooth watercolor paint', 'ColorSplash'),
    ('Paint', 'AcrylicYellow', 3.50, 'Sunshine yellow acrylic', 'ArtStudio'),
    ('Paint', 'OilBlack', 4.79, 'Deep black oil paint', 'PaintMaster'),
    ('Paint', 'WatercolorPink', 3.20, 'Soft pink watercolor', 'ArtCraft'),
    ('Paint', 'AcrylicWhite', 3.10, 'Pure white acrylic paint', 'FineBrush'),
    ('Paint', 'OilOrange', 4.99, 'Warm orange oil paint', 'ArtWorld'),
    ('Paint', 'WatercolorGreen', 2.79, 'Forest green watercolor', 'ColorSplash'),
    ('Paint', 'AcrylicBlue', 3.49, 'Deep ocean blue acrylic', 'ArtStudio'),
    
    ('Tools', 'PaintBrushSet', 8.99, 'Set of high-quality brushes', 'BrushMasters'),
    ('Tools', 'PaletteKnife', 2.50, 'Flexible palette knife', 'CraftGear'),
    ('Tools', 'CanvasStand', 15.99, 'Adjustable canvas stand', 'ArtWorld'),
    ('Tools', 'DetailBrush', 4.99, 'Brush for detailed work', 'FineBrush'),
    ('Tools', 'MixingPalette', 3.20, 'Plastic mixing palette', 'PaintPro'),
    ('Tools', 'EaselWood', 12.50, 'Wooden easel for artists', 'ArtCraft'),
    ('Tools', 'CuttingMat', 9.99, 'Durable cutting mat', 'ArtWorld'),
    ('Tools', 'GlueGun', 6.75, 'Mini hot glue gun', 'CraftGear'),
    ('Tools', 'MeasuringTape', 2.25, 'Retractable measuring tape', 'ToolPro'),
    ('Tools', 'CraftScissors', 3.99, 'Precision craft scissors', 'CraftWorks'),

    ('Yarn', 'BlueMixYarn', 6.89, 'Soft blue mix yarn', 'KnitterPro'),
    ('Yarn', 'BeigeYarn', 5.60, 'Neutral color yarn', 'FiberWorks'),
    ('Yarn', 'LavenderYarn', 6.10, 'Delicate lavender yarn', 'WeaverHub'),
    ('Yarn', 'TealYarn', 5.95, 'Unique teal color yarn', 'ArtCraft'),
    ('Yarn', 'DarkGreenYarn', 6.45, 'Deep green yarn', 'NatureSpin'),
    ('Yarn', 'MaroonYarn', 6.70, 'Elegant maroon yarn', 'ColorThreads'),
    ('Yarn', 'GoldenYarn', 7.50, 'Premium gold yarn', 'WeaverHub'),
    ('Yarn', 'SilverYarn', 7.20, 'Shiny silver yarn', 'FiberWorks'),
    ('Yarn', 'RustYarn', 6.30, 'Rustic yarn for crafts', 'CraftArt'),
    ('Yarn', 'SkyBlueYarn', 5.80, 'Light sky blue yarn', 'ArtWorld'),

    ('Paint', 'AcrylicGreen', 3.89, 'Bright green acrylic paint', 'PaintMaster'),
    ('Paint', 'OilBlue', 4.99, 'Rich blue oil paint', 'ArtStudio'),
    ('Paint', 'WatercolorYellow', 3.29, 'Vibrant yellow watercolor', 'ColorSplash'),
    ('Paint', 'AcrylicBlack', 3.60, 'Solid black acrylic', 'FineBrush'),
    ('Paint', 'OilRed', 4.80, 'Bright red oil paint', 'PaintMaster'),
    ('Paint', 'WatercolorBrown', 3.40, 'Natural brown watercolor', 'ArtCraft'),
    ('Paint', 'AcrylicPurple', 3.75, 'Royal purple acrylic paint', 'ArtWorld'),
    ('Paint', 'OilWhite', 4.20, 'Classic white oil paint', 'FineBrush'),
    ('Paint', 'WatercolorOrange', 2.89, 'Warm orange watercolor', 'ColorSplash'),
    ('Paint', 'AcrylicPink', 3.49, 'Soft pink acrylic paint', 'ArtStudio'),

    ('Tools', 'RollerBrush', 5.99, 'Foam roller brush', 'CraftGear'),
    ('Tools', 'StencilSet', 4.50, 'Artistic stencil set', 'StencilWorks'),
    ('Tools', 'CraftKnife', 3.75, 'Precision craft knife', 'ToolPro'),
    ('Tools', 'GlueStickPack', 2.99, 'Pack of glue sticks', 'CraftWorks'),
    ('Tools', 'ArtistApron', 7.99, 'Protective artist apron', 'ArtWorld'),
    ('Tools', 'DetailScissors', 4.45, 'Small detail scissors', 'CraftGear'),
    ('Tools', 'RulerSet', 2.30, 'Set of art rulers', 'PaintPro'),
    ('Tools', 'BrushCleaner', 3.70, 'Brush cleaning solution', 'BrushMasters'),
    ('Tools', 'ArtTape', 1.99, 'Low-tack art tape', 'ArtCraft'),
    ('Tools', 'PencilSharpener', 1.50, 'Sharp pencil sharpener', 'ToolPro');
   

/*!40000 ALTER TABLE `Product` ENABLE KEYS */;

UNLOCK TABLES;


-- Dump of table ProductAvailability
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `ProductAvailability`;

CREATE TABLE `ProductAvailability` (
  `Product_ProductID` int(11) NOT NULL,
  `Shop_ShopID` int(11) NOT NULL,
  `Availability` int(11) NOT NULL,
  PRIMARY KEY (`Product_ProductID`, `Shop_ShopID`),
  FOREIGN KEY (`Shop_ShopID`) REFERENCES `Shop`(`ShopID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `ProductAvailability` WRITE;
/*!40000 ALTER TABLE `ProductAvailability` DISABLE KEYS */;

INSERT INTO `ProductAvailability` (`Product_ProductID`, `Shop_ShopID`, `Availability`)
VALUES
    (1, 1, 100);

/*!40000 ALTER TABLE `ProductAvailability` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table Employee
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `Employee`;

CREATE TABLE `Employee` (
  `EmployeeID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `FirstName` varchar(45) NOT NULL,
  `Surname` varchar(45) NOT NULL,
  `Role` varchar(45) NOT NULL,
  `hoursWorked` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `Employee` WRITE;
/*!40000 ALTER TABLE `Employee` DISABLE KEYS */;

INSERT INTO `Employee` (`FirstName`, `Surname`, `Role`, `hoursWorked`)
VALUES
    ('John', 'Swift', 'Manager', 40);

/*!40000 ALTER TABLE `Employee` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table ShopEmployee
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `ShopEmployee`;

CREATE TABLE `ShopEmployee` (
  `Employee_EmployeeID` int(11) NOT NULL,
  `Shop_ShopID` int(11) NOT NULL,
  PRIMARY KEY (`Employee_EmployeeID`),
  FOREIGN KEY (`Shop_ShopID`) REFERENCES `Shop`(`ShopID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `ShopEmployee` WRITE;
/*!40000 ALTER TABLE `ShopEmployee` DISABLE KEYS */;

INSERT INTO `ShopEmployee` (`Employee_EmployeeID`, `Shop_ShopID`)
VALUES
    (1, 1);
  

/*!40000 ALTER TABLE `ShopEmployee` ENABLE KEYS */;

UNLOCK TABLES;


-- Dump of table MainOffice
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `MainOffice`;

CREATE TABLE `MainOffice` (
  `OfficeID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Location` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `MainOffice` WRITE;
/*!40000 ALTER TABLE `MainOffice` DISABLE KEYS */;

INSERT INTO `MainOffice` (`Name`, `Location`)
VALUES
    ('Headquarters', 'London');

/*!40000 ALTER TABLE `MainOffice` ENABLE KEYS */;

UNLOCK TABLES;


-- Dump of table OfficeEmployee
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `OfficeEmployee`;

CREATE TABLE `OfficeEmployee` (
  `Employee_EmployeeID` int(11) NOT NULL,
  `MainOffice_OfficeID` int(11) NOT NULL,
  PRIMARY KEY (`Employee_EmployeeID`),
  FOREIGN KEY (`MainOffice_OfficeID`) REFERENCES `MainOffice`(`OfficeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `OfficeEmployee` WRITE;
/*!40000 ALTER TABLE `OfficeEmployee` DISABLE KEYS */;

INSERT INTO `OfficeEmployee` (`Employee_EmployeeID`, `MainOffice_OfficeID`)
VALUES
    (1, 1);

/*!40000 ALTER TABLE `OfficeEmployee` ENABLE KEYS */;

UNLOCK TABLES;


-- Dump of table BankDetails
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `BankDetails`;

CREATE TABLE `BankDetails` (
  `AccountNo` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `SortCode` int(11) NOT NULL,
  `AccountName` varchar(45) NOT NULL,
  `Employee_EmployeeID` int(11) NOT NULL,
  FOREIGN KEY (`Employee_EmployeeID`) REFERENCES `Employee`(`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `BankDetails` WRITE;
/*!40000 ALTER TABLE `BankDetails` DISABLE KEYS */;

INSERT INTO `BankDetails` (`SortCode`, `AccountName`, `Employee_EmployeeID`)
VALUES
    (123456, 'John Doe Account', 1);

/*!40000 ALTER TABLE `BankDetails` ENABLE KEYS */;

UNLOCK TABLES;


-- Dump of table OnlineOrder
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `OnlineOrder`;

CREATE TABLE `OnlineOrder` (
  `OrderID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `Price` float NOT NULL,
  `OrderStatus` date NOT NULL,
  `Customer_CustomerID` int(11) NOT NULL,
  `Shop_shopID` int(11) NOT NULL,
  `TrackingNo` varchar(45),
  FOREIGN KEY (`Customer_CustomerID`) REFERENCES `Customer`(`CustomerID`),
  FOREIGN KEY (`Shop_ShopID`) REFERENCES `Shop`(`ShopID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `OnlineOrder` WRITE;
/*!40000 ALTER TABLE `OnlineOrder` DISABLE KEYS */;

INSERT INTO `OnlineOrder` (`Price`, `OrderStatus`, `Customer_CustomerID`, `Shop_shopID`, `TrackingNo`)
VALUES
    (199.99, '2024-11-13', 1, 1, 'A12345');

/*!40000 ALTER TABLE `OnlineOrder` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table OnlineReturn
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `OnlineReturn`;

CREATE TABLE `OnlineReturn` (
  `OnlineReturnID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `Reason` varchar(45) NOT NULL,
  `AmountToReturn` float NOT NULL,
  `OnlineOrder_OrderID` int(11) NOT NULL,
  FOREIGN KEY (`OnlineOrder_OrderID`) REFERENCES `OnlineOrder`(`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `OnlineReturn` WRITE;
/*!40000 ALTER TABLE `OnlineReturn` DISABLE KEYS */;

INSERT INTO `OnlineReturn` (`Reason`, `AmountToReturn`, `OnlineOrder_OrderID`)
VALUES
    ('Damaged item', 19.99, 1);

/*!40000 ALTER TABLE `OnlineReturn` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table OnlineOrder_has_Product
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `OnlineOrder_has_Product`;

CREATE TABLE `OnlineOrder_has_Product` (
  `OnlineOrder_OrderID` int(11) NOT NULL,
  `Product_ProductID` int(11) NOT NULL,
  PRIMARY KEY (`OnlineOrder_OrderID`, `Product_ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `OnlineOrder_has_Product` WRITE;
/*!40000 ALTER TABLE `OnlineOrder_has_Product` DISABLE KEYS */;

INSERT INTO `OnlineOrder_has_Product` (`OnlineOrder_OrderID`, `Product_ProductID`)
VALUES
    (1, 101);

/*!40000 ALTER TABLE `OnlineOrder_has_Product` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table Supplier
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `Supplier`;

CREATE TABLE `Supplier` (
  `SupplierID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `Name` varchar(45) NOT NULL,
  `ProductTypeSupplied` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `Supplier` WRITE;
/*!40000 ALTER TABLE `Supplier` DISABLE KEYS */;

INSERT INTO `Supplier` (`Name`, `ProductTypeSupplied`)
VALUES
    ('ABC Yarn', 'Yarn');

/*!40000 ALTER TABLE `Supplier` ENABLE KEYS */;

UNLOCK TABLES;

-- Dump of table SupplyOrder
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `SupplyOrder`;

CREATE TABLE `SupplyOrder` (
  `SupplyOrderID` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `ProductType` varchar(45) NOT NULL,
  `ShopID` int(11) NOT NULL,
  `Supplier_SupplierID` int(11) NOT NULL,
  FOREIGN KEY (`ShopID`) REFERENCES `Shop`(`ShopID`),
  FOREIGN KEY (`Supplier_SupplierID`) REFERENCES `Supplier`(`SupplierID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `SupplyOrder` WRITE;
/*!40000 ALTER TABLE `SupplyOrder` DISABLE KEYS */;

INSERT INTO `SupplyOrder` (`ProductType`, `ShopID`, `Supplier_SupplierID`)
VALUES
    ('Yarn', 1, 1);

/*!40000 ALTER TABLE `SupplyOrder` ENABLE KEYS */;

UNLOCK TABLES;


-- Dump of table Product_has_SupplyOrder
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `Product_has_SupplyOrder`;

CREATE TABLE `Product_has_SupplyOrder` (
  `Product_ProductID` int(11) NOT NULL,
  `SupplyOrder_SupplyOrderID` int(11) NOT NULL,
  PRIMARY KEY (`Product_ProductID`, `SupplyOrder_SupplyOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `Product_has_SupplyOrder` WRITE;
/*!40000 ALTER TABLE `Product_has_SupplyOrder` DISABLE KEYS */;

INSERT INTO `Product_has_SupplyOrder` (`Product_ProductID`, `SupplyOrder_SupplyOrderID`)
VALUES
    (101, 1);

/*!40000 ALTER TABLE `Product_has_SupplyOrder` ENABLE KEYS */;

UNLOCK TABLES;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;