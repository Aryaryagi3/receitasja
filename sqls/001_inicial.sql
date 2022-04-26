CREATE DATABASE app3 COLLATE 'utf8_unicode_ci';

CREATE TABLE usuarios (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    nome VARCHAR(255) NOT NULL,
    senha CHAR(60) NOT NULL,
    PRIMARY KEY (id)
)
ENGINE = InnoDB;

CREATE TABLE receitas (
    id INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    data_publicado TIMESTAMP NOT NULL,
    ingredientes TEXT NOT NULL,
    passos TEXT NOT NULL,
    usuario_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
)
ENGINE = InnoDB;

CREATE TABLE comentarios (
    id INT NOT NULL AUTO_INCREMENT,
    mensagem TEXT NOT NULL,
    data_publicado TIMESTAMP NOT NULL,
    receita_id INT NOT NULL,
    usuario_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (receita_id) REFERENCES receitas(id)
)
ENGINE = InnoDB;