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

## Partner Logos

Also available under **Patterns → Vita Health → Partner Logos** on any page.
Each insertion creates an independent, editable block with the starter logos.

Insert **Partner Logos** from the block inserter wherever the customer strip belongs.
It breaks out of the centered content wrapper to fill the viewport. The heading
(default: “Kunden”) is editable directly in the block.

The block starts with the seven approved prototype logos, bundled in the theme.
In its sidebar, choose **Select / edit logos** to replace the starter set with
images from the WordPress Media Library. Select/upload the desired logos and
confirm the gallery selection. Use the arrows below each logo to reorder it,
remove individual logos with ×, or reopen the media picker to edit the selection.
**Restore starter logos** resets the set. An explicitly empty selection renders
no section on the frontend.

Use transparent PNG/WebP images with light artwork and meaningful alternative
text (the customer name). Media Library selections are stored as attachment IDs;
PHP resolves their current image URLs and alt text on each render and skips
missing attachments. The bundled starter images do not create Media Library
records automatically.

The frontend preserves the prototype's CSS marquee, 42 seconds per loop on desktop
and 32 seconds on mobile. Small vanilla JavaScript duplicates enough sets to fill
the viewport and supplies a pause/play button; no carousel library is required.
Hover and keyboard focus pause the strip. Reduced-motion users get a manually
scrollable row without clones; without JavaScript the original row remains
scrollable. The editor shows a static, wrapping preview for easy selection.

## Contact Section (WPForms)

Insert **Patterns → Vita Health → Contact Section** on any page. Edit the eyebrow,
headline and introduction directly, then select the WPForms block in the right
column and choose an existing form. WPForms must be installed and active; the
pattern does not create a form or hard-code an ID. No form appears on the frontend
until one is selected. The native form picker follows WPForms’ normal permissions.

To match the prototype, use a Simple Name field (required), Email (required),
Single Line Text labelled “Unternehmen”, and Paragraph Text labelled “Nachricht”
(required), with submit text “Senden”. Keep labels visible and use a single-column
form. Configure notifications, confirmation, consent and spam protection normally
in WPForms. The section CSS is scoped to this pattern; WPForms elsewhere is unchanged.

The full-width `contact-section` contains a separate `contact__container` with
`--padding-xl` on every side. Its lavender `.contact` surface stays inset, including
on mobile. A decorative pseudo-element reproduces the dark lower strip. The row
stacks below 700px. All padding, margins and gaps use the theme tokens; prototype
field spacing is mapped to the existing XS/SM tokens. Contact-specific width tokens
preserve the prototype's surface and text limits.

Each insertion has independent text and form selection. Selecting the same WPForms
form on multiple pages shares that form's fields and settings, as usual. If you
need an anchor for a navigation link, set a unique HTML anchor on the outer group.

Verify with WPForms active: insert the pattern, choose a form, save/reopen,
check desktop/mobile gutters, keyboard focus, required/email validation, consent,
AJAX confirmation and delivery to the configured notification recipient. WPForms
is installed outside this theme repository, so live form rendering and submission
must be checked in the site's WPForms environment.

## Projects Slider

Prefer **Patterns → Vita Health → Projects Slider** for visual editing and query controls.
Alternatively, insert a native **Shortcode** block containing `[vita_projects]` on the desired page,
inside the normal content flow (not inside a narrow column). No page content is
changed automatically. The shortcode delegates to the reusable template part:

```php
get_template_part('parts/project-slider');
```

The template queries `project` with `WP_Query`: published posts only, newest date
first, three posts, no pagination count. For a different count or oldest-first order:

```text
[vita_projects count="6" order="ASC"]
```

PHP callers can pass `count` and `order` as template arguments:

```php
get_template_part('parts/project-slider', null, ['count' => 6, 'order' => 'DESC']);
```

The defaults and `orderby` are together at the top of `parts/project-slider.php`.
The template resets post data and outputs nothing for an empty query. It uses saved
excerpts only, without generating excerpts from long project content. A category
label appears only when a public taxonomy is already registered for `project` and
has an assigned term. No taxonomy is created.

Featured Images are rendered with `get_the_post_thumbnail()` at `large` size, with
WordPress `srcset`, responsive `sizes`, lazy loading, and `object-fit: cover`. The
Media Library alt text is retained; an empty alt falls back to the project title.
For more descriptive alternatives, edit the attachment's alt text in Media Library.
A project without an image keeps its colored card and working link.

The prototype's three-column, edge-to-edge desktop composition becomes two columns
on tablet and one on mobile. CSS scroll-snap provides touch/trackpad/scrollbar
navigation without JavaScript. The small local script adds previous/next buttons
and Arrow/Home/End keys when the scroller is focused. Controls hide when all cards
fit and expose disabled states at each end. There is no autoplay or loop. Reduced
motion disables smooth programmatic scrolling. Standard card links stay tabbable;
CTA and control icons use the existing Lucide initializer and button/icon classes.

Assets are enqueued by `inc/project-slider.php`; CSS uses the shared tokens and
breakout utility. No build tools or dependencies were added. The existing `project` registration now shares WordPress Categories with Posts;
archive and single-project templates are unchanged. Native Shortcode
blocks remain shortcode editing controls; use page Preview for the rendered slider.

### Projects Slider pattern controls

Each pattern insertion creates an independent dynamic **Projects Slider** block.
Select it and open **Projects query** in the sidebar:

- **Number of projects:** 1–24; choose 6 for the latest six projects.
- **Order:** newest or oldest first.
- **All projects:** queries published projects without a taxonomy filter.
- **Selected categories / terms:** choose an existing public project taxonomy and
  one or more terms. Projects matching any selected term are included (exact terms,
  without automatically including child categories).
- **Related to this project / article:** choose Categories (or another shared
  taxonomy); this queries projects sharing any of the current project or article’s
  terms and excludes the source project.
  On ordinary pages, use selected terms instead.

The slider has no section heading or introduction. The PHP-rendered
editor preview uses the same cards and query as the frontend; it remains a static,
scrollable preview. Queries rerun on page load, so new published projects appear
without editing the pattern again. Six requested items currently shows the three
available projects, without duplicates.

The built-in `category` taxonomy is shared by `project` and standard Posts. No
new taxonomy or category terms are created. Other public project taxonomies also
appear in the selector after reloading the editor.
Filtered/related queries with no taxonomy, no selected/shared terms or no matches
render nothing on the frontend and show an editor placeholder. They never silently
fall back to unrelated projects. The existing shortcode remains supported for count
and order; the block sidebar exposes the full query controls.

### Related projects on projects and articles

Assign shared categories using the normal **Categories** panel on both Projects
and Posts. Insert **Patterns → Vita Health → Related Projects** into the project or
article content. It defaults to four newest matching published projects, sharing
at least one exact category, with the current project excluded. Count remains
editable. Unmatched or uncategorized sources produce no section.

Articles here are WordPress Posts (`post`). Ordinary pages use explicit selected
terms instead. Shared categories, including any default category assigned by
WordPress, count as matches; assign meaningful topic categories for useful results.
The pattern is ready for both content types; automatic placement in future single
project/article templates is separate from this change. No content is automatically
recategorized and no single template is created here.

## Single project hero

WordPress automatically uses `single-project.php` for individual projects. Its
`parts/project-hero.php` header reuses the full-width breakout and `content-wrapper`.
The hero follows the Figma desktop reference: 650px minimum height, bottom-aligned
text, the project's selected gradient overlay, and a narrow text column. It grows with long text;
mobile uses smaller typography, token-based gutters and a 500px minimum height.

Edit the project Title and Excerpt to change the heading and lead. An empty excerpt
omits the lead. The Featured Image is rendered as a responsive image with `srcset`,
`object-fit: cover`, eager loading and high fetch priority. The background image is
decorative; the title and excerpt provide the text content.

Without a valid Featured Image, the template resolves the Media Library attachment
slug `project-blanco_kv` (no hard-coded media ID or upload URL). Keep that attachment
slug when replacing the fallback. If it is absent too, the hero retains its selected gradient
background and text. Existing post content renders below the hero in the shared
wrapper; no additional project-detail layout or related section is added here.

## Project listing

`archive-project.php` renders the project archive at `/projekte/`. It shows six
published projects per page, with a next or previous page link when needed. The
project slider now links to this archive below its controls. Both views render
project cards through `parts/project-card.php`.

The archive always shows **Alle Projekte**. Category filter links appear as soon
as published projects have WordPress Categories assigned; only categories used by
projects appear. Filtering and pagination update the grid in place using native
JavaScript and browser history. Their ordinary URLs remain bookmarkable and work
with a full page load if JavaScript or the request fails. Assign categories in a
project's editor to activate those filters. The archive grid uses each project's
Featured Image, Title, and optional Excerpt.

In a Project editor, use the **Project card CTA** box to choose Primary,
Secondary, or Highlight. The choice follows that project in both the slider and
archive. Projects without a saved choice use Primary. The button colors come
from the shared action tokens in `assets/css/tokens.css`.

To add editable content below the grid, publish a WordPress Page with the slug
`projekt-archiv-inhalt`. Insert any Vita Health patterns there. For the Contact
Section, select the WPForms block inside the inserted pattern and choose an
existing form. The archive renders this Page after the grid; category changes
leave its content and form in place. The Page needs to remain published.

## Named featured-image gradients

`assets/css/color.css` defines six named gradients from Figma: Lavender, Coral,
Terracotta, Evergreen, Sage, and Mint. Each has an opaque background and a
transparent image overlay. Edit a Project or Post and choose a swatch in the
**Featured image gradient** meta box. The setting is saved with that item and
applies to project cards in the slider and archive, the project detail hero, and
WordPress Query Loop featured-image blocks for Projects and Posts. A single Post
also uses its choice when its content includes a Post Featured Image block.

**Automatic** assigns an unset item a stable gradient from its post ID. Reordering
projects or adding new ones does not change an existing item's color. All six
choices currently use white text on project cards and heroes. This
feature does not add a new single-article layout or a Featured Image block to
existing article content.
