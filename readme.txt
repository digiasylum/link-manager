=== Link Manager ===
Author: Digiasylum
Donate link: https://www.digiasylum.com/
Author URI: https://www.digiasylum.com
Contributors: Umesh Kumar Sahai
Contributor URI: https://linkedin.com/in/umeshkumarsahai
Tags: links, nofollow, sponsored, noopener, SEO, link management, external links, internal links, rel attributes, link editor
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Manage `rel` attributes (nofollow, sponsored, noopener) for external links. Unlink or replace any link in your WordPress posts and pages. Includes link overview, analytics, per-post editor, global settings, and a dashboard widget.

== Description ==

Link Manager empowers you to take full control over your links.
* **Link Overview:** View all posts and pages with counts of internal/external links. Filter by Post Type (All, Posts, Pages) and search by title, link URL, or anchor text using a consolidated search.
* **Site-Wide Analytics:** Get statistics on total links (separated for Posts and Pages scanned), and counts for `nofollow`, `sponsored`, and `noopener` attributes, presented in a clear table.
* **Dashboard Widget:** See key link statistics at a glance on your WordPress Dashboard, with separate counts for posts and pages scanned.
* **Per-Post Link Editor:**
    * Individually manage `nofollow`, `sponsored`, and `noopener` attributes for each **external** link. (Attribute editing for internal links is disabled).
    * **Unlink Feature:** Remove any link (internal or external) while keeping its anchor text.
    * **Replace URL Feature:** Update the URL of any specific link instance within a post.
* **Global Settings:** Configure site-wide rules, such as automatically adding `nofollow` and/or `noopener` to all external links.
* **Direct Access:** "Manage Links" option added to row actions on the main Posts and Pages list tables for quick access to the editor.

This plugin is designed to help with SEO best practices and give you granular control over how search engines and browsers interact with links on your site.

== Installation ==

1.  Upload the 'link-manager' folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Navigate to "Link Manager" in the admin menu. The overview page will display your posts and link analytics.
4.  From the overview, or from the Posts/Pages list table actions, click "Manage Links" for any item to edit its links.
5.  Configure global settings under "Link Manager" > "Settings".
6.  View summary stats on the WordPress Dashboard via the Link Manager widget.

== Changelog ==
= 1.5.0 =
* Added Bootstrap Framework.
* Added UGC as relative value.


= 1.4.2 =
* Feature Removal: Removed "Unlink (keep text)" and "Replace URL" functionalities from the Link Editor to simplify the plugin's focus on attribute management.
* Code Cleanup: Removed associated helper functions (lm_unlink_external_link, lm_replace_link_url_in_content, lm_get_processed_dom_content, lm_dom_inner_html), JavaScript, and CSS for the removed features.
* UX: Link Editor table and actions column updated to reflect removed features. Localized JS strings for removed features also removed.
* Minor fix in `lm_get_links_from_post`: `original_html` attribute in link data removed as it's no longer used.
* Readme and plugin description updated to reflect current feature set.

= 1.4.1 =
* Fix: Improved DOM content processing after unlink/replace URL actions to prevent potential blank screens or errors on page reload.
* Feature Enhancement: Analytics in both the Dashboard Widget and Link Overview now show separate counts for "Posts Scanned" and "Pages Scanned".
* Code Refinement: Updated DOM parsing in `lm_get_links_from_post` for more robust link discovery.

= 1.4 =
* Feature: Added a Dashboard Widget to display site-wide link statistics.
* Feature: Implemented "Replace URL" functionality in the Link Editor to update specific link URLs within a post.
* UX Improvement: Added filters (All, Posts, Pages) and a "Type" column to the Link Overview page.
* UX Improvement: Added "Manage Links" action to the row actions on the main WordPress Posts and Pages list tables for direct access to the editor.
* Code Refinement: Improved localization support for JavaScript prompts and confirmations.
* Code Refinement: General code cleanup, CSS adjustments for new features, and improved HTML escaping/sanitization.
* Versioning: Consolidated recent features under version 1.4.
* UX Improvement: Attribute editing (nofollow, sponsored, noopener) in the Link Editor is now disabled for internal links. (Previously 1.3.x dev)
* Feature Enhancement: "Unlink (keep text)" option in Link Editor now available for both internal and external links. (Previously 1.3.x dev)
* Fix: Addressed critical bug where checking attribute boxes could trigger link removal. (Previously 1.3.x dev)
* Fix: Resolved "Sorry, you are not allowed to access this page." error for Link Editor. (Previously 1.3.x dev)


= 1.2 =
* Improvement: Site-wide link analytics display changed to a table format for better readability.
* Improvement: Consolidated post filtering on the overview page to a single search input for titles, link URLs, and anchor texts.
* Feature Removal: Removed the "UGC" (User Generated Content) attribute option from the plugin.
* Fix: Addressed an issue where the screen could go blank after unlinking a link in the editor; improved redirect and message handling.
* Fix: Enhanced DOM parsing in `lm_unlink_external_link` to better preserve inner HTML of unlinked text.

= 1.1 =
* Feature: Added site-wide link analytics to the overview page.
* Feature: Added a "Back to Overview" button in the Link Editor page.
* Feature: Added a global setting to automatically apply `rel="noopener"` to external links.
* Feature: Added a "Manage Links" action link on the WordPress Plugins page.
* Feature: Implemented search and filter functionality on the Link Overview page.
* Feature: Added functionality to remove an external link while keeping its anchor text (unlink).
* Improvement: Hid the "Link Editor" submenu item.

= 1.0 =
* Initial release.

== Upgrade Notice ==
= 1.4.1 =
This version includes important fixes for page reloading issues after "Unlink" or "Replace URL" actions and further refines the analytics display.
