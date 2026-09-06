# Product-Cart Connection Architecture

## 📊 Complete System Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                         USER INTERFACE                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────┐      ┌──────────────┐      ┌──────────────┐ │
│  │  products.php │      │   cart.php   │      │  header.php  │ │
│  │              │      │              │      │              │ │
│  │ • View Items │      │ • View Cart  │      │ • Cart Count │ │
│  │ • Add to Cart│      │ • Update Qty │      │   Badge      │ │
│  │              │      │ • Remove     │      │              │ │
│  │              │      │ • Checkout   │      │              │ │
│  └──────┬───────┘      └──────┬───────┘      └──────┬───────┘ │
│         │                     │                      │         │
│         │ POST Request        │ SELECT/UPDATE       │ SELECT   │
│         │ INSERT/UPDATE       │ DELETE              │ COUNT    │
│         ▼                     ▼                      ▼         │
└─────────────────────────────────────────────────────────────────┘
                            │
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER (MySQL)                     │
│                        Database: msmsdb                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                    tblproducts                           │  │
│  ├──────────────────────────────────────────────────────────┤  │
│  │ ID (PK) | ProductName | Price | Image | Stock | Status  │  │
│  │   1     | Beard Oil   | 550   | img.jpg |  45   |   1   │  │
│  │   2     | Hair Wax    | 450   | img.jpg |  50   |   1   │  │
│  │   ...   │   ...       │ ...   │   ...   │  ...  │  ...  │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│                              │ ADD TO CART                      │
│                              ▼                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                      tblcart                             │  │
│  ├──────────────────────────────────────────────────────────┤  │
│  │ ID | SessionID | ProductID | Qty | Price | AddedDate   │  │
│  │ 1  | abc123    |    1      |  2  |  550  | 2026-04-01  │  │
│  │ 2  | abc123    |    5      |  1  |  420  | 2026-04-01  │  │
│  │ 3  | xyz789    |    2      |  3  |  450  | 2026-04-01  │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│                              │ CHECKOUT                         │
│                              ▼                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                     tblorders                            │  │
│  ├──────────────────────────────────────────────────────────┤  │
│  │ ID | OrderNo  | ProductID | Qty | Total | Status       │  │
│  │ 1  | ORD-001  |    1      |  2  | 1100  | Confirmed   │  │
│  │ 2  | ORD-001  |    5      |  1  |  420  | Confirmed   │  │
│  │ 3  | ORD-002  |    2      |  3  | 1350  | Processing  │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Detailed Operation Flow

### 1️⃣ ADD TO CART OPERATION

```
USER clicks "Add to Cart" on products.php
         │
         ▼
┌─────────────────────────────────────────┐
│  products.php (Line 7-42)               │
│  if(isset($_POST['add_to_cart'])) {     │
│    - Get product details from POST      │
│    - Get/Create session_id              │
│    - Check if product in cart           │
│    - INSERT or UPDATE tblcart           │
│  }                                      │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  SQL Query Executed:                    │
│                                         │
│  INSERT INTO tblcart(                   │
│    SessionID, ProductID,                │
│    ProductName, ProductPrice,           │
│    ProductImage, Quantity               │
│  ) VALUES(                              │
│    'abc123', 1,                         │
│    'Beard Oil', 550,                    │
│    'beard-oil.jpg', 1                   │
│  );                                     │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Result:                                │
│  ✓ Product added to cart                │
│  ✓ Alert: "Product added successfully!" │
│  ✓ Page refreshes                       │
│  ✓ Cart badge updates                   │
└─────────────────────────────────────────┘
```

---

### 2️⃣ VIEW CART OPERATION

```
USER clicks Cart icon in header
         │
         ▼
┌─────────────────────────────────────────┐
│  cart.php (Line 252-300)                │
│  $session_id = $_SESSION['customer_session']; │
│  $cart_items = mysqli_query($con, "     │
│    SELECT * FROM tblcart                │
│    WHERE SessionID='$session_id'        │
│  ");                                    │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Display Loop:                          │
│  while($item = mysqli_fetch_assoc($cart_items)) { │
│    - Show product image                 │
│    - Show product name                  │
│    - Show price                         │
│    - Show quantity input                │
│    - Show total (price × qty)           │
│    - Show remove button                 │
│  }                                      │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  HTML Table Generated:                  │
│  ┌────────────────────────────────┐    │
│  │ Product │ Name  │ Price │ Qty  │    │
│  ├────────────────────────────────┤    │
│  │ [img]   │Oil    │ ₹550  │ [2]  │    │
│  │ [img]   │Wax    │ ₹450  │ [1]  │    │
│  └────────────────────────────────┘    │
│                                         │
│  Summary:                               │
│  Total Items: 3                         │
│  Subtotal: ₹1550                        │
└─────────────────────────────────────────┘
```

---

### 3️⃣ UPDATE CART OPERATION

```
USER changes quantity and clicks "Update Cart"
         │
         ▼
┌─────────────────────────────────────────┐
│  cart.php (Line 16-27)                  │
│  if(isset($_POST['update_cart'])) {     │
│    foreach($_POST['quantity'] as $id => $qty) { │
│      if($qty > 0) {                     │
│        UPDATE tblcart                   │
│        SET Quantity=$qty                │
│        WHERE SessionID=$sid             │
│          AND ProductID=$id              │
│      } else {                           │
│        DELETE FROM tblcart              │
│        WHERE...                         │
│      }                                  │
│    }                                    │
│  }                                      │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  SQL Queries Executed:                  │
│                                         │
│  UPDATE tblcart                         │
│  SET Quantity=5                         │
│  WHERE SessionID='abc123'               │
│    AND ProductID=1;                     │
│                                         │
│  (For each product in cart)             │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Result:                                │
│  ✓ Cart quantities updated              │
│  ✓ Alert: "Cart updated!"               │
│  ✓ Page refreshes with new totals       │
└─────────────────────────────────────────┘
```

---

### 4️⃣ CHECKOUT OPERATION

```
USER clicks "Proceed to Checkout"
         │
         ▼
┌─────────────────────────────────────────┐
│  cart.php (Line 38-54)                  │
│  if(isset($_POST['checkout'])) {        │
│    $order_number = 'ORD-' . date('Ymd') . '-' . rand(1000,9999); │
│                                         │
│    // Get all cart items                │
│    $cart_items = mysqli_query($con, "   │
│      SELECT * FROM tblcart              │
│      WHERE SessionID='$session_id'      │
│    ");                                  │
│                                         │
│    while($item = mysqli_fetch_assoc($cart_items)) { │
│      $total = $item['ProductPrice'] * $item['Quantity']; │
│                                         │
│      INSERT INTO tblorders(...) VALUES; │
│    }                                    │
│                                         │
│    // Clear cart                        │
│    DELETE FROM tblcart                  │
│    WHERE SessionID='$session_id';       │
│  }                                      │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  SQL Queries Executed:                  │
│                                         │
│  INSERT INTO tblorders(                 │
│    OrderNumber, ProductID,              │
│    ProductName, ProductPrice,           │
│    ProductImage, Quantity,              │
│    TotalAmount, OrderStatus             │
│  ) VALUES(                              │
│    'ORD-20260401-1234', 1,              │
│    'Beard Oil', 550,                    │
│    'oil.jpg', 2, 1100, 'Confirmed'      │
│  );                                     │
│                                         │
│  DELETE FROM tblcart                    │
│  WHERE SessionID='abc123';              │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Result:                                │
│  ✓ All items moved to tblorders         │
│  ✓ Cart emptied                         │
│  ✓ Order number generated               │
│  ✓ Alert: "Order placed successfully!"  │
│  ✓ Redirect to products page            │
└─────────────────────────────────────────┘
```

---

### 5️⃣ CART COUNT BADGE

```
Every page load with header.php
         │
         ▼
┌─────────────────────────────────────────┐
│  includes/header.php (Line 2-10)        │
│  $cart_count = 0;                       │
│  if(isset($_SESSION['customer_session'])) { │
│    $session_id = $_SESSION['customer_session']; │
│    $cart_query = mysqli_query($con, "   │
│      SELECT SUM(Quantity) as total      │
│      FROM tblcart                       │
│      WHERE SessionID='$session_id'      │
│    ");                                  │
│    $cart_result = mysqli_fetch_assoc($cart_query); │
│    $cart_count = $cart_result['total']; │
│  }                                      │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  HTML Display:                          │
│  <a href="cart.php" class="cart-btn">   │
│    <i class="fa fa-shopping-cart"></i>  │
│    Cart                                 │
│    <span class="cart-badge">5</span>    │
│  </a>                                   │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Result:                                │
│  ✓ Red badge shows total items in cart  │
│  ✓ Updates on every page load           │
│  ✓ Real-time cart count                 │
└─────────────────────────────────────────┘
```

---

## 🎯 Session Management

```
┌──────────────────────────────────────────┐
│         Multiple Users Scenario          │
├──────────────────────────────────────────┘
│
│  User A (Session: abc123)
│  ┌────────────────────────────────────┐
│  │ tblcart                            │
│  │ SessionID: abc123                  │
│  │ - ProductID: 1 (Beard Oil) × 2     │
│  │ - ProductID: 5 (Hair Spray) × 1    │
│  └────────────────────────────────────┘
│
│  User B (Session: xyz789)
│  ┌────────────────────────────────────┐
│  │ tblcart                            │
│  │ SessionID: xyz789                  │
│  │ - ProductID: 2 (Hair Wax) × 3      │
│  │ - ProductID: 8 (Face Scrub) × 1    │
│  └────────────────────────────────────┘
│
│  ✓ Each user has separate cart
│  ✓ SessionID keeps carts isolated
│  ✓ Works without login (guest checkout)
└─────────────────────────────────────────┘
```

---

## 📊 Database Schema Relationships

```
┌─────────────────┐
│   tblproducts   │
│                 │
│  PK: ID         │◄────────────────────────┐
│  ProductName    │                         │
│  ProductPrice   │                         │
│  ProductImage   │                         │
│  Category       │                         │
│  Stock          │                         │
│  Status         │                         │
└─────────────────┘                         │
         │                                  │
         │ ProductID                        │ ProductID
         ▼                                  ▼
┌─────────────────┐                ┌─────────────────┐
│    tblcart      │                │   tblorders     │
│                 │                │                 │
│  PK: ID         │                │  PK: ID         │
│  SessionID      │                │  OrderNumber    │
│  FK: ProductID──┼───────────────►│  FK: ProductID  │
│  ProductName    │                │  ProductName    │
│  ProductPrice   │                │  ProductPrice   │
│  ProductImage   │                │  ProductImage   │
│  Quantity       │                │  Quantity       │
│  AddedDate      │                │  TotalAmount    │
└─────────────────┘                │  OrderStatus    │
         │                         │  OrderDate      │
         │                         └─────────────────┘
         │ TEMPORARY                        │
         │ (Clears on checkout)             │ PERMANENT
         │                                  │ (Keeps for history)
         ▼                                  ▼
   During Shopping                   After Checkout
```

---

## 🔑 Key Points

1. **Session-Based**: Each user gets unique `customer_session`
2. **Temporary Storage**: `tblcart` holds items during shopping
3. **Permanent Records**: `tblorders` stores completed orders
4. **Real-Time Updates**: Cart badge shows live count
5. **Guest Friendly**: No login required for shopping
6. **Data Integrity**: Foreign key relationships maintained
7. **Scalable**: Supports multiple simultaneous users

---

**This architecture ensures a smooth, professional e-commerce experience!** 🚀
