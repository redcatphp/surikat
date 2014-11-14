<?php namespace surikat\control\i18n;
use surikat\control;
require_once __DIR__.'/php-gettext.php';
class i18n {
	private static $locales_root;
	private static $domain;
	private static $locale;
	private static $language;
	static $i18n_iso =  [
	  'AF' =>   [
			'name' => 'AFGHANISTAN',
			'A2' => 'AF',
			'A3' => 'AFG',
			'number' => '004',
		  ],
		  'AX
		' =>   [
			'name' => 'ALAND ISLANDS',
			'A2' => 'AX
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'AL' =>   [
			'name' => 'ALBANIA',
			'A2' => 'AL',
			'A3' => 'ALB',
			'number' => '008',
		  ],
		  'DZ' =>   [
			'name' => 'ALGERIA',
			'A2' => 'DZ',
			'A3' => 'DZA',
			'number' => '012',
		  ],
		  'AS' =>   [
			'name' => 'AMERICAN SAMOA',
			'A2' => 'AS',
			'A3' => 'ASM',
			'number' => '016',
		  ],
		  'AD' =>   [
			'name' => 'ANDORRA',
			'A2' => 'AD',
			'A3' => 'AND',
			'number' => '020',
		  ],
		  'AO' =>   [
			'name' => 'ANGOLA',
			'A2' => 'AO',
			'A3' => 'AGO',
			'number' => '024',
		  ],
		  'AI' =>   [
			'name' => 'ANGUILLA',
			'A2' => 'AI',
			'A3' => 'AIA',
			'number' => '660',
		  ],
		  'AQ' =>   [
			'name' => 'ANTARCTICA',
			'A2' => 'AQ',
			'A3' => 'ATA',
			'number' => '010',
		  ],
		  'AG' =>   [
			'name' => 'ANTIGUA AND BARBUDA',
			'A2' => 'AG',
			'A3' => 'ATG',
			'number' => '028',
		  ],
		  'AR' =>   [
			'name' => 'ARGENTINA',
			'A2' => 'AR',
			'A3' => 'ARG',
			'number' => '032',
		  ],
		  'AM' =>   [
			'name' => 'ARMENIA',
			'A2' => 'AM',
			'A3' => 'ARM',
			'number' => '051',
		  ],
		  'AW' =>   [
			'name' => 'ARUBA',
			'A2' => 'AW',
			'A3' => 'ABW',
			'number' => '533',
		  ],
		  'AU' =>   [
			'name' => 'AUSTRALIA',
			'A2' => 'AU',
			'A3' => 'AUS',
			'number' => '036',
		  ],
		  'AT' =>   [
			'name' => 'AUSTRIA',
			'A2' => 'AT',
			'A3' => 'AUT',
			'number' => '040',
		  ],
		  'AZ' =>   [
			'name' => 'AZERBAIJAN',
			'A2' => 'AZ',
			'A3' => 'AZE',
			'number' => '031',
		  ],
		  'BS' =>   [
			'name' => 'BAHAMAS',
			'A2' => 'BS',
			'A3' => 'BHS',
			'number' => '044',
		  ],
		  'BH' =>   [
			'name' => 'BAHRAIN',
			'A2' => 'BH',
			'A3' => 'BHR',
			'number' => '048',
		  ],
		  'BD' =>   [
			'name' => 'BANGLADESH',
			'A2' => 'BD',
			'A3' => 'BGD',
			'number' => '050',
		  ],
		  'BB' =>   [
			'name' => 'BARBADOS',
			'A2' => 'BB',
			'A3' => 'BRB',
			'number' => '052',
		  ],
		  'BY' =>   [
			'name' => 'BELARUS',
			'A2' => 'BY',
			'A3' => 'BLR',
			'number' => '112',
		  ],
		  'BE' =>   [
			'name' => 'BELGIUM',
			'A2' => 'BE',
			'A3' => 'BEL',
			'number' => '056',
		  ],
		  'BZ' =>   [
			'name' => 'BELIZE',
			'A2' => 'BZ',
			'A3' => 'BLZ',
			'number' => '084',
		  ],
		  'BJ' =>   [
			'name' => 'BENIN',
			'A2' => 'BJ',
			'A3' => 'BEN',
			'number' => '204',
		  ],
		  'BM' =>   [
			'name' => 'BERMUDA',
			'A2' => 'BM',
			'A3' => 'BMU',
			'number' => '060',
		  ],
		  'BT' =>   [
			'name' => 'BHUTAN',
			'A2' => 'BT',
			'A3' => 'BTN',
			'number' => '064',
		  ],
		  'BO' =>   [
			'name' => 'BOLIVIA',
			'A2' => 'BO',
			'A3' => 'BOL',
			'number' => '068',
		  ],
		  'BA' =>   [
			'name' => 'BOSNIA AND HERZEGOWINA',
			'A2' => 'BA',
			'A3' => 'BIH',
			'number' => '070',
		  ],
		  'BW' =>   [
			'name' => 'BOTSWANA',
			'A2' => 'BW',
			'A3' => 'BWA',
			'number' => '072',
		  ],
		  'BV' =>   [
			'name' => 'BOUVET ISLAND',
			'A2' => 'BV',
			'A3' => 'BVT',
			'number' => '074',
		  ],
		  'BR' =>   [
			'name' => 'BRAZIL',
			'A2' => 'BR',
			'A3' => 'BRA',
			'number' => '076',
		  ],
		  'IO' =>   [
			'name' => 'BRITISH INDIAN OCEAN TERRITORY',
			'A2' => 'IO',
			'A3' => 'IOT',
			'number' => '086',
		  ],
		  'BN' =>   [
			'name' => 'BRUNEI DARUSSALAM',
			'A2' => 'BN',
			'A3' => 'BRN',
			'number' => '096',
		  ],
		  'BG' =>   [
			'name' => 'BULGARIA',
			'A2' => 'BG',
			'A3' => 'BGR',
			'number' => '100',
		  ],
		  'BF' =>   [
			'name' => 'BURKINA FASO',
			'A2' => 'BF',
			'A3' => 'BFA',
			'number' => '854',
		  ],
		  'BI' =>   [
			'name' => 'BURUNDI',
			'A2' => 'BI',
			'A3' => 'BDI',
			'number' => '108',
		  ],
		  'KH' =>   [
			'name' => 'CAMBODIA',
			'A2' => 'KH',
			'A3' => 'KHM',
			'number' => '116',
		  ],
		  'CM' =>   [
			'name' => 'CAMEROON',
			'A2' => 'CM',
			'A3' => 'CMR',
			'number' => '120',
		  ],
		  'CA' =>   [
			'name' => 'CANADA',
			'A2' => 'CA',
			'A3' => 'CAN',
			'number' => '124',
		  ],
		  'CV' =>   [
			'name' => 'CAPE VERDE',
			'A2' => 'CV',
			'A3' => 'CPV',
			'number' => '132',
		  ],
		  'KY' =>   [
			'name' => 'CAYMAN ISLANDS',
			'A2' => 'KY',
			'A3' => 'CYM',
			'number' => '136',
		  ],
		  'CF' =>   [
			'name' => 'CENTRAL AFRICAN REPUBLIC',
			'A2' => 'CF',
			'A3' => 'CAF',
			'number' => '140',
		  ],
		  'TD' =>   [
			'name' => 'CHAD',
			'A2' => 'TD',
			'A3' => 'TCD',
			'number' => '148',
		  ],
		  'CL' =>   [
			'name' => 'CHILE',
			'A2' => 'CL',
			'A3' => 'CHL',
			'number' => '152',
		  ],
		  'CN' =>   [
			'name' => 'CHINA',
			'A2' => 'CN',
			'A3' => 'CHN',
			'number' => '156',
		  ],
		  'CX' =>   [
			'name' => 'CHRISTMAS ISLAND',
			'A2' => 'CX',
			'A3' => 'CXR',
			'number' => '162',
		  ],
		  'CC' =>   [
			'name' => 'COCOS (KEELING) ISLANDS',
			'A2' => 'CC',
			'A3' => 'CCK',
			'number' => '166',
		  ],
		  'CO' =>   [
			'name' => 'COLOMBIA',
			'A2' => 'CO',
			'A3' => 'COL',
			'number' => '170',
		  ],
		  'KM' =>   [
			'name' => 'COMOROS',
			'A2' => 'KM',
			'A3' => 'COM',
			'number' => '174',
		  ],
		  'CG' =>   [
			'name' => 'CONGO',
			'A2' => 'CG',
			'A3' => 'COG',
			'number' => '178',
		  ],
		  'CD
		' =>   [
			'name' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
			'A2' => 'CD
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'CK' =>   [
			'name' => 'COOK ISLANDS',
			'A2' => 'CK',
			'A3' => 'COK',
			'number' => '184',
		  ],
		  'CR' =>   [
			'name' => 'COSTA RICA',
			'A2' => 'CR',
			'A3' => 'CRI',
			'number' => '188',
		  ],
		  'CI' =>   [
			'name' => 'COTE D\'IVOIRE',
			'A2' => 'CI',
			'A3' => 'CIV',
			'number' => '384',
		  ],
		  'HR' =>   [
			'name' => 'CROATIA',
			'A2' => 'HR',
			'A3' => 'HRV',
			'number' => '191',
		  ],
		  'CU' =>   [
			'name' => 'CUBA',
			'A2' => 'CU',
			'A3' => 'CUB',
			'number' => '192',
		  ],
		  'CY' =>   [
			'name' => 'CYPRUS',
			'A2' => 'CY',
			'A3' => 'CYP',
			'number' => '196',
		  ],
		  'CZ' =>   [
			'name' => 'CZECH REPUBLIC',
			'A2' => 'CZ',
			'A3' => 'CZE',
			'number' => '203',
		  ],
		  'DK' =>   [
			'name' => 'DENMARK',
			'A2' => 'DK',
			'A3' => 'DNK',
			'number' => '208',
		  ],
		  'DJ' =>   [
			'name' => 'DJIBOUTI',
			'A2' => 'DJ',
			'A3' => 'DJI',
			'number' => '262',
		  ],
		  'DM' =>   [
			'name' => 'DOMINICA',
			'A2' => 'DM',
			'A3' => 'DMA',
			'number' => '212',
		  ],
		  'DO' =>   [
			'name' => 'DOMINICAN REPUBLIC',
			'A2' => 'DO',
			'A3' => 'DOM',
			'number' => '214',
		  ],
		  'EC' =>   [
			'name' => 'ECUADOR',
			'A2' => 'EC',
			'A3' => 'ECU',
			'number' => '218',
		  ],
		  'EG' =>   [
			'name' => 'EGYPT',
			'A2' => 'EG',
			'A3' => 'EGY',
			'number' => '818',
		  ],
		  'SV' =>   [
			'name' => 'EL SALVADOR',
			'A2' => 'SV',
			'A3' => 'SLV',
			'number' => '222',
		  ],
		  'GQ' =>   [
			'name' => 'EQUATORIAL GUINEA',
			'A2' => 'GQ',
			'A3' => 'GNQ',
			'number' => '226',
		  ],
		  'ER' =>   [
			'name' => 'ERITREA',
			'A2' => 'ER',
			'A3' => 'ERI',
			'number' => '232',
		  ],
		  'EE' =>   [
			'name' => 'ESTONIA',
			'A2' => 'EE',
			'A3' => 'EST',
			'number' => '233',
		  ],
		  'ET' =>   [
			'name' => 'ETHIOPIA',
			'A2' => 'ET',
			'A3' => 'ETH',
			'number' => '231',
		  ],
		  'FK' =>   [
			'name' => 'FALKLAND ISLANDS (MALVINAS)',
			'A2' => 'FK',
			'A3' => 'FLK',
			'number' => '238',
		  ],
		  'FO' =>   [
			'name' => 'FAROE ISLANDS',
			'A2' => 'FO',
			'A3' => 'FRO',
			'number' => '234',
		  ],
		  'FJ' =>   [
			'name' => 'FIJI',
			'A2' => 'FJ',
			'A3' => 'FJI',
			'number' => '242',
		  ],
		  'FI' =>   [
			'name' => 'FINLAND',
			'A2' => 'FI',
			'A3' => 'FIN',
			'number' => '246',
		  ],
		  'FR' =>   [
			'name' => 'FRANCE',
			'A2' => 'FR',
			'A3' => 'FRA',
			'number' => '250',
		  ],
		  'GF' =>   [
			'name' => 'FRENCH GUIANA',
			'A2' => 'GF',
			'A3' => 'GUF',
			'number' => '254',
		  ],
		  'PF' =>   [
			'name' => 'FRENCH POLYNESIA',
			'A2' => 'PF',
			'A3' => 'PYF',
			'number' => '258',
		  ],
		  'TF' =>   [
			'name' => 'FRENCH SOUTHERN TERRITORIES',
			'A2' => 'TF',
			'A3' => 'ATF',
			'number' => '260',
		  ],
		  'GA' =>   [
			'name' => 'GABON',
			'A2' => 'GA',
			'A3' => 'GAB',
			'number' => '266',
		  ],
		  'GM' =>   [
			'name' => 'GAMBIA',
			'A2' => 'GM',
			'A3' => 'GMB',
			'number' => '270',
		  ],
		  'GE' =>   [
			'name' => 'GEORGIA',
			'A2' => 'GE',
			'A3' => 'GEO',
			'number' => '268',
		  ],
		  'DE' =>   [
			'name' => 'GERMANY',
			'A2' => 'DE',
			'A3' => 'DEU',
			'number' => '276',
		  ],
		  'GH' =>   [
			'name' => 'GHANA',
			'A2' => 'GH',
			'A3' => 'GHA',
			'number' => '288',
		  ],
		  'GI' =>   [
			'name' => 'GIBRALTAR',
			'A2' => 'GI',
			'A3' => 'GIB',
			'number' => '292',
		  ],
		  'GR' =>   [
			'name' => 'GREECE',
			'A2' => 'GR',
			'A3' => 'GRC',
			'number' => '300',
		  ],
		  'GL' =>   [
			'name' => 'GREENLAND',
			'A2' => 'GL',
			'A3' => 'GRL',
			'number' => '304',
		  ],
		  'GD' =>   [
			'name' => 'GRENADA',
			'A2' => 'GD',
			'A3' => 'GRD',
			'number' => '308',
		  ],
		  'GP' =>   [
			'name' => 'GUADELOUPE',
			'A2' => 'GP',
			'A3' => 'GLP',
			'number' => '312',
		  ],
		  'GU' =>   [
			'name' => 'GUAM',
			'A2' => 'GU',
			'A3' => 'GUM',
			'number' => '316',
		  ],
		  'GT' =>   [
			'name' => 'GUATEMALA',
			'A2' => 'GT',
			'A3' => 'GTM',
			'number' => '320',
		  ],
		  'GG
		' =>   [
			'name' => 'GUERNSEY',
			'A2' => 'GG
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'GN' =>   [
			'name' => 'GUINEA',
			'A2' => 'GN',
			'A3' => 'GIN',
			'number' => '324',
		  ],
		  'GW' =>   [
			'name' => 'GUINEA-BISSAU',
			'A2' => 'GW',
			'A3' => 'GNB',
			'number' => '624',
		  ],
		  'GY' =>   [
			'name' => 'GUYANA',
			'A2' => 'GY',
			'A3' => 'GUY',
			'number' => '328',
		  ],
		  'HT' =>   [
			'name' => 'HAITI',
			'A2' => 'HT',
			'A3' => 'HTI',
			'number' => '332',
		  ],
		  'HM' =>   [
			'name' => 'HEARD ISLAND AND MC DONALD ISLANDS',
			'A2' => 'HM',
			'A3' => 'HMD',
			'number' => '334',
		  ],
		  'VA
		' =>   [
			'name' => 'HOLY SEE (VATICAN CITY STATE)',
			'A2' => 'VA
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'HN' =>   [
			'name' => 'HONDURAS',
			'A2' => 'HN',
			'A3' => 'HND',
			'number' => '340',
		  ],
		  'HK' =>   [
			'name' => 'HONG KONG',
			'A2' => 'HK',
			'A3' => 'HKG',
			'number' => '344',
		  ],
		  'HU' =>   [
			'name' => 'HUNGARY',
			'A2' => 'HU',
			'A3' => 'HUN',
			'number' => '348',
		  ],
		  'IS' =>   [
			'name' => 'ICELAND',
			'A2' => 'IS',
			'A3' => 'ISL',
			'number' => '352',
		  ],
		  'IN' =>   [
			'name' => 'INDIA',
			'A2' => 'IN',
			'A3' => 'IND',
			'number' => '356',
		  ],
		  'ID' =>   [
			'name' => 'INDONESIA',
			'A2' => 'ID',
			'A3' => 'IDN',
			'number' => '360',
		  ],
		  'IR' =>   [
			'name' => 'IRAN (ISLAMIC REPUBLIC OF)',
			'A2' => 'IR',
			'A3' => 'IRN',
			'number' => '364',
		  ],
		  'IQ' =>   [
			'name' => 'IRAQ',
			'A2' => 'IQ',
			'A3' => 'IRQ',
			'number' => '368',
		  ],
		  'IE' =>   [
			'name' => 'IRELAND',
			'A2' => 'IE',
			'A3' => 'IRL',
			'number' => '372',
		  ],
		  'IM
		' =>   [
			'name' => 'ISLE OF MAN',
			'A2' => 'IM
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'IL' =>   [
			'name' => 'ISRAEL',
			'A2' => 'IL',
			'A3' => 'ISR',
			'number' => '376',
		  ],
		  'IT' =>   [
			'name' => 'ITALY',
			'A2' => 'IT',
			'A3' => 'ITA',
			'number' => '380',
		  ],
		  'JM' =>   [
			'name' => 'JAMAICA',
			'A2' => 'JM',
			'A3' => 'JAM',
			'number' => '388',
		  ],
		  'JP' =>   [
			'name' => 'JAPAN',
			'A2' => 'JP',
			'A3' => 'JPN',
			'number' => '392',
		  ],
		  'JE
		' =>   [
			'name' => 'JERSEY',
			'A2' => 'JE
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'JO' =>   [
			'name' => 'JORDAN',
			'A2' => 'JO',
			'A3' => 'JOR',
			'number' => '400',
		  ],
		  'KZ' =>   [
			'name' => 'KAZAKHSTAN',
			'A2' => 'KZ',
			'A3' => 'KAZ',
			'number' => '398',
		  ],
		  'KE' =>   [
			'name' => 'KENYA',
			'A2' => 'KE',
			'A3' => 'KEN',
			'number' => '404',
		  ],
		  'KI' =>   [
			'name' => 'KIRIBATI',
			'A2' => 'KI',
			'A3' => 'KIR',
			'number' => '296',
		  ],
		  'KP' =>   [
			'name' => 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF',
			'A2' => 'KP',
			'A3' => 'PRK',
			'number' => '408',
		  ],
		  'KR' =>   [
			'name' => 'KOREA, REPUBLIC OF',
			'A2' => 'KR',
			'A3' => 'KOR',
			'number' => '410',
		  ],
		  'KW' =>   [
			'name' => 'KUWAIT',
			'A2' => 'KW',
			'A3' => 'KWT',
			'number' => '414',
		  ],
		  'KG' =>   [
			'name' => 'KYRGYZSTAN',
			'A2' => 'KG',
			'A3' => 'KGZ',
			'number' => '417',
		  ],
		  'LA' =>   [
			'name' => 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC',
			'A2' => 'LA',
			'A3' => 'LAO',
			'number' => '418',
		  ],
		  'LV' =>   [
			'name' => 'LATVIA',
			'A2' => 'LV',
			'A3' => 'LVA',
			'number' => '428',
		  ],
		  'LB' =>   [
			'name' => 'LEBANON',
			'A2' => 'LB',
			'A3' => 'LBN',
			'number' => '422',
		  ],
		  'LS' =>   [
			'name' => 'LESOTHO',
			'A2' => 'LS',
			'A3' => 'LSO',
			'number' => '426',
		  ],
		  'LR' =>   [
			'name' => 'LIBERIA',
			'A2' => 'LR',
			'A3' => 'LBR',
			'number' => '430',
		  ],
		  'LY' =>   [
			'name' => 'LIBYAN ARAB JAMAHIRIYA',
			'A2' => 'LY',
			'A3' => 'LBY',
			'number' => '434',
		  ],
		  'LI' =>   [
			'name' => 'LIECHTENSTEIN',
			'A2' => 'LI',
			'A3' => 'LIE',
			'number' => '438',
		  ],
		  'LT' =>   [
			'name' => 'LITHUANIA',
			'A2' => 'LT',
			'A3' => 'LTU',
			'number' => '440',
		  ],
		  'LU' =>   [
			'name' => 'LUXEMBOURG',
			'A2' => 'LU',
			'A3' => 'LUX',
			'number' => '442',
		  ],
		  'MO' =>   [
			'name' => 'MACAO',
			'A2' => 'MO',
			'A3' => 'MAC',
			'number' => '446',
		  ],
		  'MK' =>   [
			'name' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
			'A2' => 'MK',
			'A3' => 'MKD',
			'number' => '807',
		  ],
		  'MG' =>   [
			'name' => 'MADAGASCAR',
			'A2' => 'MG',
			'A3' => 'MDG',
			'number' => '450',
		  ],
		  'MW' =>   [
			'name' => 'MALAWI',
			'A2' => 'MW',
			'A3' => 'MWI',
			'number' => '454',
		  ],
		  'MY' =>   [
			'name' => 'MALAYSIA',
			'A2' => 'MY',
			'A3' => 'MYS',
			'number' => '458',
		  ],
		  'MV' =>   [
			'name' => 'MALDIVES',
			'A2' => 'MV',
			'A3' => 'MDV',
			'number' => '462',
		  ],
		  'ML' =>   [
			'name' => 'MALI',
			'A2' => 'ML',
			'A3' => 'MLI',
			'number' => '466',
		  ],
		  'MT' =>   [
			'name' => 'MALTA',
			'A2' => 'MT',
			'A3' => 'MLT',
			'number' => '470',
		  ],
		  'MH' =>   [
			'name' => 'MARSHALL ISLANDS',
			'A2' => 'MH',
			'A3' => 'MHL',
			'number' => '584',
		  ],
		  'MQ' =>   [
			'name' => 'MARTINIQUE',
			'A2' => 'MQ',
			'A3' => 'MTQ',
			'number' => '474',
		  ],
		  'MR' =>   [
			'name' => 'MAURITANIA',
			'A2' => 'MR',
			'A3' => 'MRT',
			'number' => '478',
		  ],
		  'MU' =>   [
			'name' => 'MAURITIUS',
			'A2' => 'MU',
			'A3' => 'MUS',
			'number' => '480',
		  ],
		  'YT' =>   [
			'name' => 'MAYOTTE',
			'A2' => 'YT',
			'A3' => 'MYT',
			'number' => '175',
		  ],
		  'MX' =>   [
			'name' => 'MEXICO',
			'A2' => 'MX',
			'A3' => 'MEX',
			'number' => '484',
		  ],
		  'FM' =>   [
			'name' => 'MICRONESIA, FEDERATED STATES OF',
			'A2' => 'FM',
			'A3' => 'FSM',
			'number' => '583',
		  ],
		  'MD' =>   [
			'name' => 'MOLDOVA, REPUBLIC OF',
			'A2' => 'MD',
			'A3' => 'MDA',
			'number' => '498',
		  ],
		  'MC' =>   [
			'name' => 'MONACO',
			'A2' => 'MC',
			'A3' => 'MCO',
			'number' => '492',
		  ],
		  'MN' =>   [
			'name' => 'MONGOLIA',
			'A2' => 'MN',
			'A3' => 'MNG',
			'number' => '496',
		  ],
		  'ME
		' =>   [
			'name' => 'MONTENEGRO',
			'A2' => 'ME
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'MS' =>   [
			'name' => 'MONTSERRAT',
			'A2' => 'MS',
			'A3' => 'MSR',
			'number' => '500',
		  ],
		  'MA' =>   [
			'name' => 'MOROCCO',
			'A2' => 'MA',
			'A3' => 'MAR',
			'number' => '504',
		  ],
		  'MZ' =>   [
			'name' => 'MOZAMBIQUE',
			'A2' => 'MZ',
			'A3' => 'MOZ',
			'number' => '508',
		  ],
		  'MM' =>   [
			'name' => 'MYANMAR',
			'A2' => 'MM',
			'A3' => 'MMR',
			'number' => '104',
		  ],
		  'NA' =>   [
			'name' => 'NAMIBIA',
			'A2' => 'NA',
			'A3' => 'NAM',
			'number' => '516',
		  ],
		  'NR' =>   [
			'name' => 'NAURU',
			'A2' => 'NR',
			'A3' => 'NRU',
			'number' => '520',
		  ],
		  'NP' =>   [
			'name' => 'NEPAL',
			'A2' => 'NP',
			'A3' => 'NPL',
			'number' => '524',
		  ],
		  'NL' =>   [
			'name' => 'NETHERLANDS',
			'A2' => 'NL',
			'A3' => 'NLD',
			'number' => '528',
		  ],
		  'AN' =>   [
			'name' => 'NETHERLANDS ANTILLES',
			'A2' => 'AN',
			'A3' => 'ANT',
			'number' => '530',
		  ],
		  'NC' =>   [
			'name' => 'NEW CALEDONIA',
			'A2' => 'NC',
			'A3' => 'NCL',
			'number' => '540',
		  ],
		  'NZ' =>   [
			'name' => 'NEW ZEALAND',
			'A2' => 'NZ',
			'A3' => 'NZL',
			'number' => '554',
		  ],
		  'NI' =>   [
			'name' => 'NICARAGUA',
			'A2' => 'NI',
			'A3' => 'NIC',
			'number' => '558',
		  ],
		  'NE' =>   [
			'name' => 'NIGER',
			'A2' => 'NE',
			'A3' => 'NER',
			'number' => '562',
		  ],
		  'NG' =>   [
			'name' => 'NIGERIA',
			'A2' => 'NG',
			'A3' => 'NGA',
			'number' => '566',
		  ],
		  'NU' =>   [
			'name' => 'NIUE',
			'A2' => 'NU',
			'A3' => 'NIU',
			'number' => '570',
		  ],
		  'NF' =>   [
			'name' => 'NORFOLK ISLAND',
			'A2' => 'NF',
			'A3' => 'NFK',
			'number' => '574',
		  ],
		  'MP' =>   [
			'name' => 'NORTHERN MARIANA ISLANDS',
			'A2' => 'MP',
			'A3' => 'MNP',
			'number' => '580',
		  ],
		  'NO' =>   [
			'name' => 'NORWAY',
			'A2' => 'NO',
			'A3' => 'NOR',
			'number' => '578',
		  ],
		  'OM' =>   [
			'name' => 'OMAN',
			'A2' => 'OM',
			'A3' => 'OMN',
			'number' => '512',
		  ],
		  'PK' =>   [
			'name' => 'PAKISTAN',
			'A2' => 'PK',
			'A3' => 'PAK',
			'number' => '586',
		  ],
		  'PW' =>   [
			'name' => 'PALAU',
			'A2' => 'PW',
			'A3' => 'PLW',
			'number' => '585',
		  ],
		  'PS
		' =>   [
			'name' => 'PALESTINIAN TERRITORY, OCCUPIED',
			'A2' => 'PS
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'PA' =>   [
			'name' => 'PANAMA',
			'A2' => 'PA',
			'A3' => 'PAN',
			'number' => '591',
		  ],
		  'PG' =>   [
			'name' => 'PAPUA NEW GUINEA',
			'A2' => 'PG',
			'A3' => 'PNG',
			'number' => '598',
		  ],
		  'PY' =>   [
			'name' => 'PARAGUAY',
			'A2' => 'PY',
			'A3' => 'PRY',
			'number' => '600',
		  ],
		  'PE' =>   [
			'name' => 'PERU',
			'A2' => 'PE',
			'A3' => 'PER',
			'number' => '604',
		  ],
		  'PH' =>   [
			'name' => 'PHILIPPINES',
			'A2' => 'PH',
			'A3' => 'PHL',
			'number' => '608',
		  ],
		  'PN' =>   [
			'name' => 'PITCAIRN',
			'A2' => 'PN',
			'A3' => 'PCN',
			'number' => '612',
		  ],
		  'PL' =>   [
			'name' => 'POLAND',
			'A2' => 'PL',
			'A3' => 'POL',
			'number' => '616',
		  ],
		  'PT' =>   [
			'name' => 'PORTUGAL',
			'A2' => 'PT',
			'A3' => 'PRT',
			'number' => '620',
		  ],
		  'PR' =>   [
			'name' => 'PUERTO RICO',
			'A2' => 'PR',
			'A3' => 'PRI',
			'number' => '630',
		  ],
		  'QA' =>   [
			'name' => 'QATAR',
			'A2' => 'QA',
			'A3' => 'QAT',
			'number' => '634',
		  ],
		  'RE' =>   [
			'name' => 'REUNION',
			'A2' => 'RE',
			'A3' => 'REU',
			'number' => '638',
		  ],
		  'RO' =>   [
			'name' => 'ROMANIA',
			'A2' => 'RO',
			'A3' => 'ROM',
			'number' => '642',
		  ],
		  'RU' =>   [
			'name' => 'RUSSIAN FEDERATION',
			'A2' => 'RU',
			'A3' => 'RUS',
			'number' => '643',
		  ],
		  'RW' =>   [
			'name' => 'RWANDA',
			'A2' => 'RW',
			'A3' => 'RWA',
			'number' => '646',
		  ],
		  'EH
		' =>   [
			'name' => 'SAHARA OCCIDENTAL',
			'A2' => 'EH
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'BL
		' =>   [
			'name' => 'SAINT BARTHELEMY',
			'A2' => 'BL
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'SH' =>   [
			'name' => 'SAINT HELENA',
			'A2' => 'SH',
			'A3' => 'SHN',
			'number' => '654
		',
		  ],
		  'KN' =>   [
			'name' => 'SAINT KITTS AND NEVIS',
			'A2' => 'KN',
			'A3' => 'KNA',
			'number' => '659',
		  ],
		  'LC' =>   [
			'name' => 'SAINT LUCIA',
			'A2' => 'LC',
			'A3' => 'LCA',
			'number' => '662',
		  ],
		  'PM' =>   [
			'name' => 'SAINT PIERRE AND MIQUELON',
			'A2' => 'PM',
			'A3' => 'SPM',
			'number' => '666
		',
		  ],
		  'VC' =>   [
			'name' => 'SAINT VINCENT AND THE GRENADINES',
			'A2' => 'VC',
			'A3' => 'VCT',
			'number' => '670',
		  ],
		  'WS' =>   [
			'name' => 'SAMOA',
			'A2' => 'WS',
			'A3' => 'WSM',
			'number' => '882',
		  ],
		  'SM' =>   [
			'name' => 'SAN MARINO',
			'A2' => 'SM',
			'A3' => 'SMR',
			'number' => '674',
		  ],
		  'ST' =>   [
			'name' => 'SAO TOME AND PRINCIPE',
			'A2' => 'ST',
			'A3' => 'STP',
			'number' => '678',
		  ],
		  'SA' =>   [
			'name' => 'SAUDI ARABIA',
			'A2' => 'SA',
			'A3' => 'SAU',
			'number' => '682',
		  ],
		  'SN' =>   [
			'name' => 'SENEGAL',
			'A2' => 'SN',
			'A3' => 'SEN',
			'number' => '686',
		  ],
		  'RS
		' =>   [
			'name' => 'SERBIA',
			'A2' => 'RS
		',
			'A3' => NULL,
			'number' => NULL,
		  ],
		  'SC' =>   [
			'name' => 'SEYCHELLES',
			'A2' => 'SC',
			'A3' => 'SYC',
			'number' => '690',
		  ],
		  'SL' =>   [
			'name' => 'SIERRA LEONE',
			'A2' => 'SL',
			'A3' => 'SLE',
			'number' => '694',
		  ],
		  'SG' =>   [
			'name' => 'SINGAPORE',
			'A2' => 'SG',
			'A3' => 'SGP',
			'number' => '702',
		  ],
		  'SK' =>   [
			'name' => 'SLOVAKIA',
			'A2' => 'SK',
			'A3' => 'SVK',
			'number' => '703',
		  ],
		  'SI' =>   [
			'name' => 'SLOVENIA',
			'A2' => 'SI',
			'A3' => 'SVN',
			'number' => '705',
		  ],
		  'SB' =>   [
			'name' => 'SOLOMON ISLANDS',
			'A2' => 'SB',
			'A3' => 'SLB',
			'number' => '090',
		  ],
		  'SO' =>   [
			'name' => 'SOMALIA',
			'A2' => 'SO',
			'A3' => 'SOM',
			'number' => '706',
		  ],
		  'ZA' =>   [
			'name' => 'SOUTH AFRICA',
			'A2' => 'ZA',
			'A3' => 'ZAF',
			'number' => '710',
		  ],
		  'SGS' =>   [
			'name' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS    GS',
			'A2' => 'SGS',
			'A3' => '239',
			'number' => NULL,
		  ],
		  'ES' =>   [
			'name' => 'SPAIN',
			'A2' => 'ES',
			'A3' => 'ESP',
			'number' => '724',
		  ],
		  'LK' =>   [
			'name' => 'SRI LANKA',
			'A2' => 'LK',
			'A3' => 'LKA',
			'number' => '144',
		  ],
		  'SD' =>   [
			'name' => 'SUDAN',
			'A2' => 'SD',
			'A3' => 'SDN',
			'number' => '736',
		  ],
		  'SR' =>   [
			'name' => 'SURINAME',
			'A2' => 'SR',
			'A3' => 'SUR',
			'number' => '740',
		  ],
		  'SJ' =>   [
			'name' => 'SVALBARD AND JAN MAYEN ISLANDS',
			'A2' => 'SJ',
			'A3' => 'SJM',
			'number' => '744',
		  ],
		  'SZ' =>   [
			'name' => 'SWAZILAND',
			'A2' => 'SZ',
			'A3' => 'SWZ',
			'number' => '748',
		  ],
		  'SE' =>   [
			'name' => 'SWEDEN',
			'A2' => 'SE',
			'A3' => 'SWE',
			'number' => '752',
		  ],
		  'CH' =>   [
			'name' => 'SWITZERLAND',
			'A2' => 'CH',
			'A3' => 'CHE',
			'number' => '756',
		  ],
		  'SY' =>   [
			'name' => 'SYRIAN ARAB REPUBLIC',
			'A2' => 'SY',
			'A3' => 'SYR',
			'number' => '760',
		  ],
		  'TW' =>   [
			'name' => 'TAIWAN, PROVINCE OF CHINA',
			'A2' => 'TW',
			'A3' => 'TWN',
			'number' => '158',
		  ],
		  'TJ' =>   [
			'name' => 'TAJIKISTAN',
			'A2' => 'TJ',
			'A3' => 'TJK',
			'number' => '762',
		  ],
		  'TZ' =>   [
			'name' => 'TANZANIA, UNITED REPUBLIC OF',
			'A2' => 'TZ',
			'A3' => 'TZA',
			'number' => '834',
		  ],
		  'TH' =>   [
			'name' => 'THAILAND',
			'A2' => 'TH',
			'A3' => 'THA',
			'number' => '764',
		  ],
		  'TG' =>   [
			'name' => 'TOGO',
			'A2' => 'TG',
			'A3' => 'TGO',
			'number' => '768',
		  ],
		  'TK' =>   [
			'name' => 'TOKELAU',
			'A2' => 'TK',
			'A3' => 'TKL',
			'number' => '772',
		  ],
		  'TO' =>   [
			'name' => 'TONGA',
			'A2' => 'TO',
			'A3' => 'TON',
			'number' => '776',
		  ],
		  'TT' =>   [
			'name' => 'TRINIDAD AND TOBAGO',
			'A2' => 'TT',
			'A3' => 'TTO',
			'number' => '780',
		  ],
		  'TN' =>   [
			'name' => 'TUNISIA',
			'A2' => 'TN',
			'A3' => 'TUN',
			'number' => '788',
		  ],
		  'TR' =>   [
			'name' => 'TURKEY',
			'A2' => 'TR',
			'A3' => 'TUR',
			'number' => '792',
		  ],
		  'TM' =>   [
			'name' => 'TURKMENISTAN',
			'A2' => 'TM',
			'A3' => 'TKM',
			'number' => '795',
		  ],
		  'TC' =>   [
			'name' => 'TURKS AND CAICOS ISLANDS',
			'A2' => 'TC',
			'A3' => 'TCA',
			'number' => '796',
		  ],
		  'TV' =>   [
			'name' => 'TUVALU',
			'A2' => 'TV',
			'A3' => 'TUV',
			'number' => '798',
		  ],
		  'UG' =>   [
			'name' => 'UGANDA',
			'A2' => 'UG',
			'A3' => 'UGA',
			'number' => '800',
		  ],
		  'UA' =>   [
			'name' => 'UKRAINE',
			'A2' => 'UA',
			'A3' => 'UKR',
			'number' => '804',
		  ],
		  'AE' =>   [
			'name' => 'UNITED ARAB EMIRATES',
			'A2' => 'AE',
			'A3' => 'ARE',
			'number' => '784',
		  ],
		  'GB' =>   [
			'name' => 'UNITED KINGDOM',
			'A2' => 'GB',
			'A3' => 'GBR',
			'number' => '826',
		  ],
		  'US' =>   [
			'name' => 'UNITED STATES',
			'A2' => 'US',
			'A3' => 'USA',
			'number' => '840',
		  ],
		  'UM' =>   [
			'name' => 'UNITED STATES MINOR OUTLYING ISLANDS',
			'A2' => 'UM',
			'A3' => 'UMI',
			'number' => '581',
		  ],
		  'UY' =>   [
			'name' => 'URUGUAY',
			'A2' => 'UY',
			'A3' => 'URY',
			'number' => '858',
		  ],
		  'UZ' =>   [
			'name' => 'UZBEKISTAN',
			'A2' => 'UZ',
			'A3' => 'UZB',
			'number' => '860',
		  ],
		  'VU' =>   [
			'name' => 'VANUATU',
			'A2' => 'VU',
			'A3' => 'VUT',
			'number' => '548',
		  ],
		  'VA' =>   [
			'name' => 'VATICAN CITY STATE (HOLY SEE)',
			'A2' => 'VA',
			'A3' => 'VAT',
			'number' => '336',
		  ],
		  'VE' =>   [
			'name' => 'VENEZUELA',
			'A2' => 'VE',
			'A3' => 'VEN',
			'number' => '862',
		  ],
		  'VN' =>   [
			'name' => 'VIET NAM',
			'A2' => 'VN',
			'A3' => 'VNM',
			'number' => '704',
		  ],
		  'VG' =>   [
			'name' => 'VIRGIN ISLANDS (BRITISH)',
			'A2' => 'VG',
			'A3' => 'VGB',
			'number' => '092',
		  ],
		  'VI' =>   [
			'name' => 'VIRGIN ISLANDS (U.S.)',
			'A2' => 'VI',
			'A3' => 'VIR',
			'number' => '850',
		  ],
		  'WF' =>   [
			'name' => 'WALLIS AND FUTUNA ISLANDS',
			'A2' => 'WF',
			'A3' => 'WLF',
			'number' => '876',
		  ],
		  'EH' =>   [
			'name' => 'WESTERN SAHARA',
			'A2' => 'EH',
			'A3' => 'ESH',
			'number' => '732',
		  ],
		  'YE' =>   [
			'name' => 'YEMEN',
			'A2' => 'YE',
			'A3' => 'YEM',
			'number' => '887',
		  ],
		  'ZM' =>   [
			'name' => 'ZAMBIA',
			'A2' => 'ZM',
			'A3' => 'ZMB',
			'number' => '894',
		  ],
		  'ZW' =>   [
			'name' => 'ZIMBABWE',
			'A2' => 'ZW',
			'A3' => 'ZWE',
			'number' => '716',
		  ],
		];
	
	static function set($lg='en'){
		self::$language = $lg;
		self::$locales_root = control::$CWD.'langs';
		self::$domain = 'messages';
		self::$locale = strtolower(self::$language).'_'.strtoupper(self::$language);
	}
	static function update_cache(){
		$filename = self::$locales_root.'/'.self::$locale.'/LC_MESSAGES/'.self::$domain.'.mo';
		if(!is_file($filename)) return;
		$mtime = filemtime($filename);
		$filename_new = self::$locales_root.'/'.self::$locale.'/LC_MESSAGES/'.self::$domain.'_'.$mtime.'.mo';
		if(!file_exists($filename_new)){
			$dir = scandir(dirname($filename));
			foreach($dir as $file){
				if(in_array($file, ['.','..', self::$domain.'.po', self::$domain.'.mo'])) continue;
				unlink(dirname($filename).DS.$file);
			}
			copy($filename,$filename_new);
		}
		self::$domain = self::$domain.'_'.$mtime;
	}
	static function handle(){
		
		$lang = self::$locale;
		putenv("LANGUAGE=$lang");
		putenv("LC_ALL=$lang");
		
		T_setlocale(LC_ALL,$lang);
		T_bindtextdomain(self::$domain,self::$locales_root);
		T_textdomain(self::$domain);
		T_bind_textdomain_codeset(self::$domain, "UTF-8");
		
		date_default_timezone_set('Europe/Paris');
		//setlocale(LC_TIME, $lang);
	}
}
