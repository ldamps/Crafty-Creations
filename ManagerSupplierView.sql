DROP VIEW IF EXISTS ManagerSupplierView;

CREATE VIEW ManagerSupplierView
As
SELECT sup.SupplierID, sup.Name, sup.ProductTypeSupplied, sup.Address, sup.Email,
so.SupplyOrderID, so.ProductType, so.ShopID
FROM Supplier sup
LEFT JOIN SupplyOrder so ON so.Supplier_SupplierID = sup.SupplierID
#WHERE so.ShopID = 1
UNION
SELECT sup.SupplierID, sup.Name, sup.ProductTypeSupplied, sup.Address, sup.Email,
so.SupplyOrderID, so.ProductType, so.ShopID
FROM Supplier sup
RIGHT JOIN SupplyOrder so ON so.Supplier_SupplierID = sup.SupplierID
WHERE so.ShopID = 1
