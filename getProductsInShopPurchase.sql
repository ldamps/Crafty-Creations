DROP Procedure IF EXISTS GetProductsInShopPurchase;
DELIMITER //
CREATE PROCEDURE GetProductsInShopPurchase(IN orderNumber int)
BEGIN
	SELECT DISTINCT ProductName, Price, spQuantity FROM ShopEmployeeStockView WHERE 
	ProductID IN 
	(SELECT DISTINCT spProduct FROM ShopEmployeeStockView p
		WHERE Purchase_PurachseID = orderNumber AND ProductID = p.spProduct )
        AND orderNumber = Purchase_PurachseID;
END //
DELIMITER ;