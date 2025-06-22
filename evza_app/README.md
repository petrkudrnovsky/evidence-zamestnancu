# Projekt aplikace EVZA - EVidence ZAměstnanců

## Před spuštěním

Je potřeba nainstalovat všechny závislosti:

```bash
docker compose up
docker exec -it twa-app composer update
```

## Informace pro developery

Při spouštění Fixtures je problém v jednom souboru balíčku, konkrétně ve `vendor/fakerphp/faker/src/Faker/Provider/Lorem.php` na řádce 95:

```php
return join($words, ' ') . '.';
```

Změnit na:

```php
return join(' ', $words) . '.';
```

## Autor

Petr Kudrnovský - kudrnpe3 v rámci předmětu BI-TWA na ČVUT FIT v Praze
