const { app, BrowserWindow } = require('electron');
const { spawn, execSync } = require('child_process');
const http = require('http');
const path = require('path');
const fs = require('fs');

let maria;
let laravel;
let scale;

/** Start a local dependency and make its output visible in the Electron log. */
function startService(name, command, args, cwd) {
    if (path.isAbsolute(command) && !fs.existsSync(command)) {
        console.error(`${name} was not started because its executable is missing: ${command}`);
        return null;
    }

    const child = spawn(command, args, {
        cwd,
        windowsHide: true
    });

    child.on('error', error => console.error(`${name} spawn error:`, error));
    child.on('exit', (code, signal) => {
        console.log(`${name} exited (code: ${code}, signal: ${signal})`);
    });
    child.stdout?.on('data', data => console.log(`${name}: ${data.toString()}`));
    child.stderr?.on('data', data => console.error(`${name}: ${data.toString()}`));

    return child;
}

/** Stop only child processes started by this Electron instance. */
function stopService(child, name) {
    if (child && !child.killed) {
        console.log(`Stopping ${name}...`);
        child.kill();
    }
}

function createWindow() {
    const win = new BrowserWindow({
        width: 1400,
        height: 900,
        autoHideMenuBar: true,
        show: false,
        icon: path.join(__dirname, 'build', 'icon.ico')
    });

    const checkLaravel = setInterval(() => {
        http.get('http://127.0.0.1:8000', () => {
            clearInterval(checkLaravel);
            win.loadURL('http://127.0.0.1:8000');
            win.show();
        }).on('error', () => {});
    }, 1000);
}

app.whenReady().then(() => {

    try { execSync('taskkill /F /IM scale-bridge.exe'); } catch {}
    try { execSync('taskkill /F /IM php.exe'); } catch {}
    try { execSync('taskkill /F /IM mariadbd.exe'); } catch {}

    const root = app.isPackaged
        ? process.resourcesPath
        : path.join(__dirname, '..');

    console.log('Root:', root);

    [
    path.join(root, 'storage'),
    path.join(root, 'storage', 'logs'),
    path.join(root, 'storage', 'framework'),
    path.join(root, 'storage', 'framework', 'cache'),
    path.join(root, 'storage', 'framework', 'sessions'),
    path.join(root, 'storage', 'framework', 'views')
    ].forEach(dir => {
        if (!fs.existsSync(dir)) {
            fs.mkdirSync(dir, { recursive: true });
        }
    });

    const bootstrapCache = path.join(root, "bootstrap", "cache");
        if (!fs.existsSync(bootstrapCache)) {
            fs.mkdirSync(bootstrapCache, { recursive: true });
        }

    const sessionPath = path.join(
        root,
        'storage',
        'framework',
        'sessions'
    );

    try {
        fs.readdirSync(sessionPath).forEach(file => {
            if (file !== '.gitignore') {
                fs.unlinkSync(path.join(sessionPath, file));
            }
        });
    } catch (e) {
        console.log(e);
    }

    const mariaPath = path.join(
        root,
        'app',
        'mariadb',
        'bin',
        'mariadbd.exe'
    );

    const mariaIni = path.join(
        root,
        'app',
        'mariadb',
        'my.ini'
    );

    const phpPath = path.join(
        root,
        'php',
        'php.exe'
    );

    // Development uses the current Python source; packaged builds use the
    // executable included through electron/package.json extraResources.
    const scaleCommand = app.isPackaged
        ? path.join(root, 'scale-bridge.exe')
        : (process.env.PYTHON_EXECUTABLE || 'python');
    const scaleArguments = app.isPackaged
        ? []
        : [path.join(root, 'scale-bridge.py')];

    console.log('Maria Path:', mariaPath);
    console.log('Maria Ini:', mariaIni);
    console.log('Maria CWD:', path.join(root, 'app', 'mariadb'));

    maria = startService(
        'MariaDB',
        mariaPath,
        [
            '--defaults-file=' + mariaIni
        ],
        path.join(root, 'app', 'mariadb')
    );

    laravel = startService(
        'Laravel',
        phpPath,
        [
            'artisan',
            'serve',
            '--host=127.0.0.1',
            '--port=8000'
        ],
        root
    );

    scale = startService(
        'Scale bridge',
        scaleCommand,
        scaleArguments,
        root
    );

    createWindow();
});

app.on('before-quit', () => {
    stopService(scale, 'scale bridge');
    stopService(laravel, 'Laravel');
    stopService(maria, 'MariaDB');

    try { execSync('taskkill /F /IM scale-bridge.exe'); } catch {}
    try { execSync('taskkill /F /IM php.exe'); } catch {}
    try { execSync('taskkill /F /IM mariadbd.exe'); } catch {}
});
