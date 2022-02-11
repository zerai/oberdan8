# Release
## manual process

- update del file `conf/prod/sentry.yaml` - aggiornare il numero versione.
```yaml
sentry:
    options:
        release: "0.1.1
```

- update del file di confgurazione di Deployer `deploy.php` - aggiornare il numero versione.

```php
    // git & composer settings
    //->set('branch', 'main')
    //->set('tag', '0.1.0')
    //->set('tag', '0.1.1')
    ->set('tag', '0.1.2')
```

Seguire la procedura standard di github:

- click nuova release 
- assegnare il numero di tag
- autogenerate il releas-notes
- save