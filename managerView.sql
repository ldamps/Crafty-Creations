From Employee e
LEFT JOIN BankDetails bd ON e.EmployeeID = bd.Employee_EmployeeID
LEFT JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
LEFT JOIN Shop s ON se.Shop_shopID = s.ShopID
LEFT JOIN ShopEmployee mse ON mse.Shop_ShopID = s.ShopID
LEFT JOIN Employee m ON  m.Role = 'manager' and m.EmployeeID < e.EmployeeID and m.EmployeeID > e.EmployeeID-10
LEFT JOIN OnlineOrder o ON se.Shop_ShopID =  o.Shop_shopID
#LEFT JOIN OnlineReturn r ON se.Shop_shopID = r.Shop_shopID
WHERE e.EmployeeID = 14
UNION
SELECT e.EmployeeID, e.Surname, e.FirstName, e.EmailAddress, e.Role, e.Password, e.hoursWorked, e.HourlyPay, 
bd.AccountName, bd.AccountNo, bd.SortCode,
o.OrderID, o.Price, o.OrderStatus, o.TrackingNo, o.Shop_shopID,
s.StreetName, s.Postcode, s.City, s.NumEmployees,
m.FirstName AS manFirst, m.Surname AS manSur
#r.OnlineReturnID, r.Reason, r.AmountToReturn, r.OnlineOrder_OrderID
From Employee e
RIGHT JOIN BankDetails bd ON e.EmployeeID = bd.Employee_EmployeeID
RIGHT JOIN ShopEmployee se ON e.EmployeeID = se.Employee_EmployeeID
RIGHT JOIN Shop s ON se.Shop_shopID = s.ShopID
RIGHT JOIN Employee m ON  m.Role = 'manager' and m.EmployeeID < e.EmployeeID and m.EmployeeID > e.EmployeeID-10
RIGHT JOIN OnlineOrder o ON se.Shop_ShopID =  o.Shop_shopID
#RIGHT JOIN OnlineReturn r ON se.Shop_shopID = r.Shop_shopID
WHERE e.EmployeeID = 14;