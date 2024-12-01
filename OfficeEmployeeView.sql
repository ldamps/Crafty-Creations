DROP VIEW IF EXISTS OfficeEmployeeView;
CREATE VIEW OfficeEmployeeView
AS
SELECT EmployeeID, FirstName, Surname, Role, HourlyPay, EmailAddress, hoursWorked,
m.OfficeID, m.Name AS OfficeName, m.Location,
s.ShopID, s.City,
bd.AccountNo, bd.SortCode, bd.AccountName, bd.Employee_EmployeeID,
o.OrderID, o.Price, o.Shop_shopID, o.OrderStatus,
sp.ShopPurchaseID, sp.Shop_shopID
FROM Employee
JOIN MainOffice m 
JOIN BankDetails bd ON bd.Employee_EmployeeID = EmployeeID
JOIN Supplier
LEFT JOIN Shop s ON s.ShopID IN (SELECT Shop_ShopID FROM ShopEmployee WHERE Employee_EmployeeID = EmployeeID)
LEFT JOIN OnlineOrder o ON o.Shop_shopID = s.ShopID
LEFT JOIN ShopPurchase sp ON sp.Shop_ShopID = s.shopID