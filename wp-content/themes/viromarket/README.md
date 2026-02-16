# 🎨 ViroMarket Theme - Installation Guide

## 📋 Theme Information

**Theme Name:** ViroMarket  
**Version:** 1.0.0  
**Default Language:** English  
**Supported Languages:** English, French, Arabic (RTL)  
**Requires:** WordPress 6.0+, WooCommerce 7.0+  

---

## 🚀 QUICK SETUP (RECOMMENDED)

### ⚡ Automated Store Setup - 30 Seconds!

We've created a **one-click setup script** that will:
- ✅ Create all categories and subcategories
- ✅ Insert 26 sample products with prices
- ✅ Set up stock and SKUs
- ✅ Configure everything automatically

**Just run this URL after activating the theme:**
```
http://localhost/ecomshop/wp-content/themes/viromarket/setup-store.php
```

**That's it!** Your store will be ready in 30 seconds! 🎉

For detailed information, see **QUICK_START.md**

---

## 🚀 Installation Steps

### 1. Activate the Theme
1. Go to **Appearance > Themes** in WordPress admin
2. Find **ViroMarket** and click **Activate**

### 2. Install Required Plugins
- **WooCommerce** (Required) - For e-commerce functionality
- **Elementor** (Recommended) - For page building with custom widgets
- **WPML** or **Polylang** (Optional) - For multi-language support

### 3. Configure Menus
Go to **Appearance > Menus** and create the following menus:

#### Menu 1: Categories Menu (with subcategories)
**Location:** Categories Navigation Bar

**Suggested Structure:**
```
📱 Electronics
   ├── Smartphones
   ├── Laptops & Computers
   ├── Tablets
   ├── Cameras & Photo
   └── Audio & Headphones

👕 Fashion
   ├── Men's Clothing
   ├── Women's Clothing
   ├── Kids & Baby
   ├── Shoes & Accessories
   └── Jewelry & Watches

🏠 Home & Living
   ├── Furniture
   ├── Kitchen & Dining
   ├── Bedding & Bath
   ├── Home Decor
   └── Garden & Outdoor

🎮 Sports & Entertainment
   ├── Gaming
   ├── Sports Equipment
   ├── Outdoor Recreation
   ├── Books & Media
   └── Toys & Hobbies

💄 Beauty & Health
   ├── Skincare
   ├── Makeup
   ├── Hair Care
   ├── Fragrances
   └── Health & Wellness

🍔 Food & Grocery
   ├── Fresh Produce
   ├── Pantry Staples
   ├── Beverages
   ├── Snacks & Sweets
   └── Organic & Natural

🚗 Automotive
   ├── Car Parts
   ├── Car Accessories
   ├── Tools & Equipment
   ├── Motorcycle
   └── Car Care

📚 Office & School
   ├── Office Supplies
   ├── School Supplies
   ├── Stationery
   ├── Art & Craft
   └── Technology
```

#### Menu 2: Primary Menu
**Location:** Primary Navigation

**Suggested Pages:**
- Home
- Shop
- About Us
- Contact
- Blog
- FAQ

#### Menu 3: Footer Menu
**Location:** Footer Navigation

**Suggested Links:**
- Privacy Policy
- Terms & Conditions
- Shipping Information
- Returns & Refunds
- Customer Service

#### Menu 4: Account Menu
**Location:** Account Menu (Mobile)

**Suggested Links:**
- Dashboard
- Orders
- Downloads
- Addresses
- Account Details
- Logout

---

## 🎨 Customizer Settings

Go to **Appearance > Customize** to configure:

### Colors
- **Primary Color:** #62D0B6 (Teal - default)
- **Secondary Color:** #333333 (Dark Gray)
- **Accent Red:** #F55157 (For badges, sales)
- **Accent Green:** #27AE60 (For success, availability)

### Design
- **Border Radius (Small):** 4px
- **Border Radius (Medium):** 8px
- **Border Radius (Large):** 12px
- **Container Width:** 1200px

### Typography
- **Base Font Size:** 0.875rem (14px)

### Contact Information
- **Phone:** +1 234 567 890
- **Email:** contact@viromarket.com
- **Address:** 123 Example Street, City

### Social Media
- Facebook URL
- Twitter URL
- Instagram URL
- YouTube URL

---

## 🛍️ WooCommerce Setup

### 1. Create Product Categories
Go to **Products > Categories** and create categories matching your menu structure.

**Example Categories:**
1. **Electronics** (Parent)
   - Smartphones (Child)
   - Laptops & Computers (Child)
   - Tablets (Child)
   
2. **Fashion** (Parent)
   - Men's Clothing (Child)
   - Women's Clothing (Child)
   - Kids & Baby (Child)

3. **Home & Living** (Parent)
   - Furniture (Child)
   - Kitchen & Dining (Child)

### 2. Add Products
1. Go to **Products > Add New**
2. Add product details, images, price
3. Assign to appropriate category
4. Set product attributes (size, color, etc.)

### 3. Configure WooCommerce Settings
- **General:** Set currency, location
- **Products:** Set shop page, dimensions
- **Shipping:** Configure shipping zones and methods
- **Payments:** Enable payment gateways
- **Accounts:** Enable customer accounts

---

## 🌍 Multi-Language Setup

### Option 1: Using WPML (Premium)
1. Install and activate WPML
2. Go to **WPML > Languages**
3. Add languages: English (default), French, Arabic
4. Enable RTL for Arabic
5. Translate menus, pages, and products

### Option 2: Using Polylang (Free)
1. Install and activate Polylang
2. Go to **Languages**
3. Add languages: English (default), French, Arabic
4. Configure language switcher
5. Translate content manually

---

## 📱 Recommended Pages to Create

### Essential Pages
1. **Home** - Use Elementor with ViroMarket widgets
2. **Shop** - Automatically created by WooCommerce
3. **About Us** - Company information
4. **Contact** - Contact form and information
5. **Blog** - News and articles
6. **FAQ** - Frequently asked questions

### WooCommerce Pages (Auto-created)
- Cart
- Checkout
- My Account
- Terms & Conditions
- Privacy Policy

---

## 🎯 Widget Areas

The theme includes the following widget areas:

1. **Sidebar Main** - Main blog sidebar
2. **Sidebar Shop** - Shop/Product pages sidebar
3. **Footer Column 1** - First footer column
4. **Footer Column 2** - Second footer column
5. **Footer Column 3** - Third footer column
6. **Footer Column 4** - Fourth footer column

---

## 🔧 Troubleshooting

### Icons not showing
- Make sure Lucide Icons script is loaded
- Check browser console for errors
- Clear cache and refresh

### Menu not displaying
- Assign menu to correct location in **Appearance > Menus**
- Check if menu items are published
- Clear cache

### WooCommerce styling issues
- Make sure WooCommerce is activated
- Go to **WooCommerce > Status** and check for issues
- Regenerate thumbnails if needed

### Language switcher not working
- Install WPML or Polylang
- Configure languages properly
- Assign translations to pages

---

## 📞 Support

For theme support and documentation:
- **Documentation:** Check NEXT_STEPS.md
- **CSS Variables:** Check CSS_VARIABLES_GUIDE.md
- **Customization:** Check CSS_CLEANUP_REPORT.md

---

## ✅ Post-Installation Checklist

- [ ] Theme activated
- [ ] WooCommerce installed and configured
- [ ] Menus created and assigned
- [ ] Categories created with subcategories
- [ ] Sample products added
- [ ] Customizer settings configured
- [ ] Contact information updated
- [ ] Social media links added
- [ ] Multi-language plugin installed (if needed)
- [ ] Languages configured
- [ ] Essential pages created
- [ ] Footer widgets configured
- [ ] Test checkout process
- [ ] Test mobile responsiveness
- [ ] Test RTL layout (for Arabic)

---

**Default Language:** English  
**Created:** 2026-02-16  
**Version:** 1.0.0
