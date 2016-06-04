CREATE TABLE User 
(
ID INT PRIMARY KEY,
Name VARCHAR(255) NOT NULL,
Login VARCHAR(255) NOT NULL,
Email VARCHAR(255) NOT NULL,
Mobile VARCHAR(100) NULL,
Address VARCHAR(100) NULL,
RecordDate TIMESTAMP,
CreatedBy int,
LastUpdated TIMESTAMP,
LastUpdatedBy int,
DeleteFlag int not null
)