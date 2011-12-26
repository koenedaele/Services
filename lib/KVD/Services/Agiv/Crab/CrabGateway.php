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
            $g = new Gemeente( $gem->Id, $gem->Niscode, 
                               $gem->GemeenteNaam, $gem->TaalCodeGemeenteNaam,
                               $gem->TaalCode, $gem->TaalCodeTweedeTaal );
            $g->setGateway( $this );
            $res[] = $g;
        }
        return $res;
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
        $gemeente = new Gemeente( $gem->Id, $gem->Niscode, 
                                  $gem->GemeenteNaam, $gem->TaalCodeGemeenteNaam,
                                  $gem->TaalCode, $gem->TaalCodeTweedeTaal,
                                  new A\Centroid( $gem->CenterX, $gem->CenterY),
                                  new A\BoundingBox( $gem->MinimumX, $gem->MinimumY, $gem->MaximumX, $gem->MaximumY ) );
        return $gemeente;
    }

}
?>
