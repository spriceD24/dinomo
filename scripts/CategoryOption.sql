CREATE TABLE CategoryOption 
(
ID INT,
CategoryID int,
ProjectID int,
CategoryOrder int,
Title VARCHAR(255),
FormType VARCHAR(255),
IsRequired int,
OptionSettings TEXT null,
RecordDate TIMESTAMP,
CreatedBy int,
LastUpdated TIMESTAMP,
LastUpdatedBy int,
DeleteFlag int,
PRIMARY KEY(ID, CategoryID, ProjectID),
CONSTRAINT FKCatOpt_ProjectID FOREIGN KEY (ProjectID) REFERENCES Project(ID),
CONSTRAINT FKCatOpt_CategoryID FOREIGN KEY (CategoryID) REFERENCES Category(ID),
CONSTRAINT FKCatOpt_CreatedBy FOREIGN KEY (CreatedBy) REFERENCES User(ID),
CONSTRAINT FKCatOpt_LastUpdatedBy FOREIGN KEY (LastUpdatedBy) REFERENCES User(ID)
)