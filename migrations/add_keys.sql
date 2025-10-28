ALTER TABLE `orders`
    ADD CONSTRAINT `fk-orders-users`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE;

ALTER TABLE `orders`
    ADD CONSTRAINT `fk-orders-services`
        FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
            ON DELETE CASCADE;

CREATE INDEX `idx-orders-status` ON `orders` (`status`);

CREATE INDEX `idx-orders-services` ON `orders` (`service_id`);

CREATE INDEX `idx-orders-users` ON `orders` (`user_id`);

CREATE INDEX `idx-orders-status-links` ON `orders` (`status`, `link`(100));

CREATE INDEX `idx-orders-status-service-id` ON `orders` (`status`, `service_id`);
