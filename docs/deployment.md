# Deployment & Manutenzione

Il deploy dell'applicazione viene effettuato tramite deployer,
attualmente sono configurati 2 environments production e stage.
E' inoltre possibile attivare la modalità manutenzione per entrambi gli ambienti
sempre tramite deployer.
In modalità manutenzione il sito non sarà accessibile, e
la homepage riporterà il seguente testo:

```text
Ops...!!! Sito in manutenzione

Ci scusiamo per l'inconveniente, torneremo online al più presto.
Riprova più tardi.
```

Per attivare la modalità di manutenzione usare i seguento comandi nell'ambiente desiderato

#### Manutenzione in production
```bash
./vendor/bin/dep maintenance:on production

oppure

./vendor/bin/dep maintenance:off production
```

#### Manutenzione in staging
```bash
./vendor/bin/dep maintenance:on stage-librai

oppure

./vendor/bin/dep maintenance:off stage-librai
```
## Deployment Production Environment

#### host: https://www.8viadeilibrai.it

#### deployer name: production

Run deploy:

```shell
./vendor/bin/dep deploy production
```

## Deployment Stage Environment

#### host: https://stage.8viadeilibrai.it

#### deployer name: stage-librai

Run deploy:

```shell
./vendor/bin/dep deploy stage-librai
```