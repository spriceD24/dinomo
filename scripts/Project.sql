CREATE TABLE Project 
(
ID INT PRIMARY KEY,
Name VARCHAR(255) NOT NULL,
Description VARCHAR(1000) NULL,
Address VARCHAR(500) NULL, 
RecordDate TIMESTAMP,
CreatedBy int,
LastUpdated TIMESTAMP,
LastUpdatedBy int,
DeleteFlag int,
CONSTRAINT FKProj_CreatedBy FOREIGN KEY (CreatedBy) REFERENCES User(ID),
CONSTRAINT FKProj_LastUpdatedBy FOREIGN KEY (LastUpdatedBy) REFERENCES User(ID)
)