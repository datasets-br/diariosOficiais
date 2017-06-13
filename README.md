# Datasets descritores dos Diários Oficiais do Brasil
(*Datasets of the Brazilian [Official Gazettes](https://en.wikipedia.org/wiki/Government_gazette) descriptors*)

Controle semântico e terminológico para a caracterização e identificação uniforme dos diários, das suas seções e das suas matérias. Subsídios para as aplicações [OFICIAL.NEWS](https://github.com/okfn-brasil/oficial.news) e apoio à interoperabilidade fazendo uso das [normas LexML](http://projeto.lexml.gov.br/).

## Fundamentação e referencial de dados

Os diários oficiais são conjuntos de publicações periódicas, não necessariamente diárias ou regulares. Cada uma destas publicaçes é um *fascículo*  do diário, e cada fascículo contém o seu conjunto de *matérias do diário*. Fascículos e matérias precisam ser identificados univocamente.  

Cada jurisdição (ex. cada município specífico) requer seu próprio Diário Oficial, podendo ainda estar ou não  cada Diário Oficial associado a um poder (legislativo, judiciário ou executivo).

Como as matérias podem ser agrupadas em seções (categorias). 

As matérias podem ser publicadas de forma fundida ao fascículo, como na tradição de papel e do formato PDF, ou podem ser publicadas na forma de *separatas*. Nas separatas é importante destacar o contexto (*jurisdição* do Diário e *data* do fascículo), a *autoridade* emitente (responsável) do conteúdo da separata, e *tipo-de-documento* (ex. lei, decreto, contrato, licitação, etc.).

As seções podem designar a *autoridade* e o *tipo-de-documento*, de forma que a extração de metadados (relativos a jurisdição, autoridade e tipo) vai demandar análise das seções. 

Isso tudo se relaciona no seguinte modelo de dados

[![](https://yuml.me/5a79098a.png)](https://yuml.me/5a79098a)


... Descrição dos grupos de *datasets*: ver [data/README.md](data/README.md).

...

* **Identificação das matérias e dos próprios Diários Oficiais**: ver metadados da composição das URN LEX.
* **Identificação única de seções dos diários**: ver "categorias" no modelo de dados.
* ...


...
