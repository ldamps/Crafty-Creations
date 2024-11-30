DROP VIEW IF EXISTS ShopEmployeeStockView;
Create OR REPLACE View ShopEmployeeStockView
AS 
SELECT P.ProductID, P.ProductName, P.Type, P.Price,	 P.Brand, P.Supplier, 
P.ProductDescription, PA.Availability
FROM Product P, ProductAvailability PA
WHERE P.ProductID = PA.Product_ProductID
