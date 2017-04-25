CREATE TABLE CategoryOptionHistoric
(
ID INT,
CategoryID int,
ProjectID int,
RecordDate TIMESTAMP,
CategoryOrder int,
Title VARCHAR(255),
FormType VARCHAR(255),
IsRequired int,
OptionSettings TEXT null,
CreatedBy int,
LastUpdated TIMESTAMP,
LastUpdatedBy int,
DeleteFlag int,
CONSTRAINT FKCatOptHist_ProjectID FOREIGN KEY (ProjectID) REFERENCES Project(ID),
CONSTRAINT FKCatOptHist_CategoryID FOREIGN KEY (CategoryID) REFERENCES Category(ID),
CONSTRAINT FKCatOptHist_CreatedBy FOREIGN KEY (CreatedBy) REFERENCES User(ID),
CONSTRAINT FKCatOptHist_LastUpdatedBy FOREIGN KEY (LastUpdatedBy) REFERENCES User(ID)
)