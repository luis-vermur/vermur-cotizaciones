<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            ['nombre' => 'INDORAMA VENTURES POLYMERS MEXICO', 'dias_credito' => 45],
            ['nombre' => 'JJ REMOLQUES EN RENTA', 'dias_credito' => 45],
            ['nombre' => 'MC MACHINERY SYSTEMS', 'dias_credito' => 30],
            ['nombre' => 'MRC MANUFACTURING TECHNOLOGY', 'dias_credito' => 30],
            ['nombre' => 'REFACCIONES SIN PARAR', 'dias_credito' => 45],
            ['nombre' => 'ROCKET FULFILLMENT LLC', 'dias_credito' => 30],
            ['nombre' => 'INTERFRACHT CONTAINER OVERSEAS SERVICE GMBH', 'dias_credito' => 60],
            ['nombre' => 'IMPORTADORA Y EXPORTADORA MAQTECH INTERNACIONAL', 'dias_credito' => 45],
            ['nombre' => 'GLOBIFY MEXICO', 'dias_credito' => 20],
            ['nombre' => 'ROCKET INTERNATIONAL LLC', 'dias_credito' => 30],
            ['nombre' => 'AMERICAN INDUSTRIES DE QUERETARO', 'dias_credito' => 30],
            ['nombre' => 'SIMBIOSIS AGRICOLA', 'dias_credito' => 30],
            ['nombre' => 'PRETTL ENERGY', 'dias_credito' => 90],
            ['nombre' => 'FIT TEAM MEXICO', 'dias_credito' => 30],
            ['nombre' => 'SHEFA FITNESS', 'dias_credito' => 30],
            ['nombre' => 'JLG MAQUINARIA MEXICO', 'dias_credito' => 30],
            ['nombre' => 'AMERICAN INDUSTRIES DEL CENTRO', 'dias_credito' => 30],
            ['nombre' => 'HAVER & BOECKER MEXICANA', 'dias_credito' => 30],
            ['nombre' => 'INGENIERIA EN PLASTICO DE PUEBLA', 'dias_credito' => 60],
            ['nombre' => 'BERICAP MEXICO', 'dias_credito' => 30],
            ['nombre' => 'TIM KOBS', 'dias_credito' => 15],
            ['nombre' => 'SEMADIN', 'dias_credito' => 15],
            ['nombre' => 'SUSPENSIONES Y REPRESENTACIONES', 'dias_credito' => 45],
            ['nombre' => 'CURTIS LOGISTICA INTERNACIONAL EIRELI', 'dias_credito' => 30],
            ['nombre' => 'AVIENT DE MEXICO', 'dias_credito' => 30],
            ['nombre' => 'SEV STANTE OVERSEAS SRL', 'dias_credito' => 30],
            ['nombre' => 'MANUPORT LOGISTICS ESPAÑA SL', 'dias_credito' => 60],
            ['nombre' => 'BAMAL FASTENER CORPORATION', 'dias_credito' => 45],
            ['nombre' => 'APG MEXICO AUTOMOTIVE PLASTICS GROUP MEXICO SA DE CV', 'dias_credito' => 60],
            ['nombre' => 'Z AGRODISTRIBUCIONES JAR SA DE CV', 'dias_credito' => 1],
            ['nombre' => 'GOPLAS SA DE CV', 'dias_credito' => 30],
            ['nombre' => 'HANSEL PROCESSING GMBH', 'dias_credito' => 30],
            ['nombre' => 'NEW CHINA CHEMICALS CO LTD', 'dias_credito' => 30],
            ['nombre' => "CANEL'S", 'dias_credito' => 30],
            ['nombre' => 'MACCAFERRI DE MEXICO', 'dias_credito' => 30],
            ['nombre' => 'QINGDAO POWTECH ELECTRONICS CO LTD', 'dias_credito' => 30],
            ['nombre' => 'BEKO TECHNOLOGIES', 'dias_credito' => 15],
            ['nombre' => 'GE MAO RUBBER INDUSTRIAL CO LTD', 'dias_credito' => 30],
            ['nombre' => 'JHAO YANG RUBBER(VN) COMPANY LIMITED', 'dias_credito' => 30],
            ['nombre' => 'NEXGEN PACKAGING MEXICO', 'dias_credito' => 45],
            ['nombre' => 'ZHENGDAO PARTS CO LTD', 'dias_credito' => 30],
            ['nombre' => 'OPTRONICS', 'dias_credito' => 30],
            ['nombre' => 'LANXESS DEUTSCHLAND GMBH', 'dias_credito' => 30],
            ['nombre' => 'COLORMATRIX EUROPE B.V.', 'dias_credito' => 30],
            ['nombre' => 'PRIDEL PRIVATE LIMITED', 'dias_credito' => 30],
            ['nombre' => 'INNDIGO TRUCKING', 'dias_credito' => 7],
            ['nombre' => 'SPEC PRODUCTS CORP', 'dias_credito' => 30],
            ['nombre' => 'JINGFONG INDUSTRY CO LTD', 'dias_credito' => 30],
            ['nombre' => 'NINGBO JINDING FASTENING PIECE CO LTD', 'dias_credito' => 30],
            ['nombre' => 'HAMON ESINDUS LATINOAMERICA SA DE CV', 'dias_credito' => 30],
            ['nombre' => 'POLYONE DE MEXICO SA DE CV', 'dias_credito' => 30],
            ['nombre' => 'DONGGUAN CHIA-YI HARDWARE PLASTIC PRODUCTS CO., LTD', 'dias_credito' => 30],
            ['nombre' => 'CHANG HSIUNG FACTORY CO. LTD', 'dias_credito' => 30],
            ['nombre' => 'PLIMAT PLASTICOS IND MATOS SA', 'dias_credito' => 30],
            ['nombre' => 'SHOU LONG PRECISION INDUSTRIAL CO LTD', 'dias_credito' => 30],
            ['nombre' => 'FLETEVAL FORWARDING SL', 'dias_credito' => 60],
            ['nombre' => 'PACIFIC COMPONENTS DE MEXICO', 'dias_credito' => 15],
            ['nombre' => 'FIBREMEX', 'dias_credito' => 30],
            ['nombre' => 'CUORE ROSSO', 'dias_credito' => 60],
        ];

        foreach ($clientes as $c) {
            Cliente::updateOrCreate(['nombre' => $c['nombre']], $c);
        }
    }
}