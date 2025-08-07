ow to Add the Book Shortcode in WordPress
Method 1: Using the Block Editor (Gutenberg)
Edit or Create a New Page/Post
Go to Pages > Add New or Posts > Add New
Or edit an existing page
Add a Shortcode Block
Click the "+" button to add a new block
Search for "Shortcode"
Click on the "Shortcode" block
Enter the Shortcode
In the shortcode block, type:
[book]
For more options, you can use:
php
[book category="fiction" number="5" show_author="1" show_price="1"]
Update/Publish the page
Method 2: Using the Classic Editor
Edit or Create a New Page/Post
Go to Pages > Add New or Posts > Add New
Add the Shortcode
In the content area, simply type:
[book]
Or with parameters:
php
[book category="fiction" number="5"]
Update/Publish the page
Available Shortcode Attributes
id
 - Show a specific book by ID
author_name - Filter by author name
year - Filter by publication year
category
 - Filter by category slug
tag
 - Filter by tag
publisher - Filter by publisher
number - Number of books to show (default: 5)
show_author - Show author (1) or not (0)
show_price - Show price (1) or not (0)
Example Shortcodes
Show 3 random books:
[book number="3"]
Show books from a specific category:
[book category="fiction" number="4"]
Show books by a specific author:
[book author_name="J.K. Rowling"]
Show a specific book by ID:
[book id="42"]
Advanced example with multiple filters:
[book category="fantasy" year="2020" number="5" show_author="1" show_price="1"]
Where to Add the Shortcode
Pages/Posts: Any page or post
Text Widget: In Appearance > Widgets, add a Text widget and paste the shortcode
Template Files: Use do_shortcode('[book]') in your theme files
The shortcode will display books in a responsive grid layout with their featured images, titles, and any other information you've chosen to display.
