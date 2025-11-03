# Link Manager for WordPress

Link Manager for WordPress helps businesses, bloggers, and SEO professionals manage external links with ease. It automatically adds and controls nofollow, sponsored, UGC and other attributes, ensuring SEO compliance and transparency. Includes analytics, per-post editing, and customizable settings for full link control. 

---
<img width="1709" height="823" alt="Screenshot 2025-11-03 140244" src="https://github.com/user-attachments/assets/cb017809-16e7-4fee-8c02-89a85d4dfbbe" />


## Overview

External links play a major role in how search engines evaluate trust, authority, and spam signals.  
For businesses that accept guest posts, run affiliate campaigns, or publish sponsored content, it is critical to manage link attributes responsibly.

The **Link Manager** plugin automates this process by detecting all external links within your posts and ensuring that each link is tagged correctly according to search-engine best practices.  
It also includes a manual editor, an analytics dashboard, and flexible settings that can be customized for different use cases.

---

## Key Features
<img width="1706" height="815" alt="Screenshot 2025-11-03 140358" src="https://github.com/user-attachments/assets/3be2c093-c32c-4fcb-a463-f5a4538c4461" />


### 1. Automatic Attribute Management
- Detects all external links in post content.
- Automatically applies recommended link attributes:
  - `nofollow` – Prevents search engines from following certain links.
  - `sponsored` – Identifies paid or affiliate links.
  - `ugc` – Marks links contributed by users or guest authors.
  - `noopener` and `noreferrer` – Enhances privacy and security for visitors.
- Internal links are never modified.

### 2. Per-Post Link Editor
- A dedicated editor page for each post lists all external links detected in that post.
- Administrators or authors can enable or disable specific attributes per link.
- Provides quick toggles for `nofollow`, `sponsored`, and other flags.
- Includes built-in WordPress nonce and permission checks for secure updates.

### 3. Link Analytics Overview
- Displays total counts of internal and external links across your site.
- Breaks down links by attribute type for better SEO insight.
- Supports pagination for large websites.
- Helps identify posts with high numbers of outbound links or missing attributes.

### 4. Global Plugin Settings
<img width="996" height="636" alt="Screenshot 2025-11-03 140524" src="https://github.com/user-attachments/assets/05f08c94-ea37-4f9c-b667-f3a50c5bae49" />

- Configure default behavior for automatic link handling.
- Choose which post types are scanned (posts, pages, or custom types).
- Enable or disable automatic attribute assignment globally.

### 5. Dashboard Summary Widget
- Presents key link metrics directly in the WordPress admin dashboard.
- Provides quick access to the full analytics and settings pages.

### 6. Developer-Friendly Architecture
- All functions use a consistent prefix (`lm_`) to prevent naming conflicts.
- Includes WordPress hooks for extending functionality.
- Written with modular include files for easy maintenance and contribution.
<img width="1015" height="712" alt="Screenshot 2025-11-03 140800" src="https://github.com/user-attachments/assets/a694b78c-f908-41d2-a4d5-0c1074f75cd1" />

---

## Benefits

### For Business and Corporate Websites
- Ensures all sponsored or partner links comply with search-engine policies.
- Protects brand reputation by preventing unsafe or manipulative link behavior.
- Provides transparent link reporting that marketing teams can audit.

### For Bloggers and Content Creators
- Simplifies link compliance when publishing guest posts or collaborations.
- Saves time by automatically managing link attributes.
- Keeps your blog SEO-friendly without technical maintenance.

### For Agencies and SEO Professionals
- Quickly review and audit all outbound links on client sites.
- Identify potential link risks and fix them in bulk.
- Maintain a consistent and compliant linking strategy across multiple sites.

### For Guest Post Management
- Automatically applies correct attributes (`nofollow`, `ugc`, or `sponsored`) to links from guest authors.
- Reduces the risk of search-engine penalties due to improperly tagged sponsored content.
- Builds trust with advertisers and guest contributors through consistent link handling.

---

## Installation

### Method 1: From the WordPress Admin Dashboard
1. Download the latest ZIP package from the [releases page](../../releases).
2. Go to **Plugins → Add New → Upload Plugin** in your WordPress dashboard.
3. Upload the ZIP file and activate the plugin.
4. Access the settings under **Settings → Link Manager**.

### Method 2: Manual Installation via FTP/SFTP
1. Extract the plugin ZIP on your local computer.
2. Upload the `link-manager` folder to `/wp-content/plugins/` on your server.
3. Activate the plugin from **Plugins → Installed Plugins**.

---

## Usage Guide

### Automatic Mode
Once activated, Link Manager automatically scans the content of your posts on the front end and adjusts external links based on your default settings. No manual action is required.

### Manual Mode (Per-Post Editor)
1. Open a post in the WordPress admin.
2. Click **“Link Manager”** under the post’s admin menu.
3. Review all external links found within the post.
4. Use the provided toggles to assign or remove attributes.
5. Save the changes to update that post’s links.

### Analytics and Reporting
- Navigate to **Link Manager → Overview** to view aggregated link statistics.
- Use filters to identify posts with missing or non-compliant links.
- Export data or share insights with your SEO or marketing teams.

---

## Security and Permissions

- Settings and analytics pages are restricted to users with the `manage_options` capability (administrators).
- Per-post link editing requires `edit_post` permission, ensuring authors can manage their own posts without full admin access.
- All data updates include nonce verification and capability checks.
- The plugin does not collect or send any information to external servers.

---

## Technical Details

| Feature | Description |
|----------|-------------|
| Minimum PHP Version | 7.4 |
| WordPress Compatibility | Tested up to 6.x |
| Database Usage | Stores settings in `wp_options` and per-post data in `wp_postmeta` |
| Custom Tables | None |
| Hooks | `lm_scannable_post_types`, `lm_before_link_process`, `lm_after_link_process` |

---

## Development and Contribution

**Development:** [Digiasylum](https://digiasylum.com)  
**Contribution:** [Umesh Kumar Sahai.](https://www.linkedin.com/in/umeshkumarsahai/)

### Contributing
Contributions are welcome.  
If you would like to improve functionality, fix bugs, or enhance the interface, please contact us here:  
**Email:** [digitalmeshu@gmail.com](mailto:digitalmeshu@gmail.com)

When submitting improvements or suggestions:
1. Clearly describe the problem or enhancement.
2. Provide screenshots or code examples where relevant.
3. Ensure your suggestions align with WordPress development standards.
