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
  SELECT DISTINCT name_lex,2,10 
  FROM (SELECT oficial.name2lex(secretaria) as name_lex FROM cateorias_tmp) t
  WHERE name_lex>''
  ORDER BY 1
;

-- autoridades nivel 2
INSERT INTO oficial.autoridades (name,id_jurisdicao,id_pai) 
  SELECT DISTINCT oficial.name2lex(departamento),2, oficial.autoridade_pai_byname(secretaria)
  FROM cateorias_tmp WHERE departamento>''
  ORDER BY 3,1
;


-- categorias nivel 1,seções do DOM-SP
INSERT INTO oficial.categorias (name,level) 
  SELECT DISTINCT oficial.name2lex(secaodoc),1
  FROM categorias_tmp 
  WHERE secaodoc>'';
;

-- categorias nivel 2,seções do DOM-SP
INSERT INTO oficial.categorias (name,level,id_pai) 
  SELECT DISTINCT oficial.name2lex(secretaria),2, categoria_pai_byname(secaodoc)
  FROM categorias_tmp 
  WHERE secretaria>'';
;
