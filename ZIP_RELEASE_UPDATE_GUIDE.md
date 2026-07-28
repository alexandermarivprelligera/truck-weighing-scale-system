# ZIP Release Update Guide — Preserve Live Transactions

This guide walks through deploying a new release ZIP onto an existing **Truck Weighing Scale System** installation. The update replaces application code (Electron, Laravel, controllers, models, views, routes, resources, PHP, MariaDB binaries, and migrations) while **preserving all existing transactions, clients, users, and drivers** in the live MariaDB database.

## How data is preserved

The update process works by:

1. **Copying new files over the old ones** — but explicitly **skipping** the MariaDB `data` directory and the `.env` file.
2. **Running `php artisan migrate --force`** — which consults the existing `migrations` table in the database and **only applies migrations that have not already run**. It never drops tables, deletes rows, or recreates the database.

Laravel tracks which migrations have been applied in a `migrations` table inside `weighing_db`. When you run `migrate --force`, it compares the migration files on disk against what is recorded in that table and runs only the new ones. **Your historical transactions, clients, drivers, and users are untouched.**

### What each pending migration does

| Migration | Effect on existing database | Effect on historical transactions |
| --- | --- | --- |
| `2026_07_05_093820_make_transaction_fields_nullable` | Allows `transactions.plate_number` to be empty. | No rows are changed or deleted. |
| `2026_07_16_024609_create_drivers_table` | Creates the new `drivers` table, linked to `clients`. | No rows in `transactions`, `clients`, or any other existing table are changed or deleted. |

## Never use these commands on a live installation

These commands **will destroy** production data and must never be part of a deployment:

```powershell
php artisan migrate:fresh      # Drops all tables and recreates from scratch
php artisan migrate:refresh     # Rolls back and re-runs all migrations
php artisan db:wipe             # Drops all tables
php artisan migrate --seed      # Runs migrations AND seeds (can overwrite data)
```

Do not import `database_backup\weighing_db.sql` into the live database. That file is a snapshot for recovery/testing, not an upgrade procedure.

## Where the live data lives

In the packaged Electron application, MariaDB stores all data here:

```text
<installation folder>\resources\app\mariadb\data
```

The `weighing_db` database is a subfolder of that directory. This folder contains all transactions, users, clients, drivers, and the migration history. **Treat this folder as sacred** — do not replace, delete, or overwrite it during a normal update.

## Deployment steps

Schedule a short maintenance window. No users should create or edit transactions until the verification in step 6 passes.

### 1. Close the running system

Close the application on every workstation. In Task Manager, confirm that these processes are **not** running:

- `Truck Weighing Scale System.exe`
- `mariadbd.exe`
- `php.exe`
- `scale-bridge.exe`

### 2. Back up the live database and configuration

Create a dated backup folder **outside** the installation directory. Copy the complete `data` directory while MariaDB is stopped, and save the live `.env`.

Adjust these paths to match your site:

```powershell
$install  = 'C:\Program Files\Truck Weighing Scale System'
$updateZip = 'C:\Installers\Truck-Weighing-Update.zip'
$backup   = 'C:\Truck-Weighing-Backups\2026-07-28-before-update'
$staging  = 'C:\Truck-Weighing-Updates\staging'

New-Item -ItemType Directory -Force -Path $backup | Out-Null
Copy-Item -Recurse -Force "$install\resources\app\app\mariadb\data" "$backup\mariadb-data"
Copy-Item -Force "$install\resources\app\.env" "$backup\.env"
```

Record the current transaction count for post-update verification. Start the app briefly to start MariaDB, then run:

```powershell
& "$install\resources\app\php\php.exe" "$install\resources\app\artisan" migrate:status
& "$install\resources\app\app\mariadb\bin\mariadb.exe" --host=127.0.0.1 --port=3307 --user=root -e "USE weighing_db; SELECT COUNT(*) AS transaction_count FROM transactions;"
```

Save the output in the backup folder, then close the app completely again.

> If the database uses a root password, add `--password` and enter it at the prompt. Never put the password in a script or document.

### 3. Extract and validate the update ZIP

Extract to a separate staging folder — **never** directly over the installation:

```powershell
Remove-Item -Recurse -Force $staging -ErrorAction SilentlyContinue
Expand-Archive -Path $updateZip -DestinationPath $staging -Force
```

Validate the ZIP contents before proceeding:

```powershell
if (Test-Path "$staging\resources\app\app\mariadb\data") {
    throw 'Unsafe ZIP: it contains a MariaDB data folder. Do not install it.'
}
if (-not (Test-Path "$staging\resources\app\artisan")) {
    throw 'Invalid ZIP: Laravel runtime files are missing.'
}
if (-not (Test-Path "$staging\resources\app\database\migrations\2026_07_16_024609_create_drivers_table.php")) {
    throw 'Invalid ZIP: expected database migration is missing.'
}
```

### 4. Copy the update over the installation, excluding live data

Run this while the application remains closed. `robocopy` updates all release files but **explicitly excludes** the MariaDB data folder and `.env` — the two items that must remain intact:

```powershell
robocopy $staging $install /E /COPY:DAT /DCOPY:DAT /R:2 /W:2 `
  /XD "$staging\resources\app\app\mariadb\data" `
  /XF "$staging\resources\app\.env"

if ($LASTEXITCODE -gt 7) { throw "Update copy failed (robocopy exit code $LASTEXITCODE)." }
```

After copying, verify the database is still there:

```powershell
Test-Path "$install\resources\app\app\mariadb\data\weighing_db"
```

Must return `True`. If it does not, **stop** and restore from backup before continuing.

### 5. Apply only the new database migrations

Open the updated application and wait for it to appear (this starts the bundled MariaDB). Then run:

```powershell
& "$install\resources\app\php\php.exe" "$install\resources\app\artisan" optimize:clear
& "$install\resources\app\php\php.exe" "$install\resources\app\artisan" migrate --force
```

**What `migrate --force` does:**

- Reads the `migrations` table in `weighing_db` to see which migrations have already run.
- Compares against the migration files in `resources\app\database\migrations\`.
- Executes **only** migrations that are not yet recorded.
- Does **not** drop tables, delete rows, or recreate any existing data.

Expected output for a site upgrading from the previous release:

```text
INFO  Preparing database and running migrations.

Migrating: 2026_07_05_093820_make_transaction_fields_nullable
Migrated:  2026_07_05_093820_make_transaction_fields_nullable (XX.XXms)
Migrating: 2026_07_16_024609_create_drivers_table
Migrated:  2026_07_16_024609_create_drivers_table (XX.XXms)
```

If a migration was already applied in a previous update, it will show as `Already run` and be skipped. Your transactions remain untouched throughout.

### 6. Verify before ending the maintenance window

Run these checks with the application open:

```powershell
& "$install\resources\app\php\php.exe" "$install\resources\app\artisan" migrate:status
& "$install\resources\app\app\mariadb\bin\mariadb.exe" --host=127.0.0.1 --port=3307 --user=root -e "USE weighing_db; SELECT COUNT(*) AS transaction_count FROM transactions; SHOW TABLES LIKE 'drivers';"
```

Confirm **all** of the following:

1. All migrations show `Ran` in `migrate:status`.
2. The `drivers` table is returned by `SHOW TABLES`.
3. **The transaction count matches the count recorded in step 2.** This is the critical check — if the count is the same, your old transactions are safe.
4. Historical transactions can be searched, printed, and downloaded.
5. The new fields work (e.g., creating and selecting a driver).
6. A new test transaction can be created and appears in the list.

Keep the backups until the site owner accepts the update.

## Rollback if something goes wrong

If verification fails:

1. Close the application and confirm MariaDB has stopped.
2. Restore the previous application files (the old release ZIP/folder).
3. Restore the saved `mariadb-data` folder to `$install\resources\app\app\mariadb\data` while MariaDB is stopped.
4. Restore `$backup\.env` if it was changed.
5. Start the old version and confirm the saved transaction count.

Do not attempt a rollback by dropping tables or running Laravel rollback commands against live transaction data.
