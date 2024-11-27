DROP VIEW IF EXISTS ManagerView;
            Create OR REPLACE View ManagerView
            AS 
            SELECT e.EmployeeID, e.Surname, e.FirstName, e.EmailAddress, e.Role, e.Password, e.hoursWorked, e.HourlyPay, 
            bd.AccountName, bd.AccountNo, bd.SortCode,
            E.EmployeeID AS empID, E.FirstName AS empFirst, E.Surname AS empSur, E.Role as empRole, E.EmailAddress AS empEmail, E.HoursWorked AS empHours, E.HourlyPay AS empPay,
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
            INNER JOIN Employee m ON m.FirstName = :ManFirst AND m.Surname = :ManLast
            INNER JOIN Employee E ON E.employeeID IN (SELECT Employee_EmployeeID FROM ShopEmployee WHERE Shop_ShopID = :shopID)
            LEFT JOIN OnlineOrder o ON se.Shop_ShopID =  s.ShopID
            LEFT JOIN ShopPurchase sp ON sp.Shop_shopID = s.ShopID
            LEFT JOIN ShopReturn sr ON sp.Shop_shopID = s.ShopID
            LEFT JOIN OnlineReturn r ON (r.Shop_shopID = s.ShopID)
            WHERE e.EmployeeID = :userID AND s.ShopID = :shopID