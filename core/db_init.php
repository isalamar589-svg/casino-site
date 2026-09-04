<?php

function initDatabaseTables($db) {
    $schema = "
    CREATE TABLE IF NOT EXISTS `info` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) DEFAULT 'Casino Site',
        `keywords` text,
        `description` text,
        `metadesc` text,
        `url` varchar(255) DEFAULT 'https://casino-site-yzuu.onrender.com',
        `port` int(11) DEFAULT 2053,
        `lang` varchar(10) DEFAULT 'en',
        `referralcomission` decimal(10,4) DEFAULT 0.0100,
        `afcomdeposit` decimal(10,4) DEFAULT 0.0100,
        `afcombet` decimal(10,4) DEFAULT 0.0100,
        `levelstart` int(11) DEFAULT 100,
        `levelnext` decimal(10,4) DEFAULT 1.5000,
        `maintenance` int(11) DEFAULT 0,
        `maintenance_message` text,
        `steam` varchar(255) DEFAULT '',
        `twitter` varchar(255) DEFAULT '',
        `facebook` varchar(255) DEFAULT '',
        `telegram` varchar(255) DEFAULT '',
        `discord` varchar(255) DEFAULT '',
        `instagram` varchar(255) DEFAULT '',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `apis` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `key` text,
        `secret` text,
        PRIMARY KEY (`id`),
        KEY `idx_name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `rewards` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `reward` decimal(20,8) DEFAULT 0.00000000,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `referral_requirements` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `req` decimal(20,8) DEFAULT 0.00000000,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `name` varchar(255) DEFAULT 'Player',
        `avatar` text,
        `balance` decimal(20,8) DEFAULT 1000.00000000,
        `rank` int(11) DEFAULT 0,
        `xp` bigint(20) DEFAULT 0,
        `verified` int(11) DEFAULT 1,
        `mute` int(11) DEFAULT 0,
        `ban` int(11) DEFAULT 0,
        `time_create` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE KEY `idx_userid` (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users_sessions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `session` varchar(255) NOT NULL,
        `expire` bigint(20) NOT NULL,
        `removed` int(11) DEFAULT 0,
        PRIMARY KEY (`id`),
        KEY `idx_session` (`session`),
        KEY `idx_userid` (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users_transactions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `service` varchar(100) DEFAULT '',
        `amount` decimal(20,8) NOT NULL,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`),
        KEY `idx_userid` (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users_trades` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `type` varchar(50) NOT NULL,
        `amount` decimal(20,8) NOT NULL,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`),
        KEY `idx_user_type` (`userid`, `type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users_restrictions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `byuserid` varchar(64) DEFAULT '',
        `restriction` varchar(50) DEFAULT 'site',
        `reason` text,
        `expire` bigint(20) DEFAULT -1,
        `removed` int(11) DEFAULT 0,
        PRIMARY KEY (`id`),
        KEY `idx_userid` (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users_rewards` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `reward` varchar(100) NOT NULL,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users_transfers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `from_userid` varchar(64) NOT NULL,
        `to_userid` varchar(64) NOT NULL,
        `amount` decimal(20,8) NOT NULL,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users_binds` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `bind` varchar(50) NOT NULL,
        `bind_id` varchar(255) DEFAULT '',
        `removed` int(11) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `bannedip` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `ip` varchar(100) NOT NULL,
        `reason` text,
        PRIMARY KEY (`id`),
        KEY `idx_ip` (`ip`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `referral_codes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `code` varchar(64) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `idx_code` (`code`),
        KEY `idx_userid` (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `referral_uses` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `referral` varchar(64) NOT NULL,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `referral_deposited` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `referral` varchar(64) NOT NULL,
        `amount` decimal(20,8) DEFAULT 0.00000000,
        `commission` decimal(20,8) DEFAULT 0.00000000,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `referral_wagered` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `referral` varchar(64) NOT NULL,
        `amount` decimal(20,8) DEFAULT 0.00000000,
        `commission` decimal(20,8) DEFAULT 0.00000000,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `steam_transactions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `amount` decimal(20,8) DEFAULT 0.00000000,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `p2p_transactions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `amount` decimal(20,8) DEFAULT 0.00000000,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `p2p_buyers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `offerid` int(11) NOT NULL,
        `userid` varchar(64) NOT NULL,
        `canceled` int(11) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `crypto_transactions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `amount` decimal(20,8) DEFAULT 0.00000000,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `transaction` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user` varchar(64) NOT NULL,
        `title` varchar(255) DEFAULT '',
        `amount` decimal(20,8) DEFAULT 0.00000000,
        `status` varchar(50) DEFAULT 'pending',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `withdraws` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userid` varchar(64) NOT NULL,
        `amount` decimal(20,8) DEFAULT 0.00000000,
        `status` varchar(50) DEFAULT 'pending',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `support_tickets` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `from_userid` varchar(64) NOT NULL,
        `to_userid` varchar(64) DEFAULT '',
        `title` varchar(255) DEFAULT '',
        `closed` int(11) DEFAULT 0,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `support_messages` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `support_id` int(11) NOT NULL,
        `userid` varchar(64) NOT NULL,
        `message` text,
        `time` bigint(20) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    $queries = explode(";", $schema);
    foreach ($queries as $q) {
        $q = trim($q);
        if (!empty($q)) {
            $db->Query($q);
            $db->Execute();
        }
    }

    // Insert default info if empty
    $db->Query("SELECT COUNT(*) AS cnt FROM `info`");
    $db->Execute();
    $infoCount = $db->Single();
    if (empty($infoCount['cnt'])) {
        $db->Query("INSERT INTO `info` (`name`, `keywords`, `description`, `metadesc`, `url`, `port`, `lang`, `referralcomission`, `afcomdeposit`, `afcombet`, `levelstart`, `levelnext`, `maintenance`) VALUES ('Casino Site', 'casino, slots, betting', 'Online Casino Platform', 'Play casino games online', 'https://casino-site-yzuu.onrender.com', 2053, 'en', 0.0100, 0.0100, 0.0100, 100, 1.5000, 0)");
        $db->Execute();
    }

    // Insert default apis if empty
    $db->Query("SELECT COUNT(*) AS cnt FROM `apis`");
    $db->Execute();
    $apiCount = $db->Single();
    if (empty($apiCount['cnt'])) {
        $db->Query("INSERT INTO `apis` (`name`, `key`, `secret`) VALUES ('mortalsoft', 'demo_key', 'demo_secret'), ('recaptcha', '', '')");
        $db->Execute();
    }

    // Insert default rewards
    $db->Query("SELECT COUNT(*) AS cnt FROM `rewards`");
    $db->Execute();
    $rewardsCount = $db->Single();
    if (empty($rewardsCount['cnt'])) {
        $db->Query("INSERT INTO `rewards` (`name`, `reward`) VALUES ('daily_start', 10.00000000), ('daily_step', 2.00000000), ('bind_google', 50.00000000), ('bind_facebook', 50.00000000), ('bind_steam', 50.00000000)");
        $db->Execute();
    }

    // Insert default referral_requirements
    $db->Query("SELECT COUNT(*) AS cnt FROM `referral_requirements`");
    $db->Execute();
    $refCount = $db->Single();
    if (empty($refCount['cnt'])) {
        $db->Query("INSERT INTO `referral_requirements` (`req`) VALUES (0), (100), (500), (1000), (5000)");
        $db->Execute();
    }
}
