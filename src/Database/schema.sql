CREATE TABLE utilisateur (
                             id INT AUTO_INCREMENT PRIMARY KEY,
                             pseudo VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE touite (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        contenu VARCHAR(235) NOT NULL,
                        date_publication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        utilisateur_id INT NOT NULL,
                        FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id)
);

CREATE TABLE hashtag (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE touite_hashtag (
                                touite_id INT NOT NULL,
                                hashtag_id INT NOT NULL,
                                PRIMARY KEY (touite_id, hashtag_id),
                                FOREIGN KEY (touite_id) REFERENCES touite(id) ON DELETE CASCADE,
                                FOREIGN KEY (hashtag_id) REFERENCES hashtag(id) ON DELETE CASCADE
);