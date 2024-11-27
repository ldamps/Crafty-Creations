DELIMITER //
# call using GetManager(employeeID) to get the manager of the employee
#DROP procedure GetManager;
CREATE PROCEDURE GetManager(IN employee_id int)
BEGIN
    SELECT manager.FirstName, manager.Surname FROM Employee manager, Shop s
	WHERE(SELECT Employee_EmployeeID
        FROM ShopEmployee
        WHERE Shop_ShopID = s.ShopID
        AND manager.Role = 'Manager'
        LIMIT 1)
	LIMIT 1;
END //
DELIMITER ;


