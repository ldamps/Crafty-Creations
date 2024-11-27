alter table Employee
ADD HourlyPay Double;

# updating hourly pay
UPDATE Employee
SET HourlyPay = CASE 
	WHEN (EmployeeID > 0 AND Role = 'Shop Assistant') THEN 13.0
    WHEN (EmployeeID > 0 AND Role = 'Supervisor') THEN 15.0
    WHEN (EmployeeID > 0 AND Role = 'Assistant Manager') THEN 16.0
    WHEN (EmployeeID > 0 AND Role = 'Manager') THEN 18.0
     WHEN (EmployeeID > 0 AND Role = 'CEO') THEN 30.0
     WHEN (EmployeeID > 0 AND Role = 'Payroll') THEN 18.0
     WHEN (EmployeeID > 0 AND Role = 'Human Resources') THEN 21.0
     WHEN (EmployeeID > 0 AND Role = 'Website Development') THEN 20.0
     WHEN (EmployeeID > 0 AND Role = 'IT Support') THEN 20.0
     WHEN (EmployeeID > 0 AND Role = 'Administration') THEN 18.0
    END
WHERE (EmployeeID > 0 and Role = 'Shop Assistant') OR 
		(EmployeeID > 0 and Role = 'Supervisor') OR
        (EmployeeID > 0 AND Role = 'Assistant Manager') or
        (EmployeeID > 0 AND Role = 'Manager') or
        (EmployeeID > 0 AND Role = 'CEO') or
        (EmployeeID > 0 AND Role = 'Payroll') or
		(EmployeeID > 0 AND Role = 'Human Resources') or
        (EmployeeID > 0 AND Role = 'Website Development') or
        (EmployeeID > 0 AND Role = 'IT Support') or
        (EmployeeID > 0 AND Role = 'Administration');
        
# have some part time employees
Update Employee 
SET HoursWorked = 16
WHERE EmployeeID = 5 or EmployeeID = 10 or EmployeeID = 25 or EmployeeID = 20 or EmployeeID = 35 or EmployeeID = 40 or EmployeeID = 45 or EmployeeID = 50 or EmployeeID = 55 or EmployeeID = 60 or EmployeeID = 65 or EmployeeID = 70;

# some full time shop employees
UPDATE Employee
Set HoursWorked = 37
WHERE EmployeeID = 6 or EmployeeID = 16 or EmployeeID = 26 or EmployeeID = 36 or EmployeeID = 46 or EmployeeID = 56 or EmployeeID = 66 or EmployeeID = 76;CREATE DATABASE `sampledatabase`;
UPDATE Supplier SET Supplier = "ABC Yarn" WHERE ProductID BETWEEN 1 and 10
