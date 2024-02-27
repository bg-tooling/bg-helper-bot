CREATE TABLE "core"."article" (
  "id" uuid NOT NULL,
  "title" text NOT NULL,
  "keywords" text NOT NULL,
  "body" text NOT NULL,
  "tsv" tsvector NOT NULL,
  PRIMARY KEY ("id")
);

CREATE FUNCTION article_update_trigger() RETURNS trigger AS $$
BEGIN
  new.tsv :=
     setweight(to_tsvector('pg_catalog.russian', coalesce(new.keywords,'')), 'B') ||
	 setweight(to_tsvector('pg_catalog.russian', coalesce(new.title,'')), 'A') ||
     setweight(to_tsvector('pg_catalog.russian', coalesce(new.body,'')), 'D');
  return new;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER tsvector_update BEFORE INSERT OR UPDATE
  ON "core"."article" FOR EACH ROW EXECUTE FUNCTION article_update_trigger();
  
ALTER TABLE "core"."article" ADD COLUMN "types" int8 NOT NULL DEFAULT 0;

ALTER TABLE "core"."article" ADD COLUMN "links" uuid[] NOT NULL DEFAULT {};
