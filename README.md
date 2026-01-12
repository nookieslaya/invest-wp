# Invest Theme

Custom WordPress theme built on Sage (Roots) with Blade, Vite, and Tailwind CSS.

Production demo: https://mediumorchid-fly-708696.hostingersite.com/

## Stack
- WordPress theme using Sage + Acorn (Laravel Blade templates).
- Frontend build with Vite + Tailwind CSS.
- ACF Pro with acf-builder for flexible fields and ACF blocks.
- Gutenberg blocks (ACF blocks and a sample native block in JS).

## Structure (high level)
- `app/` theme logic (setup, filters, ACF registration).
- `app/fields/` ACF field groups.
  - `app/fields/modules/` flexible content modules.
  - `app/fields/blocks/` ACF block field groups.
- `resources/views/` Blade templates.
  - `resources/views/modules/` module partials.
  - `resources/views/blocks/` block templates.
- `resources/js/` JS entrypoints and modules.
  - `resources/js/editor.js` registers native Gutenberg blocks.

## Build and dev
From the theme directory:
- `npm install`
- `npm run dev` (watch)
- `npm run build` (production)

## Blocks
- ACF blocks are registered in `app/setup.php` via `acf_register_block_type`.
- ACF block fields are listed in `app/fields/blocks.php`.
- Native block example is registered in `resources/js/editor.js`.

## Notes
- Editor styles/scripts are loaded in `app/setup.php`.
- The theme uses `resources/css/editor.css` for Gutenberg styling.
