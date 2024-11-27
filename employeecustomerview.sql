DROP VIEW IF EXISTS EmployeeCustomerView;
Create OR REPLACE View EmployeeCustomerView
AS 
SELECT 
c.FirstName, c.LastName, c.CustomerID
FROM Customer c