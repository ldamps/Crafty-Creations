DROP VIEW IF EXISTS ShopEmployeeView;
Create OR REPLACE View ShopEmployeeView
AS 
SELECT e.EmployeeID, e.Surname, e.FirstName, e.EmailAddress, e.Role, e.Password, e.hoursWorked, e.HourlyPay, 
bd.AccountName, bd.AccountNo, bd.SortCode,
o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, o.OrderDate, o.Customer_CustomerID AS customerID,
s.StreetName, s.Postcode, s.City, s.NumEmployees, s.TotalSales,
m.FirstName as ManagerFirstName, m.Surname AS ManagerSurname,
r.OnlineReturnID, r.Reason, r.AmountToReturn, r.Customer_CustomerID,	
sp.PurchaseID, sp.Price AS shopPrice, sp.PurchaseDate, sp.Customer_CustomerID AS shopCustomer, sp.Shop_shopID AS SID,
sr.ShopReturnID, sr.AmountToReturn as ShopAmountToReturn, sr.Reason AS shopReason, sr.Customer_CustomerID AS ShopReturnCustomer
From Employee e
INNER JOIN BankDetails bd ON e.EmployeeID = bd.Employee_EmployeeID
INNER JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
INNER JOIN Shop s ON se.Shop_shopID = s.ShopID
INNER JOIN ShopEmployee mse ON mse.Shop_ShopID = s.ShopID
INNER JOIN Employee m ON m.EmployeeID = (
        SELECT Employee_EmployeeID
        FROM ShopEmployee
        WHERE Shop_ShopID = s.ShopID
        AND m.Role = 'Manager'
        LIMIT 1)
INNER JOIN OnlineOrder o ON se.Shop_ShopID =  s.ShopID
INNER JOIN ShopPurchase sp ON sp.Shop_shopID = s.ShopID
INNER JOIN ShopReturn sr ON sp.Shop_shopID = s.ShopID
INNER JOIN OnlineReturn r ON (r.Shop_shopID = s.ShopID)
WHERE e.EmployeeID = 3 AND s.ShopID = 1
/*UNION
SELECT e.EmployeeID, e.Surname, e.FirstName, e.EmailAddress, e.Role, e.Password, e.hoursWorked, e.HourlyPay, 
bd.AccountName, bd.AccountNo, bd.SortCode,
o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID, o.OrderDate, o.Customer_CustomerID AS customerID,
s.StreetName, s.Postcode, s.City, s.NumEmployees, s.TotalSales,
m.FirstName as ManagerFirstName, m.Surname AS ManagerSurname,
r.OnlineReturnID, r.Reason, r.AmountToReturn, r.OnlineOrder_OrderID, r.Customer_CustomerID
From Employee e
RIGHT JOIN BankDetails bd ON e.EmployeeID = bd.Employee_EmployeeID
RIGHT JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
RIGHT JOIN Shop s ON se.Shop_shopID = s.ShopID
RIGHT JOIN Employee m ON m.EmployeeID = (
        SELECT Employee_EmployeeID
        FROM ShopEmployee
        WHERE Shop_ShopID = s.ShopID
        AND m.Role = 'Manager'
        LIMIT 1)
RIGHT JOIN OnlineOrder o ON se.Shop_ShopID =  s.ShopID
RIGHT JOIN ShopPurchase sp ON sp.Shop_shopID = s.ShopID
RIGHT JOIN ShopReturn sr ON sp.Shop_shopID = s.ShopID
RIGHT JOIN OnlineReturn r ON (r.Shop_shopID = s.ShopID OR r.Shop_ShopID = null)
#RIGHT JOIN OnlineOrder_has_Product ohp ON 
WHERE e.EmployeeID = 3 AND s.ShopID = 1;*/