# Zadanie č.7 - mashup
## Links of interest
### IP Api
	[ipstack](https://ipstack.com/)
### Weather API
	[openweathermap](https://openweathermap.org/api)
### Local time API
	[worldweatheronline](https://www.worldweatheronline.com/)

## Úlohy:
- Vytvorte webový „portál“, ktorý bude pozostávať z troch stránok: 
  - Na prvej stránke bude zobrazená predpoveď počasia pre miesto, ktoré je dané IP adresou návštevníka vašej stránky. Pokiaľ nebude možné nájsť predpoveď počasia pre dané miesto, tak predpoveď sa zobrazí pre najbližšie mesto, pre ktoré je predpoveď k dispozícii. 
  - Na druhej stránke budú zobrazené tieto údaje:
    - IP adresa návštevníka danej stránky, 
    - GPS súradnice zodpovedajúceho miesta, 
    - mesto, v rámci ktorého sa dané súradnice nachádzajú (ak sa toto mesto nedá lokalizovať, tak sa vypíše reťazec typu „mesto sa nedá lokalizovať alebo sa nachádzate na vidieku“), 
    - štát, ktorému daná IP adresa prislúcha, 
    - hlavné mesto tohoto štátu. 
  - Na tretej stránke budú zobrazené nasledujúce štatistické údaje:
    - počet návštevníkov vašeho portálu, pričom títo návštevníci budú rozdelení na základe štátov, z ktorých podľa svojej IP adresy pochádzajú. Tieto údaje uveďte prehľadne do tabuľky, v ktorej bude uvedená zástavka daného štátu, meno tohto štátu a počet návštevníkov z tohoto štátu. Za unikátnu návštevu sa považuje 1 návšteva z 1 IP adresy počas 1 dňa. 
    - v prípade kliknutia na daný štát sa otvorí podobná tabuľka, kde sa budú zobrazovať informácie o počtoch návštev z miest daného štátu. Neidentifikované mestá sa budú spočítavať do kolonky „nelokalizované mestá a vidiek“. 
    - ***Not implemented*** Google mapa s bodkami, odkiaľ pochádzali návštevníci vašeho portálu. 
    - informácia, v ktorom čase koľko ľudí navštívilo váš portál. Vyhodnocujte časové pásma medzi 6:00-14:00, 14:00-20:00, 20:00-24:00, 24:00-6:00. Berte do úvahy lokálny čas daného užívateľa (t.j. ak sa na stránku pozrie Bratislavčan o 19:00 a človek z New Yorku svojho lokálneho času tiež o 19:00, tak napriek tomu, že medzi týmito dvoma mestami je časový posun 6 hodín, tak sa to bude považovať za rovnaký lokálny čas). 
    - informácia o tom, ktorá z vašich troch stránok bol najčastejšie navštevovaná. 
- Pri vytváraní zadania máte možnosť používať všetky v rámci PHP preberané technológie (CURL, rôzne API,..) a všetky dostupné informačné zdroje (napr. wikipédiu, ...). Adresy pre API si kľudne môžete vymieňať prostredníctvom diskusného fóra. Na zobrazovanie vlajok môžete použiť napríklad: http://www.geonames.org/flags/x/de.gif. Názov obrázka by mal byť totožný s ISO kódom danej krajiny, ktorý sa používa aj v mailových adresách. 
- Počíta sa s tým, že počítadlo prístupov si vytvoríte sami, t.j. v tomto prípade využite nejakej služby na Internete nie je povolené. 
- Kvôli otestovaniu funkcionality môžete na simulovanie návštev z iných štátov použiť napríklad web stránku: https://hide.me/en/proxy.
- Všetky použité API uveďte do technickej dokumentácie.
