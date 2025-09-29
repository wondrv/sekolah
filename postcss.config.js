import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

// PostCSS plugin to:
// - remove deprecated/non-standard properties that trigger linters
//   (keep print-color-adjust variants, remove bare color-adjust)
// - remove unprefixed text-size-adjust (keep -webkit- for iOS)
// - add standard appearance and line-clamp alongside -webkit- versions
const compatCleanups = () => ({
    postcssPlugin: 'compat-cleanups',
    OnceExit(root) {
        // Remove deprecated/unwanted declarations
        root.walkDecls('color-adjust', (decl) => decl.remove());
        // Ensure unprefixed text-size-adjust accompanies -webkit- variant
        let hasUnprefixed = false;
        root.walkDecls('text-size-adjust', () => { hasUnprefixed = true; });
        if (!hasUnprefixed) {
            root.walkDecls('-webkit-text-size-adjust', (decl) => {
                // Add unprefixed version right after the -webkit- one
                decl.after({ prop: 'text-size-adjust', value: decl.value });
            });
        }

        // Ensure standard appearance is present alongside -webkit-appearance
        root.walkDecls('-webkit-appearance', (decl) => {
            const rule = decl.parent;
            const hasStandard = rule.some(d => d.type === 'decl' && d.prop === 'appearance');
            const hasMoz = rule.some(d => d.type === 'decl' && d.prop === '-moz-appearance');
            if (!hasStandard) {
                decl.after({ prop: 'appearance', value: decl.value });
            }
            if (!hasMoz) {
                decl.after({ prop: '-moz-appearance', value: decl.value });
            }
        });

        // Ensure standard line-clamp is present alongside -webkit-line-clamp
        root.walkDecls('-webkit-line-clamp', (decl) => {
            const rule = decl.parent;
            const hasStandard = rule.some(d => d.type === 'decl' && d.prop === 'line-clamp');
            if (!hasStandard) {
                decl.after({ prop: 'line-clamp', value: decl.value });
            }
        });
    }
});
compatCleanups.postcss = true;

export default {
    plugins: [
        tailwindcss(),
        autoprefixer({
            overrideBrowserslist: [
                'last 3 versions',
                'Safari >= 9',
                'iOS >= 9',
                'Firefox >= 52',
                'Chrome >= 60',
                'Edge >= 16'
            ]
        }),
        compatCleanups()
    ],
};
