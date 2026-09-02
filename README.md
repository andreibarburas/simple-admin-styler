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

---

## About Author

Hi, I'm [Andrei Barburas](https://barburas.com). By day, I work at the intersection of information management, intelligence gathering, and human cyber risk; designing systems that make sense of complexity and surface what usually stays unseen, drawing on a background in counter-terrorism research and the behavioral patterns behind radicalization and digital vulnerability. I also lecture in Ethics, Communication Management, and Cross-Cultural Management, and write and teach courses on psychology, culture, and critical thinking.

This corner of GitHub is the other side of that same instinct. I build a small collection of free, open-source tools for people who self-host: a suite of [Android Apps](https://github.com/stars/andreibarburas/lists/android-apps) designed around Nextcloud and selfhosters, along with [Home Assistant Apps](https://github.com/andreibarburas/hassio-apps) and a handful of [Wordpress Plugins](https://github.com/andreibarburas/lists/wordpress-plugins).

I started making these because I wanted something the existing options didn't offer; small, focused tools that fit my own self-hosted setup instead of forcing me to compromise. No ads, no tracking, GPLv3 licensed where applicable, and built to work with the tools people already run at home.

## AI Usage

I'm open about the fact that I use AI throughout development. It genuinely helps me move faster and build things I wouldn't otherwise have time for as one person. But AI doesn't replace the actual work; I still spend the evenings figuring out what something should actually do, testing it against real setups and edge cases, and tracking down the inevitable weird bugs. AI is a tool I use, not a shortcut around the thinking.

## Support

If my work saves you time, a small tip means a lot:

- ⭐ **Donate via Bunq (my bank):** [bunq.me/barburasdonations](https://bunq.me/barburasdonations?description=Donation%20from%20Github)
- 🐈‍⬛ **GitHub Sponsors:** [github.com/sponsors/andreibarburas](https://github.com/sponsors/andreibarburas)
- ☕ **Buy me a coffee:** [buymeacoffee.com/barburas](https://buymeacoffee.com/barburas)
- 🌐 **Website:** [barburas.com](https://barburas.com)
- 🐛 **Issues & feature requests:** [open an issue](/issues)

## Links

- [Play Store](https://play.google.com/store/apps/dev?id=6842866278906089090) [![Google Play Store](https://img.shields.io/badge/Google_Play-414141?logo=google-play&logoColor=white)](https://play.google.com/store/apps/dev?id=6842866278906089090)
- [r/BarburasLab](https://reddit.com/r/barburaslab) ![Subreddit subscribers](https://img.shields.io/reddit/subreddit-subscribers/BarburasLab?style=flat&logo=reddit&link=https://reddit.com/barburaslab)
- [Privacy Policy](https://barburas.com/privacy-policy/) [![License: GPL v3](https://img.shields.io/badge/License-GPLv3-purple.svg)](LICENSE)

## Other work on Github

- [Android Apps](https://github.com/stars/andreibarburas/lists/android-apps)
- [Home Assistant Apps](https://github.com/andreibarburas/hassio-apps)
- [Wordpress Plugins](https://github.com/stars/andreibarburas/lists/wordpress-plugins)

## License

All my work is licensed under the [GNU General Public License v3.0](LICENSE).

You are free to use, study, modify, and redistribute this software, provided that any distributed modifications are also licensed under GPL v3 and the source code remains available.

---

*by [andrei BARBURAS](https://barburas.com)*