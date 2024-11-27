DELIMITER //
# call using GetManager(employeeID) to get the manager of a shop
#DROP procedure GetManager;
DROP PROCEDURE GetManager;
CREATE PROCEDURE GetManager(IN shop_id int)
BEGIN
    SELECT manager.FirstName, manager.Surname FROM Employee manager, Shop s
	WHERE(SELECT Employee_EmployeeID
        FROM ShopEmployee
        WHERE Shop_ShopID = shop_id AND manager.EmployeeID = ShopEmployee.Employee_EmployeeID
        AND manager.Role = 'Manager' )
	LIMIT 1;
END //
DELIMITER ;


