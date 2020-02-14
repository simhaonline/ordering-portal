<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountriesToGlobalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::table('globals')->insert([
            'key' => 'countries',
            'value' => json_encode([
                ['name' => 'UK'],
                ['name' => 'AFGHANISTAN'],
                ['name' => 'ALBANIA'],
                ['name' => 'ALGERIA'],
                ['name' => 'AMERICAN SAMOSA'],
                ['name' => 'ANDORRA'],
                ['name' => 'ANGOLA'],
                ['name' => 'ANGUILLA'],
                ['name' => 'ANTIGUA'],
                ['name' => 'ARGENTINA'],
                ['name' => 'ARUBA'],
                ['name' => 'AUSTRALIA'],
                ['name' => 'AUSTRIA'],
                ['name' => 'AZERBAIJAN'],
                ['name' => 'BAHAMAS'],
                ['name' => 'BAHRAIN'],
                ['name' => 'BANGLADESH'],
                ['name' => 'BARBADOS'],
                ['name' => 'BELARUS'],
                ['name' => 'BELGIUM'],
                ['name' => 'BELIZE'],
                ['name' => 'BENIN'],
                ['name' => 'BERMUDA'],
                ['name' => 'BHUTAN'],
                ['name' => 'BOLIVIA'],
                ['name' => 'BONAIRE'],
                ['name' => 'BOSNIA-HERZEGOVINA'],
                ['name' => 'BOTSWANA'],
                ['name' => 'BRAZIL'],
                ['name' => 'BRUNEI'],
                ['name' => 'BULGARIA'],
                ['name' => 'BURKINO FASO'],
                ['name' => 'BURUNDI'],
                ['name' => 'CAMBODIA'],
                ['name' => 'CAMEROON'],
                ['name' => 'CANADA'],
                ['name' => 'CAPE VERDE ISLANDS'],
                ['name' => 'CAYMAN ISLANDS'],
                ['name' => 'CENTRAL AFRICAN REPUBLIC'],
                ['name' => 'CHAD'],
                ['name' => 'CHANNEL ISLANDS'],
                ['name' => 'CHILE'],
                ['name' => 'CHINA PEOPLE\'S REP'],
                ['name' => 'COLOMBIA'],
                ['name' => 'COMOROS ISLANDS'],
                ['name' => 'CONGO'],
                ['name' => 'CONGO DEM. REP'],
                ['name' => 'COOK ISLANDS'],
                ['name' => 'COSTA RICA'],
                ['name' => 'CROATIA'],
                ['name' => 'CUBA'],
                ['name' => 'CURACAO'],
                ['name' => 'CYPRUS'],
                ['name' => 'CZECH REPUBLIC'],
                ['name' => 'DENMARK'],
                ['name' => 'DJIBOUTI'],
                ['name' => 'DOMINICA'],
                ['name' => 'DOMINICAN REPUBLIC'],
                ['name' => 'EAST TIMOR'],
                ['name' => 'ECUADOR'],
                ['name' => 'EGYPT'],
                ['name' => 'EL SALVADOR'],
                ['name' => 'EQUATORIAL GUINEA'],
                ['name' => 'ERITREA'],
                ['name' => 'ESTONIA'],
                ['name' => 'ETHIOPIA'],
                ['name' => 'FALKLAND ISLANDS'],
                ['name' => 'FAROE ISLANDS'],
                ['name' => 'FIJI'],
                ['name' => 'FINLAND'],
                ['name' => 'FRANCE'],
                ['name' => 'FRENCH GUIANA'],
                ['name' => 'GABON'],
                ['name' => 'GAMBIA'],
                ['name' => 'GEORGIA'],
                ['name' => 'GERMANY'],
                ['name' => 'GHANA'],
                ['name' => 'GIBRALTAR'],
                ['name' => 'GREECE'],
                ['name' => 'GREENLAND'],
                ['name' => 'GRENADA'],
                ['name' => 'GUADELOUPE'],
                ['name' => 'GUAM'],
                ['name' => 'GUATEMALA'],
                ['name' => 'GUINEA REP'],
                ['name' => 'GUINEA-BISSAU'],
                ['name' => 'GUYANA'],
                ['name' => 'HAITI'],
                ['name' => 'HONDURAS REP'],
                ['name' => 'HONG KONG'],
                ['name' => 'HUNGARY'],
                ['name' => 'ICELAND'],
                ['name' => 'INDIA'],
                ['name' => 'INDONESIA'],
                ['name' => 'IRAN'],
                ['name' => 'IRAQ'],
                ['name' => 'IRELAND'],
                ['name' => 'ISLE OF MAN'],
                ['name' => 'ISRAEL'],
                ['name' => 'ITALY'],
                ['name' => 'IVORY COAST'],
                ['name' => 'JAMAICA'],
                ['name' => 'JAPAN'],
                ['name' => 'JERSEY'],
                ['name' => 'JORDAN'],
                ['name' => 'KAZAKHSTAN'],
                ['name' => 'KENYA'],
                ['name' => 'KOREA D.P.R OF (NORTH)'],
                ['name' => 'KOREA REP OF (SOUTH)'],
                ['name' => 'KUWAIT'],
                ['name' => 'KYRGHYZSTAN'],
                ['name' => 'LAO PEOPLES DEM.REP'],
                ['name' => 'LATVIA'],
                ['name' => 'LEBANON'],
                ['name' => 'LESOTHO'],
                ['name' => 'LIBERIA'],
                ['name' => 'LIBYA'],
                ['name' => 'LIECHTENSTEIN'],
                ['name' => 'LITHUANIA'],
                ['name' => 'LUXEMBOURG'],
                ['name' => 'MACAU'],
                ['name' => 'MACEDONIA'],
                ['name' => 'MADAGASCAR'],
                ['name' => 'MALAWI'],
                ['name' => 'MALAYSIA'],
                ['name' => 'MALDIVES'],
                ['name' => 'MALI'],
                ['name' => 'MARSHALL ISLANDS'],
                ['name' => 'MARTINIQUE'],
                ['name' => 'MAURITANIA'],
                ['name' => 'MAURITIUS'],
                ['name' => 'MEXICO'],
                ['name' => 'MOLDOVA'],
                ['name' => 'MOLDOVA REP OF'],
                ['name' => 'MONACO'],
                ['name' => 'MONGOLIA'],
                ['name' => 'MONTENEGRO'],
                ['name' => 'MONTSERRAT'],
                ['name' => 'MOROCCO'],
                ['name' => 'MOZAMBIQUE'],
                ['name' => 'MYANMAR (BURMA)'],
                ['name' => 'NAMIBIA'],
                ['name' => 'NAURU REP OF'],
                ['name' => 'NEPAL'],
                ['name' => 'NEVIS'],
                ['name' => 'NEW CALEDONIA'],
                ['name' => 'NEW ZELAND'],
                ['name' => 'NICARAGUA'],
                ['name' => 'NIGER'],
                ['name' => 'NIGERIA'],
                ['name' => 'NIUE'],
                ['name' => 'NORTHERN ISLAND'],
                ['name' => 'NORWAY'],
                ['name' => 'OMAN'],
                ['name' => 'PAKISTAN'],
                ['name' => 'PANAMA'],
                ['name' => 'PAPUA NEW GUINEA'],
                ['name' => 'PARAGUAY'],
                ['name' => 'PERU'],
                ['name' => 'PHILIPPINES'],
                ['name' => 'POLAND'],
                ['name' => 'PORTUGAL'],
                ['name' => 'PUERTO RICO'],
                ['name' => 'QATAR'],
                ['name' => 'REPUBLIC OF MACEDONIA'],
                ['name' => 'REUNION ISLANDS OF'],
                ['name' => 'ROMANIA'],
                ['name' => 'RUSSIA'],
                ['name' => 'RWANDA'],
                ['name' => 'SAIPAN'],
                ['name' => 'SAMOA'],
                ['name' => 'SAN MARINO'],
                ['name' => 'SAO TOME & PRINCIPE'],
                ['name' => 'SAUDI ARABIA'],
                ['name' => 'SENEGAL'],
                ['name' => 'SERBIA'],
                ['name' => 'SEYCHELLES'],
                ['name' => 'SINGAPORE'],
                ['name' => 'SLOVAKIA'],
                ['name' => 'SLOVENIA'],
                ['name' => 'SOLOMON ISLANDS'],
                ['name' => 'SOMALIA'],
                ['name' => 'SOMALILAND REP OF'],
                ['name' => 'SOUTH AFRICA'],
                ['name' => 'SPAIN'],
                ['name' => 'SRI LANKA'],
                ['name' => 'ST BARTHELEMY'],
                ['name' => 'ST EUSTATIUS'],
                ['name' => 'ST KITTS'],
                ['name' => 'ST LUCIA'],
                ['name' => 'ST MARTEN'],
                ['name' => 'ST VINCENT'],
                ['name' => 'SUDAN'],
                ['name' => 'SURINAME'],
                ['name' => 'SWAZILAND'],
                ['name' => 'SWEDEN'],
                ['name' => 'SWITZERLAND'],
                ['name' => 'SYRIA'],
                ['name' => 'TAHITI'],
                ['name' => 'TAIWAN'],
                ['name' => 'TAJIKISTAN'],
                ['name' => 'TANZANIA'],
                ['name' => 'THAILAND'],
                ['name' => 'THE NETHERLANDS'],
                ['name' => 'TOGO'],
                ['name' => 'TONGA'],
                ['name' => 'TRINIDAD & TOBAGO'],
                ['name' => 'TRURKS & CAICOS ISLANDS'],
                ['name' => 'TUNISIA'],
                ['name' => 'TURKEY'],
                ['name' => 'TURKMENISTAN'],
                ['name' => 'TUVALU'],
                ['name' => 'UGANDA'],
                ['name' => 'UKRAINE'],
                ['name' => 'UNITED ARAB EMIRATES'],
                ['name' => 'URUGUAY'],
                ['name' => 'USA'],
                ['name' => 'UZBEKISTAN'],
                ['name' => 'VANUATU'],
                ['name' => 'VENEZUELA'],
                ['name' => 'VIETNAM'],
                ['name' => 'VIRGIN ISLANDS BRITISH'],
                ['name' => 'VIRGIN ISLANDS US'],
                ['name' => 'YEMEN'],
                ['name' => 'ZAMBIA'],
                ['name' => 'ZIMBABWE'],
                ['name' => 'NORTHERN IRELAND'],
            ], true),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('globals')->insert([
            'key' => 'default-country',
            'value' => 'UK',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table('globals')->where('key', 'countries')->delete();
        DB::table('globals')->where('key', 'default-country')->delete();
    }
}
