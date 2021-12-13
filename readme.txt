=== Gravity Forms Live Summary ===
Contributors: geekontheroad
Donate link: https://geekontheroad.com
Tags: gravityforms, summary
Requires at least: 4.7
Tested up to: 5.8.2
Stable tag: 1.0.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin will add a live summary to a gravity forms.

== Description ==


[View the demo](https://gravitysummary.geekontheroad.com/) 


#How does it work?
1. Install the plugin
1. Go into form settings and turn on the "Turn on summary" checkbbox. This checkbox is located in the layout options just under the form css classes.
1. Decide which fields to show in the summary by checking the checkbox in the field settings of each field that you want to show.
1. Summary will now work
1. Optional: Show a total at the bottom. This will automatically work if you add any product fields to the form. To turn this total off, simply go the form settings and uncheck the "show total in summary" checkbox


** Conditional logic supported

#Currently the following field types are supported:
* Single Line Text
* Paragraph Text
* Drop Down
* Number
* Checkboxes
* Radio Buttons
* Name
* Date
* time
* Phone
* Address
* Website
* Username
* Email
* Multi Select
* Product
* Total

** Hidden fields are not yet supported.

*** Gravity forms is required to use this plugin ***


== Frequently Asked Questions ==

= I installed the plugin but I don't see a summary? =

You have to turn on the summary per form in the form settings. The settings are located just below the css class field in the form layout section.

= I don't see a total in the summary? =

There are two conditions to see the total in the summary. Firstly you have to have at least one product field in the form. Secondly you need to make sure that the "Show total" setting is turned on. You can find this setting in the form settings.

= I want to change the output of the summary, do you help? =

Yes, send me a message and tell me what you would like and I will do my best to help you. (Paid service)

== Screenshots ==

1. Empty summary shows next to the form on laptop and under the form on mobile.
2. Summary is filled while making selections in the form.
3. Settings in the form settings. Here you can turn the summary on or off.
4. Field settings. All supported fields have a checkbox to control visibility in the summary

== Changelog ==

= 1.0.2 =
Initial Release
Sanitize and validate
Rename function and class names

= 1.0.1 =
Initial Release

