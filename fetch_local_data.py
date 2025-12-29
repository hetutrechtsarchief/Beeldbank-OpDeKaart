#!/usr/bin/env python3
import argparse
import json
import re
import time
from pathlib import Path
from urllib.error import HTTPError, URLError
from urllib.parse import urlencode
from urllib.request import Request, urlopen

API_BASE = (
    "https://api.data.netwerkdigitaalerfgoed.nl/"
    "datasets/hetutrechtsarchief/Beeldbank/services/Beeldbank/sparql"
)

SPARQL_TEMPLATE = """\
PREFIX edm: <http://www.europeana.eu/schemas/edm/>
PREFIX dct: <http://purl.org/dc/terms/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX sem: <http://semanticweb.cs.vu.nl/2009/11/sem/>
SELECT ?uuid ?catalogusnummer ?description ?beginTimeStamp ?endTimeStamp ?rights WHERE {{
  ?bbitem dct:spatial <http://www.wikidata.org/entity/{wikidata_id}> .
  ?bbitem edm:isShownBy ?img .
  ?bbitem edm:isShownAt ?rec .
  ?bbitem dc:identifier ?catalogusnummer .
  ?bbitem dc:description ?description .
  ?bbitem sem:hasBeginTimeStamp ?beginTimeStamp .
  ?bbitem sem:hasEndTimeStamp ?endTimeStamp .
  ?bbitem dc:rights ?rights .
  BIND(REPLACE(str(?bbitem), "https://hetutrechtsarchief.nl/id/", "") AS ?uuid)
}}
"""

WIKIDATA_RE = re.compile(r"^Q\d+$")


def load_wikidata_ids(paths):
    wikidata_ids = set()
    for path in paths:
        data = json.loads(Path(path).read_text())
        for feature in data.get("features", []):
            wd = feature.get("properties", {}).get("wd")
            if isinstance(wd, str) and WIKIDATA_RE.match(wd):
                wikidata_ids.add(wd)
    return sorted(wikidata_ids)


def fetch_wikidata(wikidata_id):
    query = SPARQL_TEMPLATE.format(wikidata_id=wikidata_id)
    url = f"{API_BASE}?{urlencode({'query': query})}"
    req = Request(url, headers={"Accept": "application/sparql-results+json"})
    with urlopen(req) as resp:
        return json.loads(resp.read().decode("utf-8"))


def main():
    parser = argparse.ArgumentParser(
        description=(
            "Fetch SPARQL JSON for Wikidata IDs found in the GeoJSON files."
        )
    )
    parser.add_argument(
        "--files",
        nargs="+",
        default=[
            "stations/stations.geojson",
            "gemeente-utrecht.geojson",
            "buiten-utrecht.geojson",
        ],
        help="GeoJSON files to scan for properties.wd entries.",
    )
    parser.add_argument(
        "--output-dir",
        default="local-data",
        help="Directory to store JSON responses by Wikidata ID.",
    )
    parser.add_argument(
        "--force",
        action="store_true",
        help="Overwrite existing files.",
    )
    parser.add_argument(
        "--sleep",
        type=float,
        default=0.2,
        help="Seconds to wait between requests.",
    )
    args = parser.parse_args()

    output_dir = Path(args.output_dir)
    output_dir.mkdir(parents=True, exist_ok=True)

    wikidata_ids = load_wikidata_ids(args.files)
    if not wikidata_ids:
        print("No Wikidata IDs found.")
        return

    total = len(wikidata_ids)
    for idx, wikidata_id in enumerate(wikidata_ids, start=1):
        out_path = output_dir / f"{wikidata_id}.json"
        if out_path.exists() and not args.force:
            print(f"[{idx}/{total}] Skipping {wikidata_id} (exists)")
            continue
        try:
            data = fetch_wikidata(wikidata_id)
        except HTTPError as exc:
            print(f"[{idx}/{total}] HTTP error for {wikidata_id}: {exc}")
            continue
        except URLError as exc:
            print(f"[{idx}/{total}] URL error for {wikidata_id}: {exc}")
            continue
        out_path.write_text(json.dumps(data, ensure_ascii=True, indent=2))
        print(f"[{idx}/{total}] Saved {out_path}")
        if args.sleep:
            time.sleep(args.sleep)


if __name__ == "__main__":
    main()
