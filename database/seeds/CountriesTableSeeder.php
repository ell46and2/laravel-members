<?php

use App\Models\Country;
use App\Models\County;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ireland, Scoland, England, Wales
        $england = factory(Country::class)->create([
        	'name' => 'england',
        ]);
        	
        $ireland = factory(Country::class)->create([
        	'name' => 'ireland',
        ]);

        $northernIreland = factory(Country::class)->create([
        	'name' => 'northern ireland',
        ]);

        $scotland = factory(Country::class)->create([
        	'name' => 'scotland',
        ]);

        $wales = factory(Country::class)->create([
        	'name' => 'wales',
        ]);
        
    	// England Counties
        $walesCounties = [
        	"Blaenau Gwent",
			"Bridgend",
			"Caerphilly",
			"Cardiff",
			"Carmarthenshire",
			"Ceredigion",
			"Conwy",
			"Denbighshire",
			"Flintshire",
			"Gwynedd",
			"Isle of Anglesey",
			"Merthyr Tydfil",
			"Monmouthshire",
			"Neath Port Talbot",
			"Newport",
			"Pembrokeshire",
			"Powys",
			"Rhondda Cynon Taff",
			"Swansea",
			"Torfaen",
			"Vale of Glamorgan",
			"Wrexham",
        ];

        // England Counties
        $englandCounties = [
        	"County Durham",
            "Cumbria",
            "Northumberland",
            "Tyne & Wear",
            "Humberside",
            "East Yorkshire",
            "North Yorkshire",
            "South Yorkshire",
            "West Yorkshire",
            "Anglesey",
            "Cheshire",
            "Denbighshire",
            "Flintshire",
            "Greater Manchester",
            "Isle of Man",
            "Lancashire",
            "Merseyside",
            "Shropshire",
            "Leicestershire",
            "Lincolnshire",
            "Nottinghamshire",
            "Rutland",
            "Gloucestershire",
            "Herefordshire",
            "Shropshire",
            "Worcestershire",
            "Derbyshire",
            "Northamptonshire",
            "Staffordshire",
            "Warwickshire",
            "West Midlands",
            "Bedfordshire",
            "Cambridgeshire",
            "Essex",
            "Hertfordshire",
            "Norfolk",
            "Suffolk",
            "Berkshire",
            "Buckinghamshire",
            "Hampshire",
            "Isle of Wight",
            "Oxfordshire",
            "Wiltshire",
            "East Sussex",
            "Greater London",
            "Kent",
            "Surrey",
            "West Sussex",
            "Cornwall",
            "Devon",
            "Dorset",
            "Somerset"
        ];
        
        
        // Scotland Counties
        $scotlandCounties = [
        	"Aberdeenshire",
            "Angus",
            "Argyll",
            "Ayrshire",
            "Banffshire",
            "Berwickshire",
            "Bute",
            "Caithness",
            "Clackmannanshire",
            "Dumfriesshire",
            "Dunbartonshire",
            "East Lothian",
            "Fife",
            "Inverness-shire",
            "Kincardineshire",
            "Kinross-shire",
            "Kirkcudbrightshire",
            "Lanarkshire",
            "Midlothian",
            "Moray",
            "Nairnshire",
            "Orkney",
            "Peeblesshire",
            "Perthshire",
            "Renfrewshire",
            "Ross and Cromarty",
            "Roxburghshire",
            "Selkirkshire",
            "Shetland",
            "Stirlingshire",
            "Sutherland",
            "West Lothian",
            "Wigtownshire"
        ];

        // Ireland Counties
        $irelandCounties = [      	
            "Carlow",
            "Cavan",
            "Clare",
            "Cork",
            "Derry",
            "Donegal",
            "Dublin",
            "Galway",
            "Kerry",
            "Kildare",
            "Kilkenny",
            "Laois",
            "Leitrim",
            "Limerick",
            "Longford",
            "Louth",
            "Mayo",
            "Meath",
            "Monaghan",
            "Offaly",
            "Roscommon",
            "Sligo",
            "Tipperary",
            "Waterford",
            "Westmeath",
            "Wexford",
            "Wicklow"
        ];

        // Northern Ireland Counties
        $northernIrelandCounties = [
        	"Antrim",
            "Armagh",
            "Down",
            "Fermanagh",
            "Londonderry",
            "Tyrone",
        ];

        foreach ($walesCounties as $county) {
        	$wales->counties()->create([
        		'name' => $county
        	])->save();
        }

        foreach ($englandCounties as $county) {
        	$england->counties()->create([
        		'name' => $county
        	])->save();
        }

        foreach ($irelandCounties as $county) {
        	$ireland->counties()->create([
        		'name' => $county
        	])->save();
        }

        foreach ($northernIrelandCounties as $county) {
        	$northernIreland->counties()->create([
        		'name' => $county
        	])->save();
        }

        foreach ($scotlandCounties as $county) {
        	$scotland->counties()->create([
        		'name' => $county
        	])->save();
        }
    }
}
