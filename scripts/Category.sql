CREATE TABLE Category 
(
ID INT,
ProjectID int,
Name VARCHAR(255) NOT NULL,
Description VARCHAR(1000) NULL,
RecordDate TIMESTAMP,
CreatedBy int,
LastUpdated TIMESTAMP,
LastUpdatedBy int,
DeleteFlag int,
PRIMARY KEY(ID, ProjectID),
CONSTRAINT FKCat_ProjectID FOREIGN KEY (ProjectID) REFERENCES Project(ID),
CONSTRAINT FKCat_CreatedBy FOREIGN KEY (CreatedBy) REFERENCES User(ID),
CONSTRAINT FKCat_LastUpdatedBy FOREIGN KEY (LastUpdatedBy) REFERENCES User(ID)
)