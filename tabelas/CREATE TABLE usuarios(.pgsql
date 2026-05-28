CREATE TABLE usuarios(
    id SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    senha TEXT NOT NULL
);

CREATE TABLE clientes(
    id SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    cep INT NOT NULL,
    numeroCasa INT NOT NULL,
    telefone VARCHAR(11) NOT NULL
);

CREATE TABLE produtos(
    id SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    marca TEXT NOT NULL,
    preco DECIMAL NOT NULL
);

CREATE TABLE pedidos(
    id SERIAL PRIMARY KEY,
    idClientes INT REFERENCES clientes(id),
    idUsuarios INT REFERENCES usuarios(id),
    idProdutos INT REFERENCES produtos(id),
    quantidade INT NOT NULL,
    status 

)


