CREATE TABLE PreliminaryReport (
ReportID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ClientID int,
UploadedDate TIMESTAMP,
CategoryID int,
ProjectID int,
UploadedBy int,
ReportKey varchar(500),
UploadedDateString varchar(500),
UploadedForUser int,
DeleteFlag int,
MetaData TEXT not null,
CONSTRAINT FKPremReport_ProjectID FOREIGN KEY (ProjectID) REFERENCES Project(ID),
CONSTRAINT FKPremReport_CategoryID FOREIGN KEY (CategoryID) REFERENCES Category(ID),
CONSTRAINT FKPremReport_UploadedBy FOREIGN KEY (UploadedBy) REFERENCES User(ID),
CONSTRAINT FKPremReport_UploadedFor FOREIGN KEY (UploadedForUser) REFERENCES User(ID),
CONSTRAINT FKPremReport_ClientID FOREIGN KEY (ClientID) REFERENCES Client(ID)
)