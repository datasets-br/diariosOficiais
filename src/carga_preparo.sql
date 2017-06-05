----------------- ADD

DROP TABLE categorias_tmp;
CREATE TABLE categorias_tmp (
  secaoDoc text,
  secretaria text,
  departamento text,
  divisao text,
  secao text
);
COPY categorias_tmp FROM '/tmp/cateorias.csv' CSV HEADER;


-- autoridades nivel 1
INSERT INTO oficial.autoridades (name_lex,id_jurisdicao,kx_idpai) 
  SELECT DISTINCT 'secretaria;'||name_lex,2,10 
  FROM (SELECT oficial.name2lex(secretaria) as name_lex FROM cateorias_tmp) t
  WHERE name_lex>''
  ORDER BY 1
;

-- autoridades nivel 2
INSERT INTO oficial.autoridades (name_lex,id_jurisdicao,kx_idpai) 
  SELECT 'secretaria;'||sec||';'||name_lex, 2, pai
  FROM (
	  SELECT DISTINCT 
		 oficial.name2lex(departamento) AS name_lex,
		 oficial.name2lex(secretaria) as sec,
		 oficial.autoridade_pai_byname(secretaria) as pai
	  FROM cateorias_tmp 
  ) t
  WHERE name_lex>'' AND sec>'' AND sec!=name_lex and name_lex!='?' 
  ORDER BY pai,1
;

-- categorias nivel 1, seções do DOM-SP
INSERT INTO oficial.categorias (name_lex,kx_idpai) 
  SELECT DISTINCT name_lex,1
  FROM (SELECT oficial.name2lex(secaodoc) as name_lex FROM cateorias_tmp) t
  WHERE name_lex>'';
;

-- categorias nivel 2, seções do DOM-SP
INSERT INTO oficial.categorias (name_lex,kx_idpai) 
  SELECT sec||';'||name_lex, pai
  FROM (
	  SELECT DISTINCT 
		 oficial.name2lex(secretaria) AS name_lex,
		 oficial.name2lex(secaodoc) as sec,
		 oficial.categoria_pai_byname(secaodoc) as pai
	  FROM cateorias_tmp 
  ) t
  WHERE name_lex>'' AND sec>'' AND sec!=name_lex and name_lex!='?' 
  ORDER BY pai,1
;
