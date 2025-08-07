# WP Book Plugin

A comprehensive WordPress plugin for managing and displaying books with custom post types, taxonomies, metadata, shortcodes, and widgets.

## 📚 Features

- **Custom Post Type**: 'Book' with all necessary fields
- **Taxonomies**: 
  - Book Category (hierarchical)
  - Book Tag (non-hierarchical)
- **Custom Meta Boxes**: For book details (author, price, publisher, etc.)
- **Custom Table**: For storing book metadata
- **Shortcode**: `[book]` with various attributes
- **Widget**: Display books by category
- **Dashboard Widget**: Shows top 5 book categories
- **Settings Page**: For currency and books per page
- **i18n Ready**: Full translation support

## 🚀 Installation

1. Download the plugin files
2. Upload the `wp-book` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Books > Settings to configure plugin options

## 💻 Usage

### Shortcode
```
[book category="fiction" number="5" show_author="1" show_price="1"]
```

### Widget
1. Go to Appearance > Widgets
2. Find 'Book Category Widget'
3. Drag to your desired widget area
4. Configure the widget settings

## 📖 Documentation

### Post Meta Data

**What is 'Post Meta Data'?**
Post meta data is additional information that can be attached to a post. In this plugin, it's used to store book-specific information like author, price, etc.

**What fields can be accessed?**
- `_wp_book_author` - Book author
- `_wp_book_price` - Book price
- `_wp_book_publisher` - Publisher name
- `_wp_book_year` - Publication year
- `_wp_book_edition` - Edition number
- `_wp_book_url` - External URL

**Difference between `add_post_meta` and `update_post_meta`**
- `add_post_meta()`: Adds a new meta field. If the key already exists, it can add another row with the same key.
- `update_post_meta()`: Updates an existing meta field or adds it if it doesn't exist. If the key exists, it updates the existing value.

**Should metadata be used for querying posts?**
While possible using `meta_query`, it's generally better to use taxonomies for querying as they're more performant. Metadata is better for filtering or displaying additional information.

### Metadata API

**How does the Metadata API differ from other APIs?**
- **Metadata API** is specific to WordPress for handling post, comment, user, and term metadata.
- **REST/SOAP** are web service protocols for remote communication.
- Metadata API is for internal WordPress data storage, while REST/SOAP are for external communication.

**Handling Metadata Dependencies**
The plugin handles dependencies by checking if required data exists before using it. If a dependency is missing, it provides fallback values or graceful degradation.

**Serialization Formats**
- Primarily uses PHP serialization for complex data
- Also supports JSON for better interoperability
- Simple key-value pairs for basic metadata

### Shortcodes

**How WordPress parses shortcodes**
1. WordPress searches post content for square brackets
2. When found, it looks for a registered shortcode
3. If found, it calls the associated callback function
4. The function returns the output that replaces the shortcode

**If shortcode is not found**
If WordPress can't find a registered shortcode, it will display the shortcode as plain text in the frontend.

**Escaping Shortcodes**
To display a shortcode as text, use double square brackets:
```
[[book]]
```

**Creating Custom Shortcodes**
```php
function my_book_shortcode($atts) {
    // Shortcode logic here
    return $output;
}
add_shortcode('book', 'my_book_shortcode');
```

**Shortcode Storage**
Shortcodes are stored in the global `$shortcode_tags` array when registered with `add_shortcode()`.

**Shortcode Attributes**
```php
function my_book_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'category' => '',
            'number' => 5,
            'show_author' => true
        ), 
        $atts,
        'book'
    );
    // Rest of the code
}
```

**Nested Shortcodes**
Use `do_shortcode()` to handle nested shortcodes:
```php
echo do_shortcode($content);
```

## 🛠️ Development

### File Structure
```
wp-book/
├── includes/
│   ├── dashboard-widget.php
│   ├── install.php
│   ├── meta-boxes.php
│   ├── settings-page.php
│   ├── shortcodes.php
│   └── widgets.php
├── languages/
├── single-book.php
└── wp-book.php
```

### Hooks and Filters
- `wp_book_after_content` - After book content
- `wp_book_before_meta` - Before book meta data
- `wp_book_currency` - Filter the currency symbol

## 📝 License

GPL v2 or later

## 🙏 Credits

Developed by Vinay...... its me 

<img width="1895" height="861" alt="image" src="https://github.com/user-attachments/assets/dff710de-04a5-440d-a953-cd0cf29c19f0" />

<img width="1396" height="827" alt="image" src="https://github.com/user-attachments/assets/6903cc59-a216-4c52-86b4-7d76bb9dd9c4" />

```
[book] - Display all books (5 per page)
[book number="3"] - Show specific number of books
[book category="fiction"] - Filter by category
[book category="fiction, science"] - Filter by multiple categories
[book tag="bestseller"] - Filter by tag
[book author="J.K. Rowling"] - Filter by author
[book publisher="Penguin"] - Filter by publisher
[book year="2020"] - Filter by year
Display Options
[book display="grid" columns="3"] - Grid view
[book display="list"] - List view
[book show_thumbnail="0" show_excerpt="0"] - Hide thumbnails and excerpts
[book show_content="1"] - Show full content
Sorting Shortcodes
[book orderby="title" order="ASC"] - Sort by title (A-Z)
[book orderby="meta_value" meta_key="_wp_book_price" order="ASC" meta_type="NUMERIC"] - Sort by price
[book orderby="date" order="DESC"] - Newest first
Advanced Shortcodes
[book ids="1,5,8"] - Show specific books by ID
[book exclude="3,7"] - Exclude specific books
[book paginate="1" per_page="3"] - Add pagination
[book template="custom-books"] - Use custom template
[book meta_query='[{"key":"_wp_book_price","value":["10","50"],"compare":"BETWEEN","type":"NUMERIC"}'] - Price range
Combination Examples
[book tag="bestseller" year="2020"] - Bestsellers from 2020
[book category="science-fiction" author="Andy Weir"] - Filter by category and author
[book meta_query='[{"key":"_wp_book_price","value":"10","compare":"<","type":"NUMERIC"}'] - Books under $10
```

