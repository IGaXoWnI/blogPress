CREATE DATABASE blog ;


CREATE TYPE role_enum AS ENUM ('author','user');
CREATE TABLE users(
    user_id BIGSERIAL NOT NULL PRIMARY KEY ,
    username VARCHAR(50) NOT NULL UNIQUE ,
    user_email VARCHAR(100) NOT NULL UNIQUE ,
    user_password VARCHAR(50) NOT NULL ,
    user_role role_enum NOT NULL ,
    created_at TIMESTAMP NOT NULL 
);



CREATE TABLE articles (
    article_id BIGSERIAL NOT NULL PRIMARY KEY ,
    article_title VARCHAR(100) NOT NULL ,
    article_content TEXT NOT NULL ,
    author_id BIGINT REFERENCES users(user_id) NOT NULL ,
    view_count INT default 0 ,
    like_count INT default 0 ,
    create_at TIMESTAMP NOT NULL 
);

CREATE TABLE likes(
    like_id BIGSERIAL NOT NULL PRIMARY KEY , 
    article_id BIGINT REFERENCES articles(article_id) ,
    user_id BIGINT REFERENCES users(user_id) 
);

CREATE TABLE comments(
    comment_id BIGSERIAL NOT NULL PRIMARY KEY , 
    article_id BIGINT REFERENCES articles(article_id) ,
    user_id BIGINT REFERENCES users(user_id) ,
    comment_content TEXT NOT NULL ,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

