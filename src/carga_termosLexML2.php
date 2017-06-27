<?php
/**
 * Carga e análise dos arquivos XML do Vocabulário oficial LexML.
 * Rodar direto no terminal, usando opcioalmente pasta com arquivos.

No caso de L 
--br;piaui;madeiro #	Municípios::Madeiro - PI#pPLUS(3): 2 facetas ?sem virgula?
falta desmembrar o nome depois de :: e trocando piau pelo "-PI" lower

 */



// Configs:
$NS0 = 'http://www.w3.org/2008/05/skos#';
$pasta = './tmp/';
$MAXURN_LOC = 5; // jurisdicoes

// Inicializações:
$opts = getopt("m:hdr");
$modo = isset($opts['m'])? substr($opts['m'],0,1): '';
switch ($modo) {
case 'a': $ALVO = 'autoridade';
	break;
case 'e': $ALVO = 'evento';
	break;
case 'l': $ALVO = 'localidade';
	break;
case 't': $ALVO = 'tipoDocumento';	
	break;
default:
	die("\nDigite o modo de operação com option '-m' seguida do valor. Acrescente '-d' para relatório de diferenças.
	-m a|l|t|e\n\tautoridade|localidade|tipoDocumento|evento
	\n");
}


$estado2abrev = ['acre'=>'ac','alagoas'=>'al','amazonas'=>'am','amapa'=>'ap','bahia'=>'ba','ceara'=>'ce','distrito.federal'=>'df',
	'espirito.santo'=>'es','fernando.noronha'=>'fn','guanabara'=>'gb','goias'=>'go','guapore'=>'gu','iguacu'=>'ig',
	'maranhao'=>'ma','minas.gerais'=>'mg','mato.grosso.sul'=>'ms','mato.grosso'=>'mt','para'=>'pa','paraiba'=>'pb',
	'pernambuco'=>'pe','piaui'=>'pi','ponta.pora'=>'pp','parana'=>'pr','rio.branco'=>'rb','rio.janeiro'=>'rj',
	'rio.grande.norte'=>'rn','rondonia'=>'ro','roraima'=>'rr','rio.grande.sul'=>'rs','santa.catarina'=>'sc',
	'sergipe'=>'se','sao.paulo'=>'sp','tocantins'=>'to'
];
$LocRgx = 'br;('.join('|',array_keys($estado2abrev)).')';


// Preparo do XML:
$f0 = "$ALVO.rdf.xml";
$f  = "$pasta$f0";
if (!file_exists($f)) 
	$f = "http://www.lexml.gov.br/vocabulario/$f0";
$dom = new DOMDocument;
$dom->load($f);


// Gerando CSV:
$items = [];
$n = 0;
foreach ( $dom->getElementsByTagNameNS($NS0,'*')  as  $e ) 
	switch ($e->localName) {
		case 'Concept':
			$urn = $e->getAttribute('rdf:about');
			if ($modo=='l')  $urn = preg_replace_callback(
					"/^$LocRgx/i",
					function ($m) { global $estado2abrev; return 'br;'.$estado2abrev[$m[1]]; },
					$urn
				);
			$items[$n] = ['urn'=>$urn, 'urn_parts'=>explode(';',$urn), 'faceta'=>'', 'faceta_parts'=>null];
			break;
		case 'prefLabel':
			$name = $e->getAttribute('lexml:faceta');
			if ($modo=='l')
				$name = preg_replace('/\s+\-($|\s+[^: ]+)/us', '', $name);
			$items[$n]['faceta'] = $name;
			$items[$n]['faceta_parts'] = explode('::',$name);
			$n++;
			break;
	}

$diffs = [];
$OUT = '';
$doDiff = isset($opts['r']) || isset($opts['d']);
//echo "\n--isDiff = $doDiff\n";

foreach($items as $i) {
	$urn = $i['urn'];
	$urn_nx = count($i['urn_parts']);
	$faceta_n = count($i['faceta_parts']);
	$diff = $urn_nx + (($modo=='a')? 2: 1) - $faceta_n;
	if (!$doDiff) {
		$OUT.= "\n$urn_nx,$diff," . join(',',$i['urn_parts']) . str_pad('',$MAXURN_LOC-count($i['urn_parts']), ",");
		$OUT.= join(',',$i['faceta_parts']);
	} else {
		$OUT.= "\n--! $i[urn] # $i[faceta] ";
		keySum($diffs,$diff);

		if ( trim(str_replace('::','',$i['faceta'])) ) switch ($diff.$modo) {
		case '0l': 
			$OUT.= "#normal?: esfera|estado";
			break;	
		case '1l': 
			$OUT.= ($urn_nx>2)? "#p1 n=$urn_nx esfera|estado|municipio - uf": "#p1 n=$urn_nx esfera|estado - uf";
			break;
		case '2l': 
			$OUT.= "#p2 n=$urn_nx esfera|estado|municipio - uf";
			break;

		case '0a':
			$OUT.= "#normal: judisdição|escopo|nomes";
			break;
		case '1a': 
			$OUT.= "#p1 judisdição|nomes ?";
			break;
		case '2a': 
			$OUT.= "#p2 judisdição";
			break;
		case '3a': case '4a': case '5a': // erros!
			$OUT.= "#pPLUS($diff): $faceta_n facetas ".(
				(strpos($i['urn'],',')===FALSE)? '?sem virgula?':'Ok!virgula'
			); 
			break;
		case '-1a': 
			$OUT.= "#neg1: judisdição|escopo|NOMESUP|nomes";
			break;
		case '-2a': 
			$OUT.= "#neg2: judisdição|escopo|NOMESUP1|NOMESUP2|nomes";
			break;
		case '-3a': case '-4a': // revisar
			// confira ultima faceta vazia.
			$OUT.= "#negPLUS($diff):".(
				(strpos($i['urn'],',')===FALSE)? '?sem virgula?':'Ok!virgula'
			); 
			break;
		default:
			$OUT.= "#ERROR(modo=$modo diff=$diff)!";
		} // if-switch
		else 
			$OUT.= "#SEM-FACETA!";
	} // if
} // for

if (isset($opts['d'])) { // RELATÓRIO DE DIFFS:
	echo "\n---- Contagem de frequência:";
	foreach($diffs as $d=>$val) echo "\n\tdiff($d)=$val";
	echo "\n";

} else { // GERA CSV:
	echo "$OUT";
}



// LIB SNIPPETS

function keySum(&$a,$k) { if (array_key_exists($k,$a)) $a[$k]++; else $a[$k] = 1; }

?>

As autoridades civis do Poder Executivo são:
	Autoridades federais
		Presidente da República;
		Vice-Presidente da República;
		Ministros de Estado.
	Autoridades estaduais
		Governadores dos Estados;
		Vice-Governadores dos Estados;
		Secretários Estaduais
	Autoridades municipais
		Prefeitos Municipais
		Vice-Prefeitos Municipais
		Secretários Municipais



Sinônimo corretivo = eleva hierarquia
Sinônimo abreviativo = reduz hierarquia ou abrevia
Sinônimo = forma não-canônica e supostamente incorreta 


<skos:Concept rdf:about="senado.federal" rdf:id="5">
	<skos:prefLabel xml:lang="pt-BR" rdf:nodeID="label_28" lexml:faceta="Federal::Legislativo::Senado Federal" 
		lexml:facetaAcronimo="SF – Senado Federal"
	>Senado Federal</skos:prefLabel>
	<skos:altLabel xml:lang="pt-BR" rdf:nodeID="label_38" lexml:acronimoDe="label_28">SF</skos:altLabel>
	<skos:inScheme rdf:resource="#autoridade"/>
</skos:Concept>



