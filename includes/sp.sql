
USE noteApp;
DROP PROCEDURE IF EXISTS sp_addUser;
DELIMITER //

CREATE PROCEDURE sp_addUser(
  IN un VARCHAR(50),
  IN pwd CHAR(50),
  IN fname VARCHAR(50),
  IN lname VARCHAR(50)
) BEGIN

INSERT INTO users(username, password, firstname,lastname) VALUES (
  un,
  pwd,
  fname,
  lname
);
SELECT LAST_INSERT_ID() AS userID;
END;

DROP PROCEDURE IF EXISTS sp_addNotes;

CREATE PROCEDURE sp_addNotes(
IN note TEXT,
IN uid INT(11)
) BEGIN
  INSERT INTO notes(notes, userID) VALUES (note, uid);
    SELECT LAST_INSERT_ID() AS noteID;
END;

DROP PROCEDURE IF EXISTS sp_addTags;

CREATE PROCEDURE sp_addTags(
  IN tag VARCHAR(50)
) BEGIN
  INSERT INTO tags (tagName) VALUES (tag);
  SELECT LAST_INSERT_ID() AS tagID;
END;

DROP PROCEDURE IF EXISTS sp_addNoteTags;
CREATE PROCEDURE sp_addNoteTags(
  IN tagID int(10),
  IN noteID int(10)
) BEGIN
  INSERT INTO notetags(tagID, noteID) VALUES (tagID, noteID);
END;


DROP PROCEDURE IF EXISTS sp_login;

CREATE PROCEDURE sp_login(
  IN un VARCHAR(50),
  IN pwd VARCHAR(50)
)
BEGIN
SELECT * FROM users WHERE un = username AND pwd = password;
END;

DROP PROCEDURE IF EXISTS sp_showNote;

  CREATE PROCEDURE sp_showNote(
  IN uid INT(11)
  )
    BEGIN
    SELECT * FROM notes join notetags ON notes.id = notetags.noteID JOIN tags ON notetags.tagID = tags.tagID WHERE notes.USERID = uid;
    END;


DROP PROCEDURE IF EXISTS sp_deleteNote;
  CREATE PROCEDURE sp_deleteNote(
  IN id INT(10),
  IN userID INT(11)
)
  BEGIN
  DELETE FROM notes where id = notes.ID and notes.userID = userID;
  END;

DROP PROCEDURE IF EXISTS sp_search;
  CREATE PROCEDURE sp_search(
    IN userID INT(11),
    IN searchName VARCHAR(50)
  )
  BEGIN
  SELECT * FROM notes join notetags ON notes.id = notetags.noteID JOIN tags ON notetags.tagID = tags.tagID WHERE notes.USERID = userID AND tagName LIKE searchName;
  END;

//
DELIMITER ;
