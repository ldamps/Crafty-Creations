DROP VIEW IF EXISTS OfficeSupplierView;
CREATE VIEW OfficeSupplierView
As
SELECT sup.SupplierID, sup.Name, sup.ProductTypeSupplied, sup.Address, sup.Email,
so.SupplyOrderID, so.ProductType, so.ShopID
FROM Supplier sup
LEFT JOIN SupplyOrder so ON so.Supplier_SupplierID = sup.SupplierID
UNION
SELECT sup.SupplierID, sup.Name, sup.ProductTypeSupplied, sup.Address, sup.Email,
so.SupplyOrderID, so.ProductType, so.ShopID
FROM Supplier sup
RIGHT JOIN SupplyOrder so ON so.Supplier_SupplierID = sup.SupplierID