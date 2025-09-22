import { build } from 'esbuild';
import { resolve } from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = resolve(__filename, '..');

await build({
  entryPoints: [resolve(__dirname, 'resources/js/page-builder.tsx')],
  outfile: resolve(__dirname, 'public/assets/js/page-builder.js'),
  bundle: true,
  minify: false,
  sourcemap: true,
  loader: { '.ts': 'ts', '.tsx': 'tsx' },
  platform: 'browser',
  target: ['es2018'],
});

console.log('Built page-builder.js');
