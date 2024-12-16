INSERT INTO users (username, user_email, user_password, user_role, created_at)
VALUES
    ('rachid_bensalem', 'rachid@example.ma', 'password123', 'author', '2024-12-13 10:00:00'),
    ('mariam_aziz', 'mariam@example.ma', 'securepassword', 'user', '2024-12-13 10:30:00'),
    ('sami_hassani', 'sami@example.ma', 'password456', 'user', '2024-12-13 11:00:00');

    INSERT INTO articles (article_title, article_content, author_id, create_at)
VALUES
    ('The Best Tourist Destinations in Morocco', 'In this article, we will explore the best tourist destinations in Morocco that you must visit...', 1, '2024-12-13 12:00:00'),
    ('How to Learn Arabic in the Moroccan Dialect', 'If you want to learn Arabic in the Moroccan dialect, here are some tips to help you...', 1, '2024-12-13 12:30:00');

    INSERT INTO likes (article_id, user_id)
VALUES
    (1, 2),  
    (2, 3);  


    INSERT INTO comments (article_id, user_id, comment_content)
VALUES
    (1, 2, 'Great article! I loved learning about new places to visit in Morocco.'),
    (1, 3, 'Some of the most beautiful places I visited in Morocco were Fes and Marrakech.'),
    (2, 1, 'Thanks for the tips! They will be very helpful for learning the Moroccan dialect.');