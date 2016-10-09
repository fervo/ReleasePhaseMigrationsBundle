# Fervo Release Phase Migrations Bundle

## Bundle installation

Install as per usual. The bundle requires DoctrineMigrationBundle. Because this bundle is still being tried out by us internally, we're reluctant to release better instructions on how to use it. While both the library and the bundle is released as 1.0.0, this is mainly because we want to be able to strictly version it using semver. Once we've used this in production for a while, we'll go ahead and update these instructions.

## Why use this instead of just using Doctrine Migrations

This bundle is just a thin wrapper around Doctrine Migrations. The only difference is that before we start migrating, (as per Heroku's recommendation) we try to acquire an advisory lock on the database. If we fail to get that lock, the migration will not be performed, and (if you're using this for release phase migrations), the release will fail.

## Using the bundle

Add the following to your Procfile:

```
release: php bin/console fervo:release-phase-migrations:migrate --allow-no-migration -n
```

## That's it?

Sort of. If you want to make sure your deploys are zero downtime, there are two things you need to do:

1. Enable preboot. All the usual caveats for using preboot still apply: https://devcenter.heroku.com/articles/preboot#caveats
2. Make sure all of your migrations are backwards compatible, i.e. that your old codebase can still run on the migrated database. Depending on what you're doing, this can be a pretty severe restriction.
