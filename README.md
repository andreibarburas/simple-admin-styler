# Simple Admin Styler

A WordPress plugin to customize the login page and dashboard — background image, font size, admin bar visibility, and dashboard widget toggles.

## Features

- **Login background image** — set any image URL as the login page background
- **Login font size** — control the base font size on the login page
- **Admin bar toggle** — hide the admin bar on the front end
- **Dashboard widget toggles** — show or hide each built-in WordPress dashboard widget individually

## Installation

1. Download the latest `simple-admin-styler.zip` from the [Releases](https://github.com/andreibarburas/simple-admin-styler/releases) page.
2. In WordPress, go to **Plugins → Add New → Upload Plugin**.
3. Upload the zip and activate.
4. Go to **Settings → Admin Styler** to configure.

## Settings

All settings live under **Settings → Admin Styler**.

### Login Page
| Setting | Description |
|---|---|
| Background Image URL | Full URL to the image shown behind the login form |
| Font Size (px) | Base font size for the login page |

### Admin Bar
| Setting | Description |
|---|---|
| Hide Admin Bar | Hides the WordPress toolbar on the front end for all users |

### Dashboard Widgets
Toggle visibility for each dashboard widget:
- At a Glance
- Activity
- Quick Draft
- WordPress News
- Recent Comments
- Recent Drafts
- Incoming Links
- Plugins
- Site Health Status
- WP Download Manager
- Simple History
- Welcome Panel

## Version History

### Updated to v1.1.0. Here's what changed:

- New "Logo Image URL" field in the Login Page section — paste any image URL and it replaces the WordPress logo above the login form
- CSS targets #login h1 a (the standard WP logo element) and swaps the background image, with contain sizing so any aspect ratio works cleanly
- login_headerurl filter — when a custom logo is set, clicking it goes to your site's homepage instead of wordpress.org
- If the field is left blank, everything falls back to WordPress defaults — no change in behaviour

### 1.0.0
- Initial release
- Login background URL and font size settings
- Admin bar visibility toggle
- Dashboard widget toggles

## Author

[andrei BARBURAS](https://barburas.com)
