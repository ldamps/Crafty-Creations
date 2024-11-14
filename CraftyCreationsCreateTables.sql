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
    ('Mr', 'Fred', 'Smith', 123, 'fred.smith@gmail.com', 'password');

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
    ('Yarn', 'BlueYarn', 5.99, 'Best Quality Yearn', 'ArtWorld');
   

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