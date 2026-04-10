# Project Task Instructions for Copilot

You are working on a PHP-based e-commerce web application. Follow the tasks below **strictly and sequentially**. Each task has clear boundaries — do not deviate from them.

---

## GLOBAL RULES (Apply to ALL tasks)
- **Do NOT change any existing UI layout, design, or structure unless explicitly stated.**
- **Do NOT modify backend logic or database queries unless the task is specifically about adding backend.**
- **Preserve all existing class names, IDs, and HTML hierarchy.**
- Work only within the scope of each task. Do not refactor unrelated code.
- **Never crash or throw unhandled errors** when a database query returns zero results.
- For every database fetch, always check if the result is empty before rendering.
- If there are **no records to display**, show a **friendly, appropriate empty state message** in place of the data — using the existing UI's style and layout context. Examples:
  - Orders (Processing/Completed/etc.): *"No orders here yet."*
  - Wishlist: *"Your wishlist is empty."*
  - History: *"No history found."*
  - Testimonials: *"No reviews yet."*
  - Search: *"No results found for your search."*
- The empty state message must be placed **where the list/cards would normally appear** — do not add new sections or alter the layout.
- Do **not** display raw PHP errors, SQL errors, or `null`/`undefined` values to the user under any circumstance.
- Wrap all database calls in proper error handling (`try/catch` for PDO, or check `mysqli` errors) — fail silently and gracefully on the UI side.
- If a query fails due to a connection or runtime error, show a generic fallback message like *"Something went wrong. Please try again later."* — never expose error details to the frontend.

---

## TASK 1 — Fix `orderConfirmation` UI (Design/Layout Only)

**File:** `orderConfirmation.php` (or relevant file)

**What to do:**
- Fix the visual presentation of the order confirmation page so it looks polished and appropriate.
- Do **not** change any PHP logic, variables, conditionals, or data bindings.
- Do **not** restructure the HTML hierarchy.
- After the confirmation message/text, add **two buttons**:
  1. **"Shop Again"** → redirects to `index.php`
  2. **"View Order"** → redirects to the transaction details page of the current order (use the existing order ID variable already in scope)
- Style the buttons consistently with the existing design system (colors, fonts, spacing).

---

## TASK 2 — Live Search on All Pages with a Search Bar

**Files:** Any PHP page that contains a search bar input element.

**What to do:**
- Implement **live/real-time search** using JavaScript (vanilla JS or jQuery — match what's already used in the project).
- As the user types in the search bar, filter and display matching results **dynamically** without a full page reload.
- Search should be based on the **content already rendered on the page** (client-side filtering) OR make an AJAX call to the existing search/filter endpoint if one already exists.
- Do **not** change the search bar's HTML structure or styling.
- Do **not** alter any existing search form submission logic.

---

## TASK 3 — User Profile: Orders Page — Connect Tabs to Database

**File:** Profile → Orders page (e.g., `profile_orders.php` or similar)

**Tabs to connect:**
- Processing
- To Review
- Completed
- Cancelled

**What to do:**
- Each tab must **fetch and display orders from the database** filtered by their respective status.
- Display **only** the data fields currently visible in the UI — do not add new fields or remove existing ones.
- Do **not** change the tab UI, card layout, or any styling.
- Also connect the **action buttons** within each tab (e.g., Cancel, Review, Reorder) to their appropriate backend logic.

---

## TASK 4 — History Page — Add Backend

**File:** `history.php` (or equivalent)

**What to do:**
- Connect the history page to the database.
- Display **only** the data that is currently shown in the static/placeholder UI.
- Do **not** change the layout, card design, or any visual elements.
- Pull real data from the correct database table (order history or equivalent).

---

## TASK 5 — Wishlist Page — Add Backend

**File:** `wishlist.php` (or equivalent)

**What to do:**
- Connect the wishlist page to the database.
- Display the **logged-in user's** wishlist items fetched from the database.
- Show only what the current UI already displays (product name, image, price, etc.) — no new fields.
- Do **not** change the wishlist item card layout or any CSS.
- Connect the existing buttons (e.g., Remove, Add to Cart) to the correct backend actions.

---

## TASK 6 — Add Back Button to Every Page

**Files:** All PHP pages in the project.

**What to do:**
- Add a **back button** to each page that takes the user back to the page they navigated from.
- Use `history.back()` in JavaScript OR a contextually appropriate hardcoded link (e.g., from product detail → back to product list).
- Place the back button in a **logical, consistent position** (e.g., top-left of the content area).
- Style it **minimally** to match existing UI — do not introduce new design elements.
- Do **not** disrupt the existing page header or navigation structure.

---

## TASK 7 — Index Page: Testimonials Section — Connect to Reviews Database

**File:** `index.php`

**Section:** Testimonials

**What to do:**
- The testimonials section displays **customer reviews**. Connect it to the reviews table in the database.
- Fetch and display real review data: **reviewer name, review text, rating** (and any other fields already shown in the static UI).
- Do **not** change the testimonial card layout, carousel behavior, or any CSS/JS animations.
- Display only the fields currently visible in the static version — no new UI elements.

---

## Summary Checklist for Copilot

| Task | Scope |
|------|-------|
| 1. orderConfirmation UI | Layout/design fix + 2 buttons |
| 2. Live search | JS only, all pages with search bar |
| 3. Orders tabs | PHP + SQL backend per tab + buttons |
| 4. History page | PHP + SQL backend |
| 5. Wishlist page | PHP + SQL backend + buttons |
| 6. Back buttons | All pages, JS or contextual links |
| 7. Testimonials | PHP + SQL from reviews table |