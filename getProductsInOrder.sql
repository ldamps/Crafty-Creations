DROP Procedure GetProductsInOrder;
DELIMITER //
CREATE PROCEDURE GetProductsInOrder(IN orderNumber int)
BEGIN
	SELECT DISTINCT ProductName, Price, Quantity FROM ShopEmployeeStockView WHERE 
	ProductID IN 
	(SELECT DISTINCT Product_ProductID FROM ShopEmployeeStockView p
		WHERE p.OnlineOrder_OrderID = orderNumber AND ProductID = p.Product_ProductID )
        AND orderNumber = Onlineorder_OrderID;
END //
DELIMITER ;