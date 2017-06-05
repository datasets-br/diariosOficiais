
-- uso geral:
CREATE OR REPLACE FUNCTION reset_idseq(tablename text) RETURNS pg_catalog.void AS 
    $body$  
      BEGIN 
      EXECUTE
      'SELECT setval(pg_get_serial_sequence('''
         || $1 
         ||''', ''id''), coalesce(max(id),0) + 1, false) FROM '|| $1;
      END;  
$body$  LANGUAGE 'plpgsql';

-- --- -- -- --


DROP SCHEMA oficial CASCADE;
CREATE SCHEMA oficial;

CREATE FUNCTION oficial.normalizeterm(text, boolean DEFAULT true)
RETURNS text AS $f$
   SELECT tlib.normalizeterm(CASE WHEN $2 THEN substring($1 from '^[^\.\(\)\/–,;]+' ) ELSE $1 END);
$f$ LANGUAGE SQL IMMUTABLE;

CREATE FUNCTION oficial.name2lex(
-- FALTA DESACENT!
  p_name text, 
  p_normalize boolean DEFAULT true, 
  p_cut boolean DEFAULT true
)
RETURNS text AS $f$
   SELECT trim(regexp_replace(
     CASE WHEN p_normalize THEN oficial.normalizeterm($1,p_cut) ELSE $1 END,
     ' | d?[aeo] |^d?[aeo] ',
     '.', 
     'g'
   ),'.');
$f$ LANGUAGE SQL IMMUTABLE;


CREATE TABLE oficial.jurisdicoes (
  id serial NOT NULL primary key,
  name_lex text NOT NULL, -- canonico, prefixo lex.
  name_original_id int, -- REFERENCES tstore.term(id) com ns=jurisdicoes
  kx_idpai  int,  -- REFERENCES jurisdicoes(id), funcao de name_lex.
  kx JSONb, -- cache de nome completo, abreviacao, etc.
  UNIQUE(name_lex),
  UNIQUE(kx_idpai,name_original_id)
);

INSERT INTO oficial.jurisdicoes (id,name_lex,kx_idpai) VALUES 
  (1,'br',NULL), 
  (2,'br;sp',1),
  (3,'br;rj',1)
;
-- Exemplo função refresh_idpai: UPDATE SET id_pai=(select id from oficial.jurisdicoes where lexname='br') WHERE lexname ~ 'br;..';
SELECT reset_idseq('oficial.jurisdicoes');


CREATE TABLE oficial.autoridades (
  id serial NOT NULL primary key,
  id_jurisdicao int NOT NULL REFERENCES oficial.jurisdicoes(id),
  name_lex text NOT NULL, -- canonico, prefixo lex.
  name_original_id int, -- REFERENCES tstore.term(id) com ns=autoridades
  kx_idpai  int,  -- REFERENCES jurisdicoes(id), funcao de name_lex.
  kx JSONb, -- cache de nome completo, abreviacao, etc.
  UNIQUE(id_jurisdicao,name_lex),  
  UNIQUE(id_jurisdicao,kx_idpai,name_original_id)
);

INSERT INTO oficial.autoridades (id,name_lex,id_jurisdicao) VALUES 
 (1,'indefinido',1), -- geral Brasil, 
 (10,'secretaria',2) -- geral Brasil, 
;
SELECT reset_idseq('oficial.autoridades');

CREATE OR REPLACE FUNCTION oficial.autoridade_pai_byname(
	text, 
	int DEFAULT NULL
) RETURNS int AS $f$
	SELECT  id 
	FROM oficial.autoridades 
	WHERE oficial.name2lex($1)=name_lex AND ($2 IS NULL OR $2=id_pai);
$f$  LANGUAGE SQL IMMUTABLE;


CREATE TABLE oficial.categorias (
  id serial NOT NULL primary key,
  name text not null,
  level smallint, --lexname?
  id_pai  bigint,  -- REFERENCES cateorias(id)
  iscanonico boolean, -- NULL para "nao sei"
  UNIQUE(id_pai,name)  
);


CREATE OR REPLACE FUNCTION oficial.categoria_pai_byname(
	text, 
	int DEFAULT NULL
) RETURNS int AS $f$
	SELECT  id 
	FROM oficial.categorias
	WHERE oficial.name2lex($1)=name AND ($2 IS NULL OR $2=id_pai);
$f$  LANGUAGE SQL IMMUTABLE;

