<?php

// $xml = new SimpleXMLElement('http://www.lexml.gov.br/vocabulario/autoridade.rdf.xml', 0, true);
$xml = new SimpleXMLElement('tmp/autoridade.rdf.xml', 0, true);
//$namespaces = $xml->getNamespaces(true); var_dump($namespaces);

$nsrdf   = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
$nslexml = 'http://www.lexml.gov.br/voc/br/';
$xml->registerXPathNamespace("skos", "http://www.w3.org/2008/05/skos#");
//$xml->registerXPathNamespace("lexml", "http://www.lexml.gov.br/voc/br/");

foreach ($xml->xpath("//skos:Concept") as $e) {

	$attrs = $e->attributes($nsrdf);
	$urn = $attrs->about;

	$xx    = $e->xpath("skos:prefLabel");
var_dump($xx);
	//$attrs = $xx->attributes($nslexml);
	//$txt = $attrs->faceta;

	echo "\n$urn\n\ttxt";
	//lexml:faceta
	// echo $movies->movie[0]->plot;  $e->asXML();
}
exit("\nby\n\n");

//var_dump($nodes);
//while(list( , $node) = each($result)) {    echo '/a/b/c: ',$node,"\n";}

//$attribs = $resource->attributes('http://www.w3.org/1999/xlink');   // fetch all attributes from the given namespace
//var_dump($attribs->href);   // or maybe var_dump((string)$attribs->href);



$dom = new DOMDocument;
$dom->load('http://www.lexml.gov.br/vocabulario/autoridade.rdf.xml');
$nmax = 0;
// testar o conversor
// se bate nome1 com explode(';') do primeiro

foreach ($dom->getElementsByTagNameNS('http://www.w3.org/2008/05/skos#', 'Concept') as $e) {
	$urn = $e->getAttribute('rdf:about');
	$parts = explode(';',$urn);
	$parts_n = count($parts);
	if ($parts_n<=4);
		echo "\n-- urn";
	$a = explode("::",$e->getAttribute('lexml:faceta'));
	$n = count($a);
	if ($n>$nmax) $nmax=$n;
	$jurisdicao = array_shift($a);
	
	$faceta     = array_shift($a);
}

/**


foreach ($dom->getElementsByTagNameNS('http://www.w3.org/2008/05/skos#', '*') as $e) {
	if ($e->localName=='Concept') {
		$urn = $e->getAttribute('rdf:about');
		echo "\n-- URN:",$urn;
		if (preg_match('/\.(i+|iv|vi*|para|com|d[aeo]s?)(\.|$)/',$urn)) echo "\n\t! confira";
	} else {
		$p = preg_replace('~/rdf:RDF/skos:Concept\[\d+\]/?~i','',$e->getNodePath());
		if ($p=='skos:prefLabel') {
			echo "=".$e->nodeValue;
			$a = explode("::",$e->getAttribute('lexml:faceta'));
			$n = count($a);
			if ($n>$nmax) $nmax=$n;
			$jurisdicao = array_shift($a);
			
			$faceta     = array_shift($a);
			$nome1      = array_shift($a);
			$nome2      = array_shift($a);
			$nomes      = join($a,' / ');
			if ($faceta)
				echo "\n\tfaceta($faceta $n)=".$nome1, ' | ', $nome2, ' | ', $nomes;
			else
				echo "\n\t(sem faceta)=".$e->getAttribute('lexml:faceta');
		}

	}
}

echo "MAX = ",$nmax; // 7 
*/

?>




<skos:Concept rdf:about="senado.federal" rdf:id="5">
	<skos:prefLabel xml:lang="pt-BR" rdf:nodeID="label_28" lexml:faceta="Federal::Legislativo::Senado Federal" 
		lexml:facetaAcronimo="SF – Senado Federal"
	>Senado Federal</skos:prefLabel>
	<skos:altLabel xml:lang="pt-BR" rdf:nodeID="label_38" lexml:acronimoDe="label_28">SF</skos:altLabel>
	<skos:inScheme rdf:resource="#autoridade"/>
</skos:Concept>



