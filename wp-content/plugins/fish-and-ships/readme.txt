=== Fish and Ships for WooCommerce, a conditional table rate shipping cost ===
Contributors: wpcentrics
Donate link: https://www.wp-centrics.com/
Tags: table rate, conditional, woocommerce, conditional cost, cost rules, table rules, shipping rate, flexible shipping, woocommerce table rate shipping, cart costs shipping, weight shipping, price shipping, totals based shipping, shipping zones, shipping classes, group items criteria
Requires at least: 4.4
Tested up to: 5.4
WC requires at least: 2.6
WC tested up to: 4.0.1
Stable tag: 1.0.5
Requires PHP: 5.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A WooCommerce shipping method like no other: Based on table rate with multiple (as you need) conditional rules, easy to understand and easy to use, it gives you an incredible flexibility.

== Description ==

= ** Exact fit shipping costs on your WooCommerce shop ** =
Most online shops set generic shipping costs: too much for some products and fewer for another... **are you**?

**Setup exact costs easily**
Fish and Ships help you to increase sales and avoid costs loss, throught an easy to use and understand shipping table rate cost, with multiple conditional options (as you need).

You can set multiple conditional rules on the table rate: based on price, weight, product quantity on cart, dimensions, volume, shipping class, product tag and product category.

There is a few plugins table rate-based shipping costs calculation, but **only Fish and Ships** allow you to add on every rule multiple criteria selection:

*(condition #1 AND condition #2 AND condition #3)*... as you need, on every rule!

...from the simplest to the most complex selection criteria, any need can be fulfilled. Based on the WooCommerce shiping zones.

= Here is the list of all **selection methods:** =

* Price
* Weight
* Volume
* Cart items
* Min/Mid/Max dimensions
* In shipping class
* NOT In shipping class
* Volumetric [PRO]
* Length+Width+Height total [PRO]
* In category [PRO]
* NOT In category [PRO]
* Tagged as [PRO]
* NOT Tagged as [PRO]

...Everything can be combined on every rule.


= Group by =

You can **set an agroupation of the items cart** before analize the conditions:

* None (every item will be analised alone)
* Per ID / SKU (same item will be grouped)
* Per product (variations will be grouped)
* Per shipping class grouping
* All products grouped

= Shipping cost calculation =

You can **calculate shipping cost** per:

* Once
* Per cart items
* Per products weight **[NEW]**
* Per price products (percentage)
* Per matching groups (groped under your group-by option)
* Composite: all previous methods (or what you need) together

**Take the control and reduce abandoned carts**

= Special Actions =
Powerful computer coding made easy as a piece of cake, this will take you to another level

Add custom messages, rename the shipping method or add extra info on the fly when your conditions match! here is the list of all **Special Actions**:

* Abort shipping method 
* Ignore below rules
* Skip N rules [PRO]
* Reset previous costs [PRO]
* Set min/max rule costs [PRO]
* Unset match products for next rules [PRO]
* Show notice message [PRO]
* Rename method title [PRO]
* Add subtitle (text under title) [PRO]

...all combinable on every rule.

= Other shipping method options =

Apart of the rule tables, **you'll find this options** on the method:

* Method title
* Tax status (apply tax or not over the calculated shipping cost)
* Global / On every condition group-by option [PRO]
* Group-by (explained over, the options are: none, per ID/SKU, per product, per shipping class, all as one)
* Calculation type: charge all matching rules / only the most expensive
* Min shipping price: (if the method applies, you can set a min value cost in any case)
* Max shipping price: (if the method applies, you can set a max value cost in any case)


= ...and much, much more: =

* Fish and Ships for WooCommerce comes with extensvie **help files** and context links to it

* **Intuitive wizard** will guide you just activate the plugin, through WooCommerce screens

* It will work **in your currency** and measurements set in WC settings

* **Multilingual** and **Multi-currency**[new!] support using the WPML plugin (soon we will give support others)

*A built-in **log system** to help you to learn, understand or debug any complex shipping method configuration

[Here you can read the help files &rarr;](https://www.wp-centrics.com/help/fish-and-ships/)

[Here you can compare the *Free vs Pro features* &rarr;](https://www.wp-centrics.com/woocommerce-fish-and-ships-free-vs-pro/)


= Languages =

* English (plugin and help files)
* Spanish (plugin and help files)
* Catalan (plugin and help files)

<blockquote>
<h4>I want to translate Fish and Ships</h4>
<p>You’re welcomed! We offer a <strong>forever license</strong> of Fish and Ships Pro in exchange for plugin and help translation.</p>
<p><a href="https://www.wp-centrics.com/contact-support/">If you’re interested, please, contact us here &rarr;</a></p>
</blockquote>


== Upgrade Notice ==

This is a first release

== Installation	 ==

Can be installed as usual:

1. Manual: Download from wordpress.org, unzip and upload through FTP to the /wp-content/plugins/ directory. Then go to admin plugins page and activate it.

2. From admin plugins>add new page: search “fish and ships” and click on install button, then activate.

= How to configure the plugin? =

**Simply activate it**, and a wizard will appear. Follow it through WooCommerce screens and you’ll get your first Fish and Ships shipping method to start configure the shipping rules.

== Frequently Asked Questions ==

= How I can re-start the wizard? =

Go to plugins admin page again, and look for the link “**Start: run wizard**” into the Fish and Ships plugin row.

= Where I can find the help? =

Fish and Ships comes with context help. You can open it on any help icon (?) on the shipping method screen.

However, you can see also this help in [our website help docs &rarr;](https://www.wp-centrics.com/help/fish-and-ships/).

You’ll find this link also in the admin plugins page of your site.

= Multilingual support? =

Yes. For now it supports only WPML, but it’s in our roadmap make it compatible with more options in the future.

= Multicurrency supported? =

Since version 1.0.5, multi-currency is supported trough WPML+WC Multilingual. Soon we will give support to other multi-currency plugins, it’s also in our roadmap. 

= Which currency and units for dimensions and weight will be used? =

Fish and Ships will work in the currency and measurements that you’ve set in WooCommerce settings, just as the way WC does.

= Required plugins and compatibility versions? =

Fish and Ships has widely tested with older and latest; our philosophy is to cover the maximum releases (when are viable) and obviously cover all future releases.

**PHP**: from 5.5 to current release (tested up to 7.3.x).
**WordPress**: from 4.4, all posterior releases (tested up to 5.4.x).
**WooCommerce**: from 2.6, all posterior releases (tested up to 4.0.x).
**WPML**: Tested the contemporary releases with the WooCommerce / WP releases.

= Fish and Ships Free vs Pro? =

[Here you can check the two version features &rarr;](https://www.wp-centrics.com/woocommerce-fish-and-ships-free-vs-pro/)


== Screenshots ==

1. Shipping rules order diagram
2. Shipping rules table
3. Simple or multiple criteria selection
4. The selection options (Free and Pro)
5. Simple / composite price calculation
6. Group-by options
7. Special Actions options (Free and Pro)
8. Well-documented help
9. You can activate logs calculation
10. Setting custom messages on admin (Pro)
11. Cart with the custom messages (Pro)

== Changelog ==


= 1.0.5 - 2020-05-04 =
* Multi-currency support added (for now only with WPML + WC Multilingual, soon we will give support to others)
* Fixed double wizard bug on WooCommerce admin screens

= 1.0.4 - 2020-04-01 =
* Fixed selection by shipping class bug on variations (prior releases lookin the parent product shipping class, 
  and maybe the variation has set another one)
* Fixed PHP bug in 3.0.x WC versions: "undefined function get_instance_id()" message
* Added a refresh for the cached shipping cost when the shipping method options are modified
* Removed the message "we encorage you to enable the WooCommerce debug mode"

= 1.0.3 - 2020-03-24 =
* Added per product weight shipping cost calculation (single and composite)

= 1.0.2 - 2020-03-16 =
* Tested under WooCommerce 4.0.0
* Wizard works with the new WooCommerce Admin Plugin 1.0
* Contextual help can be now multilingual
* Added translated help for spanish and catalan languages

= 1.0.1 - 2020-03-10 =
* Tested under WordPress 5.4 (beta 3) 
* Added spanish and catalan languages
* Option added on calculation type: "Charge only the most cheap matching rule"
* Parse error prevention on PHP less than 5.5 ( not supported, but not buggy, a message will be shown)

= 1.0.0 - 2020-03-04 =
* Hello world!
