DROP Procedure GetProductsInOrder;
DELIMITER //
CREATE PROCEDURE GetProductsInOrder(IN orderNumber int)
BEGIN
	SELECT DISTINCT ProductName, Price, Quantity FROM Product, OnlineOrder_has_Product WHERE 
	ProductID IN 
	(SELECT DISTINCT Product_ProductID FROM OnlineOrder_has_Product p
		WHERE p.OnlineOrder_OrderID = orderNumber AND ProductID = p.Product_ProductID )
        AND orderNumber = Onlineorder_OrderID;
END //
DELIMITER ;