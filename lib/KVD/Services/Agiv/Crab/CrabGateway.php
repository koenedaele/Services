<?php
/**
 * @package   KVD.Services.Agiv.Crab
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\Crab;

use KVD\Services\Agiv as A;

/**
 * Gateway op de Crab webservice. 
 * 
 * @package   KVD.Services.Agiv.Crab
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be> 
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class CrabGateway
{
    /**
     * client 
     * 
     * @var SoapClient
     */
    protected $client;

    /**
     * __construct 
     * 
     * @param SoapClient $client 
     * @return void
     */
    public function __construct( SoapClient $client )
    {
        $this->client = $client;
    }

    /**
     * listTalen
     *
     * @return array
     */
    public function listTalen( )
    {
        $resultaat = $this->client->ListTalen( );
        $talen = $resultaat->ListTalenResult->CodeItem;
        $res = array( );
        foreach ( $talen as $taal ) {
            $t = new Taal( $taal->Code, $taal->Naam, $taal->Definitie );
            $res[] = $t;
        }
        return $res;
    }

    /**
     * listBewerkingen
     *
     * @return array
     */
    public function listBewerkingen( )
    {
        $resultaat = $this->client->ListBewerkingen( );
        $bewerkingen = $resultaat->ListBewerkingenResult->CodeItem;
        $res = array( );
        foreach ( $bewerkingen as $bewerking ) {
            $b = new Bewerking( $bewerking->Code, $bewerking->Naam, $bewerking->Definitie );
            $res[] = $b;
        }
        return $res;
    }

    /**
     * listOrganisaties
     *
     * @return array
     */
    public function listOrganisaties( )
    {
        $resultaat = $this->client->ListOrganisaties( );
        $organisaties = $resultaat->ListOrganisatiesResult->CodeItem;
        $res = array( );
        foreach ( $organisaties as $organisatie ) {
            $b = new Organisatie( $organisatie->Code, $organisatie->Naam, $organisatie->Definitie );
            $res[] = $b;
        }
        return $res;
    }

    /**
     * listAardSubadressen
     *
     * @return array
     */
    public function listAardSubadressen( )
    {
        $resultaat = $this->client->ListAardSubadressen( );
        $aarden = $resultaat->ListAardSubadressenResult->CodeItem;
        $res = array( );
        foreach ( $aarden as $aard ) {
            $a = new AardSubadres( $aard->Code, $aard->Naam, $aard->Definitie );
            $res[] = $a;
        }
        return $res;
    }

    /**
     * listAardTerreinobjecten
     *
     * @return array
     */
    public function listAardTerreinobjecten( )
    {
        $resultaat = $this->client->ListAardTerreinobjecten( );
        $aarden = $resultaat->ListAardTerreinobjectenResult->CodeItem;
        $res = array( );
        foreach ( $aarden as $aard ) {
            $a = new AardTerreinobject( $aard->Code, $aard->Naam, $aard->Definitie );
            $res[] = $a;
        }
        return $res;
    }

    /**
     * listAardWegobjecten
     *
     * @return array
     */
    public function listAardWegobjecten( )
    {
        $resultaat = $this->client->ListAardWegobjecten( );
        $aarden = $resultaat->ListAardWegobjectenResult->CodeItem;
        $res = array( );
        foreach ( $aarden as $aard ) {
            $a = new AardWegobject( $aard->Code, $aard->Naam, $aard->Definitie );
            $res[] = $a;
        }
        return $res;
    }
    /**
     * listGemeenten 
     * 
     * @param int $volgorde 
     * @return array
     */
    public function listGemeentenByGewestId( $gewestId = 2, $volgorde = 1 )
    {
        $p = new \StdClass();
        $p->GewestId = $gewestId;
        $p->SorteerVeld = $volgorde; 
        $pWrapper = new \SoapParam ( $p , "ListGemeentenByGewestId" );
        $resultaat = $this->client->ListGemeentenByGewestId( $pWrapper );
        $gemeentes = $resultaat->ListGemeentenByGewestIdResult->GemeenteItem;
        $res = array( );
        foreach ( $gemeentes as $gem ) {
            if ( $gem->TaalCodeGemeenteNaam != $gem->TaalCode ) {
                continue;
            }
            $g = new Gemeente( $gem->GemeenteId, $gem->NISGemeenteCode, 
                               array( $gem->TaalCodeGemeenteNaam =>
                                      $gem->GemeenteNaam),
                               $gem->TaalCode);
            $g->setGateway( $this );
            $res[] = $g;
        }
        return $res;
    }

    /**
     * getGemeenteByNisCode
     *
     * @param integer $nisCode
     * @return KVD\Services\Agiv\Crab\Gemeente
     */
    public function getGemeenteByNisCode( $nisCode )
    {
        $p = new \StdClass();
        $p->NISGemeenteCode = $nisCode;
        $pWrapper = new \SoapParam ( $p , "GetGemeenteByNISGemeenteCode" );
        $resultaat = $this->client->GetGemeenteByNISGemeenteCode( $pWrapper );
        $gem = $resultaat->GetGemeenteByNISGemeenteCodeResult;
        $gemeente = new Gemeente( $gem->GemeenteId, $gem->NisGemeenteCode,
                                  array( $gem->TaalCodeGemeenteNaam =>
                                         $gem->GemeenteNaam),
                                  $gem->TaalCode, null,
                                  new A\Centroid( $gem->CenterX, $gem->CenterY),
                                  new A\BoundingBox( $gem->MinimumX, $gem->MinimumY, $gem->MaximumX, $gem->MaximumY ) );
        return $gemeente;
    }

    /**
     * getGemeenteById
     *
     * @param integer $id
     * @return KVD\Services\Agiv\Crab\Gemeente
     */
    public function getGemeenteById( $id )
    {
        $p = new \StdClass();
        $p->GemeenteId = $id;
        $pWrapper = new \SoapParam ( $p , "GetGemeenteByGemeenteId" );
        $resultaat = $this->client->GetGemeenteByGemeenteId( $pWrapper );
        $gem = $resultaat->GetGemeenteByGemeenteIdResult;
        $gemeente = new Gemeente( $gem->GemeenteId, $gem->NisGemeenteCode,
                                  array( $gem->TaalCodeGemeenteNaam =>
                                         $gem->GemeenteNaam),
                                  $gem->TaalCode, null,
                                  new A\Centroid( $gem->CenterX, $gem->CenterY),
                                  new A\BoundingBox( $gem->MinimumX, $gem->MinimumY, $gem->MaximumX, $gem->MaximumY ) );
        return $gemeente;
    }

    /**
     * getGemeenteByNaam
     *
     * @param  string $naam
     * @return KVD\Services\Agiv\Crab\Gemeente
     */
    public function getGemeenteByNaam( $naam, $gewestId = 2 )
    {
        $p = new \StdClass();
        $p->GemeenteNaam = $naam;
        $p->GewestId = $gewestId;
        $pWrapper = new \SoapParam ( $p , "GetGemeenteByGemeenteNaam" );
        $resultaat = $this->client->GetGemeenteByGemeenteNaam( $pWrapper );
        $gem = $resultaat->GetGemeenteByGemeenteNaamResult;
        $gemeente = new Gemeente( $gem->GemeenteId, $gem->NisGemeenteCode,
                                  array( $gem->TaalCodeGemeenteNaam =>
                                         $gem->GemeenteNaam),
                                  $gem->TaalCode, null,
                                  new A\Centroid( $gem->CenterX, $gem->CenterY),
                                  new A\BoundingBox( $gem->MinimumX, $gem->MinimumY, $gem->MaximumX, $gem->MaximumY ) );
        return $gemeente;
    }

    /**
     * listStratenByGemeente
     *
     * @param  Gemeente $gemeente
     * @return array
     */
    public function listStratenByGemeente( Gemeente $gemeente )
    {
        $p = new \StdClass();
        $p->GemeenteId = $gemeente->getId( );
        $p->SorteerVeld = 6;
        $pWrapper = new \SoapParam ( $p , "ListStraatnamenByGemeenteId" );
        $resultaat = $this->client->ListStraatnamenByGemeenteId( $pWrapper );
        $straten = $resultaat->ListStraatnamenByGemeenteIdResult->StraatnaamItem;
        $res = array( );
        foreach ( $straten as $straat ) {
            $namen = array( );
            $namen[$straat->TaalCode] = $straat->Straatnaam;
            if ( isset( $straat->TaalCodeTweedeTaal ) ) {
                if ( isset( $straat->StraatnaamTweedeTaal ) ) {
                    $namen[$straat->TaalCodeTweedeTaal] = $straat->StraatnaamTweedeTaal;
                } else {
                    $namen[$straat->TaalCodeTweedeTaal] = $straat->Straatnaam;
                }
            }
            $s = new Straat( $straat->StraatnaamId, $gemeente,
                             $straat->StraatnaamLabel,
                             $namen,
                             $straat->TaalCode);
            $s->setGateway( $this );
            $res[] = $s;
        }
        return $res;
    }

    /**
     * getStraatById
     *
     * @param integer $id
     * @return Straat
     */
    public function getStraatById( $id )
    {
        $p = new \StdClass( );
        $p->StraatnaamId = $id;
        $pWrapper = new \SoapParam ( $p , "GetStraatnaamByStraatnaamId" );
        $resultaat = $this->client->GetStraatnaamByStraatnaamId( $pWrapper );
        $str = $resultaat->GetStraatnaamByStraatnaamIdResult;
        $namen = array( );
        $namen[$str->TaalCode] = $str->Straatnaam;
        if ( isset( $str->TaalCodeTweedeTaal ) ) {
            $namen[$str->TaalCodeTweedeTaal] = $str->StraatnaamTweedeTaal;
        }
        $gemeente = $this->getGemeenteById( $str->GemeenteId );
        $s = new Straat( $str->StraatnaamId, $gemeente,
                         $str->StraatnaamLabel,
                         $namen,
                         $str->TaalCode);
        return $s;
    }

    /**
     * listWegobjectenByStraat
     *
     * @param  Straat $straat
     * @return array
     */
    public function listWegobjectenByStraat( Straat $straat )
    {

        $p = new \StdClass();
        $p->StraatnaamId = $straat->getId( );
        $p->SorteerVeld = 1;
        $pWrapper = new \SoapParam ( $p , "ListWebobjectenByStraatnaamId" );
        $resultaat = $this->client->ListWegobjectenByStraatnaamId( $pWrapper );
        $wegobjecten = $resultaat->ListWegobjectenByStraatnaamIdResult->WegobjectItem;
        $res = array( );
        foreach ( $wegobjecten as $wegobj ) {
            $wo = new Wegobject( $wegobj->IdentificatorWegobject, $straat,
                                 $wegobj->AardWegobject );
            $wo->setGateway( $this );
            $res[] = $wo;
        }
        return $res;
    }

    /**
     * getWegobjectById
     *
     * @param Straat $straat
     * @param integer $id
     * @return void
     */
    public function getWegobjectById( Straat $straat, $id)
    {
        $p = new \StdClass( );
        $p->IdentificatorWegobject = $id;
        $pWrapper = new \SoapParam ( $p , "GetWegobjectByIdentificatorWegobject" );
        $resultaat = $this->client->GetWegobjectByIdentificatorWegobject( $pWrapper );
        $wegobj = $resultaat->GetWegobjectByIdentificatorWegobjectResult;
        $wo = new Wegobject( $wegobj->IdentificatorWegobject, $straat,
                             $wegobj->AardWegobject,
                             new A\Centroid( $wegobj->CenterX,
                                             $wegobj->CenterY),
                             new A\BoundingBox( $wegobj->MinimumX,
                                                $wegobj->MinimumY,
                                                $wegobj->MaximumX,
                                                $wegobj->MaximumY ) );
        $wo->setGateway( $this );
        return $wo;
    }

    /**
     * listHuisnummersByStraat
     *
     * @param Straat $straat
     * @return array
     */
    public function listHuisnummersByStraat( Straat $straat )
    {
        $p = new \StdClass();
        $p->StraatnaamId = $straat->getId( );
        $p->SorteerVeld = 2;
        $pWrapper = new \SoapParam ( $p , "ListHuisnummersByStraatnaamId" );
        $resultaat = $this->client->ListHuisnummersByStraatnaamId( $pWrapper );
        $huisnummers = $resultaat->ListHuisnummersByStraatnaamIdResult->HuisnummerItem;
        $res = array( );
        foreach ( $huisnummers as $hnr ) {
            $h = new Huisnummer( $hnr->HuisnummerId, $straat,
                                 $hnr->Huisnummer );
            $h->setGateway( $this );
            $res[] = $h;
        }
        return $res;
    }
}
?>
