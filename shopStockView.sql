DROP VIEW IF EXISTS ShopEmployeeStockView;
Create OR REPLACE View ShopEmployeeStockView
AS 
SELECT P.ProductID, P.ProductName, P.Type, P.Price,	 P.Brand, P.Supplier, P.ProductDescription, PA.Availability,
op.Quantity, op.OnlineOrder_OrderID, op.Product_ProductID,
sp.Quantity AS spQuantity, sp.Purchase_PurachseID, sp.Product_ProductID as spProduct
	   FROM Product P
	   LEFT JOIN ProductAvailability PA ON P.ProductID = PA.Product_ProductID
       LEFT JOIN OnlineOrder_has_Product op ON P.ProductID = op.Product_ProductID
	   LEFT JOIN ShopPurchase_has_Product sp ON P.ProductID = sp.Product_ProductID
	   WHERE PA.Shop_ShopID = 1
UNION
SELECT P.ProductID, P.ProductName, P.Type, P.Price,	 P.Brand, P.Supplier, P.ProductDescription, PA.Availability,
op.Quantity, op.OnlineOrder_OrderID, op.Product_ProductID,
sp.Quantity AS spQuantity, sp.Purchase_PurachseID, sp.Product_ProductID as spProduct
	   FROM Product P
	   RIGHT JOIN ProductAvailability PA ON P.ProductID = PA.Product_ProductID
       RIGHT JOIN OnlineOrder_has_Product op ON P.ProductID = op.Product_ProductID
       RIGHT JOIN ShopPurchase_has_Product sp ON P.ProductID = sp.Product_ProductID
	   WHERE PA.Shop_ShopID = 1