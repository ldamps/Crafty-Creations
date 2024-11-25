SET FOREIGN_KEY_CHECKS=0;
ALTER TABLE OnlineReturn ADD Customer_CustomerID INT NOT NULL;
ALTER TABLE OnlineReturn ADD CONSTRAINT fk_Customer_CustomerID FOREIGN KEY (Customer_CustomerID) REFERENCES Customer(CustomerID);
