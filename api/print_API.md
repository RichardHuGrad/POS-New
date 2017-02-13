# API Document

## 1. Authentication
the authentication use cashier's account which can be found in Database table cashiers

### 1.1 Generate Access token
- url - /api/access/generateToken
- method - POST
- params - email(mandatory), password(mandatory)
- return - access token

#### 1.1.1 Example
BASE_URL/api/access/generateToken?email=cashier@pos_v1.com&password=123456

## 2. Print
### 2.2 print pay Bill
- url: /api/print/printPayReceipt
- method: POST
- params: restaurant_id(mandatory), order_id(mandatory), access_token(mandatory)

### 2.1 print pay receipt
- url: /api/print/printPayReceipt
- method: POST
- params: restaurant_id(mandatory), order_id(mandatory), access_token(mandatory)

**notice**： printPayReceipt should be call after storing payment information in Orders table

### 2.2 print to kitchen
- url: /api/print/printTokitchen
- method: POST
- params: restaurant_id(mandatory), order_id(mandatory), access_token(mandatory)

### 2.2 print urge item to kitchen
- url: /api/print/printKitchenUrgeItem
- method: POST
- params: restaurant_id(mandatory), order_id(mandatory), item_id_list(mandatory), access_token(mandatory)

### 2.2 print removed item to kitchen
- url: /api/print/printKitchenRemoveItem
- method: POST
- params: restaurant_id(mandatory), order_id(mandatory), item_id_list(mandatory), access_token(mandatory)

### 2.2 print merge Bill
- url: /api/print/printMergeBill
- method: POST
- params: restaurant_id(mandatory), order_ids(mandatory), access_token(mandatory)

### 2.2 print merge receipt
- url: /api/print/printMergeReceipt
- method: POST
- params: restaurant_id(mandatory), order_ids(mandatory), access_token(mandatory)

### 2.2 print today's order amount
- url: /api/print/printTodayOrders
- method: POST
- params: restaurant_id(mandatory), access_token(mandatory)

### 2.2 print today's all items which are send to kitchen
- url: /api/print/printTodayItems
- method: POST
- params: restaurant_id(mandatory), access_token(mandatory)


## Notice
No Split print function API at this time
