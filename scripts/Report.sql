CREATE TABLE Report (
ReportID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
UploadedDate TIMESTAMP,
CategoryID int,
ProjectID int,
UploadedBy int,
ReportKey varchar(500),
UploadedDateString varchar(500),
UploadedForUser int,
PDFUrl varchar(500),
WebUrl varchar(500),
DeleteFlag int,
MetaData TEXT null,
CONSTRAINT FKReport_ProjectID FOREIGN KEY (ProjectID) REFERENCES Project(ID),
CONSTRAINT FKReport_CategoryID FOREIGN KEY (CategoryID) REFERENCES Category(ID),
CONSTRAINT FKReport_UploadedBy FOREIGN KEY (UploadedBy) REFERENCES User(ID),
CONSTRAINT FKReport_UploadedFor FOREIGN KEY (UploadedForUser) REFERENCES User(ID)
)