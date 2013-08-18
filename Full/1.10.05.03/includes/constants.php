<?php 
/***************************************************************************
 *                               constants.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Fast Track Sites
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

/***************************************************************************
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 ***************************************************************************/

//=====================================================
// Application
//=====================================================
define('A_NAME', 'fts_ss');
define('A_VERSION', '1.10.05.03');

//=====================================================
// Debug Level
//=====================================================
//define('DEBUG', 1); // Debugging on
define('DEBUG', 0); // Debugging off

//=====================================================
// Global state
//=====================================================
define('ACTIVE', 1);
define('INACTIVE', 0);

//=====================================================
// Urgency
//=====================================================
define('LOW', 0);
define('MEDIUM', 1);
define('HIGH', 2);

//=====================================================
// Adress types
//=====================================================
define('BILL_ADDRESS', 0);
define('SHIP_ADDRESS', 1);

//=====================================================
// Status
//=====================================================
define('STATUS_ORDER_SUBMITTED', 0);
define('STATUS_PAYMENT_PROCESSING', 1);
define('STATUS_PAYMENT_PROCESSED', 2);
define('STATUS_PREPARING_ORDER', 3);
define('STATUS_PACKAGING_PRODUCT', 4);
define('STATUS_ORDER_SHIPPED', 5);
define('STATUS_STEP2', 6);
define('STATUS_STEP3', 7);
define('STATUS_STEP4', 8);
define('STATUS_ORDER_SAVED', 9);
define('STATUS_AWAITING_PAYMENT', 10);
define('STATUS_CREDIT_CARD_PAYMENT', 11);
define('STATUS_CHECK_PAYMENT', 12);
define('STATUS_WIRE_TRANSFER_PAYMENT', 13);
define('STATUS_ORDER_CANCELLED', 14);
define('STATUS_ORDER_ON_HOLD', 15);
define('STATUS_IN_BUILD_QUEUE', 16);
define('STATUS_BUILD_STARTED', 17);

define('STATUS_ORDER_SUBMITTED_STATUS_TXT', 'Order Incomplete');
define('STATUS_PAYMENT_PROCESSING_STATUS_TXT', 'Processing Payment');
define('STATUS_PAYMENT_PROCESSED_STATUS_TXT', 'Payment Processed');
define('STATUS_PREPARING_ORDER_STATUS_TXT', 'Preparing Order');
define('STATUS_PACKAGING_PRODUCT_STATUS_TXT', 'Packaging Order');
define('STATUS_ORDER_SHIPPED_STATUS_TXT', 'Order Shipped');
define('STATUS_STEP2_STATUS_TXT', 'Checkout - Step 2');
define('STATUS_STEP3_STATUS_TXT', 'Checkout - Step 3');
define('STATUS_STEP4_STATUS_TXT', 'Checkout - Step 4');
define('STATUS_ORDER_SAVED_STATUS_TXT', 'Order Saved');
define('STATUS_AWAITING_PAYMENT_STATUS_TXT', 'Awaiting Payment');
define('STATUS_CREDIT_CARD_PAYMENT_STATUS_TXT', 'Paid for via credit card');
define('STATUS_CHECK_PAYMENT_STATUS_TXT', 'paid for via check or money order');
define('STATUS_WIRE_TRANSFER_PAYMENT_STATUS_TXT', 'paid for via wire tranfer');
define('STATUS_ORDER_CANCELLED_STATUS_TXT', 'Order Cancelled');
define('STATUS_ORDER_ON_HOLD_STATUS_TXT', 'Order On Hold');
define('STATUS_IN_BUILD_QUEUE_STATUS_TXT', 'Order is in the build queue');
define('STATUS_BUILD_STARTED_STATUS_TXT', 'Order is being built');

define('STATUS_ORDER_SUBMITTED_TXT', 'Your order is incomplete.');
define('STATUS_PAYMENT_PROCESSING_TXT', 'Your payment is being processed.');
define('STATUS_PAYMENT_PROCESSED_TXT', 'Your payment has been successfully processed.');
define('STATUS_PREPARING_ORDER_TXT', 'We are now preparing your order.');
define('STATUS_PACKAGING_PRODUCT_TXT', 'We are now packaging your order for shipment.');
define('STATUS_ORDER_SHIPPED_TXT', 'Your order has been shipped.');
define('STATUS_ORDER_SAVED_TXT', 'Your order has been saved.');
define('STATUS_AWAITING_PAYMENT_TXT', 'We are waiting for your payment to arrive.');
define('STATUS_CREDIT_CARD_PAYMENT_TXT', 'Your order has been paid for via credit card.');
define('STATUS_CHECK_PAYMENT_TXT', 'Your order has been paid for via check or money order.');
define('STATUS_WIRE_TRANSFER_PAYMENT_TXT', 'Your order has been paid for via wire tranfer.');
define('STATUS_ORDER_CANCELLED_TXT', 'Your order has been cancelled.');
define('STATUS_ORDER_ON_HOLD_TXT', 'Your order has been placed on hold.');
define('STATUS_IN_BUILD_QUEUE_TXT', 'Your order has been placed in the build queue');
define('STATUS_BUILD_STARTED_TXT', 'Your order is being built');

//=====================================================
// User Levels <- Do not change these values!!
//=====================================================
define('USER', 0);
define('ADMIN', 1);
define('MOD', 2);
define('BANNED', 3);

//=====================================================
// Currencies
//=====================================================
$FTS_CURRENCIES = array("$" => "Dollar ($)", "&euro;" => "Euro (&euro;)", "&pound;" => "Pound (&pound;)", "&yen;" => "Yen (&yen;)");
$FTS_PAYPAL_CURRENCIES = array("$" => "USD", "&euro;" => "EUR", "&pound;" => "GBP", "&yen;" => "JPY");

//=====================================================
// Shipping Codes
//=====================================================
$FTS_SHIPNAME = array();
$FTS_SHIPNAME['1'] = "UPS Ground";
$FTS_SHIPNAME['2'] = "UPS Second Day Air";
$FTS_SHIPNAME['3'] = "UPS Next Day Air";

$FTS_SHIPNUM = array();
$FTS_SHIPNUM['1'] = "GND";
$FTS_SHIPNUM['2'] = "2DA";
$FTS_SHIPNUM['3'] = "1DA";

$FTS_COUNTRIES = array("USA" => "United States", "CAN" => "Canada", "MEX" => "Mexico", "AFG" => "Afghanistan", "ALB" => "Albania", "DZA" => "Algeria", "ASM" => "American Samoa", "AND" => "Andorra", "AGO" => "Angola", "AIA" => "Anguilla", "ATA" => "Antarctica", "ATG" => "Antigua and Barbuda", "ARG" => "Argentina", "ARM" => "Armenia", "ABW" => "Aruba", "AUS" => "Australia", "AUT" => "Austria", "AZE" => "Azerbaijan", "BHS" => "Bahamas", "BHR" => "Bahrain", "BGD" => "Bangladesh", "BRB" => "Barbados", "BLR" => "Belarus", "BEL" => "Belgium", "BLZ" => "Belize", "BEN" => "Benin", "BMU" => "Bermuda", "BTN" => "Bhutan", "BOL" => "Bolivia", "BIH" => "Bosnia and Herzegowina", "BWA" => "Botswana", "BVT" => "Bouvet Island", "BRA" => "Brazil", "IOT" => "British Indian Ocean Terr.", "BRN" => "Brunei Darussalam", "BGR" => "Bulgaria", "BFA" => "Burkina Faso", "BDI" => "Burundi", "KHM" => "Cambodia", "CMR" => "Cameroon", "CPV" => "Cape Verde", "CYM" => "Cayman Islands", "CAF" => "Central African Republic", "TCD" => "Chad", "CHL" => "Chile", "CHN" => "China", "CXR" => "Christmas Island", "CCK" => "Cocos (Keeling) Islands", "COL" => "Colombia", "COM" => "Comoros", "COG" => "Congo", "COK" => "Cook Islands", "CRI" => "Costa Rica", "CIV" => "Cote d'Ivoire", "HRV" => "Croatia (Hrvatska)", "CUB" => "Cuba", "CYP" => "Cyprus", "CZE" => "Czech Republic", "DNK" => "Denmark", "DJI" => "Djibouti", "DMA" => "Dominica", "DOM" => "Dominican Republic", "TMP" => "East Timor", "ECU" => "Ecuador", "EGY" => "Egypt", "SLV" => "El Salvador", "GNQ" => "Equatorial Guinea", "ERI" => "Eritrea", "EST" => "Estonia", "ETH" => "Ethiopia", "FLK" => "Falkland Islands/Malvinas", "FRO" => "Faroe Islands", "FJI" => "Fiji", "FIN" => "Finland", "FRA" => "France", "FXX" => "France, Metropolitan", "GUF" => "French Guiana", "PYF" => "French Polynesia", "ATF" => "French Southern Terr.", "GAB" => "Gabon", "GMB" => "Gambia", "GEO" => "Georgia", "DEU" => "Germany", "GHA" => "Ghana", "GIB" => "Gibraltar", "GRC" => "Greece", "GRL" => "Greenland", "GRD" => "Grenada", "GLP" => "Guadeloupe", "GUM" => "Guam", "GTM" => "Guatemala", "GIN" => "Guinea", "GNB" => "Guinea-Bissau", "GUY" => "Guyana", "HTI" => "Haiti", "HMD" => "Heard & McDonald Is.", "HND" => "Honduras", "HKG" => "Hong Kong", "HUN" => "Hungary", "ISL" => "Iceland", "IND" => "India", "IDN" => "Indonesia", "IRN" => "Iran", "IRQ" => "Iraq", "IRL" => "Ireland", "ISR" => "Israel", "ITA" => "Italy", "JAM" => "Jamaica", "JPN" => "Japan", "JOR" => "Jordan", "KAZ" => "Kazakhstan", "KEN" => "Kenya", "KIR" => "Kiribati", "PRK" => "Korea, North", "KOR" => "Korea, South", "KWT" => "Kuwait", "KGZ" => "Kyrgyzstan", "LAO" => "Lao People's Dem. Rep.", "LVA" => "Latvia", "LBN" => "Lebanon", "LSO" => "Lesotho", "LBR" => "Liberia", "LBY" => "Libyan Arab Jamahiriya", "LIE" => "Liechtenstein", "LTU" => "Lithuania", "LUX" => "Luxembourg", "MAC" => "Macau", "MKD" => "Macedonia", "MDG" => "Madagascar", "MWI" => "Malawi", "MYS" => "Malaysia", "MDV" => "Maldives", "MLI" => "Mali", "MLT" => "Malta", "MHL" => "Marshall Islands", "MTQ" => "Martinique", "MRT" => "Mauritania", "MUS" => "Mauritius", "MYT" => "Mayotte", "FSM" => "Micronesia", "MDA" => "Moldova", "MCO" => "Monaco", "MNG" => "Mongolia", "MSR" => "Montserrat", "MAR" => "Morocco", "MOZ" => "Mozambique", "MMR" => "Myanmar", "NAM" => "Namibia", "NRU" => "Nauru", "NPL" => "Nepal", "NLD" => "Netherlands", "ANT" => "Netherlands Antilles", "NCL" => "New Caledonia", "NZL" => "New Zealand", "NIC" => "Nicaragua", "NER" => "Niger", "NGA" => "Nigeria", "NIU" => "Niue", "NFK" => "Norfolk Island", "MNP" => "Northern Mariana Is.", "NOR" => "Norway", "OMN" => "Oman", "PAK" => "Pakistan", "PLW" => "Palau", "PAN" => "Panama", "PNG" => "Papua New Guinea", "PRY" => "Paraguay", "PER" => "Peru", "PHL" => "Philippines", "PCN" => "Pitcairn", "POL" => "Poland", "PRT" => "Portugal", "PRI" => "Puerto Rico", "QAT" => "Qatar", "REU" => "Reunion", "ROM" => "Romania", "RUS" => "Russian Federation", "RWA" => "Rwanda", "KNA" => "Saint Kitts and Nevis", "LCA" => "Saint Lucia", "VCT" => "St. Vincent & Grenadines", "WSM" => "Samoa", "SMR" => "San Marino", "STP" => "Sao Tome & Principe", "SAU" => "Saudi Arabia", "SEN" => "Senegal", "SYC" => "Seychelles", "SLE" => "Sierra Leone", "SGP" => "Singapore", "SVK" => "Slovakia (Slovak Republic)", "SVN" => "Slovenia", "SLB" => "Solomon Islands", "SOM" => "Somalia", "ZAF" => "South Africa", "SGS" => "S.Georgia & S.Sandwich Is.", "ESP" => "Spain", "LKA" => "Sri Lanka", "SHN" => "St. Helena", "SPM" => "St. Pierre & Miquelon", "SDN" => "Sudan", "SUR" => "Suriname", "SJM" => "Svalbard & Jan Mayen Is.", "SWZ" => "Swaziland", "SWE" => "Sweden", "CHE" => "Switzerland", "SYR" => "Syrian Arab Republic", "TWN" => "Taiwan", "TJK" => "Tajikistan", "TZA" => "Tanzania", "THA" => "Thailand", "TGO" => "Togo", "TKL" => "Tokelau", "TON" => "Tonga", "TTO" => "Trinidad and Tobago", "TUN" => "Tunisia", "TUR" => "Turkey", "TKM" => "Turkmenistan", "TCA" => "Turks & Caicos Islands", "TUV" => "Tuvalu", "UGA" => "Uganda", "UKR" => "Ukraine", "ARE" => "United Arab Emirates", "GBR" => "United Kingdom", "UMI" => "U.S. Minor Outlying Is.", "URY" => "Uruguay", "UZB" => "Uzbekistan", "VUT" => "Vanuatu", "VAT" => "Vatican (Holy See)", "VEN" => "Venezuela", "VNM" => "Viet Nam", "VGB" => "Virgin Islands (British)", "VIR" => "Virgin Islands (U.S.)", "WLF" => "Wallis & Futuna Is.", "ESH" => "Western Sahara", "YEM" => "Yemen", "YUG" => "Yugoslavia", "ZAR" => "Zaire", "ZMB" => "Zambia", "ZWE" => "Zimbabwe");

$FTS_STATES = array("AA" => "Armed Forces (Americas (except Canada))", "AE" => "Armed Forces (Africa, Canada, Europe, Middle East)", "AL" => "Alabama", "AK" => "Alaska", "AP" => "Armed Forces (Pacific)", "AZ" => "Arizona", "AR" => "Arkansas", "CA" => "California", 
		"CO" => "Colorado", "CT" => "Connecticut", "DE" => "Delaware", "DC" => "District of Columbia", "FL" => "Florida", 
		"GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa", 
		"KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland", 
		"MA" => "Massachusetts", "MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", 
		"MT" => "Montana", "NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey", 
		"NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota", "OH" => "Ohio",
		"OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania", "RI" => "Rhode Island", "SC" => "South Carolina", 
		"SD" => "South Dakota", "TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont", 
		"VA" => "Virginia", "WA" => "Washington", "WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming");
	
//$FTS_CARDS = array("VISA" => "Visa", "MC" => "Master Card", "AE" => "American Express", "DS" => "Discover");
$FTS_CARDS = array("VISA" => "Visa", "MC" => "Master Card");
$FTS_CARDS_PROCESSOR = array("VISA" => "001", "MC" => "002");
$FTS_EXP_MONTH = array("01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "Semptember", "10" => "October", "11" => "November", "12" => "December");
$FTS_EXP_YEAR = array("08" => "2008", "09" => "2009", "10" => "2010", "11" => "2011", "12" => "2012", "13" => "2013", "14" => "2014", "15" => "2015", "16" => "2016");

//=====================================================
// Program used for thumbnail enlargement on customize pages
//=====================================================
$FTS_LIGHTBOXSCRIPT = array("lightbox", "lytebox", "prettyphoto", "greybox");

//=====================================================
// Side that slider should be on
//=====================================================
$FTS_SLIDERSIDE = array("left", "right");
?>