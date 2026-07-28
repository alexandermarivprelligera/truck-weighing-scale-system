# Updating an Existing Truck Weighing Scale System Installation

This guide is for updating a site that already contains real weighing transactions. The update must preserve the site's MariaDB data directory and run Laravel migrations against that existing database. Do **not** replace the database with the one included in a newly built package.

## What the current changes do

The current release contains two database migrations:

| Migration | Effect on an existing database | Effect on historical transactions |
| --- | --- | --- |
| `2026_07_05_093820_make_transaction_fields_nullable` | Allows `transactions.plate_number` to be empty. | No rows are changed or deleted. |
| `2026_07_16_024609_create_drivers_table` | Creates the new `drivers` table, linked to `clients`. | No rows in `transactions`, `clients`, or any other existing table are changed or deleted. |

Laravel records completed migrations in the `migrations` table. Therefore, `php artisan migrate --force` runs only migrations that have not already been applied; it does not recreate the database or erase prior transactions.

## Never use these commands on a production installation

These commands can destroy or replace production data and must not be part of an update:

```powershell
php artisan migrate:fresh
php artisan migrate:refresh
php artisan db:wipe
php artisan migrate --seed
```

Also do not import `database_backup\weighing_db.sql` over a live database unless the explicit purpose is a full restore. An import replaces/changes data according to the SQL file and is not an upgrade procedure.

## Where the deployed data is stored

In the packaged Electron application, the embedded MariaDB server uses this directory:

```text
<installation folder>\resources\app\mariadb\data
```

The actual `weighing_db` is a subfolder of that directory. This folder contains all transactions, users, clients, drivers, and the migration history. Treat the entire `data` folder as one unit: do not copy individual `.ibd`, `ibdata1`, or log files while MariaDB is running.

The examples below assume the installed application folder is `C:\Program Files\Truck Weighing Scale System`. Adjust it to the real installation folder. If an installer puts the files elsewhere, locate the folder that contains `resources\app\mariadb\my.ini` and use that as the installation root.

## Upgrade procedure

Perform this during a short maintenance window, when no one is creating or editing transactions.

### 1. Close the application completely

Ask every user to close the Truck Weighing Scale System. In Task Manager, confirm that `Truck Weighing Scale System.exe`, `mariadbd.exe`, and `php.exe` are no longer running. This makes the database backup consistent.

### 2. Make two backups before changing anything

Create a dated working folder outside the installation directory, for example `C:\Truck-Weighing-Backups\2026-07-17`.

First, make a file-level backup of the complete MariaDB `data` folder. This is the quickest full rollback copy:

```powershell
$install = 'C:\Program Files\Truck Weighing Scale System'
$backup = 'C:\Truck-Weighing-Backups\2026-07-17'
New-Item -ItemType Directory -Force -Path $backup
Copy-Item -Recurse -Path "$install\resources\app\mariadb\data" -Destination "$backup\mariadb-data"
```

Then make a portable SQL backup. Use the database port in `resources\app\mariadb\my.ini` (the current bundled configuration uses `3307`). Start the application once so MariaDB starts, wait a few seconds, and run:

```powershell
$install = 'C:\Program Files\Truck Weighing Scale System'
& "$install\resources\app\mariadb\bin\mariadb-dump.exe" --host=127.0.0.1 --port=3307 --user=root --single-transaction --routines --events --databases weighing_db | Set-Content -Encoding utf8 "$backup\weighing_db-before-update.sql"
```

Close the application again after the dump. Check that the SQL file exists and is not empty. Keep both backups until the update has been accepted.

> If the deployed database has a root password, add `--password` and enter it when prompted. Do not put the password into a shared script or documentation.

### 3. Record the pre-update state

With the application running only long enough to start MariaDB, record the existing migration and transaction counts:

```powershell
$install = 'C:\Program Files\Truck Weighing Scale System'
& "$install\php\php.exe" "$install\artisan" migrate:status
& "$install\resources\app\mariadb\bin\mariadb.exe" --host=127.0.0.1 --port=3307 --user=root -e "USE weighing_db; SELECT COUNT(*) AS transaction_count FROM transactions;"
```

Save the output with the backups, then close the application. The transaction count is a simple post-update check.

### 4. Install the new application files while preserving the old data

Use the release installer or replace the application files according to the normal deployment method. **Do not allow the old `resources\app\mariadb\data` folder to be deleted or replaced.**

If the installer must replace the complete `resources\app` folder, restore the file-level backup taken in step 2 before starting the updated application:

```powershell
$install = 'C:\Program Files\Truck Weighing Scale System'
$backup = 'C:\Truck-Weighing-Backups\2026-07-17'
Copy-Item -Recurse -Force -Path "$backup\mariadb-data\*" -Destination "$install\resources\app\mariadb\data"
```

Do this only while the application and MariaDB are stopped. This restores the exact database state from just before installation.

The production `.env` can also contain site-specific settings. Preserve it unless the release specifically requires a configuration change. In particular, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_PORT` must continue to point to the existing bundled MariaDB instance. For this package, `DB_PORT` should match `resources\app\mariadb\my.ini` (currently `3307`).

### 5. Run the migrations once

The release package must contain the `database` directory so that the migration files are available. This repository now includes it in the Electron packaging configuration. Build and distribute a new package after this change.

Start the updated application, wait until MariaDB is available, then run from an elevated PowerShell only if the installation is under `Program Files`:

```powershell
$install = 'C:\Program Files\Truck Weighing Scale System'
& "$install\php\php.exe" "$install\artisan" migrate --force
```

Expected result for a site upgrading from the previous release:

```text
Migrating: 2026_07_05_093820_make_transaction_fields_nullable
Migrated:  2026_07_05_093820_make_transaction_fields_nullable
Migrating: 2026_07_16_024609_create_drivers_table
Migrated:  2026_07_16_024609_create_drivers_table
```

Sites that already ran one of these migrations will simply show it as already applied. Do not run `migrate:rollback` as a way to retry an update.

### 6. Verify the update

Run the following checks while the application is open:

```powershell
$install = 'C:\Program Files\Truck Weighing Scale System'
& "$install\php\php.exe" "$install\artisan" migrate:status
& "$install\resources\app\mariadb\bin\mariadb.exe" --host=127.0.0.1 --port=3307 --user=root -e "USE weighing_db; SELECT COUNT(*) AS transaction_count FROM transactions; SHOW TABLES LIKE 'drivers';"
```

Confirm all of the following:

1. Both migrations show `Ran` in `migrate:status`.
2. The `drivers` table is returned.
3. The transaction count matches the count recorded in step 3.
4. The application opens, historical transaction search/print/download works, and a new driver can be created and selected.

## If the update fails

Stop the application. Restore the prior application version and restore the complete `mariadb-data\data` backup from step 2 to `resources\app\mariadb\data` while MariaDB is stopped. Start the old version and confirm the saved transaction count.

Use the SQL backup only when the data directory backup cannot be used. Restoring a SQL dump is a database recovery operation and should be performed into a stopped/clean MariaDB instance by an administrator; it is not needed for a normal upgrade.

## Recommendation for future releases

Keep live database data outside the directory replaced by an installer, for example under `%ProgramData%\TruckWeighingScaleSystem\mariadb-data`, and point MariaDB's `datadir` there. Then release installs can replace application code without ever touching customer transactions. That relocation should be implemented and tested as a dedicated migration/release, with backup and rollback, rather than mixed into this feature update.
