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

## Hero Video

Insert **Hero Video** (`vita-health/hero-video`) in the page editor. Edit the
headline directly; bold is supported and emphasis uses Vita's coral highlight.
The sidebar provides video selection/upload, replacement/removal, and four
approved overlays: Purple → Green, Green → Purple, Dark Green and None.

The block stores a Media Library attachment ID, not a copied video URL. PHP
resolves the current URL on every render. A missing, deleted or non-video
attachment falls back to a usable static hero. The editor shows a non-autoplay
video preview; the frontend reuses the existing reduced-motion and pause/play
behavior in `assets/js/hero-video.js`.

The starter row contains two native Button blocks inside Buttons. Add/remove
buttons normally and use the existing **Vita Button** controls for variant, size,
icon and position. Set the actual link destinations with WordPress's link control.
Mobile hero buttons use the shared small-button treatment automatically.

`blocks/hero-video/block.json` is registered on `init`. Its editor script uses
WordPress globals directly, without JSX or a build step. Only InnerBlocks are
saved as HTML; `render.php` delegates the hero shell to `parts/hero-video.php`.
The four gradient values map to `hero-video--gradient-*` classes using existing
color tokens. Full viewport width and negative margins break out of the centered
page wrapper, while the inner wrapper stays constrained and the headline narrower.

For an editable homepage, create/select a page under **Settings → Reading → A
static page**, then insert Hero Video on that page. `front-page.php` renders its
content without adding a second hero. The previous template-only hero remains a
fallback when the homepage is configured to show latest posts; its Customizer
video setting does not override videos selected inside blocks.

### Hero verification

On a draft page:

1. Insert Hero Video without a video and confirm its headline/buttons remain visible.
2. Select/upload a video, replace it, then remove it; save and reopen after each step.
3. Edit the headline, including bold and emphasis, and confirm it survives reload.
4. Keep two buttons, test Vita variants/icons, then add/remove a button.
5. Switch through all four gradients and compare editor with frontend preview.
6. Check desktop breakout and a narrow/mobile viewport for horizontal overflow,
   24px inner spacing, 40/48 headline typography and small wrapping buttons.
7. Check the frontend pause/play control and reduced-motion preference.

The next natural component is the introductory Mission section targeted by the
hero's first CTA, using the same controlled editing approach.
