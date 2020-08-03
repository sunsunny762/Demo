DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `login_logout_report`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `login_logout_report` (IN `searchtext` TEXT, IN `selectiondate1` DATETIME, IN `selectiondate2` DATETIME)  BEGIN

                DECLARE _sql LONGTEXT;

                 DROP TABLE IF EXISTS TEMPCUMM;
                 CREATE temporary TABLE TEMPCUMM
                 (
                        id INT(10),
                    user_id INT(10),
                    activity varchar(15),
                    ip_address varchar(50),
                    previous_at datetime,
                    created_at datetime,
                    previous_activity varchar(15),
                    current_activity varchar(15)
                 );

                INSERT INTO TEMPCUMM (id,user_id, activity ,ip_address ,previous_at,created_at,previous_activity,current_activity)
                    (
                        SELECT al.id,u.id , al.activity,al.ip_address, @prev as previous, @prev :=  al.created_at, @preact as previous_activity,@preact := al.activity as current_activity
                        FROM
                        (select @prev := null,@preact := null) as i,
                     users AS u
                        LEFT JOIN activity_logs AS al ON al.user_id = u.id
                        WHERE (al.activity = "login" OR al.activity = "logout")
                            AND
                           ( CASE
                               WHEN ( searchtext <> "") THEN  concat(u.first_name," ",u.last_name) like CONCAT("%",searchtext,"%") ESCAPE "|"  ELSE 1=1 END
                            OR
                            CASE
                               WHEN ( searchtext <> "") THEN  al.ip_address like CONCAT("%",searchtext,"%") ELSE 1=1 END
                              )
                              AND
                              CASE
                               WHEN ( selectiondate1  ) THEN
                                 (al.created_at >= selectiondate1 AND al.created_at <= selectiondate2)
                                 ELSE 1=1 END
                         ORDER BY al.created_at asc
                    );

                SET _sql = NULL;
                 SET _sql = ' IF(m.activity = "login",m.created_at,0) as login, IF(m.activity = "logout",m.created_at,0) as logout, IF(m.activity = "logout", m.previous_at,0 ) as login1 ';



                SET @DynamicPivotQuery = CONCAT('SELECT m.id,u.id as user_id , concat(u.first_name," ",u.last_name) as user,', _sql, ',m.ip_address,m.previous_activity,m.current_activity
                        FROM users as u
                      LEFT JOIN  TEMPCUMM as m ON m.user_id = u.id
                      WHERE m.activity = "login" OR m.activity = "logout"
                      group by u.id,m.created_at,m.ip_address
                      order BY m.created_at desc
                ');
                  PREPARE stmt FROM @DynamicPivotQuery;
                  EXECUTE stmt;
                  DEALLOCATE PREPARE stmt;
                  DROP  TABLE TEMPCUMM;
                END$$

DELIMITER ;