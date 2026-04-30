You are a senior Laravel ecommerce developer and UI/UX engineer.

Build a premium light-theme ecommerce project for a brand named “Sunnah Sharee Ghar”.

Project goal:
Create a simple but production-ready ecommerce website where the main focus is Sharee/Saree products. The user-facing website must look premium, elegant, and boutique-like. The admin panel must be simple, clean, and easy to manage for a non-technical client.

Important brand direction:
- Main product focus: Sharee/Saree
- No human model photos will be used in product images, so the UI must make the product itself feel attractive using large product images, closeups, fabric details, collections, colors, and premium layout.
- Theme: premium light theme
- Colors:
  - Background: ivory / warm white
  - Primary: plum / magenta
  - Accent: soft gold
  - Text: charcoal / dark brown
- Design feel: premium, modest, elegant, bridal boutique style
- User panel should be premium.
- Admin panel should be simple and practical.

Tech stack:
- Laravel
- Blade
- Tailwind CSS
- MySQL
- Alpine.js where needed
- Keep the code clean, reusable, and component-based.
- Use proper migrations, models, relationships, controllers, requests, seeders, and Blade components.
- Make the project easy to extend later.

Core categories:
1. Sharee
2. Organic Oil
3. Ornaments
4. Cosmetics
5. Baby Products

Sharee is the main focus. Other categories can be simpler.

Required user-facing pages:
1. Home page
2. Product listing page
3. Product details page
4. Category page
5. Collection page
6. Offer page
7. Combo page
8. Cart page
9. Checkout page
10. Customer login/register
11. Customer account/dashboard
12. Order history
13. Order details
14. Static pages:
   - About Us
   - Contact Us
   - Return Policy
   - Shipping Policy
   - Privacy Policy
   - Terms & Conditions

Homepage requirements:
Create a premium light-theme homepage with these sections:

1. Top announcement bar
   - Free delivery message
   - Cash on delivery available
   - Easy return message

2. Header
   - Logo
   - Search bar
   - Wishlist icon
   - Account icon
   - Cart icon
   - Navigation menu

3. Hero section
   - Large saree-focused banner
   - Premium headline
   - CTA button
   - No human model image
   - Use product flat-lay/fabric imagery style

4. Shop by Sharee Type
   - Katan Sharee
   - Chumki Sharee
   - Banarasi Sharee
   - Silk Sharee
   - Cotton Sharee
   - Bridal Sharee
   - Party Wear Sharee
   - Daily Wear Sharee

5. Shop by Color
   - Create color-based product browsing widgets.
   - Examples: Royal Blue, Maroon, Magenta, Purple, Gold, Black, Pastel.

6. Best Sellers section

7. New Arrivals section

8. Featured Collections
   - Eid Collection
   - Wedding Collection
   - Bridal Collection
   - Gift Collection
   - Budget Collection
   - Premium Collection

9. Offer Zone

10. Combo Deals

11. Why Choose Sunnah Sharee Ghar
   - Premium Fabric
   - Elegant Design
   - Gift Ready
   - Trusted Quality
   - Cash on Delivery
   - Easy Return

12. Footer
   - Brand info
   - Quick links
   - Customer care links
   - Newsletter input
   - Payment method icons/text

Product listing page:
- Show products in premium cards.
- Filters should be based on customer psychology.
- Customers usually search by saree type like Katan, Chumki, Banarasi, etc.
- Required filters:
  - Category
  - Sharee type
  - Color
  - Price range
  - Occasion
  - Fabric
  - Work type
  - Availability
  - Offer/discount
- Sorting:
  - Latest
  - Price low to high
  - Price high to low
  - Popular
- Product card should show:
  - Product image
  - Product name
  - Price
  - Discount price
  - Badge
  - Wishlist button
  - Quick view button

Product details page:
- Premium layout.
- Product image gallery with hover zoom.
- Product title
- Price and discount price
- Stock status
- Color/variant selection
- Quantity selector
- Add to cart
- Buy now
- Wishlist
- Share button
- Product description
- Saree-specific details if category is Sharee:
  - Sharee type
  - Fabric
  - Work type
  - Color
  - Occasion
  - Blouse included or not
  - Length
  - Care instruction
- Delivery and return info
- Related products
- Similar color products
- More from same collection
- Recently viewed products

Cart:
- Add product to cart
- Update quantity
- Remove product
- Show subtotal
- Show delivery charge
- Show discount/coupon if applied
- Show total

Checkout:
- Customer info
- Shipping address
- Delivery charge
- Order summary
- Payment method selection
- Cash on Delivery
- Online payment gateway placeholder integration
- Place order

Payment:
- Must support Cash on Delivery.
- Add structure for online payment gateway.
- Keep payment methods configurable from admin.
- Payment status:
  - pending
  - paid
  - failed
  - cancelled
  - refunded

Order tracking/status:
- pending
- confirmed
- processing
- shipped
- delivered
- cancelled

Admin panel:
Admin should be simple, clean, and easy to use.

Required admin modules:

1. Dashboard
   - Total orders
   - Today’s orders
   - Pending orders
   - Total sales
   - Low stock products
   - Recent orders
   - Best selling products

2. Category Management
   - Add/edit/delete category
   - Parent category support
   - Category image
   - Active/inactive status
   - Featured category option

3. Product Management
   - Add/edit/delete product
   - Product name
   - Slug
   - Category
   - Collection
   - Product type
   - Price
   - Discount price
   - SKU
   - Description
   - Multiple images
   - Active/inactive status
   - Featured product option
   - Best seller option
   - New arrival option

4. Sharee-specific product fields
   These fields should appear mainly for Sharee products:
   - Sharee type
   - Fabric
   - Work type
   - Color
   - Occasion
   - Blouse included
   - Length
   - Care instruction

5. Variant & Inventory Management
   Keep it basic.
   - Variant-wise quantity
   - Color variant
   - SKU per variant
   - Stock alert quantity
   - Stock status
   Example:
   - Royal Blue: 5 pcs
   - Maroon: 3 pcs
   - Purple: 2 pcs

6. Order Management
   - View all orders
   - View order details
   - Change order status
   - Change payment status
   - Print invoice
   - Customer info
   - Delivery address
   - Admin note

7. Offer/Coupon Management
   - Create coupon
   - Percentage discount
   - Fixed amount discount
   - Minimum order amount
   - Start date
   - End date
   - Active/inactive status

8. Offer Page Management
   - Create offer campaign
   - Campaign title
   - Campaign banner
   - Add products to campaign
   - Start/end date
   - Active/inactive status

9. Collection Management
   - Create collection
   - Collection banner
   - Collection description
   - Add products to collection
   - Featured collection option
   - Active/inactive status

10. Combo Management
   - Create combo
   - Add multiple products to combo
   - Combo image
   - Regular total price
   - Discounted combo price
   - Combo stock
   - Active/inactive status
   - Example combos:
     - Sharee + Ornament Combo
     - Sharee + Cosmetic Gift Combo
     - Organic Oil Combo
     - Baby Product Combo

11. Banner/Homepage Management
   - Hero banner
   - Promotional banner
   - Featured categories
   - Best seller section control
   - New arrival section control
   - Gift collection banner
   - Offer banner

12. Customer Management
   - Customer list
   - Customer order history
   - Customer contact details

13. Payment Management
   - Payment method settings
   - Transaction list
   - Payment status

14. Basic Website Settings
   - Website logo
   - Website name
   - Phone number
   - Email
   - Address
   - Facebook page link
   - Delivery charge
   - Free delivery minimum amount
   - Return policy text
   - Shipping policy text
   - Terms and conditions
   - Privacy policy

Database design requirements:
Create clean database tables with proper relationships.

Suggested tables:
- users
- customers or use users with role
- categories
- products
- product_images
- product_variants
- collections
- collection_product
- offers
- offer_product
- combos
- combo_items
- carts
- cart_items
- orders
- order_items
- payments
- coupons
- settings
- banners

Roles:
- admin
- customer

Authentication:
- Admin login
- Customer login/register
- Keep admin routes protected with middleware.

UI instructions:
User panel:
- Premium light theme
- Ivory/warm white background
- Plum/magenta primary buttons
- Soft gold accent lines/icons
- Elegant serif headings
- Clean product cards
- Rounded corners
- Soft shadow
- No overly dark design
- No clutter
- Product image should be the main subject.

Admin panel:
- Simple dashboard
- Sidebar navigation
- Clean forms
- Simple tables
- Search/filter where needed
- Do not overdesign admin panel.

Development approach:
Build efficiently in phases.

Phase 1:
- Migrations
- Models
- Relationships
- Seeders
- Auth and roles
- Basic layout components

Phase 2:
- Admin panel CRUD:
  - categories
  - products
  - variants/inventory
  - collections
  - offers
  - combos
  - banners/settings

Phase 3:
- User panel:
  - homepage
  - product listing
  - product details
  - category page
  - collection page
  - offer page
  - combo page

Phase 4:
- Cart, checkout, orders, payment status

Phase 5:
- Polish UI, responsive design, test flows, fix bugs

Acceptance criteria:
- Customer can browse products by category, collection, color, and saree type.
- Customer can add product to cart and place order.
- Admin can create products with variants and quantity.
- Admin can manage orders and update statuses.
- Admin can create collections, offers, and combos.
- Homepage looks premium and focused on saree.
- Admin panel stays simple and easy.
- Website is responsive for desktop and mobile.
- Code is clean and maintainable.

Important:
Do not make the project unnecessarily complex.
Do not add advanced features unless needed.
Focus on a strong MVP with premium frontend and simple admin.
Use reusable Blade components wherever possible.
Use proper validation and error handling.
Use clean naming and readable code.
After implementation, provide a short summary of created files, routes, database tables, and how to run the project.
