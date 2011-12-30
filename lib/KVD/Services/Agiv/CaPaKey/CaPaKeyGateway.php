<?php
/**
 * @package   KVD.Services.Agiv.CaPaKey
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be>
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */

namespace KVD\Services\Agiv\CaPaKey;

use KVD\Services\Agiv as A;

/**
 * Gateway op de CaPaKey webservice.
 *
 * @package   KVD.Services.Agiv.CaPaKey
 * @since     0.1.0
 * @copyright 2011 Koen Van Daele <koen_van_daele@telenet.be>
 * @author    Koen Van Daele <koen_van_daele@telenet.be>
 * @license   http://www.osor.eu/eupl The European Union Public Licence
 */
class CaPaKeyGateway
{
    /**
     * client
     *
     * @var KVD\Services\Agiv\CaPaKey\SoapClient
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
     * listGemeenten 
     * 
     * @param int $volgorde 
     * @return array
     */
    public function listGemeenten( $volgorde = 1 )
    {
        $p = new \StdClass();
        $p->SorteerVeld = $volgorde;
        $pWrapper = new \SoapParam ( $p, "ListAdmGemeenten" );
        $resultaat = $this->client->ListAdmGemeenten( $pWrapper );
        $gemeentes = $resultaat->ListAdmGemeentenResult->AdmGemeenteItem;
        $res = array( );
        foreach ( $gemeentes as $gem ) {
            $g = new Gemeente( $gem->Niscode, $gem->AdmGemeentenaam );
            $g->setGateway( $this );
            $res[] = $g;
        }
        return $res;
    }

    /**
     * getGemeenteById
     *
     * @param integer $id
     * @return KVD\Services\Agiv\CaPaKey\Gemeente
     */
    public function getGemeenteById( $id )
    {
        $p = new \StdClass();
        $p->Niscode = $id;
        $pWrapper = new \SoapParam ( $p, "GetAdmGemeenteByNiscode" );
        $resultaat = $this->client->GetAdmGemeenteByNiscode( $pWrapper );
        $gem = $resultaat->GetAdmGemeenteByNiscodeResult;
        $gemeente = new Gemeente( $gem->Niscode,
                                  $gem->AdmGemeentenaam,
                                  new A\Centroid( $gem->CenterX,
                                                  $gem->CenterY),
                                  new A\BoundingBox( $gem->MinimumX,
                                                     $gem->MinimumY,
                                                     $gem->MaximumX,
                                                     $gem->MaximumY ) );
        return $gemeente;
    }

    /**
     * listKadastraleAfdelingen
     *
     * @param int $volgorde
     * @return array
     */
    public function listKadastraleAfdelingen( $volgorde = 1  )
    {
        $p = new \StdClass();
        $p->SorteerVeld = $volgorde;
        $pWrapper = new \SoapParam ( $p, "ListKadAfdelingen" );
        $resultaat = $this->client->ListKadAfdelingen( $pWrapper );
        $afdelingen = $resultaat->ListKadAfdelingenResult->KadAfdelingItem;
        $res = array( );
        foreach ( $afdelingen as $afd ) {
            $g = new Gemeente( $afd->Niscode );
            $g->setGateway( $this );
            $a = new Afdeling( $afd->KadAfdelingcode, $afd->KadAfdelingnaam, $g );
            $a->setGateway( $this );
            $res[] = $a;
        }
        return $res;
    }

    /**
     * listKadastraleAfdelingenByGemeente 
     * 
     * @param Gemeente $gemeente 
     * @param int $volgorde 
     * @return array
     */
    public function listKadastraleAfdelingenByGemeente( Gemeente $gemeente, $volgorde = 1 )
    {
        $p = new \StdClass();
        $p->Niscode = $gemeente->getId( );
        $p->SorteerVeld = $volgorde;
        $pWrapper = new \SoapParam ( $p, "ListKadAfdelingenByNiscode" );
        $resultaat = $this->client->ListKadAfdelingenByNiscode( $pWrapper );
        $afdelingen = $resultaat->ListKadAfdelingenByNiscodeResult->KadAfdelingItem;
        $res = array( );
        foreach ( $afdelingen as $afd ) {
            $a = new Afdeling( $afd->KadAfdelingcode, $afd->KadAfdelingnaam, $gemeente );
            $a->setGateway( $this );
            $res[] = $a;
        }
        return $res;
    }

    /**
     * getKadastraleAfdelingById 
     * 
     * @param mixed $id 
     * @return Afdeling
     */
    public function getKadastraleAfdelingById( $id )
    {
        $p = new \StdClass();
        $p->KadAfdelingcode = $id;
        $pWrapper = new \SoapParam ( $p, "GetKadAfdelingByKadAfdelingcode" );
        $resultaat = $this->client->GetKadAfdelingByKadAfdelingcode( $pWrapper );
        $afd = $resultaat->GetKadAfdelingByKadAfdelingcodeResult;
        $g = new Gemeente( $afd->Niscode );
        $g->setGateway( $this );
        $centroid = new A\Centroid( $afd->CenterX, $afd->CenterY );
        $box = new A\BoundingBox( $afd->MinimumX, $afd->MinimumY, $afd->MaximumX, $afd->MaximumY );
        $a = new Afdeling( $afd->KadAfdelingcode, $afd->KadAfdelingnaam, $g,
                           $centroid, $box);
        return $a;
    }

    /**
     * listSectiesByAfdeling 
     * 
     * @param Afdeling $afdeling 
     * @return array
     */
    public function listSectiesByAfdeling( Afdeling $afdeling )
    {
        $p = new \StdClass();
        $p->KadAfdelingcode = $afdeling->getId( );
        $pWrapper = new \SoapParam ( $p, "ListKadSectiesByKadAfdelingcode" );
        $resultaat = $this->client->ListKadSectiesByKadAfdelingcode( $pWrapper );
        $secties = $resultaat->ListKadSectiesByKadAfdelingcodeResult->KadSectieItem;
        $res = array( );
        foreach ( $secties as $sec ) {
            $s = new Sectie( $sec->KadSectiecode, $afdeling );
            $s->setGateway( $this );
            $res[] = $s;
        }
        return $res;
    }

    /**
     * getSectieByIdAndAfdeling 
     * 
     * @param string $id 
     * @param Afdeling $afdeling 
     * @return Sectie
     */
    public function getSectieByIdAndAfdeling( $id, Afdeling $afdeling)
    {
        $p = new \StdClass();
        $p->KadAfdelingcode = $afdeling->getId( );
        $p->KadSectieCode = $id;
        $pWrapper = new \SoapParam ( $p, "GetKadSectieByKadSectiecode" );
        $resultaat = $this->client->GetKadSectieByKadSectiecode( $pWrapper );
        $sec = $resultaat->GetKadSectieByKadSectiecodeResult;
        $centroid = new A\Centroid( $sec->CenterX, $sec->CenterY );
        $box = new A\BoundingBox( $sec->MinimumX, $sec->MinimumY, $sec->MaximumX, $sec->MaximumY );
        $s = new Sectie( $sec->KadSectiecode, $afdeling, $centroid, $box);
        return $s;
    }

    /**
     * listPercelenBySectie 
     * 
     * @param Sectie $sectie 
     * @param int $volgorde 
     * @return array
     */
    public function listPercelenBySectie( Sectie $sectie, $volgorde = 1 )
    {
        $p = new \StdClass();
        $p->KadAfdelingcode = $sectie->getAfdeling( )->getId( );
        $p->KadSectieCode = $sectie->getId( );
        $p->SorteerVeld = $volgorde;
        $pWrapper = new \SoapParam ( $p, "ListKadPerceelsnummersByKadSectiecode" );
        $resultaat = $this->client->ListKadPerceelsnummersByKadSectiecode( $pWrapper );
        $percelen = $resultaat->ListKadPerceelsnummersByKadSectiecodeResult->KadPerceelsnummerItem;
        $res = array( );
        foreach ( $percelen as $perc ) {
            $p = new Perceel( $perc->KadPerceelsnummer, $sectie, $perc->CaPaKey, $perc->PERCID );
            $p->setGateway( $this );
            $res[] = $p;
        }
        return $res;
    }

    /**
     * getPerceelByIdAndSectie
     *
     * @param string $id
     * @param Sectie $sectie
     * @return Perceel
     */
    public function getPerceelByIdAndSectie( $id, Sectie $sectie )
    {
        $p = new \StdClass();
        $p->KadAfdelingcode = $sectie->getAfdeling( )->getId( );
        $p->KadSectieCode = $sectie->getId( );
        $p->KadPerceelsnummer = $id;
        $pWrapper = new \SoapParam ( $p, "GetKadPerceelsnummerByKadPerceelsnummer" );
        $resultaat = $this->client->GetKadPerceelsnummerByKadPerceelsnummer( $pWrapper );
        $perc = $resultaat->GetKadPerceelsnummerByKadPerceelsnummerResult;
        $p = new Perceel( $perc->KadPerceelsnummer, $sectie,
                          $perc->CaPaKey, $perc->PERCID,
                          $perc->CaPaTy, $perc->CaShKey,
                          new A\Centroid( $perc->CenterX,
                                          $perc->CenterY ),
                          new A\BoundingBox( $perc->MinimumX,
                                             $perc->MinimumY,
                                             $perc->MaximumX,
                                             $perc->MaximumY ) );
        return $p;
    }

    /**
     * getPerceelByCaPaKey
     *
     * @param string $capakey
     * @return Perceel
     */
    public function getPerceelByCaPaKey( $capakey )
    {
        $p = new \StdClass();
        $p->CaPaKey = $capakey;
        $pWrapper = new \SoapParam ( $p, "GetKadPerceelsnummerByCaPaKey" );
        $resultaat = $this->client->GetKadPerceelsnummerByCaPaKey( $pWrapper );
        $perc = $resultaat->GetKadPerceelsnummerByCaPaKeyResult;
        $afdeling = $this->getKadastraleAfdelingById( $perc->KadAfdelingcode );
        $sectie = new Sectie( $perc->KadSectiecode, $afdeling);
        $p = new Perceel( $perc->KadPerceelsnummer, $sectie,
                          $perc->CaPaKey, $perc->PERCID,
                          $perc->CaPaTy, $perc->CaShKey,
                          new A\Centroid( $perc->CenterX,
                                          $perc->CenterY ),
                          new A\BoundingBox( $perc->MinimumX,
                                             $perc->MinimumY,
                                             $perc->MaximumX,
                                             $perc->MaximumY ) );
        return $p;
    }

    /**
     * getPerceelByPercid
     *
     * @param string $percid
     * @return Perceel
     */
    public function getPerceelByPercid( $percid )
    {
        $p = new \StdClass();
        $p->PERCID = $percid;
        $pWrapper = new \SoapParam ( $p, "GetKadPerceelsnummerByPERCID" );
        $resultaat = $this->client->GetKadPerceelsnummerByPERCID( $pWrapper );
        $perc = $resultaat->GetKadPerceelsnummerByPERCIDResult;
        $afdeling = $this->getKadastraleAfdelingById( $perc->KadAfdelingcode );
        $sectie = new Sectie( $perc->KadSectiecode, $afdeling);
        $p = new Perceel( $perc->KadPerceelsnummer, $sectie,
                          $perc->CaPaKey, $perc->PERCID,
                          $perc->CaPaTy, $perc->CaShKey,
                          new A\Centroid( $perc->CenterX,
                                          $perc->CenterY ),
                          new A\BoundingBox( $perc->MinimumX,
                                             $perc->MinimumY,
                                             $perc->MaximumX,
                                             $perc->MaximumY ) );
        return $p;
    }
}
?>
