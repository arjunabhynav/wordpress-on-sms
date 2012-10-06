WordPress on SMS v1.0
=====================

#### Publish blog posts on WordPress blogs through SMS
##### Developed for 55444.in Platform

*Author: Arjun Abhynav*  
*Contact: arjun.abhynav@gmail.com*  
*Website: http://arjunabhynav.com*  
*Twitter: @arjunabhynav*  

##### App registered at http://55444.in/apps/wordpress on 6th October 2012

This application has been developed to enable users to publish on WordPress.com through SMS, and uses XML Remote Procedure Calls. XML-RPC.

Version 1.0 allows users to name post titles, and add content to their posts. Future updates will offer more features like adding tags, keywordsand categories to the blog posts. Also, the user will be able to manage drafts, and append contents of a current blog post in each SMS to finally publish the post.

This application will also work for sites using self-hosted WordPress installation, as long as the XML-RPC publishing is enabled.

##### Developer Instructions:  
The two parameters passed to the application by 55444.in are GET variables 'mobile' and 'message', where 'mobile' is an unique hashed identifier of the user's phone number, and 'message' is the message that was sent by the user as additional parameters.

##### User Instructions:  
Send #wordpress.register BlogName to 55444 to register a blog.  
Send #wordpress.set UserName,PassWord to 55444 to set credentials.  
Send #wordpress PostTitle > BlogPostContent to 55444 to start publishing posts.  

To use this application on a self-hosted WordPress blog, in the admin dashboard, go to Settings > Writing and enable XML-RPC.
