use tradugeek;

-- Tabela de assinatura
CREATE TABLE IF NOT EXISTS `tradugeek`.`assinatura` (
    `idassinatura` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(55) NULL DEFAULT NULL,
    `custo` VARCHAR(45) NULL DEFAULT NULL,
    `traduPermitidas` VARCHAR(45) NULL DEFAULT NULL,
    `maxArquivos` VARCHAR(45) NULL DEFAULT NULL,
    `recursos` VARCHAR(255) NULL DEFAULT NULL,
    `suporte` VARCHAR(45) NULL DEFAULT NULL,
    `limitacoes` VARCHAR(255) NULL DEFAULT NULL,
    `beneficiosExtras` VARCHAR(255) NULL DEFAULT NULL,
    `imagem` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`idassinatura`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

-- Tabela de usuário
CREATE TABLE IF NOT EXISTS `tradugeek`.`usuario` (
    `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
    `adm` TINYINT(1) NULL DEFAULT NULL,
    `nome` VARCHAR(255) NULL DEFAULT NULL,
    `dataNascimento` DATE NULL DEFAULT NULL,
    `sexo` VARCHAR(20) NULL DEFAULT NULL,
    `nomeMaterno` VARCHAR(255) NULL DEFAULT NULL,
    `cpf` VARCHAR(14) NULL DEFAULT NULL,
    `email` VARCHAR(255) NULL DEFAULT NULL,
    `telefoneCelular` VARCHAR(15) NULL DEFAULT NULL,
    `telefoneFixo` VARCHAR(15) NULL DEFAULT NULL,
    `cep` VARCHAR(10) NULL DEFAULT NULL,
    `logradouro` VARCHAR(255) NULL DEFAULT NULL,
    `bairro` VARCHAR(50) NULL DEFAULT NULL,
    `cidade` VARCHAR(50) NULL DEFAULT NULL,
    `estado` VARCHAR(45) NULL DEFAULT NULL,
    `complemento` VARCHAR(255) NULL DEFAULT NULL,
    `numero` VARCHAR(10) NULL DEFAULT NULL,
    `usuario_login` VARCHAR(255) NOT NULL,
    `senha` VARCHAR(255) NOT NULL,
    `status` TINYINT(4) NULL DEFAULT NULL,
    `data_criacao` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `idassinatura` INT(11) NULL DEFAULT NULL, -- A chave estrangeira
    PRIMARY KEY (`idusuario`),
    FOREIGN KEY (`idassinatura`) REFERENCES `tradugeek`.`assinatura` (`idassinatura`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

-- Tabela de log
CREATE TABLE IF NOT EXISTS `tradugeek`.`log` (
    `idlog` INT(11) NOT NULL AUTO_INCREMENT,
    `dataAcesso` DATETIME NULL DEFAULT NULL,
    `pergunta` VARCHAR(255) NULL DEFAULT NULL,
    `resposta` VARCHAR(255) NULL DEFAULT NULL,
    `resultado` VARCHAR(255) NULL DEFAULT NULL,
    `usuario_idusuario` INT(11) NOT NULL,
    PRIMARY KEY (`idlog`, `usuario_idusuario`),
    INDEX `fk_log_usuario_idx` (`usuario_idusuario` ASC),
    CONSTRAINT `fk_log_usuario` FOREIGN KEY (`usuario_idusuario`) REFERENCES `tradugeek`.`usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

-- nava tabela
CREATE TABLE IF NOT EXISTS `tradugeek`.`contrato` (
    `idcontrato` INT(11) NOT NULL AUTO_INCREMENT,
    `idassinatura` INT(11) NOT NULL,
    `idusuario` INT(11) NOT NULL,
    `valor` FLOAT(10, 2) NULL DEFAULT NULL,
    `formaPag` VARCHAR(90) NULL DEFAULT NULL,
    `situacao` INT(1) NULL DEFAULT NULL,
    `dataPag` DATE NULL DEFAULT NULL,
    `dataFim` DATE NULL DEFAULT NULL,
    `periodo` VARCHAR(20) NULL DEFAULT NULL,
    PRIMARY KEY (`idcontrato`),
    FOREIGN KEY (`idassinatura`) REFERENCES `assinatura` (`idassinatura`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

INSERT INTO
    tradugeek.assinatura (
        nome,
        custo,
        traduPermitidas,
        maxArquivos,
        recursos,
        suporte,
        limitacoes,
        beneficiosExtras
    )
VALUES (
        'Plano Gratuito (Free)',
        'Gratuito',
        'Até 5 traduções no mês',
        10,
        'Marca d\'água nas traduções',
        'Básico (resposta em até 48 horas)',
        'Anúncios exibidos no site e nas traduções',
        NULL
    ),
    (
        'Plano Básico (Basic)',
        'R$29.90',
        'Até 50 traduções no mês',
        50,
        'Sem marca d\'água',
        'Prioritário (resposta em até 24 horas úteis)',
        NULL,
        'Sem anúncios; acesso a histórico de traduções'
    ),
    (
        'Plano Premium (Pro)',
        'R$79.90',
        'Ilimitados',
        100,
        'Sem marca d\'água; relatórios detalhados',
        'Prioritário (resposta em até 24 horas úteis)',
        NULL,
        'Sem anúncios; acesso a histórico de traduções'
    );

INSERT INTO
    usuario (
        adm,
        nome,
        dataNascimento,
        sexo,
        nomeMaterno,
        cpf,
        email,
        telefoneCelular,
        telefoneFixo,
        cep,
        logradouro,
        bairro,
        cidade,
        estado,
        complemento,
        numero,
        usuario_login,
        senha
    )
VALUES (
        1,
        'TraduGeek Administrador',
        '1980-06-12',
        'outro',
        'TraduGeek Criadores',
        '173.484.877-42',
        'tradugeek@gmail.com',
        '(21) 94002-8922',
        '(21) 4002-8922',
        '21863-000',
        'Avenida Brasil',
        'Bangu',
        'Rio de Janeiro',
        'RJ',
        'Transporte e Turismo Real Brasil',
        '32800',
        'tdGeek',
        '$2y$10$kLZAD973KnvNPszfubnyIun.9hQq9Gh4BYW.JIcYYnXjTBrtZHh5m'
    );