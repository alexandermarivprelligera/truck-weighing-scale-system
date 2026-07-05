const { app, BrowserWindow } = require('electron');
const { spawn, execSync } = require('child_process');
const http = require('http');
const path = require('path');
const fs = require('fs');

let maria;
let laravel;
let scale;

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

    const scalePath = app.isPackaged
        ? path.join(root, 'scale-bridge.exe')
        : path.join(root, 'dist', 'scale-bridge.exe');

    console.log('Maria Path:', mariaPath);
    console.log('Maria Ini:', mariaIni);
    console.log('Maria CWD:', path.join(root, 'app', 'mariadb'));

    maria = spawn(
        mariaPath,
        [
            '--defaults-file=' + mariaIni
        ],
        {
            cwd: path.join(root, 'app', 'mariadb')
        }
    );

    maria.on('error', err => {
        console.error('Maria Spawn Error:', err);
    });

    maria.on('exit', (code, signal) => {
        console.log('Maria Exit:', code, signal);
    });

    laravel = spawn(
        phpPath,
        [
            'artisan',
            'serve',
            '--host=127.0.0.1',
            '--port=8000'
        ],
        {
            cwd: root
        }
    );

    scale = spawn(
        scalePath,
        [],
        {
            cwd: root
        }
    );

    maria.stdout?.on('data', d => console.log('MariaDB:', d.toString()));
    maria.stderr?.on('data', d => console.error('MariaDB:', d.toString()));

    laravel.stdout?.on('data', d => console.log('Laravel:', d.toString()));
    laravel.stderr?.on('data', d => console.error('Laravel:', d.toString()));

    scale.stdout?.on('data', d => console.log('Scale:', d.toString()));
    scale.stderr?.on('data', d => console.error('Scale:', d.toString()));

    createWindow();
});

app.on('before-quit', () => {
    try { execSync('taskkill /F /IM scale-bridge.exe'); } catch {}
    try { execSync('taskkill /F /IM php.exe'); } catch {}
    try { execSync('taskkill /F /IM mariadbd.exe'); } catch {}
});