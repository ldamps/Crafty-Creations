DROP VIEW IF EXISTS LoggedOutView;
Create View LoggedOutView AS
SELECT Name, Type, Price, ProductDescription, Brand FROM Product