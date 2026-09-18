# Vita Health WordPress Theme

Custom WordPress theme for the Vita Health Media website relaunch.

The goal of this project is to translate the approved Figma design system and page concepts into a clean, maintainable, responsive WordPress theme that is easy for the Vita Health team to manage.

## Project Goals

- Build a custom WordPress theme based on the approved Vita Health design
- Reuse the visual system already defined in Figma
- Keep the codebase lightweight and maintainable
- Create reusable components and layout patterns
- Support responsive behavior across desktop, tablet, and mobile
- Make content editing straightforward for the Vita Health team
- Avoid unnecessary plugin or page-builder dependency
- Keep design and implementation closely aligned
- Use Git for theme development and version control
- Develop and test locally before deploying to staging / production

## Design System

The theme should reflect the existing design system, including:

- Brand colors
- Semantic color tokens
- Typography scale
- Spacing tokens
- Border radii
- Buttons
- Focus states
- Layout widths
- Responsive behavior
- Reusable section patterns

Where possible, CSS custom properties should mirror the token structure defined in Figma.

Example:

```css
:root {
  --color-brand-primary-500: #000000;
  --space-md: 1.5rem;
  --radius-sm: 0.5rem;
}
```

## Gutenberg buttons

Insert a normal Button block inside Buttons, select the individual button, and open
**Vita Button** in its block sidebar. Choose Primary, Secondary or Highlight;
Default or Small; and optionally enable the chevron on the left or right.

The attributes are registered in `inc/button-block.php` and passed to the editor
by WordPress. `assets/js/editor/button-controls.js` uses WordPress browser globals
without JSX or a build step. The native Button save function is unchanged: Vita
classes and decorative icon markup are added by `render_block_core/button` only
when rendering. Existing buttons default to Primary / Default / No icon.

`assets/css/buttons.css` is shared by template buttons and Gutenberg. Existing
explicit WordPress color, gradient and typography settings can override the Vita
defaults. The editor icon preview uses SVG data from the same local Lucide build;
the frontend uses the existing `icons.js` initializer. No extra initialization or
icon files are needed.

### Verification

On a draft page, add five normal Button blocks with descriptive text:

| Variant | Size | Icon |
| --- | --- | --- |
| Primary | Default | None |
| Primary | Default | Right |
| Secondary | Small | None |
| Secondary | Small | Right |
| Highlight | Default | Left |

Save, reload the editor, and preview the page. Check that the controls retain their
values, there are no invalid-block warnings, and the styles and icon positions
match. Test bold text, a link with a new-tab target, and an existing Button block.
Disabling Show icon should remove the icon, icon selector and position control. Native
button text remains the accessible label; frontend icon wrappers are aria-hidden.

When Show icon is enabled, the Icon dropdown offers Chevron right (the default),
Arrow right, Arrow up right, External link, Download, Mail, Send, Play, Plus and
Check. The choice is saved in `vitaIconName` and used by both the editor preview
and frontend. To extend the list, add a valid Lucide name to the dropdown options
in `assets/js/editor/button-controls.js`.
