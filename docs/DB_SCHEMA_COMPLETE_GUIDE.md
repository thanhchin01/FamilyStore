# DB Schema Complete Guide - KhoaQuyenStore V2 (1 file/table)

## Tổng quan
- **Legacy tables** (v1 POS): sales, imports, customer__debts...
- **V2 expansions**: sale_invoices, import_receipts, inventory_movements...
- **Online features**: carts, orders expansions, chat, reviews.

## Danh sách tất cả tables (purpose, relations)

### 1. Core
**users**: Tài khoản admin/customer
- Relations: customers, carts, orders, conversations...
**customers**: Hồ sơ khách (POS/online)
- Relations: user_id → users, customer_addresses, sale_invoices...

### 2. Catalog
**categories**: Danh mục SP (expanded slug, is_active)
**products**: Sản phẩm trung tâm (expanded sku, sale_price...)
- Relations: category, product_images, sale_items, inventory_movements...

**product_images**: Gallery ảnh SP
- product_id → products

### 3. Cart/Online
**carts**: Header giỏ hàng
**cart_items**: Items (expanded cart_id)
**orders**: Header đơn online (expanded pricing)
**order_items**: Items (snapshots)
**order_addresses**: Snapshot giao hàng
**order_payments**: Thanh toán
**order_status_histories**: Lịch sử trạng thái

### 4. Engagement
**conversations**: Hồi thoại user-shop
**messages**: Tin nhắn
**notifications**: Thống báo
**reviews**: Đánh giá SP

### 5. POS V2
**sale_invoices**: Header hóa đơn
**sale_items**: Lines
**sale_payments**: Thu tiền
**customer_debt_balances**: Tổng nợ
**debt_transactions**: Lịch sử nợ

### 6. Inventory V2
**import_receipts**: Phieu nhập
**import_items**: Lines
**inventory_movements**: Biến động kho

## ERD Diagram
```mermaid
erDiagram
    USERS ||--o{ CUSTOMERS : "user_id"
    USERS ||--o{ CARTS : "owns"
    USERS ||--o{ ORDERS : "places"
    USERS ||--o{ CONVERSATIONS : "starts"
    USERS ||--o{ NOTIFICATIONS : "receives"
    USERS ||--o{ REVIEWS : "writes"
    USERS ||--o{ SALE_INVOICES : "creates"
    
    CUSTOMERS ||--o{ CUSTOMER_ADDRESSES : "has"
    CUSTOMERS ||--o{ SALE_INVOICES : "buys"
    CUSTOMERS ||--|| CUSTOMER_DEBT_BALANCES : "balance"
    
    CATEGORIES ||--o{ PRODUCTS : "contains"
    
    PRODUCTS ||--o{ PRODUCT_IMAGES : "gallery"
    PRODUCTS ||--o{ SALE_ITEMS : "sold"
    PRODUCTS ||--o{ IMPORT_ITEMS : "imported"
    PRODUCTS ||--o{ INVENTORY_MOVEMENTS : "tracks"
    
    SALE_INVOICES ||--o{ SALE_ITEMS : "lines"
    SALE_INVOICES ||--o{ SALE_PAYMENTS : "payments"
    
    IMPORT_RECEIPTS ||--o{ IMPORT_ITEMS : "lines"
```

