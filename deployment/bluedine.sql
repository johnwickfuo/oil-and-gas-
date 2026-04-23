-- Blue Dine Cuisines — seeded database
-- Generated: 2026-04-23T17:52:28+00:00
-- MySQL 8.0 compatible. Import into a pre-created database.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

-- -----------------------------
-- Table: academy_waitlist
-- -----------------------------
DROP TABLE IF EXISTS `academy_waitlist`;
CREATE TABLE `academy_waitlist` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL,
    `interest_level` VARCHAR(255) NOT NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: blog_posts
-- -----------------------------
DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `excerpt` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL,
    `cover_image` VARCHAR(255) NULL,
    `author_id` BIGINT NOT NULL,
    `category` VARCHAR(255) NOT NULL,
    `tags` TEXT NULL,
    `published_at` DATETIME NULL,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` VARCHAR(255) NULL,
    `views` BIGINT NOT NULL DEFAULT '0',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `blog_posts_slug_unique` (`slug`),
    KEY `blog_posts_category_index` (`category`),
    KEY `blog_posts_published_at_index` (`published_at`),
    CONSTRAINT `fk_ddf8fd3c831fc9d5` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `excerpt`, `body`, `cover_image`, `author_id`, `category`, `tags`, `published_at`, `meta_title`, `meta_description`, `views`, `created_at`, `updated_at`) VALUES
    (1, 'Sourcing in Port Harcourt: Where Our Menus Really Start', 'sourcing-in-port-harcourt-where-our-menus-really-start', 'From Mile 1 Market to the jetties at Abonnema Wharf, a walk-through of where Blue Dine''s weekly produce comes from.', '<p>Every Monday before the dinner week begins, our kitchen starts at the Mile 1 Market. The snapper we serve on Thursday is usually on ice by Tuesday morning; the tomatoes in the jollof risotto were in a stall the day before.</p><p>This post walks you through our produce route, the vendors we have loved for years, and how a Port Harcourt pantry shapes what ends up on your table.</p>', NULL, 1, 'Behind The Scenes', '["port harcourt","sourcing","behind the scenes"]', '2026-04-22 17:51:06', 'Sourcing in Port Harcourt: Where Our Menus Really Start | Blue Dine Cuisines', 'From Mile 1 Market to the jetties at Abonnema Wharf, a walk-through of where Blue Dine''s weekly produce comes from.', 0, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (2, 'Plating for a Private Dinner of Eight', 'plating-for-a-private-dinner-of-eight', 'A working chef''s notes on how we design, prep and plate a four-course private dinner for a household of eight.', '<p>Small dinners look simple from the outside, but an eight-person private menu has a rhythm. The first hour is set-up, the next hour is ensuring every protein reaches temperature at exactly the same moment.</p><p>We walk through a recent Saturday: prep list, equipment, service choreography, and the final dessert plating sequence.</p>', NULL, 1, 'Craft', '["technique","private dining"]', '2026-03-31 17:51:06', 'Plating for a Private Dinner of Eight | Blue Dine Cuisines', 'A working chef''s notes on how we design, prep and plate a four-course private dinner for a household of eight.', 0, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (3, 'The Case for Jollof Risotto', 'the-case-for-jollof-risotto', 'Why we cross-pollinated the two most iconic rice dishes in our repertoire, and how to taste the result properly.', '<p>Jollof is Nigerian comfort food; risotto is an Italian technique. Our jollof risotto uses locally grown ofada as the base and a smoky jollof stock as the cooking liquid.</p><p>In this post we break down the build, the tasting order we recommend, and the wine pairings that hold up without overpowering the atarodo heat.</p>', NULL, 1, 'Menu Notes', '["menu","jollof","risotto"]', '2026-04-22 17:51:06', 'The Case for Jollof Risotto | Blue Dine Cuisines', 'Why we cross-pollinated the two most iconic rice dishes in our repertoire, and how to taste the result properly.', 0, '2026-04-23 17:51:06', '2026-04-23 17:51:06');

-- -----------------------------
-- Table: bookings
-- -----------------------------
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `reference` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL,
    `service_id` BIGINT NULL,
    `event_date` DATE NOT NULL,
    `event_time` TIME NOT NULL,
    `guests` BIGINT NOT NULL,
    `location` VARCHAR(255) NOT NULL,
    `dietary_notes` TEXT NULL,
    `special_requests` TEXT NULL,
    `estimated_total` DECIMAL(20,6) NOT NULL,
    `deposit_amount` DECIMAL(20,6) NOT NULL,
    `status` VARCHAR(255) NOT NULL DEFAULT 'pending_payment',
    `payment_status` VARCHAR(255) NOT NULL DEFAULT 'unpaid',
    `admin_notes` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    `deleted_at` DATETIME NULL,
    `menu_preferences` TEXT NULL,
    `addons` TEXT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `bookings_reference_unique` (`reference`),
    KEY `bookings_status_index` (`status`),
    KEY `bookings_event_date_index` (`event_date`),
    CONSTRAINT `fk_191a105f9c547268` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: cache
-- -----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` TEXT NOT NULL,
    `expiration` BIGINT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: cache_locks
-- -----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` BIGINT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: failed_jobs
-- -----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` TEXT NOT NULL,
    `exception` TEXT NOT NULL,
    `failed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: gallery_images
-- -----------------------------
DROP TABLE IF EXISTS `gallery_images`;
CREATE TABLE `gallery_images` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255) NULL,
    `category` VARCHAR(255) NULL,
    `sort_order` BIGINT NOT NULL DEFAULT '0',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `gallery_images_category_index` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gallery_images` (`id`, `image`, `caption`, `category`, `sort_order`, `created_at`, `updated_at`) VALUES
    (1, 'gallery/placeholder-dinner-1.jpg', 'A four-course private dinner service in Old GRA.', 'dinners', 1, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (2, 'gallery/placeholder-dinner-2.jpg', 'Tableside plating of pepper grilled snapper.', 'dinners', 2, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (3, 'gallery/placeholder-meal-prep-1.jpg', 'A Sunday-evening meal prep delivery ready to dispatch.', 'meal-prep', 3, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (4, 'gallery/placeholder-meal-prep-2.jpg', 'Oven-safe packaging with reheating instructions.', 'meal-prep', 4, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (5, 'gallery/placeholder-small-chops-1.jpg', 'A 50-guest small chops presentation board.', 'small-chops', 5, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (6, 'gallery/placeholder-small-chops-2.jpg', 'Peppered gizzards and suya skewers plated hot.', 'small-chops', 6, '2026-04-23 17:51:06', '2026-04-23 17:51:06');

-- -----------------------------
-- Table: job_batches
-- -----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` BIGINT NOT NULL,
    `pending_jobs` BIGINT NOT NULL,
    `failed_jobs` BIGINT NOT NULL,
    `failed_job_ids` TEXT NOT NULL,
    `options` TEXT NULL,
    `cancelled_at` BIGINT NULL,
    `created_at` BIGINT NOT NULL,
    `finished_at` BIGINT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: jobs
-- -----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `queue` VARCHAR(255) NOT NULL,
    `payload` TEXT NOT NULL,
    `attempts` BIGINT NOT NULL,
    `reserved_at` BIGINT NULL,
    `available_at` BIGINT NOT NULL,
    `created_at` BIGINT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: media
-- -----------------------------
DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `model_type` VARCHAR(255) NOT NULL,
    `model_id` BIGINT NOT NULL,
    `uuid` VARCHAR(255) NULL,
    `collection_name` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `mime_type` VARCHAR(255) NULL,
    `disk` VARCHAR(255) NOT NULL,
    `conversions_disk` VARCHAR(255) NULL,
    `size` BIGINT NOT NULL,
    `manipulations` TEXT NOT NULL,
    `custom_properties` TEXT NOT NULL,
    `generated_conversions` TEXT NOT NULL,
    `responsive_images` TEXT NOT NULL,
    `order_column` BIGINT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `media_order_column_index` (`order_column`),
    UNIQUE KEY `media_uuid_unique` (`uuid`),
    KEY `media_model_type_model_id_index` (`model_type`, `model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: menu_items
-- -----------------------------
DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `price` DECIMAL(20,6) NOT NULL,
    `photo` VARCHAR(255) NULL,
    `category` VARCHAR(255) NOT NULL,
    `week_of` DATE NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT '1',
    `sort_order` BIGINT NOT NULL DEFAULT '0',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `menu_items_week_of_category_index` (`week_of`, `category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `photo`, `category`, `week_of`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
    (1, 'Suya Spiced Lamb', 'Slow-roasted lamb rubbed with our house suya blend.', 12000, NULL, 'protein', '2026-04-20 00:00:00', 1, 1, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (2, 'Pepper Grilled Snapper', 'Whole snapper, scored and grilled with atarodo and citrus.', 15000, NULL, 'protein', '2026-04-20 00:00:00', 1, 2, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (3, 'Jollof Risotto', 'Arborio rice cooked down in smoky jollof stock.', 6500, NULL, 'side', '2026-04-20 00:00:00', 1, 3, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (4, 'Coconut Rice with Prawns', 'Fragrant coconut rice, prawns, and charred bell peppers.', 7500, NULL, 'side', '2026-04-20 00:00:00', 1, 4, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (5, 'Amala with Gbegiri', 'Silky amala paired with creamy gbegiri and ewedu.', 5500, NULL, 'swallow', '2026-04-20 00:00:00', 1, 5, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (6, 'Hibiscus Sorbet', 'Bright zobo-lime sorbet with crystallised ginger.', 4500, NULL, 'dessert', '2026-04-20 00:00:00', 1, 6, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (7, 'Chapman Fizz', 'Classic chapman with house-made grenadine.', 3500, NULL, 'drink', '2026-04-20 00:00:00', 1, 7, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (8, 'Signature Small Chops Tray', 'Puff puff, samosa, spring rolls, peppered gizzards, suya skewers.', 18000, NULL, 'small_chops', '2026-04-20 00:00:00', 1, 8, '2026-04-23 17:51:05', '2026-04-23 17:51:05');

-- -----------------------------
-- Table: migrations
-- -----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `migration` VARCHAR(255) NOT NULL,
    `batch` BIGINT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
    (1, '0001_01_01_000000_create_users_table', 1),
    (2, '0001_01_01_000001_create_cache_table', 1),
    (3, '0001_01_01_000002_create_jobs_table', 1),
    (4, '2026_04_17_224727_create_media_table', 1),
    (5, '2026_04_17_230000_add_is_admin_to_users_table', 1),
    (6, '2026_04_17_230100_create_services_table', 1),
    (7, '2026_04_17_230200_create_menu_items_table', 1),
    (8, '2026_04_17_230300_create_bookings_table', 1),
    (9, '2026_04_17_230400_create_testimonials_table', 1),
    (10, '2026_04_17_230500_create_blog_posts_table', 1),
    (11, '2026_04_17_230600_create_recipes_table', 1),
    (12, '2026_04_17_230700_create_resources_table', 1),
    (13, '2026_04_17_230800_create_newsletter_subscribers_table', 1),
    (14, '2026_04_17_230900_create_academy_waitlist_table', 1),
    (15, '2026_04_17_231000_create_payments_table', 1),
    (16, '2026_04_17_231100_create_site_settings_table', 1),
    (17, '2026_04_18_000100_add_included_items_to_services_table', 1),
    (18, '2026_04_18_000200_create_gallery_images_table', 1),
    (19, '2026_04_18_000300_add_menu_and_addons_to_bookings_table', 1),
    (20, '2026_04_19_000100_add_source_to_newsletter_subscribers_table', 1);

-- -----------------------------
-- Table: newsletter_subscribers
-- -----------------------------
DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE `newsletter_subscribers` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NULL,
    `subscribed_at` DATETIME NOT NULL,
    `unsubscribed_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    `source` VARCHAR(255) NOT NULL DEFAULT 'footer',
    PRIMARY KEY (`id`),
    UNIQUE KEY `newsletter_subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: password_reset_tokens
-- -----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: payments
-- -----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `booking_id` BIGINT NOT NULL,
    `gateway` VARCHAR(255) NOT NULL,
    `reference` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(20,6) NOT NULL,
    `currency` VARCHAR(255) NOT NULL DEFAULT 'NGN',
    `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
    `gateway_response` TEXT NULL,
    `paid_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `payments_reference_unique` (`reference`),
    KEY `payments_status_index` (`status`),
    CONSTRAINT `fk_84e4a35796291287` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: recipes
-- -----------------------------
DROP TABLE IF EXISTS `recipes`;
CREATE TABLE `recipes` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `excerpt` VARCHAR(255) NOT NULL,
    `ingredients` TEXT NOT NULL,
    `instructions` TEXT NOT NULL,
    `cover_image` VARCHAR(255) NULL,
    `prep_time` BIGINT NOT NULL,
    `cook_time` BIGINT NOT NULL,
    `servings` BIGINT NOT NULL,
    `difficulty` VARCHAR(255) NOT NULL,
    `meal_type` VARCHAR(255) NOT NULL,
    `published_at` DATETIME NULL,
    `views` BIGINT NOT NULL DEFAULT '0',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `recipes_slug_unique` (`slug`),
    KEY `recipes_meal_type_index` (`meal_type`),
    KEY `recipes_published_at_index` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `recipes` (`id`, `title`, `slug`, `excerpt`, `ingredients`, `instructions`, `cover_image`, `prep_time`, `cook_time`, `servings`, `difficulty`, `meal_type`, `published_at`, `views`, `created_at`, `updated_at`) VALUES
    (1, 'Party Jollof Rice', 'party-jollof-rice', 'Our smoky party-style jollof with the deep tomato base that makes guests come back for seconds.', '[{"item":"Long-grain parboiled rice","quantity":"500g"},{"item":"Plum tomatoes","quantity":"6"},{"item":"Red bell peppers","quantity":"4"},{"item":"Atarodo (scotch bonnet)","quantity":"2"},{"item":"Onion","quantity":"1 large"},{"item":"Vegetable stock","quantity":"750ml"},{"item":"Curry powder","quantity":"1 tbsp"},{"item":"Bay leaves","quantity":"2"}]', '["Blend the tomatoes, peppers, atarodo and half the onion until smooth, then reduce in a heavy pot for 20 minutes.","Saute the remaining onion, add curry and bay leaves, then pour in the reduced pepper mixture and simmer.","Add the rinsed rice and stock, cover tightly, and cook on a low flame for 35-40 minutes until liquid is absorbed.","Lift the lid for the last five minutes on a higher flame to develop the signature smoky bottom."]', NULL, 20, 60, 6, 'medium', 'Main', '2026-02-26 17:51:06', 0, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (2, 'Egusi Soup', 'egusi-soup', 'A rich, protein-packed egusi soup with beef, stockfish and bitterleaf.', '[{"item":"Ground egusi (melon seeds)","quantity":"300g"},{"item":"Assorted beef & tripe","quantity":"800g"},{"item":"Stockfish","quantity":"100g"},{"item":"Palm oil","quantity":"150ml"},{"item":"Ground crayfish","quantity":"2 tbsp"},{"item":"Bitterleaf (washed)","quantity":"2 handfuls"},{"item":"Atarodo","quantity":"3"},{"item":"Seasoning & salt","quantity":"to taste"}]', '["Season and boil the beef, tripe and stockfish until tender; reserve the stock.","Heat palm oil, add ground egusi and toast until it forms small lumps and smells nutty.","Pour in the reserved stock, add crayfish, atarodo and seasoning; simmer for 20 minutes.","Stir in the meats and bitterleaf, simmer a further 10 minutes, and serve with pounded yam or eba."]', NULL, 30, 75, 8, 'medium', 'Soup', '2026-04-11 17:51:06', 0, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (3, 'Nigerian Meat Pie', 'nigerian-meat-pie', 'Flaky, buttery meat pies with a soft, well-seasoned minced beef and potato filling.', '[{"item":"Plain flour","quantity":"500g"},{"item":"Unsalted butter, cold","quantity":"250g"},{"item":"Baking powder","quantity":"1 tsp"},{"item":"Ice water","quantity":"120ml"},{"item":"Minced beef","quantity":"400g"},{"item":"Potato, diced","quantity":"1 large"},{"item":"Carrot, diced","quantity":"1"},{"item":"Onion, chopped","quantity":"1"},{"item":"Seasoning cube, thyme, curry","quantity":"to taste"},{"item":"Egg wash","quantity":"1 egg"}]', '["Rub the cold butter into the flour and baking powder until sandy, then bring together with ice water. Rest for 30 minutes.","Saute the onion, brown the mince, then add carrot, potato, seasoning and a splash of water. Cook until the potato is just tender and the filling is moist but not wet.","Roll the pastry to 3mm, cut discs, fill, crimp and brush with egg wash.","Bake at 190C for 25-30 minutes until deeply golden."]', NULL, 40, 30, 12, 'easy', 'Snack', '2026-03-16 17:51:06', 0, '2026-04-23 17:51:06', '2026-04-23 17:51:06');

-- -----------------------------
-- Table: resources
-- -----------------------------
DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `file` VARCHAR(255) NOT NULL,
    `cover_image` VARCHAR(255) NULL,
    `download_count` BIGINT NOT NULL DEFAULT '0',
    `is_active` TINYINT(1) NOT NULL DEFAULT '1',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `resources_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `resources` (`id`, `title`, `slug`, `description`, `file`, `cover_image`, `download_count`, `is_active`, `created_at`, `updated_at`) VALUES
    (1, 'Blue Dine Weekly Menu Planner', 'blue-dine-weekly-menu-planner', 'A printable planner to map your household menu against our weekly rotation.', 'resources/weekly-menu-planner.pdf', NULL, 0, 1, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (2, 'Signature Jollof Rice Recipe Card', 'signature-jollof-rice-recipe-card', 'The printable version of our party jollof method, sized for a household of six.', 'resources/jollof-recipe-card.pdf', NULL, 0, 1, '2026-04-23 17:51:06', '2026-04-23 17:51:06');

-- -----------------------------
-- Table: services
-- -----------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `short_description` VARCHAR(255) NOT NULL,
    `long_description` TEXT NOT NULL,
    `base_price` DECIMAL(20,6) NOT NULL,
    `image` VARCHAR(255) NULL,
    `sort_order` BIGINT NOT NULL DEFAULT '0',
    `is_active` TINYINT(1) NOT NULL DEFAULT '1',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    `included_items` TEXT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` (`id`, `title`, `slug`, `short_description`, `long_description`, `base_price`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`, `included_items`) VALUES
    (1, 'Private Chef Dinner', 'private-chef-dinner', 'Chef-curated dining experiences in your home, from intimate two-seaters to festive tables of twenty.', '<p>A fully bespoke dinner designed around your guests, allergies and occasion. Chef Eureka arrives with every ingredient, plates each course at your table, and leaves your kitchen cleaner than we found it.</p><p>Menus draw from coastal Nigerian produce with Mediterranean and continental accents, and every booking includes a discovery call to fine-tune the courses.</p>', 250000, NULL, 1, 1, '2026-04-23 17:51:05', '2026-04-23 17:51:05', '["Discovery call and bespoke menu design","All ingredients sourced from local Port Harcourt markets","On-site cooking and tableside plating","Service of 3- to 5-course menu","House-made sauces, breads and accompaniments","Full kitchen clean-down after service"]'),
    (2, 'Weekly Meal Prep', 'weekly-meal-prep', 'Wholesome, chef-plated meals delivered across Port Harcourt on a weekly rotation.', '<p>A five-day meal plan delivered every Sunday evening, built from the week''s market-fresh produce. Choose from signature proteins, sides and swallows, with options for low-carb, pescatarian, and family-size portions.</p><p>Packaging is oven-safe and reusable, and every order includes reheating instructions and a simple pairing guide.</p>', 85000, NULL, 2, 1, '2026-04-23 17:51:05', '2026-04-23 17:51:05', '["5 lunches and 5 dinners per week","Weekly rotating menu reflecting seasonal produce","Oven-safe reusable packaging","Reheating instructions for every dish","Option to add family-size portions","Sunday evening delivery across Port Harcourt"]'),
    (3, 'Small Chops Catering', 'small-chops-catering', 'Gold-standard small chops trays for engagements, birthdays and office events.', '<p>Platters of puff puff, samosa, spring rolls, peppered gizzards, suya skewers and seasonal dips, arranged on presentation boards sized for 10, 25 or 50 guests.</p><p>Delivery across Port Harcourt with hot-hold packaging so everything arrives crisp. Vegetarian and pepper-free options available.</p>', 120000, NULL, 3, 1, '2026-04-23 17:51:05', '2026-04-23 17:51:05', '["Trays sized for 10, 25 or 50 guests","Puff puff, samosa, spring rolls, peppered gizzards, suya skewers","House dips and pepper sauces","Presentation boards and cocktail napkins","Hot-hold insulated delivery","Vegetarian and pepper-free options on request"]');

-- -----------------------------
-- Table: sessions
-- -----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT NULL,
    `ip_address` VARCHAR(255) NULL,
    `user_agent` TEXT NULL,
    `payload` TEXT NOT NULL,
    `last_activity` BIGINT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_last_activity_index` (`last_activity`),
    KEY `sessions_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- no data

-- -----------------------------
-- Table: site_settings
-- -----------------------------
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    `value` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
    (1, 'contact_email', 'hello@bluedinecuisines.com', '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (2, 'contact_phone', '+234 803 000 0000', '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (3, 'whatsapp_number', '2348030000000', '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (4, 'instagram_url', 'https://instagram.com/bluedinecuisines', '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (5, 'facebook_url', 'https://facebook.com/bluedinecuisines', '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (6, 'twitter_url', '', '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (7, 'deposit_percentage', '30', '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (8, 'business_address', 'Port Harcourt, Rivers State, Nigeria', '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (9, 'service_area', 'Port Harcourt and environs', '2026-04-23 17:51:05', '2026-04-23 17:51:05');

-- -----------------------------
-- Table: testimonials
-- -----------------------------
DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `client_name` VARCHAR(255) NOT NULL,
    `quote` TEXT NOT NULL,
    `photo` VARCHAR(255) NULL,
    `rating` BIGINT NOT NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT '0',
    `sort_order` BIGINT NOT NULL DEFAULT '0',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `testimonials` (`id`, `client_name`, `quote`, `photo`, `rating`, `is_featured`, `sort_order`, `created_at`, `updated_at`) VALUES
    (1, 'Amaka & Tobi', 'Eureka turned our anniversary into the most memorable evening we have ever had. Every course was a story.', NULL, 5, 1, 1, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (2, 'Dr. Ifeanyi', 'The weekly meal prep has genuinely changed how our family eats. Beautifully plated, deeply flavorful.', NULL, 5, 1, 2, '2026-04-23 17:51:05', '2026-04-23 17:51:05'),
    (3, 'Ngozi O.', 'The small chops trays at my son''s naming ceremony were the talk of the room. Hot, crisp and impeccably presented.', NULL, 5, 0, 3, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (4, 'The Okonkwos', 'Warm, professional and genuinely skilled. We felt completely looked after from the tasting to the clean-up.', NULL, 4, 0, 4, '2026-04-23 17:51:06', '2026-04-23 17:51:06'),
    (5, 'Funmi A.', 'I''ve booked chefs from Lagos to Dubai and Eureka is easily in the top three. The coastal menu blew me away.', NULL, 5, 1, 5, '2026-04-23 17:51:06', '2026-04-23 17:51:06');

-- -----------------------------
-- Table: users
-- -----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` DATETIME NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    `is_admin` TINYINT(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_admin`) VALUES
    (1, 'Eureka Francis', '[email protected]', '2026-04-23 17:51:05', '$2y$12$bUHXacql0umknC0M1TtYH.XaafHRENAk/VXRPeQDVvNGtngN6djx6', NULL, '2026-04-23 17:51:05', '2026-04-23 17:51:05', 1);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=1;
