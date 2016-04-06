CREATE TABLE IF NOT EXISTS categories (
  categoryId INTEGER(10) PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(30) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

CREATE TABLE IF NOT EXISTS articles (
  articleId INTEGER(10) PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  shortDescription TEXT NOT NULL,
  content TEXT NOT NULL,
  price FLOAT DEFAULT 0,
  authorId INTEGER(10) NOT NULL,
  categoryId INTEGER(10),
  FOREIGN KEY (categoryId) REFERENCES categories(categoryId)
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

CREATE TABLE IF NOT EXISTS authors (
  authorId INTEGER(10) PRIMARY KEY AUTO_INCREMENT,
  firstName VARCHAR(20) NOT NULL,
  lastName VARCHAR(20) NOT NULL,
  about TEXT
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

CREATE TABLE IF NOT EXISTS tags (
  tagId INTEGER(10) PRIMARY KEY AUTO_INCREMENT,
  tagName VARCHAR(20) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

CREATE TABLE IF NOT EXISTS users (
  userId INTEGER(10) PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(20) NOT NULL,
  password VARCHAR(32) NOT NULL,
  wallet FLOAT DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

CREATE TABLE IF NOT EXISTS comments (
  commentId INTEGER(10) PRIMARY KEY AUTO_INCREMENT,
  text TEXT NOT NULL,
  date DATETIME NOT NULL,
  userId INTEGER(10) NOT NULL,
  FOREIGN KEY (userId) REFERENCES users(userId)
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;



# Many to Many: Articles-Authors
CREATE TABLE articles_authors (
  articleId INTEGER(10) NOT NULL,
  authorId INTEGER(10) NOT NULL,
  PRIMARY KEY (articleId, authorId),
  FOREIGN KEY (articleId) REFERENCES articles (articleId),
  FOREIGN KEY (authorId) REFERENCES authors(authorId)
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

# Many to Many: Articles-Tags
CREATE TABLE IF NOT EXISTS articles_tags (
  articleId INTEGER(10) NOT NULL,
  tagId INTEGER(10) NOT NULL ,
  PRIMARY KEY (articleId, tagId),
  FOREIGN KEY (articleId) REFERENCES articles (articleId),
  FOREIGN KEY (tagId) REFERENCES tags(tagId)
) ENGINE = InnoDB DEFAULT CHARSET = UTF8;

# Many to Many: Articles-Users
CREATE TABLE IF NOT EXISTS orders (
  orderId INTEGER(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  price FLOAT NOT NULL,
  orderDate DATETIME NOT NULL,
  customerId INTEGER(10) NOT NULL,
  articleId INTEGER(10) NOT NULL,
  FOREIGN KEY (customerId) REFERENCES users(userId),
  FOREIGN KEY (articleId) REFERENCES articles (articleId)
);

# indexes
CREATE INDEX articles_authors_idx_1
ON articles_authors(articleId);

CREATE INDEX articles_tags_idx_1
ON articles_tags(articleId);

CREATE UNIQUE INDEX users_username_uindex ON users (username);