# KVD Services

Deze bibliotheek bevat code om een aantal services van het AGIV te gebruiken vanuit php. Er zijn interfaces beschikbaar voor zoals wel CaPaKey service als de CRAB service. Om de CaPaKey service te kunnen gebruiken heb je toegang nodig. Een login en wachtwoord kan je verkrijgen op de website van het AGIV (http://www.agiv.be). De CRAB service valt onder open data en is vrij toegankelijk.

## Build status

[![travis-ci status](https://secure.travis-ci.org/koenedaele/Services.png)](http://travis-ci.org/koenedaele/Services)

## Gebruik van de Unit Tests

Deze bibliotheek werd ontwikkeld met bijhorende unit tests. Om te controleren of de code naar behoren werkt op uw systeem kun je deze uitvoeren. Hiervoor is een recente versie van PHPUnit nodig (3.5 of 3.6) en een recente versie van Phing (2.4).

Vooraleer je de tests met de capakey webservice kunt uitvoeren moet je je gebruikersnaam en wachtwoord kenbaar maken. Doe dit door een bestand build.properties aan te maken en daarin het volgende te zetten:

	crab.run_integration_tests=false

	capakey.user=<gebruiker>
	capakey.password=<wachtwoord>
	capakey.run_integration_tests=false

Vervang <gebruiker> en <wachtwoord> door je gegevens die je van het AGIV hebt gekregen. De parameters run_integration_tests geven aan of er unit tests moeten gebruikt worden die de webservice rechtstreeks oproepen. Indien deze op false staat zal enkel getest worden wat met een mock verbinding kan getest worden.

De unit tests voer je als volgt uit:

	phing runTests

Indien je een volledig rapport over code coverage wil, doe je:

	phing genTestReports

Dit commando zal een map build/reports/coverage aanmaken waar je kunt zie welke stukken van de code gecovered worden door unit tests.

## CaPaKey Gateway

###Voorbeelden

Aanmaken van de Gateway.

```php
use KVD\Services\Agiv\caPaKey\SoapClient;                                          
use KVD\Services\Agiv\CaPaKey\CaPaKeyGateway;

$wsdl = 'http://ws.agiv.be/capakeyws/nodataset.asmx?WSDL';
$client = new SoapClient( $wsdl, array( 'trace' => 1 ) );
$client->setAuthentication( $gebruiker, $wachtwoord );
$gateway = new CaPaKeyGateway( $client );
```

Afdalen tot een perceel.

```php
$gemeenten = $gateway->listGemeenten( );
$gemeente = $gemeenten[0];
$afdelingen = $gateway->listKadastraleAfdelingenByGemeente( $gemeente );
$afdeling = $afdelingen[0];
$secties = $gateway->listSectiesByAfdeling( $afdeling );
$sectie = $secties[0];
$percelen = $gateway->listPercelenBySectie( $sectie );
$perceel = $percelen[0];
echo $perceel->getCaPaKey();
```

Rechtstreeks informatie over een perceel ophalen op basis van de CaPaKey

```php
$capakey = '40613A1154/02C000';
$perceel = $gateway->getPerceelByCaPaKey( $capakey );
echo 'Gemeente: ' . $perceel->getSectie()->getAfdeling()->getGemeente()->getNaam() . "\n";
echo 'Afdeling: ' . $perceel->getSectie()->getAfdeling()->getNaam() . "\n";
echo 'Sectie: ' . $perceel->getSectie()->getId() . "\n";
echo 'Perceelsnummer: ' . $perceel->getId() . "\n";
```

## CRAB Gateway

In tegenstelling tot de Capakey gateway en tot vroeger heb je voor de CRAB service 
geen wachtwoord meer nodig.

###Voorbeelden

Aanmaken van de Gateway.

```php
use KVD\Services\Agiv\Crab\SoapClient;                                          
use KVD\Services\Agiv\Crab\CrabGateway;

$wsdl = 'http://crab.agiv.be/wscrab/wscrab.svc?wsdl';
$client = new SoapClient( $wsdl, array( 'trace' => 1 ) );
$gateway = new CrabGateway( $client );
```

Afdalen tot een huisnummer.

```php
$gemeenten = $gateway->listGemeentenByGewestId( );
$gemeente = $gemeenten[0];
$straten = $gateway->listStratenByGemeente( $gemeente );
$straat = $straten[0];
$huisnummers = $gateway->listHuisnummersByStraat( $straat );
$huisnummer = $huisnummers[0];
echo $huisnummer->getStraat()->getLabel() . ' ' . $huisnummer->getId() . PHP_EOL;
```
