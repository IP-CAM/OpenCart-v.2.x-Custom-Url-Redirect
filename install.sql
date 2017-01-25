CREATE TABLE IF NOT EXISTS oc_redirect
(
    redirect_id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    redirect_from VARCHAR(255) DEFAULT '' NOT NULL,
    redirect_to VARCHAR(255)
);
CREATE UNIQUE INDEX oc_redirect_redirect_from_uindex ON oc_redirect (redirect_from);