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