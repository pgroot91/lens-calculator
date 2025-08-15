const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const pkg = require('../package.json');

const isWindows = process.platform === 'win32';
const wpCliPath = isWindows
  ? 'vendor\\wp-cli\\wp-cli\\bin\\wp.bat'
  : 'vendor/wp-cli/wp-cli/bin/wp';

const potFile = path.join(__dirname, '..', 'languages', `${pkg.name}.pot`);

try {
  console.log('Generating POT file...');

  // Read old contents first (if exists)
  let oldContents = '';
  if (fs.existsSync(potFile)) {
    oldContents = fs.readFileSync(potFile, 'utf8')
      .split('\n')
      .filter(line => !line.startsWith('POT-Creation-Date:'))
      .join('\n');
  }

  // Run wp-cli make-pot with proper quotes for Windows
  const command = `"${wpCliPath}" i18n make-pot . "${potFile}" --slug=${pkg.name} --exclude=node_modules,tests,docs,assets/js/lib`;
  execSync(command, { stdio: 'inherit' });

  if (!fs.existsSync(potFile)) {
    console.error('POT file was not created!');
    process.exit(1);
  }

  // Read new contents ignoring POT-Creation-Date
  const newContents = fs.readFileSync(potFile, 'utf8')
    .split('\n')
    .filter(line => !line.startsWith('POT-Creation-Date:'))
    .join('\n');

  if (newContents === oldContents) {
    console.log('Only POT-Creation-Date changed. Reverting file to previous state.');
    fs.writeFileSync(potFile, oldContents, 'utf8');
    console.log('No meaningful changes in POT file.');
    process.exit(0);
  }

  console.log('Meaningful changes detected. Staging POT file...');
  execSync(`git add "${potFile}"`);

  // Check if only POT file changed
  const staged = execSync('git diff --cached --name-only').toString().trim().split('\n');
  if (staged.length === 1 && staged[0] === potFile) {
    console.log('Only POT file changed. Commit aborted. Please include other changes.');
    process.exit(1);
  }

} catch (err) {
  console.error('Error generating POT:', err.message);
  process.exit(1);
}
