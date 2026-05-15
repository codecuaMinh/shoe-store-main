# 👟 Build Shoe Shop System with Payment Integration

> **SE445D — System Integration | Group 3 | Duy Tan University**

A fully integrated online shoe selling system built with PHP + MySQL, featuring VNPay payment gateway integration, real-time inventory synchronization, and a comprehensive admin dashboard.

---

## 🚀 Features

### Customer Interface
- Browse 80+ shoe products across 6 brands (Nike, Adidas, Converse, Vans, New Balance, Puma)
- Filter products by category: Sneaker, Skate, Running, Lifestyle
- Product detail page with stock availability
- Shopping cart with quantity management
- Secure checkout with VNPay payment integration
- Customer registration & login

### Admin Interface
- Dashboard with real-time metrics (revenue, orders, products, low-stock alerts)
- Product management (Add / Edit / Delete)
- Order management with status workflow: Pending → Paid → Shipping → Done
- Inventory status with low-stock warnings (stock < 10)

### Integration Types
| Type | Description |
|------|-------------|
| **API Integration** | VNPay Sandbox payment gateway with HMAC-SHA512 signature verification |
| **Data Integration** | Real-time inventory sync — stock auto-decremented on payment confirmation |
| **Presentation Integration** | Admin dashboard aggregating data from multiple tables into one interface |

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.x
- **Database:** MySQL (phpMyAdmin)
- **Server:** Apache (XAMPP)
- **Frontend:** HTML / CSS / JavaScript
- **Payment:** VNPay Sandbox API
- **Font:** Google Fonts (Bebas Neue, DM Sans, DM Mono)

---

## ⚙️ Installation

### Prerequisites
- XAMPP (Apache + MySQL)
- PHP 8.x
- Web browser

### Setup Steps

**1. Clone or download the project**
```
Place the shoe-store-main folder into: C:\xampp\htdocs\
```

**2. Start XAMPP**
```
Open XAMPP Control Panel → Start Apache → Start MySQL
```

**3. Create the database**
```
Go to: localhost:8080/phpmyadmin
Create database: shoe_store
Import the SQL schema (or run the setup SQL manually)
```

**4. Configure database connection**

Edit `includes/db.php`:
```php
$host     = 'localhost';
$dbname   = 'shoe_store';
$username = 'root';
$password = '';
```

**5. Access the application**
```
Customer:  http://localhost:8080/shoe-store-main/
Admin:     http://localhost:8080/shoe-store-main/admin/
phpMyAdmin: http://localhost:8080/phpmyadmin
```

---

## 🗄️ Database Schema

| Table | Description |
|-------|-------------|
| `users` | Customer and admin accounts |
| `products` | Shoe catalog with real-time stock |
| `orders` | Customer orders with status tracking |
| `order_items` | Line items linking orders to products |
| `payments` | VNPay transaction records |

---

## 💳 VNPay Configuration

> ⚠️ **Sandbox environment only — no real transactions**

```php
// api/vnpay.php
VNP_TMNCODE   = 'GRARNE8L'
VNP_HASHSECRET = 'DD51961KXQRFBBM7JHMDX3FZ9XXWH4U0'
VNP_RETURNURL  = 'http://localhost:8080/shoe-store-main/payment_return.php'
```

**VNPay Merchant Dashboard:** https://sandbox.vnpayment.vn/merchantv2/

---

## 🧪 Test Payment (Demo)

Use the following test card to simulate a successful payment:

| Field | Value |
|-------|-------|
| Bank | NCB |
| Card Number | `9704198526191432198` |
| Cardholder Name | `NGUYEN VAN A` |
| Issue Date | `07/15` |
| OTP | `123456` |

---

## 👤 Default Admin Account

```
Email:    admin@shoestore.com
Password: password
```

> To access admin panel: `http://localhost:8080/shoe-store-main/admin/login.php`

---

## 📁 Project Structure

```
shoe-store-main/
├── admin/
│   ├── index.php          # Admin dashboard
│   ├── login.php          # Admin login
│   ├── logout.php
│   ├── products.php       # Product management
│   └── orders.php         # Order management
├── api/
│   └── vnpay.php          # VNPay payment handler
├── assets/
│   ├── css/
│   │   ├── style.css      # Main stylesheet
│   │   └── admin.css      # Admin stylesheet
│   └── js/
│       └── main.js        # Filter & interaction logic
├── includes/
│   ├── db.php             # Database connection
│   ├── header.php         # Shared header
│   └── footer.php         # Shared footer
├── index.php              # Home / Product listing
├── product.php            # Product detail
├── cart.php               # Shopping cart
├── checkout.php           # Checkout form
├── payment_return.php     # VNPay callback handler
├── login.php              # Customer login
├── register.php           # Customer registration
├── logout.php             # Session logout
└── README.md
```

---

## 🔄 Payment Flow

```
Customer → Checkout → api/vnpay.php → VNPay Sandbox
                                            ↓
payment_return.php ← HMAC-SHA512 Verified ←┘
        ↓
Update orders (status = 'paid')
Update payments (status = 'success')
Update products (stock -= quantity)   ← Data Integration
Clear cart session
```

---

## 📌 Future Improvements

- [ ] Email / SMS notification on order confirmation
- [ ] GHN / GHTK shipping API integration
- [ ] Google OAuth login
- [ ] Product search and advanced filtering
- [ ] Customer order history page
- [ ] Discount code / promotion system

---

## 👨‍💻 Group 3 — SE445D

| Member | Role |
|--------|------|
| Member 1 | |
| Member 2 | |
| Member 3 | |
| Member 4 | |
| Member 5 | |
| Member 6 | |

**Lecturer:** Nguyen Minh Nhat  
**University:** Duy Tan University — Faculty of Software Engineering