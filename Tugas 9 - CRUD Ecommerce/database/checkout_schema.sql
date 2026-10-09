ALTER TABLE orders
    ADD COLUMN customer_name VARCHAR(100) NULL AFTER user_id,
    ADD COLUMN customer_phone VARCHAR(20) NULL AFTER customer_name,
    ADD COLUMN invoice_token CHAR(64) NULL AFTER status,
    ADD UNIQUE KEY orders_invoice_token_unique (invoice_token);
