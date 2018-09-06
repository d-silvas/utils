/********
*
*   1ST TRY -- BUT: you can't use RAND in user functions :(((
*
*********/
CREATE FUNCTION dbo.RandomName (@length INT)  
RETURNS varchar(100)
AS  
BEGIN  
	DECLARE @str varchar(100) = '';
	DECLARE @i INT = 0;

	WHILE @i < @length
	BEGIN
	   SET @str = @str + CHAR(CAST(RAND()*26 AS int)+97);
	   SET @i = @i + 1;
	END;

	RETURN(@str); 
END;  
GO

/********
*
*   2ND TRY
*
*********/
USE MyDatabase
GO
CREATE VIEW vw_GetRandValue
AS
SELECT RAND() AS Value
GO
CREATE FUNCTION dbo.RandomName (@length INT)  
RETURNS varchar(100)
AS  
BEGIN  
	DECLARE @str varchar(100) = '';
	DECLARE @i INT = 0;

	SET @str = @str + CHAR(CAST((SELECT Value FROM vw_GetRandValue) * 26 AS int) + 65);
	SET @i = 1;

	WHILE @i < @length
	BEGIN
	   SET @str = @str + CHAR(CAST((SELECT Value FROM vw_GetRandValue) * 26 AS int) + 97);
	   SET @i = @i + 1;
	END;

	RETURN(@str); 
END;  
GO