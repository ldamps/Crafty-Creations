DROP VIEW IF EXISTS CustomerView;
Create OR REPLACE View CustomerView
AS 
SELECT c.CustomerID, c.LastName, c.FirstName, c.EmailAddress, c.PhoneNumber, c.Password, c.Title, 
o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, 
p.CardNumber, p.CVV, p.ExpiryDate, 
a.City, a.HouseNumber, a.Postcode, a.StreetName,
r.OnlineReturnID, r.Reason, r.AmountToReturn, r.OnlineOrder_OrderID
From Customer c 
LEFT JOIN CustomerAddress a ON c.CustomerID = a.Customer_CustomerID 
LEFT JOIN OnlineOrder o ON c.CustomerID = o.Customer_CustomerID
LEFT JOIN CustomerAddress a ON c.CustomerID = a.Customer_CustomerID
LEFT JOIN PaymentMethods p ON c.CustomerID = p.Customer_CustomerID
LEFT JOIN OnlineReturn r ON c.CustomerID = r.Customer_CustomerID
WHERE CustomerID = 2
UNION
SELECT c.CustomerID, c.LastName, c.FirstName, c.EmailAddress, c.PhoneNumber, c.Password, c.Title, 
o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, 
p.CardNumber, p.CVV, p.ExpiryDate, 
a.City, a.HouseNumber, a.Postcode, a.StreetName,
r.OnlineReturnID, r.Reason, r.AmountToReturn, r.OnlineOrder_OrderID
From Customer c 
RIGHT JOIN CustomerAddress a ON c.CustomerID = a.Customer_CustomerID
RIGHT JOIN OnlineOrder o ON c.CustomerID = o.Customer_CustomerID 
RIGHT JOIN CustomerAddress a ON c.CustomerID = a.Customer_CustomerID
RIGHT JOIN PaymentMethods p ON c.CustomerID = p.Customer_CustomerID
RIGHT JOIN OnlineReturn r ON c.CustomerID = r.Customer_CustomerID
WHERE CustomerID = 2