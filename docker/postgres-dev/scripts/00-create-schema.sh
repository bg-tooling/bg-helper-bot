#!/bin/sh

#set -e

psql -v ON_ERROR_STOP=1 --username "postgres" --dbname "${POSTGRES_DB}" <<-EOSQL
    CREATE USER "${POSTGRES_APP_USER}";
    -- GRANT ALL PRIVILEGES ON DATABASE "${POSTGRES_DB}" TO "${POSTGRES_APP_USER}";
EOSQL

psql -v ON_ERROR_STOP=1 --username "postgres" --dbname "${POSTGRES_DB}" <<-EOSQL
    CREATE SCHEMA IF NOT EXISTS "core" AUTHORIZATION "${POSTGRES_APP_USER}";
EOSQL

