ALTER TABLE `invoices` 
ADD COLUMN `cancelled_at` DATETIME NULL 
AFTER `paid_at`; 