DROP VIEW IF EXISTS ShopStockView;
Create OR REPLACE View ShopStockView
AS 
SELECT P.ProductID, P.ProductName, P.Type, P.Brand, P.Supplier, PA.Availability
                       FROM Product P
                       INNER JOIN ProductAvailability PA ON P.ProductID = PA.Product_ProductID
                       WHERE PA.Shop_ShopID = 1