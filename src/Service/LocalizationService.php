<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LocalizationService
{

    private string $leafletGeolocalizationDatas = '';

    public function __construct(ParameterBagInterface $params)
    {
        $this->leafletGeolocalizationDatas = $params->get('leaflet_geolocalization_datas');
    }

    /** 
     * Utilisation d'un fichier json pour avoir la localisation d'un ville à partir de son code postal
     * @param string $zip
     * @return array|null
     */
    public function getGPSCoordinates(string $zip, string $city): ?array
    {
        $datas = json_decode(file_get_contents($this->leafletGeolocalizationDatas), true);
        foreach($datas as $data) {
            if($data['fields']['code_postal'] === $zip && $data['fields']['nom_de_la_commune'] === strtoupper($city)) {

                return [
                    'longitude' => $data['fields']['coordonnees_gps'][1], 
                    'latitude' => $data['fields']['coordonnees_gps'][0]
                ];
            }
        }

        return null;
    }

    public function getCities (string $zip): array {
        $datas = json_decode(file_get_contents($this->leafletGeolocalizationDatas), true);
        $cities = [];
        foreach($datas as $data) {
            if($data['fields']['code_postal'] === $zip) {
                $cities[$data['fields']['nom_de_la_commune']] = $data['fields']['nom_de_la_commune'];       
            }
        }

        return $cities;
    }

    // public function getGPSCoordinates(string $zip): array //$request->headers->get('user-agent') à ajouter en second param de la fonction pour avoir dinamiquement celui fourni par symfony
    // {
    //     $url = "https://nominatim.openstreetmap.org/search.php?country=France&postalcode=$zip&polygon_geojson=1&accept-language=fr&format=jsonv2";
    //     $request = curl_init();
    //     curl_setopt($request, CURLOPT_URL, $url);
    //     curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($request, CURLOPT_HEADER, false);
    //     curl_setopt($request, CURLOPT_REFERER, $url);
    //     curl_setopt($request, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36"); //Second param de la fct à mettre ici
    //     $localization = curl_exec($request);
    //     curl_close($request);
    //     $localization = json_decode($localization);
    //     $lat = $localization[0]->lat;
    //     $long = $localization[0]->lon;

    //     return ['longitude' => $long, 'latitude' => $lat];
    // }

}