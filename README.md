# EneoLink — Property Discovery & Business Opportunity Platform

EneoLink ni jukwaa la Yii2 linalowezesha watu kugundua, kuchunguza, kulinganisha na kuchagua
mali zinazofaa (vyumba, vyumba vya kujitegemea, apartments, nyumba, maduka, ofisi) na kutoa
mwanga wa fursa za biashara kulingana na eneo na bajeti.

Lengo la awali: **Kinondoni Municipality, Dar es Salaam**. Inaweza kutanuka kwa maeneo
mengine ya Tanzania.

---

## Features zilizo tayari (MVP)

- Homepage with hero search + featured properties
- User signup with role selection (Seeker / Owner / Agent) + login
- Property listing page with search + filters (keyword, price, listing_type, bedrooms, furnished, parking, internet)
- Property detail page with gallery, features, contact owner, save to favorites, report listing
- Property comparison (multi-select via `?ids[]=`)
- Interactive Leaflet map of all properties
- Business Opportunity Advisor (simple heuristic) — pick ward + budget, get suggestions
- Favorites, Inquiries, My Listings for the logged-in user
- Admin dashboard with verification queue (Approve / Reject), reports, users

---

## Mahitaji

- PHP 7.4+
- MySQL / MariaDB
- Composer
- (Inapakana kwa XAMPP kwa urahisi)

---

## Hatua za kuisakinisha

1. **Database:** Tengeneza database mpya kwenye MySQL (mfano `eneolink_db`).

2. **Configure DB:** Hariri `config/db.php`:
   ```php
   'dsn' => 'mysql:host=localhost;dbname=eneolink_db',
   'username' => 'root',
   'password' => '',
   ```

3. **Enable URL manager (Apache/XAMPP):** Hakikisha `web/.htaccess` ipo na inawezesha URL manager.
   (Yii2 basic default tayari ina `.htaccess`).

4. **Run migrations:**
   ```
   php yii migrate
   ```

5. **Seed data (wards, categories, admin user):**
   ```
   php yii seed/run
   ```
   Hii itaweka:
   - Admin login: `admin` / `admin123`
   - Wards 15 za Kinondoni
   - Categories 15 (rooms, apartments, houses, business spaces)

6. **Run web server:**
   - XAMPP: weka mradi chini ya `htdocs` na tembelea `http://localhost/eneolink/web/`
   - Au: `php -S localhost:8000 -t web`

---

## URLs muhimu

| URL | Purpose |
|---|---|
| `/` | Homepage |
| `/search` | Property search & filter |
| `/map` | Interactive Leaflet map |
| `/property/<id>` | Property detail |
| `/compare?ids[]=1&ids[]=2` | Side-by-side comparison |
| `/business-advice` | Business opportunity advisor |
| `/signup` | Create account |
| `/my/favorites` | Saved properties |
| `/my/listings` | Owner/agent listings |
| `/admin` | Admin dashboard (admin only) |

---

## Mtiririko wa project

- **Models** (`models/`): User, Property, PropertyCategory, PropertyImage, Location, Favorite, Inquiry, Report, SignupForm, LoginForm, PropertySearch.
- **Controllers** (`controllers/`): Site, Account, Property, PropertySubmission, Business, Admin.
- **Services** (`services/`): BusinessOpportunityService — heuristics za MVP.
- **Migrations** (`migrations/`): schema kamili ya database.
- **Views** (`views/`): Bootstrap 5 UI.

---

## Kinachofuata (Future)

- AI-powered search (NLP queries)
- Image classification (kuthibitisha picha za property)
- Premium business insights
- Mobile app
- Expansion kwa mikoa mingine Tanzania