DROP VIEW IF EXISTS OfficeEmployeeView;
CREATE VIEW OfficeEmployeeView
AS
SELECT EmployeeID, FirstName, Surname, Role, HourlyPay, EmailAddress, hoursWorked,
m.OfficeID, m.Name AS OfficeName, m.Location,
s.ShopID, s.City,
bd.AccountNo, bd.SortCode, bd.AccountName, bd.Employee_EmployeeID
FROM Employee
JOIN MainOffice m 
JOIN BankDetails bd ON bd.Employee_EmployeeID = EmployeeID
JOIN Supplier
LEFT JOIN Shop s ON s.ShopID IN (SELECT Shop_ShopID FROM ShopEmployee WHERE Employee_EmployeeID = EmployeeID)