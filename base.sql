CREATE TABLE page (
    id_page SERIAL PRIMARY KEY,
    slug VARCHAR(150) UNIQUE NOT NULL
);

CREATE TABLE type (
    id_type SERIAL PRIMARY KEY,
    nom_type VARCHAR(100) NOT NULL,
    balise VARCHAR(50)
);

CREATE TABLE contenu (
    id_contenu SERIAL PRIMARY KEY,
    texte TEXT NOT NULL,
    id_type INT,
    slug VARCHAR(150) UNIQUE,
    id_page INT,
    id_parent INT,
    ordre INT DEFAULT 0,

    FOREIGN KEY (id_type) REFERENCES type(id_type) ON DELETE SET NULL,
    FOREIGN KEY (id_page) REFERENCES page(id_page) ON DELETE CASCADE,
    FOREIGN KEY (id_parent) REFERENCES contenu(id_contenu) ON DELETE CASCADE
);

CREATE TABLE image (
    id_image SERIAL PRIMARY KEY,
    id_contenu INT,
    path TEXT,
    
    FOREIGN KEY (id_contenu) REFERENCES contenu(id_contenu) ON DELETE CASCADE
);

CREATE TABLE tag (
    id_tag SERIAL PRIMARY KEY,
    libelle VARCHAR(100) UNIQUE
);

CREATE TABLE contenu_tag (
    id_contenu_tag SERIAL PRIMARY KEY,
    id_contenu INT,
    id_tag INT,

    FOREIGN KEY (id_contenu) REFERENCES contenu(id_contenu) ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tag(id_tag) ON DELETE CASCADE
);

CREATE TABLE role (
    id_role SERIAL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

CREATE TABLE utilisateur (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL,
    id_role INT,

    FOREIGN KEY (id_role) REFERENCES role(id_role) ON DELETE SET NULL
);