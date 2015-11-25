Karina Banda

Rettner plugin for Wordpress

=== Overview ===
A system to handle the loaning equipment for the University of 
Rochester's Rettner Media Lab was created. Rettner has many Google
Glasses, Cameras, Gear Pros, and Kinects for students to check out
for 3 day periods. Rettner's webteam created a website using 
Wordpress so that the other team member's would be able to utilize 
the system as well. We used Wootheme's Boutique Storefront child 
theme along with their Woocommerce plugin as the basis of the website. 
I created the coding portion of this project, which included the 
css and a plugin to change parts of the theme and woocommerce plugin 
to fit our needs, and is what is included in this repository.  


=== Rettner plugin ===
Inside the rettner-plugin folder is the plugin's main file 
"rettner-plugin.php". Once installed onto Wordpress, it makes edits
to the contents of the theme and woocommerce's folders to fit our 
needs. Major changes include the deletion of the carts and prices
because our lending library is free to use and only one item is 
allowed to be checked out at a time by a user. The checkout page was 
also edited to only include the student's university netid and 
password as that is the only information that we require. Changes
were made to the functionality of the Woocommcerce Wordpress 
admin view to reflect the different groups of orders- Processing, 
Active Rental, and Completed. Utilizing these different groups,
the Rettner Staff can keep track of the loaning equipment. Full list 
of changes can be seen in the rettner-plugin.php file with comment
descriptions.


=== CSS ===
The file newstyle.css reflects the css changes that were inserted through
Wordpress's jetpack custom css settings. These changes can be viewed
through the RettnerScreenshot.jpeg that is included in this repository. 
A screenshot of the Boutique's orignial theme view is also included 
as BoutiqueScreenshot.jpeg for comparison purposes. 
