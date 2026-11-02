import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig, lazyPlugins } from 'vite-plus';

export default defineConfig({
  plugins: lazyPlugins(() => [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.ts'],
      refresh: true,
      fonts: [
        bunny('Instrument Sans', {
          weights: [400, 500, 600],
        }),
      ],
    }),
    inertia(),
    tailwindcss(),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    wayfinder({
      formVariants: true,
    }),
  ]),
  server: {
    watch: {
      ignored: [
        '**/.agents/**',
        '**/.claude/**',
        '**/.cursor/**',
        '**/.junie/**',
        '**/vendor/**',
      ],
    },
  },
  lint: {
    ignorePatterns: [
      'vendor/**',
      'node_modules/**',
      'public/**',
      'bootstrap/ssr/**',
      'tailwind.config.js',
      'resources/js/actions/**',
      'resources/js/components/ui/*',
      'resources/js/routes/**',
      'resources/js/wayfinder/**',
    ],
    options: {
      denyWarnings: true,
      typeAware: true,
    },
    overrides: [
      {
        files: ['**/*.ts'],
        plugins: ['typescript'],
        rules: {
          // https://oxc.rs/docs/guide/usage/linter/rules/typescript/array-type.html
          'typescript/array-type': ['warn', { default: 'array' }],

          // https://oxc.rs/docs/guide/usage/linter/rules/typescript/consistent-indexed-object-style.html
          'typescript/consistent-indexed-object-style': [
            'warn',
            'index-signature',
          ],

          // https://oxc.rs/docs/guide/usage/linter/rules/typescript/consistent-type-exports.html
          'typescript/consistent-type-exports': [
            'warn',
            { fixMixedExportsWithInlineTypeSpecifier: true },
          ],

          // https://oxc.rs/docs/guide/usage/linter/rules/typescript/explicit-function-return-type.html
          'typescript/explicit-function-return-type': 'error',

          // https://oxc.rs/docs/guide/usage/linter/rules/typescript/no-explicit-any.html
          'typescript/no-explicit-any': 'error',
        },
      },
      {
        files: ['**/*.ts', '**/*.vue'],
        plugins: ['eslint'],
        rules: {
          // https://oxc.rs/docs/guide/usage/linter/rules/eslint/capitalized-comments.html
          'capitalized-comments': [
            'error',
            'always',
            {
              ignoreInlineComments: true,
            },
          ],

          // https://oxc.rs/docs/guide/usage/linter/rules/eslint/curly.html
          curly: ['error', 'multi-line', 'consistent'],

          // https://oxc.rs/docs/guide/usage/linter/rules/eslint/max-lines.html
          'max-lines': ['error', { max: 200 }],

          // https://oxc.rs/docs/guide/usage/linter/rules/eslint/no-empty-function.html
          'no-empty-function': 'error',

          // https://oxc.rs/docs/guide/usage/linter/rules/eslint/no-unused-expressions.html
          'no-unused-expressions': 'error',

          // https://oxc.rs/docs/guide/usage/linter/rules/eslint/no-unused-vars.html
          'no-unused-vars': 'error',

          // https://oxc.rs/docs/guide/usage/linter/rules/eslint/prefer-destructuring.html
          'prefer-destructuring': ['warn', { array: true, object: true }],
        },
      },
      {
        files: ['**/*.vue'],
        plugins: ['vue'],
        rules: {
          // https://oxc.rs/docs/guide/usage/linter/rules/vue/component-definition-name-casing.html
          'vue/component-definition-name-casing': ['error', 'kebab-case'],
        },
      },
      {
        files: ['**/*.ts', '**/*.vue'],
        plugins: ['import'],
        rules: {
          // https://oxc.rs/docs/guide/usage/linter/rules/import/consistent-type-specifier-style.html
          'import/consistent-type-specifier-style': [
            'error',
            'prefer-top-level-if-only-type-imports',
          ],

          // https://oxc.rs/docs/guide/usage/linter/rules/import/newline-after-import.html
          'import/newline-after-import': 'warn',
        },
      },
      {
        files: ['**/*.ts', '**/*.vue'],
        plugins: ['unicorn'],
        rules: {
          'unicorn/filename-case': ['error', { case: 'kebabCase' }],
        },
      },
    ],
  },
  fmt: {
    printWidth: 80,
    tabWidth: 2,
    singleQuote: true,
    semi: true,
    singleAttributePerLine: false,
    htmlWhitespaceSensitivity: 'css',
    trailingComma: 'all',
    bracketSpacing: true,
    vueIndentScriptAndStyle: true,
    ignorePatterns: [
      '.github/**',
      'resources/js/components/ui/*',
      'resources/views/mail/*',
    ],
    sortTailwindcss: {
      functions: ['clsx', 'cn', 'cva'],
      entryPoint: 'resources/css/app.css',
    },
  },
});
