=== Content Anchor Links ===
Contributors: xnau
Donate link: https://xnau.com/work/wordpress-plugins/#donation-link
Tags: anchor
Requires at least: 4.6.0
Tested up to: 4.6.1
Stable Tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Easily add content anchors to your pages and posts

== Description ==

This plugin adds a unique ID attribute to healings in your page and post content, and then makes those anchors available in the linking pop-up. This makes it very easy to add "jump" links to headings within the content.

= Using the Plugin =
This plugin doesn't use any settings or require any setup, just activate it and it's features are available.

The plugin works by adding a unique "id" to the headings in your content. When you open your content for editing, the plugin adds unique ids to all the h2 and h3 headings. After saving the content, those headings become available as "anchors" which can be linked to.

To create an anchor link, add the desired link text, select it and click the "add link" icon. If you begin typing the heading you want the link to jump to, the anchor link for that heading will appear in the search results. Choose that anchor to place your link.

If you want a link from another page to jump to a heading, you can add the anchor link (begins with a "#") to the regular page link and when that link is clicked on the user will be taken to that heading on the new page.

For example, let's say you have a page at `https://your-website.co/making-a-link-list` that explains how to make a link list, and on that page is a tutorial with the heading "Tutorial" If you look at the HTML for that page, you'll see that the heading has an "id", like this" `<h2 id="tutorial">Tutorial</h2>` On your other page you can link directly to that heading with an URL like this: `https://your-website.com/making-a-link-list#tutorial`

== Installation ==

1. In the admin for your WordPress site, click on "add new" in the plug-ins menu.
2. Search for "participants database" in the WP plugin repository and install
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place `[pdb_record]` in your blog posts and pages to show the signup form
5. Additonal features and instructions can be found on the help tab of the plugin's settings page

**or**

1. Download the zip file
2. Click on "Add New" in the plugins menu
3. At the top of the "Add Plugins" page find and click the "Upload Plugin" button
4. Select the zip file on your computer and upload it
5. The plugin will install itself. Click on "activate" to activate the plugin

== Frequently Asked Questions ==

= I don't see the anchor links in the linker =

First, be sure there are headings in your content. The plugin creates links on Heading 2 and Heading 3 

== Changelog ==

= 1.0 =
initial public release

== Upgrade Notice ==