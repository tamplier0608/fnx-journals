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

# insert authors
INSERT INTO authors (firstName, lastName, about) VALUES ('Gordon', 'Ramsay', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo.');
INSERT INTO authors (firstName, lastName, about) VALUES ('Roger', 'Penrose', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo.');
INSERT INTO authors (firstName, lastName, about) VALUES ('Johann', 'Voynich', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo.');
INSERT INTO authors (firstName, lastName, about) VALUES ('Martha', 'Stewart', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo.');
INSERT INTO authors (firstName, lastName, about) VALUES ('Henry', 'Walton Jones III', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo.');

# insert categories
INSERT INTO categories (categoryId, name) VALUES (1, 'Health');
INSERT INTO categories (categoryId, name) VALUES (2, 'Science');
INSERT INTO categories (categoryId, name) VALUES (3, 'Archeology');
INSERT INTO categories (categoryId, name) VALUES (4, 'Miscellaneous');
INSERT INTO categories (categoryId, name) VALUES (5, 'Daily Life');

# insert tags
INSERT INTO tags (tagId, tagName) VALUES (1, 'everyday');
INSERT INTO tags (tagId, tagName) VALUES (2, 'healthy');
INSERT INTO tags (tagId, tagName) VALUES (3, 'fit');
INSERT INTO tags (tagId, tagName) VALUES (4, 'research');
INSERT INTO tags (tagId, tagName) VALUES (5, 'breakthrough');
INSERT INTO tags (tagId, tagName) VALUES (6, 'technology');
INSERT INTO tags (tagId, tagName) VALUES (7, 'digsite');
INSERT INTO tags (tagId, tagName) VALUES (8, 'ruins');
INSERT INTO tags (tagId, tagName) VALUES (9, 'law');
INSERT INTO tags (tagId, tagName) VALUES (10, 'funny');
INSERT INTO tags (tagId, tagName) VALUES (11, 'mistake');
INSERT INTO tags (tagId, tagName) VALUES (12, 'habits');

# insert users
INSERT INTO users (username, password, wallet) VALUES ('test-user1', '13fa508eacfa943f8b0c51a46561fc89', 999);
INSERT INTO users (username, password, wallet) VALUES ('test-user2', 'a5689a0f9fc49923783a896dbb765ae9', 999);

# insert articles
INSERT INTO articles (articleId, title, shortDescription, content, price, categoryId) VALUES (
  1,
  'Healthy dish you can preapare quickly',
  'Nulla vestibulum nec, dignissim in, cursus molestie. Donec est. Integer neque quis porta nisl. Nam pulvinar, quam molestie ultricies vitae.',
  'Lorem ipsum primis in erat consectetuer viverra semper orci, viverra lacinia. Vestibulum aliquam lacinia, risus nunc, placerat ornare dapibus. Aenean et netus et velit. Duis hendrerit magna sapien, tempus ac, dictum sed, vestibulum vehicula. Etiam leo at risus commodo ante. Curabitur elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi sem dolor eu wisi. Suspendisse at lorem non orci. Proin gravida sit amet nunc volutpat a, pellentesque sed, sodales pede. Duis vulputate nunc. Praesent tortor. Donec vitae felis. Mauris leo. Donec molestie a, tellus. Suspendisse at magna. Etiam vestibulum tristique vitae, lectus. Nam suscipit, risus velit, a dolor lacus, congue quis, dictum id, eleifend purus scelerisque odio sit amet felis odio vitae fringilla fringilla eget, nulla. Nunc justo ac posuere cubilia Curae, Sed vehicula wisi, aliquam arcu. Sed feugiat sapien, congue odio non arcu. Nam risus et ultrices iaculis. Curabitur arcu elit, dictum ut, diam.',
  0,
  1
);

INSERT INTO articles_authors (articleId, authorId) VALUES (1, 1);
INSERT INTO articles_tags (articleId, tagId) VALUES (1, 1);
INSERT INTO articles_tags (articleId, tagId) VALUES (1, 2);
INSERT INTO articles_tags (articleId, tagId) VALUES (1, 3);

INSERT INTO articles (articleId, title, shortDescription, content, price, categoryId) VALUES (
  2,
  'Germanium-based CPU cores',
  'Cum sociis natoque penatibus et ultrices urna, pellentesque tincidunt, velit in dui. Lorem ipsum aliquet elit. Mauris luctus et magnis.',
  'Curae, Mauris vel risus. Nulla facilisi. Nullam et lacus a mauris. Nunc ultricies tortor id tortor quis massa ac ipsum. Proin cursus, mi quis viverra elit. Nunc consectetuer adipiscing ornare. Nam molestie. Quisque pharetra, urna ut urna mauris, consectetuer nisl. Fusce mollis, orci a augue. Nam scelerisque pede ac nisl. Morbi fermentum leo facilisis dui ligula, quis eleifend eget, nunc. Nunc velit non sem. Nam lorem eu eros. Pellentesque laoreet metus vitae tellus consectetuer adipiscing quam sagittis eget, bibendum ac, ultricies vehicula, dui gravida vitae, lectus. Curabitur commodo. Curabitur condimentum magna sapien, nec tellus. Quisque nulla. Aenean massa molestie justo euismod scelerisque vel, ipsum. Nunc accumsan at, egestas risus libero, posuere cubilia Curae, In accumsan augue nec turpis quis euismod nibh, dignissim dolor eu elementum quis, ornare at, bibendum porttitor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Curabitur eu dui quis justo. Vestibulum enim.',
  1.5,
  2
);

INSERT INTO articles_authors (articleId, authorId) VALUES (2, 2);
INSERT INTO articles_tags (articleId, tagId) VALUES (2, 4);
INSERT INTO articles_tags (articleId, tagId) VALUES (2, 5);
INSERT INTO articles_tags (articleId, tagId) VALUES (2, 6);

INSERT INTO articles (articleId, title, shortDescription, content, price, categoryId) VALUES (
  3,
  'A Pyramid? Found one more!',
  'Morbi vitae dui odio nonummy eget, dignissim porttitor, arcu nunc ut erat. Duis ut aliquet ipsum sit amet, ante. Morbi.',
  'Aenean feugiat nec, nibh. Donec dolor nibh, dignissim tempor, pede urna mi, nec sapien mauris lacus a blandit malesuada. Suspendisse vel leo. In euismod. Integer lacinia id, sapien. Maecenas sapien quis consectetuer dignissim, lorem fermentum mi, viverra ligula. Phasellus ac lectus. Sed adipiscing risus at tortor. Integer neque ut venenatis augue quis pellentesque consectetuer, augue at consequat tortor, pretium vitae, tortor. Proin dui at sapien. Maecenas imperdiet convallis. Fusce blandit justo, posuere cubilia Curae, Vivamus semper quis, tellus. Aliquam nulla. Aliquam ultricies lobortis sed, ullamcorper varius nunc, tempus nunc. Ut sodales, dictum sed, aliquet elit, varius risus metus gravida non, nunc. Sed aliquet quis, ornare quam. Vestibulum ullamcorper augue, sagittis urna. Donec odio sit amet, sodales nulla. Phasellus urna fringilla vel, ornare bibendum sapien vitae lectus vulputate faucibus. Sed sagittis nibh ultricies leo. In urna. Proin imperdiet dignissim volutpat at, pretium pellentesque. Praesent gravida iaculis odio, sagittis lacus et augue.',
  5.75,
  3
);

INSERT INTO articles_authors (articleId, authorId) VALUES (3, 5);
INSERT INTO articles_tags (articleId, tagId) VALUES (3, 4);
INSERT INTO articles_tags (articleId, tagId) VALUES (3, 7);
INSERT INTO articles_tags (articleId, tagId) VALUES (3, 8);
INSERT INTO articles_tags (articleId, tagId) VALUES (3, 5);
INSERT INTO articles_tags (articleId, tagId) VALUES (3, 12);

INSERT INTO articles (articleId, title, shortDescription, content, price, categoryId) VALUES (
  4,
  'The prosecution just couldn''t handle the truth',
  'Vestibulum convallis nisl, sollicitudin sed, fermentum facilisis. Maecenas fermentum quis, velit. Duis lobortis, varius sit amet, felis. Pellentesque porta tincidunt.',
  'Mauris vestibulum ligula. Ut sagittis, nunc semper feugiat. Cum sociis natoque penatibus et eros orci luctus et lectus. Curabitur placerat, nisl ac odio eget velit wisi, ullamcorper mauris. Etiam ac ligula. Lorem ipsum aliquet feugiat nec, scelerisque arcu. Sed mauris sit amet, vulputate luctus. Sed fringilla sollicitudin, odio vitae velit sit amet, massa. Nunc gravida. Suspendisse est. Lorem ipsum dolor ut magna. Quisque vestibulum. Nulla consequat sed, ullamcorper ac, laoreet a, ligula. Aenean non felis. Pellentesque at ipsum. Aliquam consequat eu, luctus bibendum. Nulla eleifend purus consectetuer massa. Proin sed porta turpis velit, scelerisque odio eget augue commodo volutpat quam nibh faucibus in, dapibus sit amet, iaculis ante, tincidunt quis, ultricies porta. Vivamus pede. Vestibulum aliquam enim. Nunc leo. Phasellus ipsum dolor placerat vehicula elit ac turpis egestas. Mauris rutrum, enim sed massa in accumsan congue. Donec vitae libero at purus. Sed nonummy id, elit. Curabitur fringilla ante sodales eu.',
  0,
  4
);

INSERT INTO articles_authors (articleId, authorId) VALUES (4, 3);
INSERT INTO articles_tags (articleId, tagId) VALUES (4, 9);
INSERT INTO articles_tags (articleId, tagId) VALUES (4, 10);
INSERT INTO articles_tags (articleId, tagId) VALUES (4, 11);

INSERT INTO articles (articleId, title, shortDescription, content, price, categoryId) VALUES (
  5,
  'A walk to the park',
  'Lorem ipsum dolor sapien accumsan congue. Donec ullamcorper, lorem hendrerit wisi. Vestibulum turpis egestas. Aenean posuere elementum odio fermentum suscipit.',
  'Nullam aliquet. Vestibulum cursus vitae, sollicitudin mauris sed viverra elit viverra quis, faucibus orci quis nibh. Curabitur ultrices urna, pellentesque leo. Aenean congue porta, metus sed est. Quisque ut mauris sed eros malesuada vitae, dapibus vel, orci. Sed mauris turpis, molestie turpis sagittis libero. Morbi pede. In quis nibh vel lectus. Nullam rutrum et, faucibus orci vitae dui eget sem tincidunt in, tristique eget, aliquam purus. Integer eget tempus ornare arcu quis nibh. Phasellus lorem tempus nunc. Etiam dictum ut, pulvinar interdum. Quisque cursus, mi libero, id rutrum nulla, auctor scelerisque, ante nec quam. Sed porttitor, quam risus, pellentesque at, metus. Vestibulum ante in nonummy id, semper quis, aliquam arcu. In molestie lorem velit eleifend quam nunc, vitae fringilla mi, eu felis augue id leo vitae est sit amet felis mollis pulvinar. Nulla euismod, quam at arcu. Etiam nibh eu urna. Aenean bibendum vitae, vulputate adipiscing. Mauris eget lectus felis.',
  0,
  5
);

INSERT INTO articles_authors (articleId, authorId) VALUES (5, 4);
INSERT INTO articles_tags (articleId, tagId) VALUES (5, 9);
INSERT INTO articles_tags (articleId, tagId) VALUES (5, 10);