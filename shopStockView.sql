DROP VIEW IF EXISTS ShopEmployeeStockView;
Create OR REPLACE View ShopEmployeeStockView
AS 
SELECT P.ProductID, P.ProductName, P.Type, P.Price,	 P.Brand, P.Supplier, P.ProductDescription, PA.Availability,
op.Quantity, op.OnlineOrder_OrderID, op.Product_ProductID
sp.Quantity, sp.Purchase_PurchaseID, sp.Product_ProductID as spProduct
	   FROM Product P
	   INNER JOIN ProductAvailability PA ON P.ProductID = PA.Product_ProductID
       INNER JOIN OnlineOrder_has_Product op ON P.ProductID = op.Product_ProductID
       INNER JOIN ShopPurchase_has_Product sp ON P.ProductID = sp.Product_ProductID
	   WHERE PA.Shop_ShopID = 1