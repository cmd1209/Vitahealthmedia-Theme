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
# Shared typography and editor content

New native headings, paragraphs, lists, and quotes automatically use the Vita
type system in `assets/css/typography.css`. Editors can duplicate an existing
page and add normal Gutenberg content without adding CSS classes. Heading
levels express document hierarchy; existing pattern classes can deliberately
give a heading a different visual role, such as the serif Hero Video H1 or
the lead-sized Leistung card H3.

For a standalone introduction or eyebrow, select a Paragraph and choose
**Styles → Vita Lead** or **Vita Eyebrow**. Choose the default style to return
to normal body text. The font-size picker offers the existing Vita scale;
custom numerical sizes are disabled for new edits. Each Vita size preset has
a matching token-based line-height. Choose Lead as a style, rather than just
an 18px size, to also get its intended weight and tracking.

Existing explicit inline formatting and older saved font-size presets are not
stripped. If copied content retains a previous manual size, reset that block's
Typography settings to let the native heading/body default apply again. Normal
content links use the Vita link color while retaining underlines; ordered and
unordered lists retain their markers and indentation.

`inc/typography.php` registers the paragraph styles and font-size choices and
loads shared typography into the editor independently of Hero Video. Buttons
keep their existing system in `buttons.css`, with an explicit sans-serif family.
Responsive typography variants are centralized in `typography.css`: Hero Video
remains 40/48 on mobile, Quote Section 32/32, and Leistungen retains its 40/40
heading, 16/24 introduction, and 12/16 card descriptions. Layout, gaps, alignment,
and section padding remain in component CSS. The existing H2 tracking token
is preserved; its previously identified difference from Figma has not been
silently redefined. The serif display-highlight style now uses the available
regular face instead of synthesizing semibold.

Verified native heading/body/list/quote/button computed styles at desktop and
420px, with WordPress's editor reset also applied. Existing section typography
retains its intended variants. Verified registration of both paragraph styles
and all 11 existing token sizes, plus PHP lint and whitespace checks. No build
step or dependencies were added.

# Leistung patterns

Under **Patterns → Vita Health**, use **Headline + Lead**, **Leistung Card**,
**Leistung Grid**, or the assembled **Leistung Section**. These are native Group,
Heading, Paragraph, and Image blocks; no custom block or build step is involved.
The heading/lead remains left-aligned, as in Figma. Card content is centered
with the icon above on desktop and left-aligned with the icon alongside on mobile.

Use List View to select a **Leistung Card** inside **Leistung Grid**, then duplicate,
move, or delete it. Insert the single-card pattern inside that grid to add another.
Eight cards are starter content, not a limit: rows are automatic, with four columns
on desktop, two at 769–1024px, and one at 768px and below. Individual cards use
content-only editing to protect their internal layout; the grid is unlocked so
its card count and order remain editable. Click headings and descriptions to edit;
replace icons through the native Image controls. The bundled icons are decorative
and have empty alt text because the adjacent heading supplies their meaning.

`patterns/headline-lead.php`, `patterns/leistung-card.php`,
`patterns/leistung-grid.php`, and `patterns/leistung-section.php` are registered by
the existing `inc/patterns.php` setup. `parts/leistung-card.php` shares starter
markup between patterns, but inserted content remains independent and editable.
`assets/css/components/leistung.css` supplies frontend and editor styling using
the existing typography, spacing, and wrapper tokens. Exact Figma SVG exports
live in `assets/images/leistung/`. The section shares the existing full-width
breakout rule. Mobile uses 24px gutters, 40/40 heading type, and 12/16 card text;
desktop uses 56/64 heading type and 14/20 card text. Icons remain 52px at both sizes.
Content determines row height; manual line breaks and fixed row heights from the
mockup are not imposed on editable text.

Verified with WordPress registration/rendering, Gutenberg parsing and serialization
for all four patterns, and Chrome desktop/420px mobile renders. Three-, five-, and
nine-card grids save correctly, form the expected rows, and do not overflow.
A standalone native Gutenberg editor render shows eight cards in four centered
columns with all 18 heading/paragraph fields editable. Authenticated admin
inserter, media replacement, and save/reopen interactions still need a manual check.

# Quote Section pattern

Insert **Patterns → Vita Health → Quote Section** on any page. Click the quote,
author, or role to edit. To change the coral words, select text and choose the
native toolbar's **Highlight → Text → Coral highlight**; clear the text color
to remove highlighting. Each inserted pattern is independent.

`inc/patterns.php` registers the category and ensures `vita-health/quote-section`
is registered even when WordPress has cached the theme's pattern file list.
`patterns/quote-section.php` contains only native Group, Quote, and Paragraph
blocks. The content-only Group lock keeps presentation controls out of normal
editing. The quotation is a blockquote inside a figure with a figcaption for
the two editable attribution paragraphs.

`assets/css/components/quote-section.css` shares the theme's wrapper, typography,
and spacing tokens. The full-width rule in `base.css` is shared with Hero Video.
Desktop uses an 1180px wrapper, 960px quote measure, 64/76 type, and 12px signature
gap. At 768px and below it uses 24px side gutters, 32/32 type, and a 4px signature
gap. Both retain the 64px quote-to-signature gap. Figma's 847px/477px heights are
minimums, allowing longer editorial content to grow without clipping text.
Text-edge trimming follows Figma where supported; older browsers retain normal
line boxes. The requested spelling “vertrauenswürdig” is preserved without the
desktop mockup's manual hyphen, so line wrapping can differ slightly.

The exact exported desktop V lives in `assets/images/quote-v.svg`. The mobile
export was checked and uses the same shape. A noninteractive CSS pseudo-element
resizes and repositions it, keeps it out of the accessibility tree, and clips
it within the section. No asset URL, custom block, JavaScript, or dependency is
needed for this pattern.

Validation: PHP lint; live WordPress category/pattern registration and repeated
rendering; Gutenberg parsing, serialization, and all three paragraph content
edits; headless Chrome measurements at 1493px and 420px confirmed the widths,
type, gaps, colors, and absence of horizontal overflow. The authenticated
inserter UI and toolbar interaction still need a manual editor check: insert
twice, edit each field, apply/remove inline coral, save, and reopen the page.

Accessibility limitation inherited from the approved palette: coral #ff8c73
on lavender #f4f1ff has approximately 2.04:1 contrast, below WCAG AA's 3:1
requirement for large text. Exact color matching is preserved; meeting that
requirement needs approval of a darker coral. Color does not convey exclusive
meaning in the quote.
