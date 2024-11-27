DELIMITER //
CREATE PROCEDURE GetShopWorkedAT(IN Employee_ID int)
BEGIN
   SELECT Shop_ShopID
			FROM ShopEmployee
			WHERE Employee_EmployeeID = Employee_ID;
END //
DELIMITER ;