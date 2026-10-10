#!/usr/bin/env bash

set -euo pipefail
umask 077

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

for command in php mysqldump date mktemp; do
    if ! command -v "$command" >/dev/null 2>&1; then
        printf 'Required command not found: %s\n' "$command" >&2
        exit 1
    fi
done

if [[ ! -f .env ]]; then
    printf 'Missing .env file in %s\n' "$ROOT_DIR" >&2
    exit 1
fi

backup_date="$(date +%F)"
defaults_file="$(mktemp)"
dump_file="$(mktemp "$ROOT_DIR/.database-${backup_date}.XXXXXX")"
trap 'rm -f "$defaults_file" "$dump_file"' EXIT

php -r '
require "vendor/autoload.php";
$vars = (new CodeIgniter\Config\DotEnv(getcwd()))->parse();
$keys = [
    "database.default.hostname",
    "database.default.username",
    "database.default.password",
    "database.default.port",
];
foreach ($keys as $key) {
    if (! isset($vars[$key]) || $vars[$key] === "") {
        fwrite(STDERR, "Missing required .env setting: {$key}\n");
        exit(1);
    }
}
if (! ctype_digit((string) $vars["database.default.port"])) {
    fwrite(STDERR, "database.default.port must be numeric\n");
    exit(1);
}
$quote = static function (string $value): string {
    if (str_contains($value, "\n") || str_contains($value, "\r")) {
        throw new RuntimeException("Database connection settings cannot contain newlines");
    }
    return "\"" . addcslashes($value, "\\\"") . "\"";
};
echo "[client]\n";
echo "host=" . $quote($vars["database.default.hostname"]) . "\n";
echo "port=" . $vars["database.default.port"] . "\n";
echo "user=" . $quote($vars["database.default.username"]) . "\n";
echo "password=" . $quote($vars["database.default.password"]) . "\n";
' > "$defaults_file"

database="$(php -r '
require "vendor/autoload.php";
$vars = (new CodeIgniter\Config\DotEnv(getcwd()))->parse();
$database = $vars["database.default.database"] ?? "";
if ($database === "") {
    fwrite(STDERR, "Missing required .env setting: database.default.database\n");
    exit(1);
}
echo $database;
')"

mysqldump --defaults-extra-file="$defaults_file" \
    --single-transaction \
    --routines \
    --events \
    --triggers \
    --add-drop-database \
    --databases "$database" > "$dump_file"

if [[ ! -s "$dump_file" ]]; then
    printf 'Database dump is empty; keeping any existing backup unchanged\n' >&2
    exit 1
fi

chmod 600 "$dump_file"
mv -f "$dump_file" "$ROOT_DIR/${backup_date}.sql"
printf 'Database backup written to %s/%s.sql\n' "$ROOT_DIR" "$backup_date"