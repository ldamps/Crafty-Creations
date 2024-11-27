DROP Procedure GetProductsInOrder;
DELIMITER //
CREATE PROCEDURE GetProductsInOrder(IN orderNumber int)
BEGIN
	SELECT ProductName, Price, Quantity FROM ShopEmployeeStockView WHERE 
	ProductID IN 
	(SELECT Product_ProductID FROM ShopEmployeeStockView p
		WHERE OnlineOrder_OrderID = orderNumber AND ProductID = p.Product_ProductID );
END //
DELIMITER ;