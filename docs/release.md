# Release
## manual process

- update del file `conf/prod/sentry.yaml` - aggiornare il numero versione.
```yaml
sentry:
    options:
        release: "0.1.1
```

Seguire la procedura standard di github:

- click nuova release 
- assegnare il numero di tag
- autogenerate il releas-notes
- save