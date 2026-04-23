<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            ['nombre' => 'SKHOLL RISK MANAGEMENT, S.A.',                    'terminos_pago' => 30, 'correo' => 'dgi@gala.com.pa'],
            ['nombre' => 'Charter Link Logistics',                           'terminos_pago' => 30, 'correo' => 'irma.carrasco@ctl-latam.com'],
            ['nombre' => 'AMASS GLOBAL NETWORK MEXICO',                      'terminos_pago' => 20, 'correo' => 'irodriguez@agn-mexico.com.mx'],
            ['nombre' => 'Supply Logistics Solutions LTDA',                  'terminos_pago' => 30, 'correo' => 'docs@slsholding.com'],
            ['nombre' => 'TRANSPORTES GODOY CABRERA',                        'terminos_pago' => 30, 'correo' => 'logistica@transportesgodoy.com'],
            ['nombre' => 'CLOVERSTAR LOGISTICS CO.,LTD',                     'terminos_pago' => 30, 'correo' => 'overseas05@clv-shipping.com'],
            ['nombre' => 'ASIA SHIP CO., LTD',                              'terminos_pago' => 30, 'correo' => 'apple@asiaship.com.cn'],
            ['nombre' => 'SHENZHEN WORLDCARGO LOGISTICS LIMITED',            'terminos_pago' => 30, 'correo' => 'Tina@szworldcargo.com'],
            ['nombre' => 'INTERLOG LOGISTICS',                               'terminos_pago' => 15, 'correo' => 'rsuarez@interlog.mx'],
            ['nombre' => 'TRANSPORTES AMARRADORES',                          'terminos_pago' => 30, 'correo' => 'operaciones.tama@amarradores.com'],
            ['nombre' => 'OPORTS-PORTSROAD',                                 'terminos_pago' => 30, 'correo' => 'customerservices1@oports.mx'],
            ['nombre' => 'TRANSMONTES',                                      'terminos_pago' => 30, 'correo' => 'ivonne.najera@grupo-tm.com'],
            ['nombre' => 'FLETES MEXICO CARGA EXPRESS',                      'terminos_pago' => 15, 'correo' => 'dbueno@fletes-mexico.com'],
            ['nombre' => 'TAGGART INTERNATIONAL LTD',                        'terminos_pago' => 21, 'correo' => 'guillermoc@taggart-intl.com'],
            ['nombre' => 'OPTIMO LOGISTICS',                                 'terminos_pago' => 30, 'correo' => null],
            ['nombre' => 'GOLDEN SHIPPING CO LTD',                          'terminos_pago' => 30, 'correo' => 'financial@szgolden-shipping.com'],
            ['nombre' => 'INTERGLOBO MEXICO',                                'terminos_pago' => 30, 'correo' => null],
            ['nombre' => 'SOLIBRA LOJISTIK HIZMETLERI AS',                  'terminos_pago' => 15, 'correo' => 'overseas@solibra.com.tr'],
            ['nombre' => 'SHANGHAI LEAGUE SHIPPING CO LTD',                 'terminos_pago' => 30, 'correo' => 'overseas02@leagueshipping.com'],
            ['nombre' => 'VR LOGISTICS PVT LTD',                            'terminos_pago' => 30, 'correo' => 'overseas2@vrlogistic.co.in'],
            ['nombre' => 'TRANSPORTES ESPECIALIZADOS MOVIMIENTO PERFECTO',   'terminos_pago' => 30, 'correo' => null],
            ['nombre' => 'SHENZHEN BRAND NEW SUPPLY CHAIN MANAGEMENT',       'terminos_pago' => 21, 'correo' => 'bncharl@szbrandnew.com'],
            ['nombre' => 'FASTAR LOGISTICS CO LTD',                         'terminos_pago' => 30, 'correo' => null],
            ['nombre' => 'G GLOBAL',                                         'terminos_pago' => 0,  'correo' => 'daniela.gaytan@g-global.com'],
            ['nombre' => 'OIN MANIOBRAS MEXICO SA DE CV',                   'terminos_pago' => 30, 'correo' => 'nayeli.rosas@owcia.com'],
            ['nombre' => 'LEVISA MONTERREY AGENCIA ADUANAL',                'terminos_pago' => 0,  'correo' => null],
            ['nombre' => 'MARLENE MARTINEZ SÁNCHEZ',                        'terminos_pago' => 14, 'correo' => null],
            ['nombre' => 'OÑATE WILLY COMPAÑIA SC',                        'terminos_pago' => 0,  'correo' => null],
            ['nombre' => 'ROAR LOGÍSTICA INTEGRADA, S.A. DE C.V.',         'terminos_pago' => 0,  'correo' => 'contacto@roarlogistica.mx'],
            ['nombre' => 'ASOCIACION MEXICANA DE AGENTES DE CARGA AC',      'terminos_pago' => 10, 'correo' => 'comercial@amacarga.mx'],
            ['nombre' => 'VCPB TRANSPORTATION',                              'terminos_pago' => 30, 'correo' => null],
            ['nombre' => 'DI DI GLOBAL LOGISTICS CO., LTD',                 'terminos_pago' => 30, 'correo' => null],
            ['nombre' => 'XIAMEN TRANS-CHINA LOGISTICS CO.,LTD.',           'terminos_pago' => 30, 'correo' => 'market30@trans-china.com'],
            ['nombre' => 'MKS GLOBAL LOGISTICS INDIA',                      'terminos_pago' => 30, 'correo' => 'finance@mksgl.com'],
            ['nombre' => 'K&L FREIGHT MANAGEMENT LLC',                      'terminos_pago' => 30, 'correo' => null],
            ['nombre' => 'IFS',                                              'terminos_pago' => 30, 'correo' => null],
        ];

        foreach ($proveedores as $p) {
            Proveedor::updateOrCreate(['nombre' => $p['nombre']], array_merge($p, ['activo' => true]));
        }
    }
}