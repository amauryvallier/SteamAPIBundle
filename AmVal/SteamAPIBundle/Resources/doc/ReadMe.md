# README

## Technical informations & TODOs

Just an old bundle used to gather informations (mainly achievements) from Steam API for users with public profile

###Infos & References:

Symfony 3.0

* https://steamcommunity.com/dev
* https://github.com/MattRyder/SteamAPI/tree/master/steam
* https://github.com/Neoseeker/SteamAPI
* https://github.com/syntaxerrors/Steam

Commands:

* `php bin/console doctrine:generate:entities AmValSteamAPIBundle`
* `php bin/console doctrine:schema:update --em=steam --force`
* `php bin/console steamapi:crawl-profile <Profile_ID>`

###TODO:

Everything
* ManyToMany entre User et Game (possède)
* Console command to automatically crawling datas for a user => multithread
* Specific templating (profile & others)
* Achievements statistics
* Timeline des achievements

* Augmented reality like ES2
  * Exemple: on game page, you press space and switch between generic informations/player information/comparison

* Analyse getGlobalStatsForGame function
* URLS for Help:
  * http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2?appid=17740&format=json&key=ADA752FBAF6A863DE8A8323EC5CC53A7&
  * http://api.steampowered.com/ISteamUserStats/GetGlobalStatsForGame/v0001?appid=17740&count=29&name[0]=player.times_commanded&name[1]=player.time_played&name[2]=player.rounds_played&name[3]=player.engineer_played&name[4]=player.rifleman_played&name[5]=player.grenadier_played&name[6]=player.scout_played&name[7]=player.avg_xp&name[8]=global.map.emp_isle&name[9]=global.map.emp_crossroads&name[10]=global.map.emp_canyon&name[11]=global.map.emp_urbanchaos&name[12]=global.map.emp_coast&name[13]=global.map.emp_slaughtered&name[14]=global.map.emp_streetsoffire&name[15]=global.map.emp_cyclopean&name[16]=global.map.emp_duststorm&name[17]=global.map.emp_mvalley&name[18]=global.map.emp_district402&name[19]=global.map.emp_arid&name[20]=global.map.emp_bush&name[21]=global.map.emp_escort&name[22]=global.map.emp_glycenplains&name[23]=global.map.emp_money&name[24]=global.map.emp_midbridge&name[25]=global.map.emp_palmbay&name[26]=global.map.emp_eastborough&name[27]=global.rounds_won.nf&name[28]=global.rounds_won.be&format=json&key=ADA752FBAF6A863DE8A8323EC5CC53A7&
  * http://api.steampowered.com/ISteamUserStats/GetGlobalStatsForGame/v0001?appid=17740&count=20&name[0]=global.map.emp_isle&name[1]=global.map.emp_crossroads&name[2]=global.map.emp_canyon&name[11]=global.map.emp_urbanchaos&name[3]=global.map.emp_coast&name[4]=global.map.emp_slaughtered&name[5]=global.map.emp_streetsoffire&name[6]=global.map.emp_cyclopean&name[7]=global.map.emp_duststorm&name[8]=global.map.emp_mvalley&name[9]=global.map.emp_district402&name[10]=global.map.emp_arid&name[11]=global.map.emp_bush&name[12]=global.map.emp_escort&name[13]=global.map.emp_glycenplains&name[14]=global.map.emp_money&name[15]=global.map.emp_midbridge&name[16]=global.map.emp_palmbay&name[17]=global.map.emp_eastborough&name[18]=global.rounds_won.nf&name[19]=global.rounds_won.be&format=json&key=ADA752FBAF6A863DE8A8323EC5CC53A7&
