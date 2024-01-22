CREATE TABLE comment
(
    id      INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    postId  INTEGER,
    author  TEXT,
    content TEXT,
    CONSTRAINT comment_post_fk FOREIGN KEY (postId) REFERENCES post (id)
);

