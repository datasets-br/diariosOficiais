*Datasets* descritores (metadados) dos diários oficiais do Brasil.

Maiores detalhes: compor [`datapackage.json`](../datapackage.json) na raiz, em conformidade com [specs.frictionlessdata.io](http://specs.frictionlessdata.io/) ou [exemplo](https://github.com/datasets-br/state-codes/blob/master/datapackage.json).

## URN LEX como referência

A organização dos *metadados de controle* para recuperação de dados relativos a Diários Oficiais 
tem como base a definição da [URN-LEX](https://en.wikipedia.org/wiki/Lex_(URN)) na [RFC-draft-urn-lex-v10](https://datatracker.ietf.org/doc/draft-spinosa-urn-lex/),
já adotada no Brasil desde 2009 com o inicio das operações do Portal LexML e da vigência da norma [LexML-URN](http://projeto.lexml.gov.br/documentacao/Parte-2-LexML-URN.pdf), reforçada pelo [E-PING](http://eping.governoeletronico.gov.br/#p2s5) desde 2010.

## Escopo

Apesar de não ser um elemento explícito na norma URN-LEX ou sua especialização LexML-URN, 
as URNs de matérias (separatas dos diários oficiaias) podem ser agrupadas em grandes conjuntos:

* Poder Governamental (`gov`): subdividido em,

  * **Executivo** (`exe`): matérias emitidas por autoridades do Porder Executivo.
  * **Legislativo** (`leg`): matérias emitidas por autoridades do Porder Legislativo.
  * **Judiciário** (`jud`): matérias emitidas por autoridades do Porder Judiciário.
  * **Projetos** (`prj`): proposições e projetos de oficialização (tipicamente "projetos de Lei") de matérias emitidas pelo Legislativo ou Executivo.

* **Organizações** (`org`): associações, condomínios e sociedades anônimas também requerem publicação em Diário Oficial.

* **Modelos de contrato** (`mct`): de interesse misto (duas ou mais autoridades), que requerem publicação em Diário Oficial, e afetam ambos, `gov` e `org`,   de modo que são tratados de forma distinta, tais como as proposições normativas. Além de modelos de contrato, os termos de referência citados por contratos.

O *escopo* pode eventualmente se misturar dentro de um mesmo diário oficial, tipicamente matérias do Judiciário (ex. TRM) e do executivo são publicadas num mesmo diário, 
separadas eventualmente por seções.  De forma geral, todavia, os escopos são claros e sempre encontram-se publicados em meios (diários) separados.

## Jurisdições

As jurisdições são, a grosso modo, os limites geográficos de atuação das *autoridades*. 
Denominada na norma URN-LEX de *jurisdiction*, na sua definição sintática LexML-URN 
é também denominada [*local*](http://okfn-brasil.github.io/getlex/lexBr/#local).

O arquivo [`jurisdicoes.csv`](jurisdicoes.csv) reúne todas as jurisdições previstas, com *URN LEX* respectivo nome oficial expresso por extenso.

O conceito tradicional das "esferas dos três poderes" (municipal, estadual e federal) reforça o uso 
do mapa  da divisão administrativa. Do ponto de vista de relevância quantitativa, essa é, de longe, 
a forma de jurisdição mais usada, bem documentada e amplamente aceita.

Ainda assim, no Brasil existem outras formas de parcelamento do território "para efeitos de jurisdição",
sendo a mais significativa a divisão oficial em bacias hidrográficas, ligada à autoridade dos [Comitês de Bacia](http://www.cbh.gov.br/). 
Há também a divisão por exemplo em territórios indígenas, que leva também a uma correlação geográfica de certas autoridades. 
Ambos exemplos demonstram que, *a priori*, nem sequer a hierarquia federal/estadual/municipal define, 
na escala geral (ex. mapas 1:50.000 ou menores), um mosaico único.  

A jurisdição portanto não pode ser tratada como um meio de classificação, com conjuntos disjuntos: existe a possibilidade 
de interseção espacial entre jurisdições. O que se oferece são regras (não-sintáticas)  "seletoras de mosaico", 
que garantem a independência (disjunção) das hierarquias jurisdição-autoriade. 

A estabiliade e consenso em torno de jurisdições deve ser analisada dentro de cada _escopo_. 
As convenções para se "brecar" uma hierarquia não são claras, 
requerendo que os curadores deste *dataset* tomem eventuais decisões relativas aos limites de hierarquisação (tamanho do _path_ da jurisdição). 

Na esfera municipal, por exemplo, o Executivo de São Paulo e o IBGE adotaram em consenso a segmentação do território em 9 zonas, 
ao passo que o [TRT fixoou 5 regiões](http://trt2.jus.br/indice-noticias-em-destaque/2320-trt-2-desenvolve-projeto-para-divisao-da-jurisdicao-de-sao-paulo) ([atualização](https://trt-2.jusbrasil.com.br/noticias/100378222/trt-2-desenvolve-projeto-para-divisao-da-jurisdicao-do-municipio-de-sao-paulo)).

NOTA: para a curadoria falta conferir por exemplo se a subdivisão municipal será mantida por zonas (subprefeituras como autoridades) ou alterado para distritos (subprefeituras como jurisdições). Quanto ao uso da sigla de UF, é uma extensão da sigla de país, presente na mesma norma ISO 3166-2, e já prevista na próxima versão do LEXML, e revista no datasets-br/state-codes.

## Autoridades

...

Os arquivos `autoridades.csv` ocorrem repetidamente a cada path de jurisdição nas pastas do presente dataset. 
Para evitar o uso de longos nomes e dificuldades de navegação, foram utilizadas as URN LEX abreviadas, conforme dataset `jurisdicao-abrev.csv`...

...
