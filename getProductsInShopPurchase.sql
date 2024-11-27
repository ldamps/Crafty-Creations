#DROP Procedure GetProductsInShopPurchas;
DELIMITER //
CREATE PROCEDURE GetProductsInShopPurchase(IN orderNumber int)
BEGIN
	SELECT ProductName, Price, p.Quantity FROM Product, ShopPurchase_has_Product p WHERE 
	ProductID IN 
	(SELECT Product_ProductID FROM ShopPurchase_has_Product 
		WHERE Purchase_PurachseID = orderNumber AND ProductID = p.Product_ProductID );
END //
DELIMITER ;