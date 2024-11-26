DROP Procedure GetProductsInOrder;
DELIMITER //
CREATE PROCEDURE GetProductsInOrder(IN orderNumber int)
BEGIN
	SELECT ProductName, Price, p.Quantity FROM Product, OnlineOrder_has_Product p WHERE 
	ProductID IN 
	(SELECT Product_ProductID FROM OnlineOrder_has_Product 
		WHERE OnlineOrder_OrderID = orderNumber AND ProductID = p.Product_ProductID );
END //
DELIMITER ;