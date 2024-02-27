FROM postgres:14

# Fix for build
ARG DEBIAN_FRONTEND=noninteractive

ENV LANG C.UTF-8
ENV POSTGRES_APP_USER bot

# Directory for postgres data
RUN ls -l "$PGDATA"
RUN chown -v -R postgres "$PGDATA"
RUN chmod 700 "$PGDATA"
RUN ls -l "$PGDATA"

# required files
#WORKDIR /docker-entrypoint-initdb.d/
COPY ./docker/postgres-dev/scripts/* /docker-entrypoint-initdb.d/
RUN chmod -R 755 /docker-entrypoint-initdb.d/

WORKDIR "$PGDATA"
