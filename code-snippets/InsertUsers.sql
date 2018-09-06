
 INSERT INTO [USERS-TABLE] (username, password, user_type, name, surname, email)
 VALUES ('username', LOWER(CONVERT(VARCHAR(32), HASHBYTES('MD5', 'password'), 2)), 'admin', 'name', 'surname', 'email')