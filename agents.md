# AGENTS.md

## Project: Vita Health WordPress Theme

This repository contains the custom WordPress theme for the Vita Health Media website relaunch.

The design phase is already substantially complete.

Your job is not to invent a new visual direction.

Your job is to translate the approved design system, page layouts, and tested prototype behavior into a clean, maintainable WordPress theme.

---

## Primary Objective

Build a custom WordPress theme that:

- matches the approved Vita Health design
- remains easy to maintain
- uses reusable components and template parts
- keeps the WordPress editing experience simple
- avoids unnecessary dependencies
- stays performant and accessible
- can be deployed independently from WordPress core

The implementation should feel like a design system translated into WordPress, not a collection of unrelated page templates.

---

## Important Context

A React / Vercel prototype has already been used to test:

- buttons
- typography
- colors
- design tokens
- hero layout
- responsive behavior
- video treatment
- spacing
- component structure

Where useful, preserve the same component logic and naming philosophy.

Do not recreate React inside WordPress.

Use the prototype as a reference for behavior and structure.

---

## Development Philosophy

Prefer simple solutions.

Do not over-engineer.

Before introducing:

- a framework
- a dependency
- a JavaScript library
- a custom abstraction
- a plugin requirement

ask whether the same result can be achieved cleanly with:

- PHP
- semantic HTML
- CSS
- native WordPress functionality
- minimal vanilla JavaScript

If yes, prefer the simpler solution.


## CSS Formatting

Use expanded, readable CSS formatting.

Prefer:

```css
.component {
  display: flex;
  align-items: center;
  gap: var(--gap-md);
}

---

## Theme Scope

Only the custom theme should be considered part of this repository.

Expected location:

```text
wp-content/themes/vita-health/

## theme Figma Layout: https://www.figma.com/design/rcmTi7j9XxjcgfZCR3HCAN/Vita-Health-Wordpress?node-id=1384-6610&t=OdgpvvR0BBM3GR0B-1

## theme prototype: https://vita-health-prieview.vercel.app/
