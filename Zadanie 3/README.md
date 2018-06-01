# Zadanie č.3 - Autentifikácia
## Prerequirements
```
apt-get install php-ldap
apt-get install php7.0-curl
```

## Úlohy:
- Vytvorte web aplikáciu, do ktorej sa užívateľ bude môcť prihlásiť podľa svojeho výberu jednou z troch možností:
  - pomocou vlastnej registrácie (kvôli tomuto bodu je potrebné sprístupniť užívateľovi registračný formulár, pri ktorom si zadá meno, priezvisko, email, login a heslo, pričom tieto údaje sa budú ukladať do databázy),
  - pomocou LDAP na STU Bratislava,
  - pomocou konta na Google.
- Do databázy ukladajte aj informáciu o jednotlivých prihláseniach užívateľov. V tabuľke, ktorú na tento účel vytvoríte, evidujte login užívateľa, čas jeho prihlásenia a spôsob prihlásenia (registrácia, ldap, google).
- Po prihlásení sa do aplikácie zobrazte užívateľovi informáciu, kto je prihlásený, vhodnú uvítaciu správu a hyperlinku (tlačidlo) "Minulé prihlásenia". Po kliknutí na tento odkaz sa užívateľovi zobrazí história prihlásení pre daný účet (registrácia, ldap, google) a štatistika, koľko užívateľov sa doteraz prihlásilo do aplikácie cez jednotlivé spôsoby prihlásenia (registrácia, ldap, google).
- Informácia o prihlásenom užívateľovi musí zostať stále zobrazená. Nezabudnite zapezpečiť aj odhlásenie užívateľa z aplikácie.
